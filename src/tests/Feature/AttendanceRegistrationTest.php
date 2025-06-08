<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;
use App\Models\User;

class AttendanceRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_displayed_datetime_matches_now()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);

        $date = now()->isoFormat('YYYY年M月D日(ddd)');
        $time = now()->format('H:i');
        $response->assertSee($date);
        $response->assertSee($time);
    }

    public function test_work_status_is_off_duty()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSee('勤務外');
    }
}
