<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CorrectionAttendance;
use App\Models\User;
use App\Models\Attendance;

class CorrectionAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'attendance_id' => Attendance::inRandomOrder()->first()->id,
            'clock_in' => $this->faker->time('H:i'),
            'clock_out' => $this->faker->time('H:i'),
            'reason' => $this->faker->sentence(),
            'requested_at' => now(),
            'approval_status' => '承認待ち',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
