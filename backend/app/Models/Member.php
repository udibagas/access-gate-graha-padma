<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'card_number', 'expired_date', 'plate_number'];

    protected $appends = ['is_expired', 'readable_expired_date'];

    public static function parseExcelDate($date)
    {
        if ($date === null) {
            return null;
        }

        if (!is_numeric($date)) {
            return date('Y-m-d', strtotime($date));
        }

        $unix_date = ($date - 25569) * 86400;
        $date = 25569 + ($unix_date / 86400);
        $unix_date = ($date - 25569) * 86400;
        return gmdate("Y-m-d", $unix_date);
    }

    public function getIsExpiredAttribute()
    {
        if ($this->expired_date) {
            return Carbon::now() > (new Carbon($this->expired_date));
        }

        return false;
    }

    public function getReadableExpiredDateAttribute()
    {
        if ($this->expired_date) {
            return (new Carbon($this->expired_date))->format('d-M-Y');
        }

        return null;
    }
}
