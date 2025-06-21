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

class AdminAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
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
}
