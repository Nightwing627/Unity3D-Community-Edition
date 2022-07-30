<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Playlist;
use App\Models\PlaylistTorrent;
use App\Models\Torrent;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaylistTorrentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlaylistTorrent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'position'    => $this->faker->randomNumber(),
            'playlist_id' => fn () => Playlist::factory()->create()->id,
            'torrent_id'  => fn () => Torrent::factory()->create()->id,
            'tmdb_id'     => $this->faker->randomNumber(),
        ];
    }
}
