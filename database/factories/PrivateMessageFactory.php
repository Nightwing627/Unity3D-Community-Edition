<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrivateMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrivateMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_id'   => fn () => User::factory()->create()->id,
            'receiver_id' => fn () => User::factory()->create()->id,
            'subject'     => $this->faker->word,
            'message'     => $this->faker->text,
            'read'        => $this->faker->boolean,
            'related_to'  => $this->faker->randomNumber(),
        ];
    }
}
