<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UsersTableSeeder::class);
    }

    public function test_all_attendances_are_listed()
    {
        $users = User::where('id', '!=', 1)->get();
        foreach($users as $user) {
            Attendance::factory()->clocked_out()->create([
                'user_id' => $user->id,
            ]);
        }
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get('/admin/attendance/list');
        $response->assertStatus(200);
        foreach($users as $user) {
            $response->assertSee($user->name);
        }
        $content = $response->getContent();
        $this->assertEquals(3, substr_count($content, '09:00'));
        $this->assertEquals(3, substr_count($content, '18:00'));
    }

    public function test_today_is_displayed()
    {
        $today = Carbon::today();
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get('/admin/attendance/list');
        $response->assertStatus(200);
        $response->assertSee($today->format('Y/m/d'));
    }

    public function test_previous_day_attendance()
    {
        $user = User::find(2);
        $today = Carbon::today();
        $prev_date = $today->copy()
        ->subDay();
        $attendance = Attendance::factory()->clocked_out()->create([
            'user_id' => $user->id,
            'date' => $prev_date,
        ]);
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/admin/attendance/list?date={$prev_date->format('Y-m-d')}");
        $response->assertStatus(200);
        $response->assertSee($prev_date->format('Y/m/d'));
        $response->assertSee($user->name);
    }

    public function test_next_day_attendance()
    {
        $user = User::find(3);
        $today = Carbon::today();
        $next_date = $today->copy()
        ->addDay();
        $attendance = Attendance::factory()->clocked_out()->create([
            'user_id' => $user->id,
            'date' => $next_date,
        ]);
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/admin/attendance/list?date={$next_date->format('Y-m-d')}");
        $response->assertStatus(200);
        $response->assertSee($next_date->format('Y/m/d'));
        $response->assertSee($user->name);
    }
}
