<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CorrectionBreak;
use App\Models\CorrectionAttendance;

class CorrectionBreakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $correction_attendance = $this->correctionAttendance ?? null;
        if (!$correction_attendance) {
            $correction_attendance = CorrectionAttendance::factory()->create();
        }
        $clock_in = strtotime($correction_attendance->clock_in);
        $clock_out = strtotime($correction_attendance->clock_out);
        $break_start = $this->faker->numberBetween($clock_in, max($clock_in, $clock_out - 3600));
        $break_end = $this->faker->numberBetween($break_start + 600, $clock_out);

        return [
            'correction_attendance_id' => CorrectionAttendance::factory(),
            'break_start' => date('H:i', $break_start),
            'break_end' => date('H:i', $break_end),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
