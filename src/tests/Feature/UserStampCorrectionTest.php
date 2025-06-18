<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;


class UserStampCorrectionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_clock_in_is_after_clock_out()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $today = Carbon::today();
        $response = $this->post("/attendance/{$attendance->id}/request", [
            'clock_in' => $today->copy()->setTime(18, 0, 0),
            'clock_out' => $today->copy()->setTime(9, 0, 0),
            'reason' => 'test'
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

}
