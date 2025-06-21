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

class AdminStaffAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_all_staff_are_listed()
    {
        $admin = User::find(1);
        $this->actingAs($admin);
        $users = User::where('id', '!=', 1)->get();
        $response = $this->get('/admin/staff/list');
        $response->assertStatus(200);
        foreach($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }
}
