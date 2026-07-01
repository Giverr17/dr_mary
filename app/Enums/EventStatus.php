<?php

namespace App\Enums;

enum EventStatus: string
{
    case Upcoming = 'upcoming';
    case Past = 'past';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Upcoming => 'Upcoming',
            self::Past => 'Past',
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
