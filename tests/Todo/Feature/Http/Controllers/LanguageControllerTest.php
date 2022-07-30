<?php

namespace Tests\Todo\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LanguageController
 */
class LanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function back_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $language = Language::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('back', ['locale' => $language->locale]));

        $response->assertRedirect(withSuccess('Language Changed!'));

        // TODO: perform additional assertions
    }

    // test cases...
}