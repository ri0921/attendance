<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StampCorrectionController extends Controller
{
    public function index()
    {
        return view('request_list');
    }
}
