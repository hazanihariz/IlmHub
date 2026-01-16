<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
 use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'booking_time'
    ];

    // --- RELATIONSHIPS ---

    // A Booking belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A Booking belongs to an Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
