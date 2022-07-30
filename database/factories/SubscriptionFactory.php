<?php

/* @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Subscription;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'  => fn () => User::factory()->create()->id,
            'forum_id' => fn () => Forum::factory()->create()->id,
            'topic_id' => fn () => Topic::factory()->create()->id,
        ];
    }
}
