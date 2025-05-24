<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionAttendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $month_param = $request->query('month');
        $current = $month_param
            ? Carbon::createFromFormat('Y-m', $month_param)
            : Carbon::now();
        $current_month= $current->format('Y/m');
        $prev_month = $current->copy()
            ->subMonth()->format('Y-m');
        $next_month = $current->copy()
            ->addMonth()->format('Y-m');

        $user_id = Auth::id();
        $start_of_month = $current->copy()
            ->startOfMonth();
        $end_of_month = $current->copy()
            ->endOfMonth();
        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user_id)
            ->whereBetween('date', [$start_of_month->toDateString(), $end_of_month->toDateString()])
            ->get()
            ->keyBy('date');
        $days = CarbonPeriod::create($start_of_month, $end_of_month);

        return view('index', compact('current_month', 'prev_month', 'next_month', 'days', 'attendances'));
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

    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        $user = Auth::user();
        $break_times = $attendance->breakTimes;

        $correction = $attendance->correctionAttendance;

        return view('attendance', compact('attendance', 'break_times', 'correction'));
    }
}
