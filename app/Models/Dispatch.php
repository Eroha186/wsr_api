<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Dispatch extends Model
{
    use HasFactory;

    public $date;

    public function stationFrom()
    {
        return $this->belongsTo(Station::class, 'from_id', 'id');
    }

    public function stationTo()
    {
        return $this->belongsTo(Station::class, 'to_id', 'id');
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getTimeFromAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getTimeToAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
}
