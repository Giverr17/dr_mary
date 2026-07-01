<?php

namespace App\Enums;

enum MediaType: string
{
    case Video = 'video';
    case Audio = 'audio';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Video => 'Video',
            self::Audio => 'Audio',
        };
    }

    /**
     * [value => label] map for building select dropdowns.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $case) => $carry + [$case->value => $case->label()],
            []
        );
    }
}
