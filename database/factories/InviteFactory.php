<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InviteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'     => fn () => User::factory()->create()->id,
            'email'       => $this->faker->safeEmail,
            'code'        => $this->faker->word,
            'expires_on'  => $this->faker->dateTime(),
            'accepted_by' => fn () => User::factory()->create()->id,
            'accepted_at' => $this->faker->dateTime(),
            'custom'      => $this->faker->text,
        ];
    }
}
