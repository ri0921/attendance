<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'correction_attendance_id',
        'approved_at',
        'approval_status',
    ];

    public function correctionRequest() {
        return $this->belongsTo('App\Models\CorrectionRequest');
    }
}
