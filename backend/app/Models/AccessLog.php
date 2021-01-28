<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'access_gate_id'];

    protected $with = ['member', 'accessGate'];

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
}
