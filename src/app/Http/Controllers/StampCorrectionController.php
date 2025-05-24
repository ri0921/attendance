<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StampCorrectionRequest;

class StampCorrectionController extends Controller
{
    public function store(StampCorrectionRequest $request)
    {
        return back();
    }


    public function index()
    {
        return view('request_list');
    }
}
