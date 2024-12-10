<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardReader extends Model
{
    protected $fillable = ['access_gate_id', 'name', 'prefix', 'type', 'camera_ids'];

    protected $casts = [
        'camera_ids' => 'array'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'camera_names'
    ];

    public function accessGate()
    {
        return $this->belongsTo(AccessGate::class);
    }

    public function getCameraNamesAttribute()
    {
        return Camera::whereIn('id', $this->camera_ids)->pluck('name');
    }
}
