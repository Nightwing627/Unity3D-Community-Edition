<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Graveyard;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GraveyardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Graveyard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'    => fn () => User::factory()->create()->id,
            'torrent_id' => fn () => Torrent::factory()->create()->id,
            'seedtime'   => $this->faker->randomNumber(),
            'rewarded'   => $this->faker->boolean,
        ];
    }
}
