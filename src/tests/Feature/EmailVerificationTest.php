<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use GuzzleHttp\Client;
use Carbon\Carbon;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_registration_sends_verification_email()
    {
        Notification::fake();
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect();
        $user = User::where('email', 'test@example.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_email_verification_page()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $client = new Client();
        $response = $client->get('https://mailtrap.io/inboxes');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_email_verified()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $verification_url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
        $response = $this->get($verification_url);
        $response->assertRedirectContains('/attendance');
    }
}
