<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Snapshot extends Model
{
    use HasFactory;

    protected $fillable = ['access_log_id', 'camera_id', 'filename', 'path'];

    protected $appends = ['url', 'time'];

    protected $with = ['camera'];

    public function camera()
    {
        return $this->belongsTo(Camera::class);
    }

    public function getUrlAttribute()
    {
        return url(Storage::url($this->path));
    }

    public function getTimeAttribute()
    {
        return $this->created_at->format('d-M-Y H:i:s');
    }
}
