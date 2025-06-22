<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionBreak;
use App\Models\Approval;
use Carbon\Carbon;

class AdminStampCorrectionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_all_requests_are_displayed_in_list()
    {
        $admin = User::find(1);
        $this->actingAs($admin);
        $correction_attendances = CorrectionAttendance::factory()
            ->count(5)
            ->has(CorrectionBreak::factory())
            ->create();
        $response = $this->get("/stamp_correction_request/list");
        $response->assertStatus(200);
        foreach ($correction_attendances as $correction_attendance) {
            $response->assertSee($correction_attendance->reason);
        }
    }

    public function test_all_approvals_are_displayed_in_list()
    {
        $correction_attendances = CorrectionAttendance::factory()
            ->count(5)
            ->has(CorrectionBreak::factory())
            ->create();
        foreach ($correction_attendances as $correction_attendance) {
            Approval::factory()
                ->create(['correction_attendance_id' => $correction_attendance->id]);
        }
        $approvals = Approval::all();

        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/stamp_correction_request/list/?tab=approved");
        $response->assertStatus(200);
        foreach($approvals as $approval) {
            $response->assertSee($approval->correctionAttendance->reason);
        }
    }

    public function test_correction_request_detail()
    {
        $user = User::find(2);
        $correction_attendance = CorrectionAttendance::factory()
            ->has(CorrectionBreak::factory())
            ->create(['user_id' => $user->id]);

        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->get("/stamp_correction_request/approve/{$correction_attendance->id}");
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee(Carbon::parse($correction_attendance->attendance->date)->format('m月d日'));
        $response->assertSee($correction_attendance->clock_in);
        $response->assertSee($correction_attendance->clock_out);
        $response->assertSee($correction_attendance->reason);
    }

    public function test_approve()
    {
        $attendance = Attendance::factory()->clocked_out()->create();
        $correction_attendance = CorrectionAttendance::factory()
            ->create([
                'attendance_id' => $attendance->id,
                'clock_in' => '08:00',
                'clock_out' => '17:00',
                'reason' => 'test',
            ]);
        $correction_break = CorrectionBreak::factory()
            ->create([
                'correction_attendance_id' => $correction_attendance->id,
                'break_start' => '11:00',
                'break_end' => '12:00',
            ]);

        $admin = User::find(1);
        $this->actingAs($admin);
        $response = $this->post("/stamp_correction_request/approve/{$correction_attendance->id}");

        $this->assertDatabaseHas('approvals', [
            'correction_attendance_id' => $correction_attendance->id,
            'approval_status' => '承認済み',
        ]);
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
        ]);
        $this->assertDatabaseHas('break_times', [
            'attendance_id' => $attendance->id,
            'break_start' => '11:00:00',
            'break_end' => '12:00:00',
        ]);
    }
}
