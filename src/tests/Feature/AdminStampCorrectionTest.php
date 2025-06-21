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
}
