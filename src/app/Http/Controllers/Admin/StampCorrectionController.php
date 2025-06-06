<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StampCorrectionRequest;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionBreak;
use App\Models\Approval;
use App\Models\User;

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
            $approvals = Approval::with('correctionAttendance.user', 'correctionAttendance.attendance')->get();
        } else {
            $correction_attendances = CorrectionAttendance::with('user', 'attendance')
                ->where('approval_status', '承認待ち')
                ->get();
        }

        return view('admin.request_list', compact('tab', 'correction_attendances', 'approvals'));
    }

    public function show($correction_attendance_id, Request $request)
    {
        $correction_attendance = CorrectionAttendance::find($correction_attendance_id);
        $correction_breaks = $correction_attendance->correctionBreaks;
        return view('admin.approve', compact('correction_attendance', 'correction_breaks'));
    }

    public function approve($correction_attendance_id, Request $request)
    {
        $correction_attendance = CorrectionAttendance::find($correction_attendance_id);

        DB::transaction(function () use ($correction_attendance_id) {
            $correction_attendance = CorrectionAttendance::find($correction_attendance_id);
            $correction_attendance->approval_status = '承認済み';
            $correction_attendance->save();

            $attendance = Attendance::find($correction_attendance->attendance_id);
            $attendance->clock_in = $correction_attendance->clock_in;
            $attendance->clock_out = $correction_attendance->clock_out;
            $attendance->save();

            BreakTime::where('attendance_id', $attendance->id)->delete();
            foreach ($correction_attendance->correctionBreaks as $break) {
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $break->break_start,
                    'break_end' => $break->break_end,
                ]);
            }
            Approval::create([
                'correction_attendance_id' => $correction_attendance->id,
                'approval_status' => '承認済み',
            ]);
        });
        return redirect('/stamp_correction_request/approve/'. $correction_attendance->id);
    }
}
