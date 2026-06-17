<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $step_number
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ProcessStep extends Model
{
    protected $fillable = [
        'step_number', 'title', 'description'
    ];
}
