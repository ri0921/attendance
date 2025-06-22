<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Support\Carbon;

class BreakTimeTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UsersTableSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_break_time()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->post('/attendance/clock-in');
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩入');

        $response = $this->followingRedirects()->post('/break/start');
        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    public function test_user_can_take_multiple_breaks_in_a_day()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->post('/attendance/clock-in');
        $response = $this->post('/break/start');
        $response = $this->post('/break/end');
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩入');
    }

    public function test_break_end()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->post('/attendance/clock-in');
        $response = $this->post('/break/start');
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩戻');

        $response = $this->post('/break/end');
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    public function test_user_can_take_multiple_breaks_end_in_a_day()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $now = Carbon::now();
        $attendance = Attendance::factory()->working()->create([
            'clock_in' => $now->copy()->subHours(2),
        ]);
        $break_time = BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'break_start' => $now->copy()->subHour(1),
            'break_end' => $now->copy()->subMinutes(30),
            'created_at' => $now->copy()->subMinutes(30),
            'updated_at' => $now->copy()->subMinutes(30),
        ]);
        $response = $this->get('/attendance');
        $response = $this->post('/break/start', [
            'attendance_id' => $attendance->id,
            'break_start' => $now->copy()->subMinutes(5),
        ]);
        $response = $this->get('/attendance');
        $response->assertSee('休憩戻');
    }

    public function test_attendance_list_displays_break_times()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->working()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);
        $now = now()->setSeconds(0);
        $today = $now->format('m/d');
        $html = $response->getContent();

        preg_match('/<tr[^>]*>.*?' . preg_quote($today, '/') . '.*?<\/tr>/s', $html, $matches);
        $row = $matches[0];
        $this->assertStringContainsString('0:45', $row);
    }
}
