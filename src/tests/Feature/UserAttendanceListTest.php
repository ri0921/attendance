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
}
