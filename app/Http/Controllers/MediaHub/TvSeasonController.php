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

namespace App\Http\Controllers\MediaHub;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\Tv;

class TvSeasonController extends Controller
{
    /**
     * Display All TV Seasons.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show A TV Season.
     *
     * @param $id
     */
    public function show($id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $season = Season::with(['episodes'])->findOrFail($id);
        $show = Tv::whereId($season->tv_id)->first();

        return \view('mediahub.tv.season.show', [
            'season' => $season,
            'show'   => $show,
        ]);
    }
}
