<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessGate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'device'];

    public function cardReaders()
    {
        return $this->hasMany(CardReader::class);
    }
}
