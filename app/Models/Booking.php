<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Booking pivot model between events and attendees.
 */
class Booking extends Model
{
    use HasFactory;
    
    /** Fields allowed for mass assignment. */
    protected $fillable = ['event_id', 'attendee_id'];
    
    /** Booking belongs to one event. */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /** Booking belongs to one attendee. */
    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }
}
