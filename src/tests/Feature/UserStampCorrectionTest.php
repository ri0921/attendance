<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionBreak;
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

    public function test_stamp_correction_request()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $attendance = Attendance::factory()->clocked_out()->create();
        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
        ]);
        $response = $this->post("/attendance/{$attendance->id}/request", [
            'clock_in' => '08:00',
            'clock_out' => '17:00',
            'break_time' => [
                [
                    'break_start' => '12:00',
                    'break_end' => '13:00',
                ]
            ],
            'reason' => 'test',
        ]);
        $correction_attendance = CorrectionAttendance::where('attendance_id', $attendance->id)->latest()->first();

        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/stamp_correction_request/approve/{$correction_attendance->id}");
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $today = Carbon::today();
        $response->assertSee($today->format('m月d日'));
        $response->assertSee('08:00');
        $response->assertSee('17:00');
        $response->assertSee('12:00');
        $response->assertSee('13:00');
        $response->assertSee('test');

        $response = $this->get("/stamp_correction_request/list");
        $response->assertStatus(200);
        $response->assertSee($correction_attendance->approval_status);
        $response->assertSee($correction_attendance->user->name);
        $response->assertSee(Carbon::parse($correction_attendance->attendance->date)->format('Y/m/d'));
    }

    public function test_own_requests_are_displayed_in_list()
    {
        $user = User::find(2);
        $this->actingAs($user);
        $correction_attendances = CorrectionAttendance::factory()
            ->count(5)
            ->has(CorrectionBreak::factory())
            ->create(['user_id' => $user->id]);
        $response = $this->get("/stamp_correction_request/list");
        $response->assertStatus(200);
        foreach ($correction_attendances as $correction_attendance) {
            $response->assertSee($correction_attendance->reason);
        }

    }
}
