<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'clock_in',
        'clock_out',
        'reason',
        'requested_at',
        'approval_status',
        'pending',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function attendance() {
        return $this->belongsTo('App\Models\Attendance');
    }

    public function correctionBreaks() {
        return $this->hasMany('App\Models\CorrectionBreak');
    }

    public function approval() {
        return $this->hasOne('App\Models\Approval');
    }
}
