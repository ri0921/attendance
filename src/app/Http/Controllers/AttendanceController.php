<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create()
    {
        return view('clock_in');
    }

    public function show()
    {
        return view('attendance');
    }
}
