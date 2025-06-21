<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Approval;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionBreak;

class ApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'correction_attendance_id' => CorrectionAttendance::factory(),
            'approval_status' => '承認済み',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
