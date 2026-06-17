<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property array $bullet_points
 * @property bool $is_popular
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ConsultingService extends Model
{
    protected $fillable = [
        'icon', 'title', 'description', 'bullet_points', 'is_popular', 'order'
    ];

    protected $casts = [
        'bullet_points' => 'array',
        'is_popular' => 'boolean',
    ];
}
