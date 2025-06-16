<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_user_can_see_all_attendances()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);

        $now = Carbon::now();
        $start_of_month = $now->copy()->startOfMonth();
        $end_of_month = $now->copy()->endOfMonth();
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start_of_month->toDateString(), $end_of_month->toDateString()])
            ->get();

        foreach ($attendances as $attendance) {
            $response->assertSee(Carbon::parse($attendance->clock_in)->format('H:i'));
            $response->assertSee(Carbon::parse($attendance->clock_out)->format('H:i'));
        }
    }

    public function test_current_month()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);
        $now = Carbon::now();
        $current_month = $now->format('Y/m');
        $response->assertSee($current_month);
    }

    public function test_prev_month()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance/list');
        $prev_month = Carbon::now()->subMonth()->format('Y-m');
        $response = $this->get('/attendance/list?month=' . $prev_month);
        $response->assertStatus(200);

        $start = Carbon::now()->subMonth()->startOfMonth();
        $end = Carbon::now()->subMonth()->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get();

        foreach ($attendances as $attendance) {
            $response->assertSee(Carbon::parse($attendance->clock_in)->format('H:i'));
            $response->assertSee(Carbon::parse($attendance->clock_out)->format('H:i'));
        }
    }

    public function test_next_month()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance/list');
        $next_month = Carbon::now()->addMonth()->format('Y-m');
        $response = $this->get('/attendance/list?month=' . $next_month);
        $response->assertStatus(200);

        $start = Carbon::now()->addMonth()->startOfMonth();
        $end = Carbon::now()->addMonth()->endOfMonth();
        $days = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $days[] = $date->copy();
        }
        foreach ($days as $day) {
            $date = $day->format('m/d');
            $response->assertSee($date);
        }
    }

    public function test_attendance_detail()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);

        $now = Carbon::now();
        $start_of_month = $now->copy()->startOfMonth();
        $end_of_month = $now->copy()->endOfMonth();
        $attendance = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start_of_month->toDateString(), $end_of_month->toDateString()])
            ->first();

        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee(Carbon::parse($attendance->date)->format('Y年'));
        $response->assertSee(Carbon::parse($attendance->date)->format('m月d日'));
    }
}
