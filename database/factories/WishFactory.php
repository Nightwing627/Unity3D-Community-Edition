<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wish::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'title'   => $this->faker->word,
            'imdb'    => $this->faker->word,
            'type'    => $this->faker->word,
            'source'  => $this->faker->word,
        ];
    }
}
