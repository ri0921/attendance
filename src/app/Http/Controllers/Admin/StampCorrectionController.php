<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StampCorrectionRequest;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionAttendance;
use App\Models\Approval;

class StampCorrectionController extends Controller
{
    public function update($attendance_id, StampCorrectionRequest $request)
    {
        $attendance = Attendance::find($attendance_id);
        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'reason' => $request->reason,
        ]);

        DB::transaction(function () use ($attendance, $request) {
            $attendance->breakTimes()->delete();
            foreach ($request->input('break_time') as $break_time) {
                if (empty($break_time['break_start']) || empty($break_time['break_end'])) {
                    continue;
                }
                $attendance->breakTimes()->create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $break_time['break_start'],
                    'break_end'   => $break_time['break_end'],
                ]);
            }
        });
        return redirect(session('return_url', '/admin/attendance/list'));

    }

    public function index()
    {
        $tab = request('tab');
        $approvals = null;
        $correction_attendances = null;

        if ($tab === 'approved') {
            $approvals = Approval::all();
        } else {
            $correction_attendances = CorrectionAttendance::all();
        }

        return view('admin.request_list', compact('tab', 'correction_attendances', 'approvals'));
    }

    public function show()
    {
        return view('admin.approve');
    }
}
