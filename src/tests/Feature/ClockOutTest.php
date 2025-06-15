<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;

class ClockOutTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_clock_out()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->post('/attendance/clock-in');
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('退勤');

        $response = $this->post('/attendance/clock-out');
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('退勤済');
    }

    public function test_clock_out_time_is_displayed_in_attendance_list()
    {
        $user = User::find(2);
        $this->actingAs($user);
        Attendance::factory()->clocked_out()->create();
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);

        $today = now()->format('m/d');
        $html = $response->getContent();
        preg_match('/<tr[^>]*>.*?' . preg_quote($today, '/') . '.*?<\/tr>/s', $html, $matches);
        $row = $matches[0];
        $this->assertStringContainsString('18:00', $row);
    }
}
