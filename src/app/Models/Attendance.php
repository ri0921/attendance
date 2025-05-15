<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'clock_in',
        'clock_out',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function breakTimes() {
        return $this->hasMany('App\Models\BreakTime');
    }

    public function correctionAttendance() {
        return $this->hasOne('App\Models\CorrectionAttendance');
    }
}
