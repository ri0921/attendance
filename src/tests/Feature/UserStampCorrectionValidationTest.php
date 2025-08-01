<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class UserStampCorrectionValidationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UsersTableSeeder::class);
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

        $response = $this->post("/attendance/{$attendance->id}/request", [
            'clock_in' => '18:00',
            'clock_out' => '09:00',
            'reason' => 'test'
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_break_start_is_after_clock_out()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/request", [
            'clock_in' => '09:00',
            'clock_out' => '15:00',
            'break_time' => [
                [
                    'break_start' => '16:00',
                    'break_end' => '17:00',
                ]
            ],
            'reason' => 'test'
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'break_time.0.break_start' => '休憩時間が勤務時間外です',
        ]);
    }

    public function test_break_end_is_after_clock_out()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/request", [
            'clock_in' => '09:00',
            'clock_out' => '15:00',
            'break_time' => [
                [
                    'break_start' => '14:00',
                    'break_end' => '16:00',
                ]
            ],
            'reason' => 'test'
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'break_time.0.break_start' => '休憩時間が勤務時間外です',
        ]);
    }

    public function test_reason_is_required()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/request", [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_time' => [
                [
                    'break_start' => '12:00',
                    'break_end' => '13:00',
                ]
            ],
            'reason' => '',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'reason' => '備考を記入してください',
        ]);
    }
}
