<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StampCorrectionRequest;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionBreak;
use App\Models\Approval;

class StampCorrectionController extends Controller
{
    public function store($attendance_id, StampCorrectionRequest $request)
    {
        $user = Auth::user();
        $correction_attendance = CorrectionAttendance::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance_id,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'reason' => $request->reason,
            'requested_at' => now(),
            'approval_status' => '承認待ち',
        ]);

        foreach ($request->input('break_time', []) as $break) {
            if (!empty($break['break_start']) && !empty($break['break_end'])) {
                CorrectionBreak::create([
                    'correction_attendance_id' => $correction_attendance->id,
                    'break_start' => $break['break_start'],
                    'break_end' => $break['break_end'],
                ]);
            }
        }
        return redirect("/attendance/{$attendance_id}");
    }


    public function index()
    {
        $tab = request('tab');
        $user_id = Auth::id();
        $approvals = null;
        $correction_attendances = null;

        if ($tab === 'approved') {
            $approvals = Approval::whereHas('correctionAttendance', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get();
        } else {
            $correction_attendances = CorrectionAttendance::where('user_id', $user_id)
                ->where('approval_status', '承認待ち')
                ->get();
        }
        return view('request_list', compact('tab', 'approvals', 'correction_attendances'));
    }
}
