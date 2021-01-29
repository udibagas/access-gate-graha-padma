<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessGate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'cameras', 'host'];

    protected $casts = ['cameras' => 'json'];

    protected $appends = ['camera_list'];

    public function getCameraListAttribute()
    {
        return Camera::whereIn('id', $this->cameras)->get();
    }
}
