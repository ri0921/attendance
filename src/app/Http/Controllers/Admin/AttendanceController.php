<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;

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

        return view('admin.attendance', compact('attendance', 'break_times'));
    }

    public function listStaffAttendance()
    {
        return view('admin.staff_attendance_list');
    }
}
