<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'access_gate_id'];

    protected $with = ['member', 'accessGate', 'snapshots'];

    protected $appends = ['time'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function accessGate()
    {
        return $this->belongsTo(AccessGate::class);
    }

    public function snapshots()
    {
        return $this->hasMany(Snapshot::class);
    }

    public function getTimeAttribute()
    {
        return $this->created_at->format('d-M-Y H:i:s');
    }
}
