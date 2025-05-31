<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date_param = $request->query('date');
        $current = $date_param
            ? Carbon::createFromFormat('Y-m-d', $date_param)
            : Carbon::now();
        $prev_date = $current->copy()
            ->subDay()->format('Y-m-d');
        $next_date = $current->copy()
            ->addDay()->format('Y-m-d');

        $attendances = Attendance::with('breakTimes')
            ->where('date', $current->format('Y/m/d'))
            ->get();

        return view('admin.index', compact('current', 'prev_date', 'next_date', 'attendances'));
    }

    public function show($attendance_id)
    {
        $attendance = Attendance::find($attendance_id);
        $break_times = $attendance->breakTimes;
        session()->flash('return_url', url()->previous());

        return view('admin.attendance', compact('attendance', 'break_times'));
    }

    public function listStaffAttendance(User $user, Request $request)
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

        $start_of_month = $current->copy()
            ->startOfMonth();
        $end_of_month = $current->copy()
            ->endOfMonth();
        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start_of_month->toDateString(), $end_of_month->toDateString()])
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        $days = CarbonPeriod::create($start_of_month, $end_of_month);

        return view('admin.staff_attendance_list', compact('current_month', 'prev_month', 'next_month', 'days', 'user', 'attendances'));
    }
}
