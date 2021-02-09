<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['dispatch_from', 'dispatch_back', 'date_from', 'date_back', 'code'];

    public function dispatchFrom()
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_from', 'id');
    }

    public function dispatchBack()
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_to', 'id');
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'booking_id', 'id');
    }

    public function countCost()
    {
        $cost = $this->dispatchFrom->cost;

        if($this->dispatchBack) {
            $cost += $this->dispatchBack->cost;
        }

        return $cost * $this->passengers()->count();
    }
}
