<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StampCorrectionController extends Controller
{
    public function index()
    {
        return view('admin.request_list');
    }

    public function show()
    {
        return view('admin.approve');
    }
}
