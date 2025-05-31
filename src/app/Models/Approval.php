<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'correction_attendance_id',
        'approval_status',
    ];

    public function correctionAttendance() {
        return $this->belongsTo('App\Models\CorrectionAttendance');
    }
}
