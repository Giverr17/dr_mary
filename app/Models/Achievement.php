<?php

namespace App\Models;

use App\Enums\AchievementCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'year',
        'category',
        'issuing_body',
        'link_url',
        'link_label',
        'link_preview_title',
        'link_preview_description',
        'link_preview_image',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'year' => 'integer',
        'order' => 'integer',
        'category' => AchievementCategory::class,
    ];

    /**
     * Scope for featured items first, then custom order, then newest year first.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('is_featured', 'desc')
                     ->orderBy('order', 'asc')
                     ->orderBy('year', 'desc');
    }

    /**
     * Automatically crawl Open Graph meta elements on save.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Crawl if URL has changed OR if URL exists but preview data is completely empty
            if ($model->isDirty('link_url') || ($model->link_url && empty($model->link_preview_title))) {
                if ($model->link_url) {
                    $metadata = static::fetchLinkMetadata($model->link_url);
                    if ($metadata) {
                        $model->link_preview_title = $metadata['title'];
                        $model->link_preview_description = $metadata['description'];
                        $model->link_preview_image = $metadata['image'];
                    } else {
                        $model->link_preview_title = null;
                        $model->link_preview_description = null;
                        $model->link_preview_image = null;
                    }
                } else {
                    $model->link_preview_title = null;
                    $model->link_preview_description = null;
                    $model->link_preview_image = null;
                }
            }
        });
    }

    /**
     * Crawl and parse HTML for meta headers using robust DOMDocument.
     */
    public static function fetchLinkMetadata(string $url): ?array
    {
        try {
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $url = 'https://' . $url;
            }

            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
                    ]
                ])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.0.0 Safari/537.36'
                ])->timeout(8)->get($url);

            if ($response->successful()) {
                $html = $response->body();

                $dom = new \DOMDocument();
                // Suppress parse warnings for invalid elements or HTML5 structures
                @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

                $title = null;
                $description = null;
                $image = null;

                // 1. Tag fallback title
                $titleTags = $dom->getElementsByTagName('title');
                if ($titleTags->length > 0) {
                    $title = trim($titleTags->item(0)->textContent);
                }

                // 2. Loop meta elements
                $metaTags = $dom->getElementsByTagName('meta');
                foreach ($metaTags as $meta) {
                    $property = strtolower($meta->getAttribute('property'));
                    $name = strtolower($meta->getAttribute('name'));
                    $content = trim($meta->getAttribute('content'));

                    if ($property === 'og:title' || $name === 'twitter:title') {
                        $title = $content;
                    }
                    if ($property === 'og:description' || $name === 'description' || $name === 'twitter:description') {
                        $description = $content;
                    }
                    if ($property === 'og:image' || $name === 'twitter:image' || $name === 'image') {
                        $image = $content;
                    }
                }

                // Resolve relative image URLs to absolute ones
                if ($image && !str_starts_with($image, 'http') && !str_starts_with($image, '//')) {
                    $parsed = parse_url($url);
                    $base = ($parsed['scheme'] ?? 'http') . '://' . ($parsed['host'] ?? '');
                    if (str_starts_with($image, '/')) {
                        $image = $base . $image;
                    } else {
                        $image = $base . '/' . $image;
                    }
                }

                return [
                    'title' => $title ? mb_strimwidth(html_entity_decode($title, ENT_QUOTES, 'UTF-8'), 0, 100, '...') : null,
                    'description' => $description ? mb_strimwidth(html_entity_decode($description, ENT_QUOTES, 'UTF-8'), 0, 200, '...') : null,
                    'image' => $image ?: null,
                ];
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return null;
    }
}
