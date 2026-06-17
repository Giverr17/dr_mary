<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'media_type',
        'platform',
        'embed_url',
        'audio_url',
        'thumbnail_url',
        'event_id',
        'duration',
        'recorded_at',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'recorded_at' => 'date',
        'order' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Boot function to automatically detect platform before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->platform = static::detectPlatform($model->embed_url);
        });
    }

    /**
     * Detect platform from pasted URL.
     */
    public static function detectPlatform(string $url): string
    {
        $url = strtolower($url);

        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be') || str_contains($url, 'youtube-nocookie.com')) {
            return 'youtube';
        }
        if (str_contains($url, 'spotify.com')) {
            return 'spotify';
        }
        if (str_contains($url, 'vimeo.com')) {
            return 'vimeo';
        }

        return 'other';
    }

    /**
     * Get processed clean embed URL suitable for inside an iframe.
     */
    public function getCleanEmbedUrlAttribute(): string
    {
        $url = $this->embed_url;
        $platform = $this->platform;

        if ($platform === 'youtube') {
            // YouTube standard watch URL: https://www.youtube.com/watch?v=dQw4w9WgXcQ
            if (preg_match('/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
                return "https://www.youtube-nocookie.com/embed/" . $match[1];
            }
            // YouTube Shorts: https://www.youtube.com/shorts/abcdefg
            if (preg_match('/youtube(?:-nocookie)?\.com\/shorts\/([^"&?\/ ]{11})/i', $url, $match)) {
                return "https://www.youtube-nocookie.com/embed/" . $match[1];
            }
        }

        if ($platform === 'spotify') {
            // Spotify episode or show link: https://open.spotify.com/episode/abcde12345
            // Replace /episode/ with /embed/episode/ or /show/ with /embed/show/
            if (str_contains($url, 'open.spotify.com/')) {
                if (str_contains($url, 'open.spotify.com/embed/')) {
                    return $url;
                }
                return str_replace('open.spotify.com/', 'open.spotify.com/embed/', $url);
            }
        }

        if ($platform === 'vimeo') {
            // Vimeo URL: https://vimeo.com/123456789
            if (preg_match('/vimeo\.com\/([0-9]+)/i', $url, $match)) {
                return "https://player.vimeo.com/video/" . $match[1];
            }
        }

        return $url;
    }

    /**
     * Get processed clean audio URL suitable for inside an iframe.
     */
    public function getCleanAudioUrlAttribute(): ?string
    {
        $url = $this->audio_url;
        if (!$url) {
            return null;
        }

        // Spotify episode or show link: https://open.spotify.com/episode/abcde12345
        if (str_contains($url, 'open.spotify.com/')) {
            if (str_contains($url, 'open.spotify.com/embed/')) {
                return $url;
            }
            return str_replace('open.spotify.com/', 'open.spotify.com/embed/', $url);
        }

        return $url;
    }
}
