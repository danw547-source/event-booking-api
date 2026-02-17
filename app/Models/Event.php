<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

/**
 * Event domain model.
 */
class Event extends Model
{
    use HasFactory;

    /** Fields allowed for mass assignment. */
    protected $fillable = ['title', 'description', 'date', 'country', 'capacity'];

    /** Event has many bookings. */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /** Event has many attendees through bookings. */
    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'bookings');
    }
}
