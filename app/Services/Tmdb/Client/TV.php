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

namespace App\Services\Tmdb\Client;

class TV
{
    /**
     * @var \GuzzleHttp\Client|mixed
     */
    public $client;

    public const API_BASE_URI = 'https://api.TheMovieDB.org/3';

    public $data;

    public function __construct($id)
    {
        $this->client = new \GuzzleHttp\Client(
            [
                'base_uri'    => self::API_BASE_URI,
                'verify'      => false,
                'http_errors' => false,
                'headers'     => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'query' => [
                    'api_key'            => \config('api-keys.tmdb'),
                    'language'           => \config('app.locale'),
                    'append_to_response' => 'videos,images,credits,external_ids,keywords,recommendations,alternative_titles',
                ],
            ]
        );

        $response = $this->client->request('get', 'https://api.TheMovieDB.org/3/tv/'.$id);

        $this->data = \json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getData()
    {
        return $this->data;
    }

    public function get_backdrop()
    {
        if ($this->data['backdrop_path']) {
            return 'https://image.tmdb.org/t/p/original'.$this->data['backdrop_path'];
        }

        return null;
    }

    public function get_creator()
    {
        return $this->data['created_by'] ?? null;
    }

    public function get_runtime()
    {
        return $this->data['episode_run_time'];
    }

    public function get_air_date()
    {
        return $this->data['first_air_date'];
    }

    public function get_genres()
    {
        return $this->data['genres'];
    }

    public function get_homepage()
    {
        return $this->data['homepage'];
    }

    public function get_id()
    {
        return $this->data['id'];
    }

    public function get_in_production()
    {
        return $this->data['in_production'];
    }

    public function get_last_air_date()
    {
        return $this->data['last_air_date'];
    }

    public function get_last_episode_to_air()
    {
        return $this->data['last_episode_to_air'];
    }

    public function get_name()
    {
        return \preg_replace('/[[:^print:]]/', '', $this->data['name']);
    }

    public function get_alternative_names()
    {
        return $this->data['alternative_titles'];
    }

    public function get_next_episode_to_air()
    {
        return $this->data['next_episode_to_air'];
    }

    public function get_networks()
    {
        return $this->data['networks'];
    }

    public function get_number_of_episodes()
    {
        return $this->data['number_of_episodes'];
    }

    public function get_origin_country()
    {
        return $this->data['origin_country'];
    }

    public function get_original_language()
    {
        return $this->data['original_language'];
    }

    public function get_original_name()
    {
        return \preg_replace('/[[:^print:]]/', '', $this->data['original_name']);
    }

    public function get_overview()
    {
        return \preg_replace('/[[:^print:]]/', '', $this->data['overview']);
    }

    public function get_poster()
    {
        if ($this->data['poster_path']) {
            return 'https://image.tmdb.org/t/p/original'.$this->data['poster_path'];
        }

        return null;
    }

    public function get_production_companies()
    {
        return $this->data['production_companies'];
    }

    public function get_seasons()
    {
        return $this->data['seasons'];
    }

    public function get_status()
    {
        return $this->data['status'];
    }

    public function get_type()
    {
        return $this->data['type'];
    }

    public function get_vote_average()
    {
        return $this->data['vote_average'];
    }

    public function get_vote_count()
    {
        return $this->data['vote_count'];
    }

    public function get_trailer()
    {
        if (! empty($this->data['videos']['results'])) {
            return 'https://www.youtube.com/embed/'.$this->data['videos']['results'][0]['key'];
        }

        return null;
    }

    public function get_videos()
    {
        if ($this->data['videos']['results']) {
            return 'https://www.youtube-nocookie.com/embed/'.$this->data['videos']['results'];
        }

        return null;
    }

    public function get_images()
    {
        if ($this->data['images']['results']) {
            return 'https://www.youtube.com/embed/'.$this->data['images']['results'];
        }

        return null;
    }
}
