<?php

namespace App\Enums;

enum PublicationType: string
{
    case ResearchPaper = 'Research Paper';
    case PolicyBrief = 'Policy Brief';
    case BookChapter = 'Book Chapter';
    case WorkingPaper = 'Working Paper';
    case JournalArticle = 'Journal Article';

    /**
     * Human-readable label for UI display.
     */
    public function label(): string
    {
        return match ($this) {
            self::ResearchPaper => 'Research Paper',
            self::PolicyBrief => 'Policy Brief',
            self::BookChapter => 'Book Chapter',
            self::WorkingPaper => 'Working Paper',
            self::JournalArticle => 'Journal Article',
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
