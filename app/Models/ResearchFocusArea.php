<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ResearchFocusArea extends Model
{
    protected $fillable = [
        'icon', 'title', 'description', 'order'
    ];
}
