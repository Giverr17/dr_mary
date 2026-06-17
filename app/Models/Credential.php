<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $institution
 * @property string $description
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Credential extends Model
{
    protected $fillable = [
        'title', 'institution', 'description', 'order'
    ];
}
