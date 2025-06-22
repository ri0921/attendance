<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class UserAttendanceDetailTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UsersTableSeeder::class);
    }

    public function test_name()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();

        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_date()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();

        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $today = Carbon::today();
        $year = $today->copy()->format('Y年');
        $date = $today->copy()->format('m月d日');
        $response->assertSee($year);
        $response->assertSee($date);
    }

    public function test_attendance_times()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();

        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee('9:00');
        $response->assertSee('18:00');
    }

    public function test_break_times()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);

        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee('12:00');
        $response->assertSee('12:45');
    }
}
