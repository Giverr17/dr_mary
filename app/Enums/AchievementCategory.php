<?php

namespace App\Enums;

enum AchievementCategory: string
{
    case Award = 'Award';
    case Recognition = 'Recognition';
    case Fellowship = 'Fellowship';
    case Certification = 'Certification';
    case Other = 'Other';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Award => 'Award',
            self::Recognition => 'Recognition',
            self::Fellowship => 'Fellowship',
            self::Certification => 'Certification',
            self::Other => 'Other',
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
