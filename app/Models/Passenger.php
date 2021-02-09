<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $with = ['booking'];

    protected $fillable = ['first_name', 'last_name', 'birth_date', 'document_number', 'place_form', 'place_back', 'booking_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
}
