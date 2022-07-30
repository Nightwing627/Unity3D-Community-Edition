<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'     => fn () => User::factory()->create()->id,
            'chatroom_id' => fn () => Chatroom::factory()->create()->id,
            'receiver_id' => fn () => User::factory()->create()->id,
            'bot_id'      => fn () => Bot::factory()->create()->id,
            'message'     => $this->faker->text,
        ];
    }
}
