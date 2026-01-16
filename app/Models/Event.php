<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'capacity',
        'gender_policy', // Enum: mixed, males_only, etc.
        'image_path',
        'category_id',   // Foreign Key
        'user_id',       // Creator
    ];

    // --- RELATIONSHIPS ---

    // An Event belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // An Event belongs to a User (creator)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An Event has many Bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
