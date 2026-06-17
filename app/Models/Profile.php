<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $full_name
 * @property string $title_line
 * @property string $hero_tagline
 * @property string $bio_paragraph_1
 * @property string $bio_paragraph_2
 * @property string $bio_paragraph_3
 * @property array $expertise_tags
 * @property string $stat_years
 * @property string $stat_focus
 * @property string $stat_approach
 * @property string $email
 * @property string $location
 * @property string $response_time
 * @property string $website_url
 * @property string $scholar_url
 * @property string $linkedin_url
 * @property string|null $photo_path
 * @property string|null $speaker_kit_path
 * @property string $footer_tagline
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Profile extends Model
{
    protected $fillable = [
        'full_name', 'title_line', 'hero_tagline', 'bio_paragraph_1', 'bio_paragraph_2', 'bio_paragraph_3',
        'expertise_tags', 'stat_years', 'stat_focus', 'stat_approach', 'email', 'location', 'response_time',
        'website_url', 'scholar_url', 'linkedin_url', 'social_links', 'photo_path', 'speaker_kit_path', 'footer_tagline'
    ];

    protected $casts = [
        'expertise_tags' => 'array',
        'social_links' => 'array',
    ];

    /**
     * Get the singleton profile instance.
     */
    public static function instance(): self
    {
        return static::firstOrFail();
    }
}
