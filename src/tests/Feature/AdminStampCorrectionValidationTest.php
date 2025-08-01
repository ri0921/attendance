<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AdminStampCorrectionValidationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UsersTableSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_attendance_detail()
    {
        $attendance = Attendance::factory()->clocked_out()->create();
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);
        $response->assertSee($attendance->user->name);
        $response->assertSee(Carbon::parse($attendance->date)->format('m月d日'));
        $response->assertSee(Carbon::parse($attendance->clock_in)->format('H:i'));
        $response->assertSee(Carbon::parse($attendance->clock_out)->format('H:i'));
    }

    public function test_clock_in_is_after_clock_out()
    {
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/update", [
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
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/update", [
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
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/update", [
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
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/attendance/{$attendance->id}");
        $response->assertStatus(200);

        $response = $this->post("/attendance/{$attendance->id}/update", [
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
