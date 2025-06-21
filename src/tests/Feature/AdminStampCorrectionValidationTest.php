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

class AdminStampCorrectionValidationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
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
}
