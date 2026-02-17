<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Attendee domain model.
 */
class Attendee extends Model
{
    use HasFactory;

    /** Fields allowed for mass assignment. */
    protected $fillable = ['name', 'email'];

    /** Attendee has many bookings. */
    public function bookings()
    {
        return  $this->hasMany(Booking::class);
    }

    /** Attendee belongs to many events through bookings. */
    public function events()
    {
        return  $this->belongsToMany(Event::class, 'bookings');
    }
}
