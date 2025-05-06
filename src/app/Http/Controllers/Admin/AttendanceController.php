<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function show()
    {
        return view('admin.attendance');
    }

    public function listStaffAttendance()
    {
        return view('admin.staff_attendance_list');
    }
}
