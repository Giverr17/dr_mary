<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $type
 * @property int $year
 * @property string $abstract
 * @property string|null $pdf_path
 * @property bool $is_featured
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Publication extends Model
{
    protected $fillable = [
        'title', 'type', 'year', 'abstract', 'pdf_path', 'is_featured', 'order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
