<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $date = Carbon::today();
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'date' => $date,
            'clock_in' => null,
            'clock_out' => null,
            'reason' => null,
        ];
    }

    public function working()
    {
        return $this->state(function (array $attributes) {
            $date = Carbon::today();
            return [
                'clock_in' => $date->copy()->setTime(9, 0, 0),
                'clock_out' => null,
            ];
        });
    }

    public function clocked_out()
    {
        return $this->state(function (array $attributes) {
            $date = Carbon::today();
            return [
                'clock_in' => $date->copy()->setTime(9, 0, 0),
                'clock_out' => $date->copy()->setTime(18, 0, 0),
            ];
        });
    }
}
