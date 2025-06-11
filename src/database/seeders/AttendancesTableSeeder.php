<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', 'user')->get();
        $startDate = Carbon::today()->subMonths(3);
        $endDate = Carbon::yesterday();

        foreach ($users as $user) {
            $date = $startDate->copy();

            while ($date <= $endDate) {
                if ($date->isWeekday()) {
                    $clockIn = $date->copy()->setTime(rand(8, 9), rand(0, 59));
                    $clockOut = (clone $clockIn)->addHours(8)->addMinutes(rand(0, 30));

                    $attendance = Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'clock_in' => $clockIn,
                        'clock_out' => $clockOut,
                    ]);

                    $lastBreakEnd = clone $clockIn;
                    $breakCount = rand(1, 2);
                    for ($i = 0; $i < $breakCount; $i++) {
                        $breakStart = (clone $lastBreakEnd)->addHours(rand(1, 2))->addMinutes(rand(0, 30));
                        $breakEnd = (clone $breakStart)->addMinutes(rand(15, 45));
                        if ($breakEnd > $clockOut) break;

                        BreakTime::create([
                            'attendance_id' => $attendance->id,
                            'break_start' => $breakStart,
                            'break_end' => $breakEnd,
                        ]);
                        $lastBreakEnd = clone $breakEnd;
                    }
                }

                $date->addDay();
            }
        }
    }
}
