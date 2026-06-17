<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $quote
 * @property string $author_name
 * @property string $author_title
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Testimonial extends Model
{
    protected $fillable = [
        'quote', 'author_name', 'author_title', 'order'
    ];
}
