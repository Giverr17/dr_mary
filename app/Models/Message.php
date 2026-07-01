<?php

namespace App\Models;

use App\Enums\MessageSubject;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property MessageSubject $subject
 * @property string|null $organization
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Message extends Model
{
    protected $fillable = [
        'name', 'email', 'subject', 'organization', 'body', 'read_at', 'replied_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'subject' => MessageSubject::class,
    ];
}
