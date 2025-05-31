<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'reason',
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

    public function getTotalBreakDurationAttribute()
    {
        $total_break_times = 0;
        foreach ($this->breakTimes as $break) {
            if ($break->break_start && $break->break_end) {
                $start = Carbon::parse($break->break_start);
                $end = Carbon::parse($break->break_end);
                $total_break_times += $end->diffInMinutes($start);
            }
        }
        return CarbonInterval::minutes($total_break_times);
    }

    public function getTotalBreakFormattedAttribute()
    {
        $duration = $this->total_break_duration;
        if (!$duration) return '';
        $duration = $duration->cascade();

        return sprintf('%d:%02d', $duration->h, $duration->i);
    }

    public function getTotalWorkDurationAttribute()
    {
        if (!$this->clock_in || !$this->clock_out)
        return null;

        $in = Carbon::parse($this->clock_in);
        $out = Carbon::parse($this->clock_out);
        $break = $this->total_break_duration;
        return $out->diffAsCarbonInterval($in)
            ->sub($break);
    }

    public function getTotalWorkFormattedAttribute()
    {
        $duration = $this->total_work_duration;
        if (!$duration) return '';
        $duration = $duration->cascade();

        return sprintf('%d:%02d', $duration->h, $duration->i);
    }
}
