<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendanceStatusDisplayTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_displayed_datetime_matches_now()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $date = now()->isoFormat('YYYY年M月D日(ddd)');
        $time = now()->format('H:i');
        $response->assertSee($date);
        $response->assertSee($time);
    }

    public function test_status_is_off_duty()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('勤務外');
    }

    public function test_status_is_working()
    {
        Attendance::factory()->working()->create();
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    public function test_status_is_on_break()
    {
        $attendance = Attendance::factory()->working()->create();
        BreakTime::factory()->on_break()->create(['attendance_id' => $attendance->id]);
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    public function test_status_is_clocked_out()
    {
        Attendance::factory()->clocked_out()->create();
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('退勤済');
    }
}
