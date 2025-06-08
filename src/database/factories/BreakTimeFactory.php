<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BreakTime;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class BreakTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition():array
    {
        $date = Carbon::today();
        return [
            'attendance_id' => Attendance::factory(),
            'break_start' => $date->copy()->setTime(12, 0, 0),
            'break_end' => $date->copy()->setTime(12, 45, 0),
        ];
    }

    public function on_break()
    {
        $date = Carbon::today();
        return $this->state(function (array $attributes) use ($date) {
            return [
                'break_start' => $date->copy()->setTime(15, 0, 0),
                'break_end' => null,
            ];
        });
    }
}
