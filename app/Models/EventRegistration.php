<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'full_name', 'email', 'organization', 'job_title', 'message'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
