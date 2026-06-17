<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon $date_start
 * @property \Illuminate\Support\Carbon|null $date_end
 * @property string $location
 * @property string|null $time
 * @property string|null $description
 * @property string|null $role
 * @property bool $is_virtual
 * @property bool $is_featured
 * @property string|null $registration_url
 * @property string|null $link_url
 * @property string|null $link_label
 * @property string|null $attendee_count
 * @property array|null $stats
 * @property string $status
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Event extends Model
{
    protected $fillable = [
        'title', 'date_start', 'date_end', 'location', 'time', 'description', 'image_path', 'role',
        'is_virtual', 'is_featured', 'registration_url', 'link_url', 'link_label',
        'attendee_count', 'stats', 'status', 'order'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'is_virtual' => 'boolean',
        'is_featured' => 'boolean',
        'stats' => 'array',
    ];
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
