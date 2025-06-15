<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class ClockInTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_clock_in()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('action="/attendance/clock-in"', false);

        $response = $this->followingRedirects()->post('/attendance/clock-in');
        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    public function test_clock_in_only_once_per_day()
    {
        Attendance::factory()->clocked_out()->create();
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertDontSee('action="/attendance/clock-in"', false);
    }

    public function test_clock_in_time_is_displayed_in_attendance_list()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->post('/attendance/clock-in');
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);

        $now = now()->setSeconds(0);
        $today = $now->format('m/d');
        $html = $response->getContent();
        preg_match('/<tr[^>]*>.*?' . preg_quote($today, '/') . '.*?<\/tr>/s', $html, $matches);
        $row = $matches[0];
        $this->assertStringContainsString($now->format('H:i'), $row);
    }
}
