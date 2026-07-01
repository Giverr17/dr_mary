<?php

namespace App\Services;

use App\Enums\PublicationType;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
use Throwable;

class AiService
{
    /**
     * Structure a raw publication citation/description into form fields.
     *
     * Returns a normalized array:
     * [
     *   'success'  => bool,
     *   'title'    => string|null,
     *   'type'     => string|null,   // a valid PublicationType value, or null if AI's guess was invalid
     *   'year'     => int|null,
     *   'abstract' => string|null,
     *   'warnings' => string[],      // human-readable flags for anything ambiguous/invalid
     *   'message'  => string,        // status message for the admin
     * ]
     */
    public function structurePublication(string $rawText): array
    {
        // Build the list of valid types from the enum — single source of truth.
        $validTypes = array_map(fn ($c) => $c->value, PublicationType::cases());
        $typeList = implode(', ', $validTypes);

        $prompt = <<<PROMPT
        You are a metadata extraction assistant for an academic portfolio.
        Extract structured publication data from the text below.
        

        Return ONLY a JSON object (no markdown, no code fences, no commentary) with these exact keys:
        - "title": the full publication title as a string.
        - "type": MUST be exactly one of these values: {$typeList}. Use "Journal Article" if it appeared in a named journal; "Working Paper" or "Policy Brief" for non-peer-reviewed institutional output; "Book Chapter" if part of an edited volume; otherwise "Research Paper".        - "year": the 4-digit publication year as an integer. If not found, use null.
        - "abstract": a concise 2-4 sentence summary or the abstract if present. If none exists, write a brief neutral summary from the title.

        If a field cannot be determined, set it to null. Do not invent a DOI, authors, or facts not present.

        TEXT:
        {$rawText}
        PROMPT;

        try {
            $model = config('services.gemini.model');

            $response = Prism::text()
                ->using(Provider::Gemini, $model)
                ->withPrompt($prompt)
                ->asText()
                ->text;

            // Strip ```json fences if the model added them despite instructions.
            $clean = trim($response);
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);

            $data = json_decode($clean, true);

            if (! is_array($data)) {
                Log::warning('AiService: publication JSON decode failed', ['raw' => $response]);
                return $this->failure('AI returned an unreadable response — please fill the form manually.');
            }

            $warnings = [];

            // --- Validate title ---
            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (! $title) {
                $warnings[] = 'Could not extract a title — please add one.';
            }

            // --- Validate type against the real enum (single source of truth) ---
            $rawType = $data['type'] ?? null;
            $type = PublicationType::tryFrom((string) $rawType)?->value;
            if (! $type) {
                $warnings[] = "AI suggested an unrecognized type" . ($rawType ? " (\"{$rawType}\")" : '') . " — defaulted to the first type; please confirm.";
                $type = $validTypes[0] ?? null;
            }

            // --- Validate year ---
            $year = null;
            if (isset($data['year']) && is_numeric($data['year'])) {
                $year = (int) $data['year'];
                if ($year < 1900 || $year > 2100) {
                    $warnings[] = "AI returned an out-of-range year ({$year}) — please correct it.";
                    $year = null;
                }
            } else {
                $warnings[] = 'Could not determine the year — please add it.';
            }

            // --- Abstract ---
            $abstract = isset($data['abstract']) ? trim((string) $data['abstract']) : null;
            if (! $abstract || strlen($abstract) < 10) {
                $warnings[] = 'Abstract is missing or too short — please review.';
            }

            return [
                'success'  => true,
                'title'    => $title,
                'type'     => $type,
                'year'     => $year,
                'abstract' => $abstract,
                'warnings' => $warnings,
                'message'  => 'AI filled the form below — review and edit before saving.',
            ];
        } catch (Throwable $e) {
            Log::error('AiService: publication structuring failed', [
                'message' => $e->getMessage(),
            ]);

            return $this->failure('AI is busy right now — please fill the form manually.');
        }
    }

    private function failure(string $message): array
    {
        return [
            'success'  => false,
            'title'    => null,
            'type'     => null,
            'year'     => null,
            'abstract' => null,
            'warnings' => [],
            'message'  => $message,
        ];
    }

    /**
     * Structure a raw event description into form fields.
     *
     * Note: status (upcoming/past) is NOT extracted by the AI — it's derived
     * from date_start by the calling component, since it's pure date logic.
     *
     * Returns a normalized array:
     * [
     *   'success'         => bool,
     *   'title'           => string|null,
     *   'date_start'      => string|null,   // 'Y-m-d'
     *   'date_end'        => string|null,   // 'Y-m-d' or null
     *   'location'        => string|null,
     *   'time'            => string|null,
     *   'description'     => string|null,
     *   'role'            => string|null,
     *   'is_virtual'      => bool,
     *   'attendee_count'  => string|null,
     *   'warnings'        => string[],
     *   'message'         => string,
     * ]
     */
    public function structureEvent(string $rawText): array
    {
        $today = date('Y-m-d');

        $prompt = <<<PROMPT
        You are a metadata extraction assistant for an academic/professional portfolio.
        Extract structured event data from the text below.

        Return ONLY a JSON object (no markdown, no code fences, no commentary) with these exact keys:
        - "title": the event name as a string.
        - "date_start": the start date in strict YYYY-MM-DD format. If only a month/year is given, use the 1st of that month. If no date, use null.
        - "date_end": the end date in YYYY-MM-DD format for multi-day events, otherwise null.
        - "location": the city/venue/country as a string. If the event is online, use null and set is_virtual true.
        - "time": the time of day if stated (e.g. "2:00 PM WAT"), otherwise null.
        - "description": a concise 1-3 sentence summary of the event. If none, null.
        - "role": the person's role at the event if stated (e.g. "Keynote Speaker", "Panelist"), otherwise null.
        - "is_virtual": true if the event is online/virtual, otherwise false.
        - "attendee_count": attendance as a short string if stated (e.g. "500+ attendees"), otherwise null.

        Do not invent dates, locations, or facts not present in the text.

        TEXT:
        {$rawText}
        PROMPT;

        try {
            $model = config('services.gemini.model');

            $response = Prism::text()
                ->using(Provider::Gemini, $model)
                ->withPrompt($prompt)
                ->asText()
                ->text;

            $clean = trim($response);
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);

            $data = json_decode($clean, true);

            if (! is_array($data)) {
                Log::warning('AiService: event JSON decode failed', ['raw' => $response]);
                return $this->eventFailure('AI returned an unreadable response — please fill the form manually.');
            }

            $warnings = [];

            // --- Title ---
            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (! $title) {
                $warnings[] = 'Could not extract an event title — please add one.';
            }

            // --- date_start (required by your form) ---
            $dateStart = $this->normalizeDate($data['date_start'] ?? null);
            if (! $dateStart) {
                $warnings[] = 'Could not determine a start date — please set it before saving.';
            }

            // --- date_end (optional, must be >= start if present) ---
            $dateEnd = $this->normalizeDate($data['date_end'] ?? null);
            if ($dateEnd && $dateStart && $dateEnd < $dateStart) {
                $warnings[] = 'AI returned an end date before the start date — cleared it; please check.';
                $dateEnd = null;
            }

            // --- is_virtual + location ---
            $isVirtual = (bool) ($data['is_virtual'] ?? false);
            $location = isset($data['location']) ? trim((string) $data['location']) : null;
            if (! $location && ! $isVirtual) {
                $warnings[] = 'No location found — your form requires one; please add it.';
            }
            if ($isVirtual && ! $location) {
                $location = 'Virtual';
            }

            return [
                'success'        => true,
                'title'          => $title,
                'date_start'     => $dateStart,
                'date_end'       => $dateEnd,
                'location'       => $location,
                'time'           => $this->cleanString($data['time'] ?? null),
                'description'    => $this->cleanString($data['description'] ?? null),
                'role'           => $this->cleanString($data['role'] ?? null),
                'is_virtual'     => $isVirtual,
                'attendee_count' => $this->cleanString($data['attendee_count'] ?? null),
                'warnings'       => $warnings,
                'message'        => 'AI filled the form below — review and edit before saving.',
            ];
        } catch (Throwable $e) {
            Log::error('AiService: event structuring failed', [
                'message' => $e->getMessage(),
            ]);

            return $this->eventFailure('AI is busy right now — please fill the form manually.');
        }
    }

    /**
     * Normalize a date string to 'Y-m-d', or null if unparseable / out of sane range.
     */
    private function normalizeDate($value): ?string
    {
        if (! $value || ! is_string($value)) {
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }

        // Sanity bound — reject obviously wrong years.
        if ($date->year < 1950 || $date->year > 2100) {
            return null;
        }

        return $date->format('Y-m-d');
    }

    private function cleanString($value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $value = trim($value);
        return $value !== '' ? $value : null;
    }

    private function eventFailure(string $message): array
    {
        return [
            'success'        => false,
            'title'          => null,
            'date_start'     => null,
            'date_end'       => null,
            'location'       => null,
            'time'           => null,
            'description'    => null,
            'role'           => null,
            'is_virtual'     => false,
            'attendee_count' => null,
            'warnings'       => [],
            'message'        => $message,
        ];
    }

    /**
     * Structure a raw achievement/award description into form fields.
     *
     * Returns a normalized array:
     * [
     *   'success'      => bool,
     *   'title'        => string|null,
     *   'description'  => string|null,
     *   'year'         => int|null,
     *   'category'     => string|null,   // a valid AchievementCategory value
     *   'issuing_body' => string|null,
     *   'warnings'     => string[],
     *   'message'      => string,
     * ]
     */
    public function structureAchievement(string $rawText): array
    {
        $validCategories = array_map(fn ($c) => $c->value, \App\Enums\AchievementCategory::cases());
        $categoryList = implode(', ', $validCategories);

        $prompt = <<<PROMPT
        You are a metadata extraction assistant for an academic/professional portfolio.
        Extract structured achievement/award data from the text below.

        Return ONLY a JSON object (no markdown, no code fences, no commentary) with these exact keys:
        - "title": the name of the award, honor, or achievement as a string.
        - "category": MUST be exactly one of these values: {$categoryList}. Use "Award" for prizes/honors, "Recognition" for non-prize acknowledgements, "Fellowship" for fellowships/memberships, "Certification" for credentials/certificates, and "Other" if none fit.
        - "year": the 4-digit year it was received as an integer. If not found, use null.
        - "issuing_body": the organization that granted it (e.g. "World Health Organization"), or null if not stated.
        - "description": a concise 1-3 sentence summary of the achievement and its significance. If none, write a brief neutral summary from the title.

        Do not invent an issuing body, year, or facts not present in the text.

        TEXT:
        {$rawText}
        PROMPT;

        try {
            $model = config('services.gemini.model');

            $response = Prism::text()
                ->using(Provider::Gemini, $model)
                ->withPrompt($prompt)
                ->asText()
                ->text;

            $clean = trim($response);
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);

            $data = json_decode($clean, true);

            if (! is_array($data)) {
                Log::warning('AiService: achievement JSON decode failed', ['raw' => $response]);
                return $this->achievementFailure('AI returned an unreadable response — please fill the form manually.');
            }

            $warnings = [];

            // --- Title ---
            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (! $title) {
                $warnings[] = 'Could not extract a title — please add one.';
            }

            // --- Category against the real enum ---
            $rawCategory = $data['category'] ?? null;
            $category = \App\Enums\AchievementCategory::tryFrom((string) $rawCategory)?->value;
            if (! $category) {
                $warnings[] = "AI suggested an unrecognized category" . ($rawCategory ? " (\"{$rawCategory}\")" : '') . " — defaulted to \"Award\"; please confirm.";
                $category = \App\Enums\AchievementCategory::Award->value;
            }

            // --- Year ---
            $year = null;
            if (isset($data['year']) && is_numeric($data['year'])) {
                $year = (int) $data['year'];
                if ($year < 1900 || $year > 2100) {
                    $warnings[] = "AI returned an out-of-range year ({$year}) — please correct it.";
                    $year = null;
                }
            } else {
                $warnings[] = 'Could not determine the year — please add it.';
            }

            // --- Description ---
            $description = isset($data['description']) ? trim((string) $data['description']) : null;
            if (! $description || strlen($description) < 5) {
                $warnings[] = 'Description is missing or too short — please review.';
            }

            return [
                'success'      => true,
                'title'        => $title,
                'description'  => $description,
                'year'         => $year,
                'category'     => $category,
                'issuing_body' => $this->cleanString($data['issuing_body'] ?? null),
                'warnings'     => $warnings,
                'message'      => 'AI filled the form below — review and edit before saving.',
            ];
        } catch (Throwable $e) {
            Log::error('AiService: achievement structuring failed', [
                'message' => $e->getMessage(),
            ]);

            return $this->achievementFailure('AI is busy right now — please fill the form manually.');
        }
    }

    private function achievementFailure(string $message): array
    {
        return [
            'success'      => false,
            'title'        => null,
            'description'  => null,
            'year'         => null,
            'category'     => null,
            'issuing_body' => null,
            'warnings'     => [],
            'message'      => $message,
        ];
    }
    /**
     * Read an uploaded PDF and structure its metadata into publication form fields.
     *
     * @param string $pdfPath  Absolute path to the PDF on disk (e.g. the Livewire
     *                         temporary upload's real path).
     *
     * Returns the same normalized shape as structurePublication().
     */
    public function structurePublicationFromPdf(string $pdfPath): array
    {
        if (! is_file($pdfPath)) {
            return $this->failure('Could not read the uploaded file — please try again or fill the form manually.');
        }

        $validTypes = array_map(fn ($c) => $c->value, PublicationType::cases());
        $typeList = implode(', ', $validTypes);

        $prompt = <<<PROMPT
        You are a metadata extraction assistant for an academic portfolio.
        Read the attached PDF (an academic publication) and extract its metadata,
        looking primarily at the first page, title block, and abstract.

        Return ONLY a JSON object (no markdown, no code fences, no commentary) with these exact keys:
        - "title": the full publication title as a string.
        - "type": MUST be exactly one of these values: {$typeList}. Pick the closest match based on the document. Use "Journal Article" if it shows a journal name and volume; otherwise the closest fit.
        - "year": the 4-digit publication year as an integer. If not found, use null.
        - "abstract": the paper's abstract if present, otherwise a concise 2-4 sentence summary of the paper. Keep it under ~150 words.

        If a field cannot be determined from the document, set it to null. Do not invent facts not present in the document.
        PROMPT;

        try {
            $model = config('services.gemini.model');

            $response = Prism::text()
                ->using(Provider::Gemini, $model)
                ->withPrompt($prompt, [
                    \Prism\Prism\ValueObjects\Media\Document::fromLocalPath($pdfPath),
                ])
                ->asText()
                ->text;

            $clean = trim($response);
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);

            $data = json_decode($clean, true);

            if (! is_array($data)) {
                Log::warning('AiService: publication PDF JSON decode failed', ['raw' => $response]);
                return $this->failure('AI could not read structured data from that PDF — please fill the form manually.');
            }

            $warnings = [];

            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (! $title) {
                $warnings[] = 'Could not extract a title from the PDF — please add one.';
            }

            $rawType = $data['type'] ?? null;
            $type = PublicationType::tryFrom((string) $rawType)?->value;
            if (! $type) {
                $warnings[] = "AI suggested an unrecognized type" . ($rawType ? " (\"{$rawType}\")" : '') . " — defaulted to the first type; please confirm.";
                $type = $validTypes[0] ?? null;
            }

            $year = null;
            if (isset($data['year']) && is_numeric($data['year'])) {
                $year = (int) $data['year'];
                if ($year < 1900 || $year > 2100) {
                    $warnings[] = "AI returned an out-of-range year ({$year}) — please correct it.";
                    $year = null;
                }
            } else {
                $warnings[] = 'Could not determine the year from the PDF — please add it.';
            }

            $abstract = isset($data['abstract']) ? trim((string) $data['abstract']) : null;
            if (! $abstract || strlen($abstract) < 10) {
                $warnings[] = 'Abstract is missing or too short — please review.';
            }

            return [
                'success'  => true,
                'title'    => $title,
                'type'     => $type,
                'year'     => $year,
                'abstract' => $abstract,
                'warnings' => $warnings,
                'message'  => 'AI read the PDF and filled the form below — review and edit before saving.',
            ];
        } catch (Throwable $e) {
            Log::error('AiService: publication PDF structuring failed', [
                'message' => $e->getMessage(),
            ]);

            return $this->failure('AI is busy right now — please fill the form manually.');
        }
    }
}