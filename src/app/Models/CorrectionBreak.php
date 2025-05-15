<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'correction_attendance_id',
        'break_start',
        'break_end',
    ];

    public function correctionAttendance() {
        return $this->belongsTo('App\Models\CorrectionAttendance');
    }
}
