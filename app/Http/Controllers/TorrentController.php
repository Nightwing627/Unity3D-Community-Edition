<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers;

use andkab\LaravelJoyPixels\LaravelJoyPixels;
use App\Bots\IRCAnnounceBot;
use App\Helpers\Bbcode;
use App\Helpers\Bencode;
use App\Helpers\Linkify;
use App\Helpers\MediaInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Models\BonTransactions;
use App\Models\Category;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\Graveyard;
use App\Models\History;
use App\Models\Keyword;
use App\Models\Movie;
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use App\Models\PlaylistTorrent;
use App\Models\PrivateMessage;
use App\Models\Resolution;
use App\Models\Subtitle;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\TorrentRequest;
use App\Models\Tv;
use App\Models\Type;
use App\Models\User;
use App\Models\Warning;
use App\Notifications\NewReseedRequest;
use App\Repositories\ChatRepository;
use App\Services\Tmdb\TMDBScraper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\PlatformLogo;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentController extends Controller
{
    /**
     * TorrentController Constructor.
     */
    public function __construct(private ChatRepository $chatRepository)
    {
    }

    /**
     * Displays Torrent List View.
     */
    public function torrents(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('torrent.torrents');
    }

    /**
     * Torrent Similar Results.
     *
     * @param $categoryId
     * @param $tmdbId
     */
    public function similar($categoryId, $tmdbId): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::where('category_id', '=', $categoryId)
            ->where('tmdb', '=', $tmdbId)
            ->first();

        if (! $torrent || $torrent->count() === 0) {
            \abort(404, 'No Similar Torrents Found');
        }

        $meta = null;
        if ($torrent->category->tv_meta) {
            $meta = Tv::with('genres', 'cast', 'networks', 'seasons')->where('id', '=', $tmdbId)->first();
        }

        if ($torrent->category->movie_meta) {
            $meta = Movie::with('genres', 'cast', 'companies', 'collection')->where('id', '=', $tmdbId)->first();
        }

        return \view('torrent.similar', [
            'meta'       => $meta,
            'torrent'    => $torrent,
            'categoryId' => $categoryId,
            'tmdbId'     => $tmdbId,
        ]);
    }

    /**
     * Anonymize A Torrent Media Info.
     *
     * @param $mediainfo
     *
     * @return array
     */
    private static function anonymizeMediainfo($mediainfo)
    {
        if ($mediainfo === null) {
            return;
        }

        $completeNameI = \strpos($mediainfo, 'Complete name');
        if ($completeNameI !== false) {
            $pathI = \strpos($mediainfo, ': ', $completeNameI);
            if ($pathI !== false) {
                $pathI += 2;
                $endI = \strpos($mediainfo, "\n", $pathI);
                $path = \substr($mediainfo, $pathI, $endI - $pathI);
                $newPath = MediaInfo::stripPath($path);

                return \substr_replace($mediainfo, $newPath, $pathI, \strlen($path));
            }
        }

        return $mediainfo;
    }

    /**
     * Parse Torrent Keywords.
     *
     * @param $text
     *
     * @return array
     */
    private static function parseKeywords($text)
    {
        $parts = \explode(', ', $text);
        $result = [];
        foreach ($parts as $part) {
            $part = \trim($part);
            if ($part !== '') {
                $result[] = $part;
            }
        }

        return array_unique($result);
    }

    /**
     * Display The Torrent.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Torrent      $id
     *
     * @throws \JsonException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     * @throws \ReflectionException
     */
    public function torrent(Request $request, $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->with(['comments', 'category', 'type', 'resolution', 'subtitles'])->findOrFail($id);
        $uploader = $torrent->user;
        $user = $request->user();
        $freeleechToken = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();
        $personalFreeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();
        $comments = $torrent->comments()->latest()->paginate(5);
        $totalTips = BonTransactions::where('torrent_id', '=', $id)->sum('cost');
        $userTips = BonTransactions::where('torrent_id', '=', $id)->where('sender', '=', $request->user()->id)->sum('cost');
        $lastSeedActivity = History::where('info_hash', '=', $torrent->info_hash)->where('seeder', '=', 1)->latest('updated_at')->first();

        $meta = null;
        $trailer = null;
        $platforms = null;
        if ($torrent->category->tv_meta && $torrent->tmdb && $torrent->tmdb != 0) {
            $meta = Tv::with('genres', 'cast', 'companies', 'networks', 'recommendations')->where('id', '=', $torrent->tmdb)->first();
            $trailer = ( new \App\Services\Tmdb\Client\TV($torrent->tmdb))->get_trailer();
        }

        if ($torrent->category->movie_meta && $torrent->tmdb && $torrent->tmdb != 0) {
            $meta = Movie::with('genres', 'cast', 'companies', 'collection', 'recommendations')->where('id', '=', $torrent->tmdb)->first();
            $trailer = ( new \App\Services\Tmdb\Client\Movie($torrent->tmdb))->get_trailer();
        }

        if ($torrent->category->game_meta && ($torrent->igdb || $torrent->igdb != 0)) {
            $meta = Game::with([
                'cover'    => ['url', 'image_id'],
                'artworks' => ['url', 'image_id'],
                'genres'   => ['name'],
                'videos'   => ['video_id', 'name'],
                'involved_companies.company',
                'involved_companies.company.logo',
                'platforms', ])
                ->find($torrent->igdb);
            $link = collect($meta->videos)->take(1)->pluck('video_id');
            $trailer = 'https://www.youtube.com/embed/'.$link;
            $platforms = PlatformLogo::whereIn('id', collect($meta->platforms)->pluck('platform_logo')->toArray())->get();
        }

        $featured = $torrent->featured == 1 ? FeaturedTorrent::where('torrent_id', '=', $id)->first() : null;

        $mediaInfo = null;
        if ($torrent->mediainfo !== null) {
            $mediaInfo = (new MediaInfo())->parse($torrent->mediainfo);
        }

        $playlists = $user->playlists;

        return \view('torrent.torrent', [
            'torrent'            => $torrent,
            'comments'           => $comments,
            'user'               => $user,
            'personal_freeleech' => $personalFreeleech,
            'freeleech_token'    => $freeleechToken,
            'meta'               => $meta,
            'trailer'            => $trailer,
            'platforms'          => $platforms,
            'total_tips'         => $totalTips,
            'user_tips'          => $userTips,
            'featured'           => $featured,
            'mediaInfo'          => $mediaInfo,
            'uploader'           => $uploader,
            'last_seed_activity' => $lastSeedActivity,
            'playlists'          => $playlists,
        ]);
    }

    /**
     * Torrent Edit Form.
     *
     * @param \App\Models\Torrent $id
     */
    public function editForm(Request $request, $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        \abort_unless($user->group->is_modo || $user->id == $torrent->user_id, 403);

        return \view('torrent.edit_torrent', [
            'categories'  => Category::all()->sortBy('position'),
            'types'       => Type::all()->sortBy('position'),
            'resolutions' => Resolution::all()->sortBy('position'),
            'torrent'     => $torrent,
            'user'        => $user,
        ]);
    }

    /**
     * Edit A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        \abort_unless($user->group->is_modo || $user->id == $torrent->user_id, 403);
        $torrent->name = $request->input('name');
        $torrent->slug = Str::slug($torrent->name);
        $torrent->description = $request->input('description');
        $torrent->category_id = $request->input('category_id');
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->type_id = $request->input('type_id');
        $torrent->resolution_id = $request->input('resolution_id');
        $torrent->mediainfo = $request->input('mediainfo');
        $torrent->bdinfo = $request->input('bdinfo');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $request->input('internal');
        $torrent->personal_release = $request->input('personal_release');

        $category = Category::findOrFail($request->input('category_id'));

        $resRule = 'nullable|exists:resolutions,id';
        if ($category->movie_meta || $category->tv_meta) {
            $resRule = 'required|exists:resolutions,id';
        }

        $v = \validator($torrent->toArray(), [
            'name'          => 'required',
            'slug'          => 'required',
            'description'   => 'required',
            'category_id'   => 'required|exists:categories,id',
            'type_id'       => 'required|exists:types,id',
            'resolution_id' => $resRule,
            'imdb'          => 'required|numeric',
            'tvdb'          => 'required|numeric',
            'tmdb'          => 'required|numeric',
            'mal'           => 'required|numeric',
            'igdb'          => 'required|numeric',
            'anon'          => 'required',
            'stream'        => 'required',
            'sd'            => 'required',
        ]);

        if ($v->fails()) {
            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors($v->errors());
        }

        $torrent->save();

        // Cover Image for No-Meta Torrents
        if ($request->hasFile('torrent-cover') == true) {
            $image_cover = $request->file('torrent-cover');
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(400, 600)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner') == true) {
            $image_cover = $request->file('torrent-banner');
            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        $tmdbScraper = new TMDBScraper();
        if ($torrent->category->tv_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->tv($torrent->tmdb);
        }

        if ($torrent->category->movie_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->movie($torrent->tmdb);
        }

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Successfully Edited!');
    }

    /**
     * Delete A Torrent.
     *
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTorrent(Request $request)
    {
        $v = \validator($request->all(), [
            'id'      => 'required|exists:torrents',
            'slug'    => 'required|exists:torrents',
            'message' => 'required|alpha_dash|min:1',
        ]);

        if ($v) {
            $user = $request->user();
            $id = $request->id;
            $torrent = Torrent::withAnyStatus()->findOrFail($id);

            if ($user->group->is_modo || ($user->id == $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay()))) {
                foreach (History::where('info_hash', '=', $torrent->info_hash)->get() as $pm) {
                    $pmuser = new PrivateMessage();
                    $pmuser->sender_id = 1;
                    $pmuser->receiver_id = $pm->user_id;
                    $pmuser->subject = \sprintf('Torrent Deleted! - %s', $torrent->name);
                    $pmuser->message = \sprintf('[b]Attention:[/b] Torrent %s has been removed from our site. Our system shows that you were either the uploader, a seeder or a leecher on said torrent. We just wanted to let you know you can safely remove it from your client.
                                        [b]Removal Reason:[/b] %s
                                        [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]', $torrent->name, $request->message);
                    $pmuser->save();
                }

                // Reset Requests
                $torrentRequest = TorrentRequest::where('filled_hash', '=', $torrent->info_hash)->get();
                foreach ($torrentRequest as $req) {
                    if ($req) {
                        $req->filled_by = null;
                        $req->filled_when = null;
                        $req->filled_hash = null;
                        $req->approved_by = null;
                        $req->approved_when = null;
                        $req->save();
                    }
                }

                //Remove Torrent related info
                \cache()->forget(\sprintf('torrent:%s', $torrent->info_hash));
                Peer::where('torrent_id', '=', $id)->delete();
                History::where('info_hash', '=', $torrent->info_hash)->delete();
                Warning::where('torrent', '=', $id)->delete();
                TorrentFile::where('torrent_id', '=', $id)->delete();
                PlaylistTorrent::where('torrent_id', '=', $id)->delete();
                Subtitle::where('torrent_id', '=', $id)->delete();
                Graveyard::where('torrent_id', '=', $id)->delete();
                if ($torrent->featured == 1) {
                    FeaturedTorrent::where('torrent_id', '=', $id)->delete();
                }

                $torrent->delete();

                return \redirect()->route('torrents')
                    ->withSuccess('Torrent Has Been Deleted!');
            }
        } else {
            $errors = '';
            foreach ($v->errors()->all() as $error) {
                $errors .= $error."\n";
            }

            Log::notice(\sprintf('Deletion of torrent failed due to: %s', $errors));

            return \redirect()->route('home.index')
                ->withErrors('Unable to delete Torrent');
        }
    }

    /**
     * Display Peers Of A Torrent.
     *
     * @param \App\Models\Torrent $id
     */
    public function peers($id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $peers = Peer::with(['user'])->where('torrent_id', '=', $id)->latest('seeder')->paginate(25);

        return \view('torrent.peers', ['torrent' => $torrent, 'peers' => $peers]);
    }

    /**
     * Display History Of A Torrent.
     *
     * @param \App\Models\Torrent $id
     */
    public function history($id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $history = History::with(['user'])->where('info_hash', '=', $torrent->info_hash)->latest()->get();

        return \view('torrent.history', ['torrent' => $torrent, 'history' => $history]);
    }

    /**
     * Torrent Upload Form.
     *
     * @param int    $categoryId
     * @param string $title
     * @param int    $imdb
     * @param int    $tmdb
     */
    public function uploadForm(Request $request, $categoryId = 0, $title = '', $imdb = 0, $tmdb = 0): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();

        return \view('torrent.upload', [
            'categories'  => Category::all()->sortBy('position'),
            'types'       => Type::all()->sortBy('position'),
            'resolutions' => Resolution::all()->sortBy('position'),
            'user'        => $user,
            'category_id' => $categoryId,
            'title'       => $title,
            'imdb'        => \str_replace('tt', '', $imdb),
            'tmdb'        => $tmdb,
        ]);
    }

    /**
     * Preview torrent description.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview(Request $request)
    {
        // Preview The Upload
        $joyPixel = \app()->make(LaravelJoyPixels::class);
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        $previewContent = $joyPixel->toImage(
            $linkify->linky(
                $bbcode->parse($request->input('description'), true)
            )
        );

        return \response()->json($previewContent);
    }

    /**
     * Upload A Torrent.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $user = $request->user();

        // Find the right category
        $category = Category::withCount('torrents')->findOrFail($request->input('category_id'));

        $requestFile = $request->file('torrent');
        if ($request->hasFile('torrent') == false) {
            return \redirect()->route('upload_form', ['category_id' => $category->id])
                ->withErrors('You Must Provide A Torrent File For Upload!')->withInput();
        }

        if ($requestFile->getError() != 0 || $requestFile->getClientOriginalExtension() != 'torrent') {
            return \redirect()->route('upload_form', ['category_id' => $category->id])
                ->withErrors('You Must Provide A Valid Torrent File For Upload!')->withInput();
        }

        // Deplace and decode the torrent temporarily
        $decodedTorrent = TorrentTools::normalizeTorrent($requestFile);
        $infohash = Bencode::get_infohash($decodedTorrent);

        try {
            $meta = Bencode::get_meta($decodedTorrent);
        } catch (\Exception) {
            return \redirect()->route('upload_form', ['category_id' => $category->id])
                ->withErrors('You Must Provide A Valid Torrent File For Upload!')->withInput();
        }

        foreach (TorrentTools::getFilenameArray($decodedTorrent) as $name) {
            if (! TorrentTools::isValidFilename($name)) {
                return \redirect()->route('upload_form', ['category_id' => $category->id])
                    ->withErrors('Invalid Filenames In Torrent Files!')->withInput();
            }
        }

        $fileName = \uniqid('', true).'.torrent'; // Generate a unique name
        \file_put_contents(\getcwd().'/files/torrents/'.$fileName, Bencode::bencode($decodedTorrent));

        // Create the torrent (DB)
        $torrent = new Torrent();
        $torrent->name = $request->input('name');
        $torrent->slug = Str::slug($torrent->name);
        $torrent->description = $request->input('description');
        $torrent->mediainfo = self::anonymizeMediainfo($request->input('mediainfo'));
        $torrent->bdinfo = $request->input('bdinfo');
        $torrent->info_hash = $infohash;
        $torrent->file_name = $fileName;
        $torrent->num_file = $meta['count'];
        $torrent->announce = $decodedTorrent['announce'];
        $torrent->size = $meta['size'];
        $torrent->nfo = ($request->hasFile('nfo')) ? TorrentTools::getNfo($request->file('nfo')) : '';
        $torrent->category_id = $category->id;
        $torrent->type_id = $request->input('type_id');
        $torrent->resolution_id = $request->input('resolution_id');
        $torrent->user_id = $user->id;
        $torrent->imdb = $request->input('imdb');
        $torrent->tvdb = $request->input('tvdb');
        $torrent->tmdb = $request->input('tmdb');
        $torrent->mal = $request->input('mal');
        $torrent->igdb = $request->input('igdb');
        $torrent->anon = $request->input('anonymous');
        $torrent->stream = $request->input('stream');
        $torrent->sd = $request->input('sd');
        $torrent->internal = $request->input('internal');
        $torrent->personal_release = $request->input('personal_release');
        $torrent->moderated_at = Carbon::now();
        $torrent->moderated_by = 1; //System ID
        $torrent->free = $user->group->is_modo || $user->group->is_internal ? $request->input('free') : 0;

        //Require Resolution if Category is for Movies or TV
        $resRule = 'nullable|exists:resolutions,id';
        if ($category->movie_meta || $category->tv_meta) {
            $resRule = 'required|exists:resolutions,id';
        }

        // Validation
        $v = \validator($torrent->toArray(), [
            'name'           => 'required|unique:torrents',
            'slug'           => 'required',
            'description'    => 'required',
            'info_hash'      => 'required|unique:torrents',
            'file_name'      => 'required',
            'num_file'       => 'required|numeric',
            'announce'       => 'required',
            'size'           => 'required',
            'category_id'    => 'required|exists:categories,id',
            'type_id'        => 'required|exists:types,id',
            'resolution_id'  => $resRule,
            'user_id'        => 'required|exists:users,id',
            'imdb'           => 'required|numeric',
            'tvdb'           => 'required|numeric',
            'tmdb'           => 'required|numeric',
            'mal'            => 'required|numeric',
            'igdb'           => 'required|numeric',
            'anon'           => 'required',
            'stream'         => 'required',
            'sd'             => 'required',
        ]);

        if ($v->fails()) {
            if (\file_exists(\getcwd().'/files/torrents/'.$fileName)) {
                \unlink(\getcwd().'/files/torrents/'.$fileName);
            }

            return \redirect()->route('upload_form', ['category_id' => $category->id])
                ->withErrors($v->errors())->withInput();
        }

        // Save The Torrent
        $torrent->save();
        // Count and save the torrent number in this category
        $category->num_torrent = $category->torrents_count;
        $category->save();
        // Backup the files contained in the torrent
        foreach (TorrentTools::getTorrentFiles($decodedTorrent) as $file) {
            $torrentFile = new TorrentFile();
            $torrentFile->name = $file['name'];
            $torrentFile->size = $file['size'];
            $torrentFile->torrent_id = $torrent->id;
            $torrentFile->save();
            unset($torrentFile);
        }

        $tmdbScraper = new TMDBScraper();
        if ($torrent->category->tv_meta !== 0 && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->tv($torrent->tmdb);
        }

        if ($torrent->category->movie_meta !== 0 && ($torrent->tmdb || $torrent->tmdb != 0)) {
            $tmdbScraper->movie($torrent->tmdb);
        }

        // Torrent Keywords System
        foreach (self::parseKeywords($request->input('keywords')) as $keyword) {
            $tag = new Keyword();
            $tag->name = $keyword;
            $tag->torrent_id = $torrent->id;
            $tag->save();
        }

        // Cover Image for No-Meta Torrents
        if ($request->hasFile('torrent-cover') == true) {
            $image_cover = $request->file('torrent-cover');
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(400, 600)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner') == true) {
            $image_cover = $request->file('torrent-banner');
            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = \public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // check for trusted user and update torrent
        if ($user->group->is_trusted) {
            $appurl = \config('app.url');
            $user = $torrent->user;
            $username = $user->username;
            $anon = $torrent->anon;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chatRepository->systemMessage(
                    \sprintf('User [url=%s/users/', $appurl).$username.']'.$username.\sprintf('[/url] has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now! :slight_smile:'
                );
            } else {
                $this->chatRepository->systemMessage(
                    \sprintf('An anonymous user has uploaded a new '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], grab it now! :slight_smile:'
                );
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return \redirect()->route('download_check', ['id' => $torrent->id])
            ->withSuccess('Your torrent file is ready to be downloaded and seeded!');
    }

    /**
     * Download Check.
     *
     * @param \App\Models\Torrent $id
     */
    public function downloadCheck(Request $request, $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $user = $request->user();

        return \view('torrent.download_check', ['torrent' => $torrent, 'user' => $user]);
    }

    /**
     * Download A Torrent.
     *
     * @param \App\Models\Torrent $id
     * @param null                $rsskey
     *
     * @return TorrentFile
     */
    public function download(Request $request, $id, $rsskey = null)
    {
        $user = $request->user();
        if (! $user && $rsskey) {
            $user = User::where('rsskey', '=', $rsskey)->firstOrFail();
        }

        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        // User's ratio is too low
        if ($user->getRatio() < \config('other.ratio')) {
            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Your Ratio Is To Low To Download!');
        }

        // User's download rights are revoked
        if ($user->can_download == 0 && $torrent->user_id != $user->id) {
            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Your Download Rights Have Been Revoked!');
        }

        // Torrent Status Is Rejected
        if ($torrent->isRejected()) {
            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('This Torrent Has Been Rejected By Staff');
        }

        // Define the filename for the download
        $tmpFileName = \str_replace([' ', '/', '\\'], ['.', '-', '-'], '['.\config('torrent.source').']'.$torrent->name.'.torrent');

        // The torrent file exist ?
        if (! \file_exists(\getcwd().'/files/torrents/'.$torrent->file_name)) {
            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withErrors('Torrent File Not Found! Please Report This Torrent!');
        }

        // Delete the last torrent tmp file
        if (\file_exists(\getcwd().'/files/tmp/'.$tmpFileName)) {
            \unlink(\getcwd().'/files/tmp/'.$tmpFileName);
        }

        // Get the content of the torrent
        $dict = Bencode::bdecode(\file_get_contents(\getcwd().'/files/torrents/'.$torrent->file_name));
        if ($request->user() || ($rsskey && $user)) {
            // Set the announce key and add the user passkey
            $dict['announce'] = \route('announce', ['passkey' => $user->passkey]);
            // Remove Other announce url
            unset($dict['announce-list']);
        } else {
            return \redirect()->route('login');
        }

        $fileToDownload = Bencode::bencode($dict);
        \file_put_contents(\getcwd().'/files/tmp/'.$tmpFileName, $fileToDownload);

        return \response()->download(\getcwd().'/files/tmp/'.$tmpFileName)->deleteFileAfterSend(true);
    }

    /**
     * Bump A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bumpTorrent(Request $request, $id)
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent->bumped_at = Carbon::now();
        $torrent->save();

        // Announce To Chat
        $torrentUrl = \href_torrent($torrent);
        $profileUrl = \href_profile($user);

        $this->chatRepository->systemMessage(
            \sprintf('Attention, [url=%s]%s[/url] has been bumped to the top by [url=%s]%s[/url]! It could use more seeds!', $torrentUrl, $torrent->name, $profileUrl, $user->username)
        );

        // Announce To IRC
        if (\config('irc-bot.enabled') == true) {
            $appname = \config('app.name');
            $ircAnnounceBot = new IRCAnnounceBot();
            $ircAnnounceBot->message(\config('irc-bot.channel'), '['.$appname.'] User '.$user->username.' has bumped '.$torrent->name.' , it could use more seeds!');
            $ircAnnounceBot->message(\config('irc-bot.channel'), '[Category: '.$torrent->category->name.'] [Type: '.$torrent->type->name.'] [Size:'.$torrent->getSize().']');
            $ircAnnounceBot->message(\config('irc-bot.channel'), \sprintf('[Link: %s]', $torrentUrl));
        }

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Has Been Bumped To The Top Successfully!');
    }

    /**
     * Sticky A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sticky(Request $request, $id)
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrent->sticky = $torrent->sticky == 0 ? '1' : '0';
        $torrent->save();

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent Sticky Status Has Been Adjusted!');
    }

    /**
     * 100% Freeleech A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantFL(Request $request, $id)
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrentUrl = \href_torrent($torrent);

        if ($torrent->free == 0) {
            $torrent->free = '1';

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted 100%% FreeLeech! Grab It While You Can! :fire:', $torrentUrl, $torrent->name)
            );
        } else {
            $torrent->free = '0';

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been revoked of its 100%% FreeLeech! :poop:', $torrentUrl, $torrent->name)
            );
        }

        $torrent->save();

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent FL Has Been Adjusted!');
    }

    /**
     * Feature A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantFeatured(Request $request, $id)
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if ($torrent->featured == 0) {
            $torrent->free = '1';
            $torrent->doubleup = '1';
            $torrent->featured = '1';
            $torrent->save();

            $featured = new FeaturedTorrent();
            $featured->user_id = $user->id;
            $featured->torrent_id = $torrent->id;
            $featured->save();

            $torrentUrl = \href_torrent($torrent);
            $profileUrl = \href_profile($user);
            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been added to the Featured Torrents Slider by [url=%s]%s[/url]! Grab It While You Can! :fire:', $torrentUrl, $torrent->name, $profileUrl, $user->username)
            );

            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withSuccess('Torrent Is Now Featured!');
        }

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withErrors('Torrent Is Already Featured!');
    }

    /**
     * UnFeature A Torrent.
     *
     * @param \App\Models\Torrent $id
     * @param \App\Models\FeaturedTorrent torrent_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokeFeatured(Request $request, $id)
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo, 403);

        $featured_torrent = FeaturedTorrent::where('torrent_id', '=', $id)->firstOrFail();

        $torrent = Torrent::withAnyStatus()->findOrFail($id);

        if (isset($torrent)) {
            $torrent->free = '0';
            $torrent->doubleup = '0';
            $torrent->featured = '0';
            $torrent->save();

            $appurl = \config('app.url');

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s/torrents/%s]%s[/url] is no longer featured. :poop:', $appurl, $torrent->id, $torrent->name)
            );
        }

        $featured_torrent->delete();

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Revoked featured from Torrent!');
    }

    /**
     * Double Upload A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grantDoubleUp(Request $request, $id)
    {
        $user = $request->user();

        \abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $torrentUrl = \href_torrent($torrent);

        if ($torrent->doubleup == 0) {
            $torrent->doubleup = '1';

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been granted Double Upload! Grab It While You Can! :fire:', $torrentUrl, $torrent->name)
            );
        } else {
            $torrent->doubleup = '0';
            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, [url=%s]%s[/url] has been revoked of its Double Upload! :poop:', $torrentUrl, $torrent->name)
            );
        }

        $torrent->save();

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withSuccess('Torrent DoubleUpload Has Been Adjusted!');
    }

    /**
     * Reseed Request A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reseedTorrent(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::findOrFail($id);
        $reseed = History::where('info_hash', '=', $torrent->info_hash)->where('active', '=', 0)->get();

        if ($torrent->seeders <= 2) {
            // Send Notification
            foreach ($reseed as $r) {
                User::find($r->user_id)->notify(new NewReseedRequest($torrent));
            }

            $torrentUrl = \href_torrent($torrent);
            $profileUrl = \href_profile($user);

            $this->chatRepository->systemMessage(
                \sprintf('Ladies and Gents, a reseed request was just placed on [url=%s]%s[/url] can you help out :question:', $torrentUrl, $torrent->name)
            );

            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withSuccess('A notification has been sent to all users that downloaded this torrent along with original uploader!');
        }

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withErrors('This torrent doesnt meet the rules for a reseed request.');
    }

    /**
     * Use Freeleech Token On A Torrent.
     *
     * @param \App\Models\Torrent $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function freeleechToken(Request $request, $id)
    {
        $user = $request->user();
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $activeToken = FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first();

        if ($user->fl_tokens >= 1 && ! $activeToken) {
            $freeleechToken = new FreeleechToken();
            $freeleechToken->user_id = $user->id;
            $freeleechToken->torrent_id = $torrent->id;
            $freeleechToken->save();

            $user->fl_tokens -= '1';
            $user->save();

            return \redirect()->route('torrent', ['id' => $torrent->id])
                ->withSuccess('You Have Successfully Activated A Freeleech Token For This Torrent!');
        }

        return \redirect()->route('torrent', ['id' => $torrent->id])
            ->withErrors('You Dont Have Enough Freeleech Tokens Or Already Have One Activated On This Torrent.');
    }
}
