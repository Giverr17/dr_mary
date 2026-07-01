<?php

namespace App\Enums;

enum MessageSubject: string
{
    case ConsultingInquiry = 'Consulting Inquiry';
    case SpeakingEngagement = 'Speaking Engagement';
    case ResearchCollaboration = 'Research Collaboration';
    case MediaPressInquiry = 'Media / Press Inquiry';
    case Other = 'Other';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::ConsultingInquiry => 'Consulting Inquiry',
            self::SpeakingEngagement => 'Speaking Engagement',
            self::ResearchCollaboration => 'Research Collaboration',
            self::MediaPressInquiry => 'Media / Press Inquiry',
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
