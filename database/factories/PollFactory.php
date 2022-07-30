<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Poll::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'         => fn () => User::factory()->create()->id,
            'title'           => $this->faker->word,
            'slug'            => $this->faker->slug,
            'ip_checking'     => $this->faker->boolean,
            'multiple_choice' => $this->faker->boolean,
        ];
    }
}
