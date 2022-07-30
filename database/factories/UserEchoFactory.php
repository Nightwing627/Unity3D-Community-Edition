<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\User;
use App\Models\UserEcho;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEchoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserEcho::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'   => fn () => User::factory()->create()->id,
            'room_id'   => fn () => Chatroom::factory()->create()->id,
            'target_id' => fn () => User::factory()->create()->id,
            'bot_id'    => fn () => Bot::factory()->create()->id,
        ];
    }
}
