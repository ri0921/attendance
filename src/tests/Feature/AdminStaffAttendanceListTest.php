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

class AdminStaffAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_all_staff_are_listed()
    {
        $admin = User::find(1);
        $this->actingAs($admin);
        $users = User::where('id', '!=', 1)->get();
        $response = $this->get('/admin/staff/list');
        $response->assertStatus(200);
        foreach($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }

    public function test_staff_attendance_list()
    {
        $admin = User::find(1);
        $this->actingAs($admin);
        $user = User::find(2);
        $response = $this->get("/admin/attendance/staff/{$user->id}");
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

    public function test_previous_month()
    {
        $admin = User::find(1);
        $this->actingAs($admin);
        $user = User::find(2);
        $response = $this->get("/admin/attendance/staff/{$user->id}");
        $prev_month = Carbon::now()->subMonth()->format('Y-m');
        $response = $this->get("/admin/attendance/staff/{$user->id}?month={$prev_month}");
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
        $admin = User::find(1);
        $this->actingAs($admin);
        $user = User::find(2);
        $response = $this->get("/admin/attendance/staff/{$user->id}");
        $next_month = Carbon::now()->addMonth()->format('Y-m');
        $response = $this->get("/admin/attendance/staff/{$user->id}?month={$next_month}");
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
}
