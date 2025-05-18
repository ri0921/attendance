<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create()
    {
        $now = Carbon::now()->locale('ja');
        $today = $now->isoFormat('YYYY年M月D日(ddd)');
        $time = $now->format('H:i');

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', Carbon::today())
            ->first();
        $break_time = null;
        if ($attendance) {
            $break_time = BreakTime::where('attendance_id', $attendance->id)
                ->latest()
                ->first();
        }

        return view('clock_in', compact('today', 'time', 'attendance', 'break_time'));
    }

    public function clockIn(Request $request)
    {
        $today = Carbon::today();
        $attendance = Attendance::firstOrCreate(
            ['user_id' => auth()->id(), 'date' => $today],
            ['clock_in' => now()]
        );

        $attendance->clock_in = now();
        $attendance->save();

        return redirect()->back();
    }

    public function clockOut(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', today())
            ->firstOrFail();

        $attendance->clock_out = now();
        $attendance->save();

        return redirect()->back();
    }

    public function startBreak(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', today())
            ->firstOrFail();

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        return redirect()->back();
    }

    public function endBreak(Request $request)
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', today())
            ->firstOrFail();

        $break_time = BreakTime::where('attendance_id', $attendance->id)
            ->whereNull('break_end')
            ->latest('break_start')
            ->first();

        if ($break_time) {
            $break_time->break_end = now();
            $break_time->save();
        }

        return redirect()->back();
    }

    public function show()
    {
        return view('attendance');
    }
}
