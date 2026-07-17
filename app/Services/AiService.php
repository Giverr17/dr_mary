<?php

namespace App\Services;

use App\Enums\PublicationType;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismProviderOverloadedException;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Exceptions\PrismRequestTooLargeException;
use Prism\Prism\Exceptions\PrismServerException;
use Prism\Prism\Facades\Prism;
use Throwable;

class AiService
{
    // ──────────────────────────────────────────────────────────────────────────
    // Core fallback engine
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Single entry point for every AI text call.
     *
     * Fallback chain:
     *   1. PRIMARY  = Gemini (config: services.ai.model).
     *      Retried up to 3× on transient blips with exponential backoff + jitter.
     *   2. FALLBACK = Groq  (config: services.ai.fallback_model).
     *      Used once if Gemini is rate-limited or all retries are exhausted.
     *
     * API keys are resolved by Prism from config/prism.php
     * (GEMINI_API_KEY / GROQ_API_KEY).
     */
    private function runPrompt(string $prompt): string
    {
        $primaryModel  = config('services.ai.model', 'gemini-2.5-flash');
        $fallbackModel = config('services.ai.fallback_model', 'llama-3.3-70b-versatile');

        // ── PRIMARY: Gemini, bounded exponential backoff + jitter ────────────
        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return $this->callPrism(Provider::Gemini, $primaryModel, $prompt);
            } catch (Throwable $e) {
                $rateLimited = $e instanceof PrismRateLimitedException;

                // Retry only on transient non-rate-limit blips (250 ms, 500 ms …)
                if ($attempt < $maxAttempts && !$rateLimited && $this->isTransient($e)) {
                    usleep((int) (250_000 * (2 ** ($attempt - 1))) + random_int(0, 250_000));
                    continue;
                }

                // Rate-limited or transient error that outlived all retries → failover
                if ($rateLimited || $this->isTransient($e)) {
                    break;
                }

                // Any other error (auth, bad request, etc.) — bubble up unchanged
                throw $e;
            }
        }

        // ── FALLBACK: Groq, single attempt ───────────────────────────────────
        Log::warning('AiService failover: Gemini → Groq', [
            'primary_model'  => $primaryModel,
            'fallback_model' => $fallbackModel,
        ]);

        return $this->callPrism(Provider::Groq, $fallbackModel, $prompt);
    }

    /**
     * Same as runPrompt() but attaches a document (e.g. a PDF) to the request.
     * Falls back to Groq on Gemini failures; note that Groq does not support
     * document inputs, so the fallback is text-only from the prompt.
     */
    private function runPromptWithDocument(string $prompt, string $filePath): string
    {
        $primaryModel  = config('services.ai.model', 'gemini-2.5-flash');
        $fallbackModel = config('services.ai.fallback_model', 'llama-3.3-70b-versatile');

        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $response = Prism::text()
                    ->using(Provider::Gemini, $primaryModel)
                    ->withPrompt($prompt, [
                        \Prism\Prism\ValueObjects\Media\Document::fromLocalPath($filePath),
                    ])
                    ->withClientOptions(['timeout' => 30, 'connect_timeout' => 5])
                    ->asText();

                return trim($response->text);
            } catch (Throwable $e) {
                $rateLimited = $e instanceof PrismRateLimitedException;

                if ($attempt < $maxAttempts && !$rateLimited && $this->isTransient($e)) {
                    usleep((int) (250_000 * (2 ** ($attempt - 1))) + random_int(0, 250_000));
                    continue;
                }

                if ($rateLimited || $this->isTransient($e)) {
                    break;
                }

                throw $e;
            }
        }

        // Groq fallback — text-only (no document), Groq cannot read PDFs
        Log::warning('AiService PDF failover: Gemini → Groq (text-only)', [
            'primary_model'  => $primaryModel,
            'fallback_model' => $fallbackModel,
        ]);

        return $this->callPrism(Provider::Groq, $fallbackModel, $prompt);
    }

    /** One Prism text call against a specific provider + model. */
    private function callPrism(Provider $provider, string $model, string $prompt): string
    {
        $response = Prism::text()
            ->using($provider, $model)
            ->withPrompt($prompt)
            ->withClientOptions(['timeout' => 30, 'connect_timeout' => 5])
            ->asText();

        return trim($response->text);
    }

    /** Returns true for errors that are worth retrying (busy / transient). */
    private function isTransient(Throwable $e): bool
    {
        if (
            $e instanceof PrismProviderOverloadedException
            || $e instanceof PrismServerException
            || $e instanceof PrismRateLimitedException
            || $e instanceof ConnectionException
        ) {
            return true;
        }

        $msg = strtolower($e->getMessage());
        return Str::contains($msg, [
            'overloaded', 'curl error', 'unexpected eof', 'ssl',
            'timed out', 'rate', 'quota', 'resource has been exhausted',
        ]);
    }

    /** Strip the model's JSON fences and return decoded array or null. */
    private function decodeJson(string $raw): ?array
    {
        $clean = trim($raw);
        $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
        $clean = preg_replace('/\s*```$/', '', $clean);
        $data  = json_decode(trim($clean), true);
        return is_array($data) ? $data : null;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Public methods
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Structure a raw publication citation/description into form fields.
     *
     * Returns a normalized array:
     * [
     *   'success'  => bool,
     *   'title'    => string|null,
     *   'type'     => string|null,   // a valid PublicationType value, or null
     *   'year'     => int|null,
     *   'abstract' => string|null,
     *   'warnings' => string[],
     *   'message'  => string,
     * ]
     */
    public function structurePublication(string $rawText): array
    {
        $validTypes = array_map(fn ($c) => $c->value, PublicationType::cases());
        $typeList   = implode(', ', $validTypes);

        $prompt = <<<PROMPT
        You are a metadata extraction assistant for an academic portfolio.
        Extract structured publication data from the text below.

        Return ONLY a JSON object (no markdown, no code fences, no commentary) with these exact keys:
        - "title": the full publication title as a string.
        - "type": MUST be exactly one of these values: {$typeList}. Use "Journal Article" if it appeared in a named journal; "Working Paper" or "Policy Brief" for non-peer-reviewed institutional output; "Book Chapter" if part of an edited volume; otherwise "Research Paper".
        - "year": the 4-digit publication year as an integer. If not found, use null.
        - "abstract": a concise 2-4 sentence summary or the abstract if present. If none exists, write a brief neutral summary from the title.

        If a field cannot be determined, set it to null. Do not invent a DOI, authors, or facts not present.

        TEXT:
        {$rawText}
        PROMPT;

        try {
            $data = $this->decodeJson($this->runPrompt($prompt));

            if ($data === null) {
                Log::warning('AiService: publication JSON decode failed');
                return $this->failure('AI returned an unreadable response — please fill the form manually.');
            }

            $warnings = [];

            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (!$title) {
                $warnings[] = 'Could not extract a title — please add one.';
            }

            $rawType = $data['type'] ?? null;
            $type    = PublicationType::tryFrom((string) $rawType)?->value;
            if (!$type) {
                $warnings[] = 'AI suggested an unrecognized type' . ($rawType ? " (\"{$rawType}\")" : '') . ' — defaulted to the first type; please confirm.';
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
                $warnings[] = 'Could not determine the year — please add it.';
            }

            $abstract = isset($data['abstract']) ? trim((string) $data['abstract']) : null;
            if (!$abstract || strlen($abstract) < 10) {
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
            Log::error('AiService: publication structuring failed', ['message' => $e->getMessage()]);
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
            $data = $this->decodeJson($this->runPrompt($prompt));

            if ($data === null) {
                Log::warning('AiService: event JSON decode failed');
                return $this->eventFailure('AI returned an unreadable response — please fill the form manually.');
            }

            $warnings = [];

            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (!$title) {
                $warnings[] = 'Could not extract an event title — please add one.';
            }

            $dateStart = $this->normalizeDate($data['date_start'] ?? null);
            if (!$dateStart) {
                $warnings[] = 'Could not determine a start date — please set it before saving.';
            }

            $dateEnd = $this->normalizeDate($data['date_end'] ?? null);
            if ($dateEnd && $dateStart && $dateEnd < $dateStart) {
                $warnings[] = 'AI returned an end date before the start date — cleared it; please check.';
                $dateEnd = null;
            }

            $isVirtual = (bool) ($data['is_virtual'] ?? false);
            $location  = isset($data['location']) ? trim((string) $data['location']) : null;
            if (!$location && !$isVirtual) {
                $warnings[] = 'No location found — your form requires one; please add it.';
            }
            if ($isVirtual && !$location) {
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
            Log::error('AiService: event structuring failed', ['message' => $e->getMessage()]);
            return $this->eventFailure('AI is busy right now — please fill the form manually.');
        }
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
        $categoryList    = implode(', ', $validCategories);

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
            $data = $this->decodeJson($this->runPrompt($prompt));

            if ($data === null) {
                Log::warning('AiService: achievement JSON decode failed');
                return $this->achievementFailure('AI returned an unreadable response — please fill the form manually.');
            }

            $warnings = [];

            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (!$title) {
                $warnings[] = 'Could not extract a title — please add one.';
            }

            $rawCategory = $data['category'] ?? null;
            $category    = \App\Enums\AchievementCategory::tryFrom((string) $rawCategory)?->value;
            if (!$category) {
                $warnings[] = 'AI suggested an unrecognized category' . ($rawCategory ? " (\"{$rawCategory}\")" : '') . ' — defaulted to "Award"; please confirm.';
                $category = \App\Enums\AchievementCategory::Award->value;
            }

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

            $description = isset($data['description']) ? trim((string) $data['description']) : null;
            if (!$description || strlen($description) < 5) {
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
            Log::error('AiService: achievement structuring failed', ['message' => $e->getMessage()]);
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
     * @param string $pdfPath  Absolute path to the PDF on disk.
     *
     * Returns the same normalized shape as structurePublication().
     */
    public function structurePublicationFromPdf(string $pdfPath): array
    {
        if (!is_file($pdfPath)) {
            return $this->failure('Could not read the uploaded file — please try again or fill the form manually.');
        }

        $validTypes = array_map(fn ($c) => $c->value, PublicationType::cases());
        $typeList   = implode(', ', $validTypes);

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
            $data = $this->decodeJson($this->runPromptWithDocument($prompt, $pdfPath));

            if ($data === null) {
                Log::warning('AiService: publication PDF JSON decode failed');
                return $this->failure('AI could not read structured data from that PDF — please fill the form manually.');
            }

            $warnings = [];

            $title = isset($data['title']) ? trim((string) $data['title']) : null;
            if (!$title) {
                $warnings[] = 'Could not extract a title from the PDF — please add one.';
            }

            $rawType = $data['type'] ?? null;
            $type    = PublicationType::tryFrom((string) $rawType)?->value;
            if (!$type) {
                $warnings[] = 'AI suggested an unrecognized type' . ($rawType ? " (\"{$rawType}\")" : '') . ' — defaulted to the first type; please confirm.';
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
            if (!$abstract || strlen($abstract) < 10) {
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
            Log::error('AiService: publication PDF structuring failed', ['message' => $e->getMessage()]);
            return $this->failure('AI is busy right now — please fill the form manually.');
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /** Normalize a date string to 'Y-m-d', or null if unparseable / out of sane range. */
    private function normalizeDate($value): ?string
    {
        if (!$value || !is_string($value)) {
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }

        if ($date->year < 1950 || $date->year > 2100) {
            return null;
        }

        return $date->format('Y-m-d');
    }

    private function cleanString($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }
        $value = trim($value);
        return $value !== '' ? $value : null;
    }
}