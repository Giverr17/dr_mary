<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedModel(\App\Models\Profile::class, 'profile.json', true);
        $this->seedModel(\App\Models\Publication::class, 'publications.json');
        $this->seedModel(\App\Models\ResearchFocusArea::class, 'research_focus.json');
        $this->seedModel(\App\Models\Credential::class, 'credentials.json');
        $this->seedModel(\App\Models\CoreValue::class, 'core_values.json');
        $this->seedModel(\App\Models\ConsultingService::class, 'consulting_services.json');
        $this->seedModel(\App\Models\ProcessStep::class, 'process_steps.json');
        $this->seedModel(\App\Models\Testimonial::class, 'testimonials.json');
        $this->seedModel(\App\Models\Event::class, 'events.json');
        $this->seedModel(\App\Models\Faq::class, 'faqs.json');
    }

    private function seedModel(string $modelClass, string $fileName, bool $isSingleton = false): void
    {
        $path = database_path('seeders/data/' . $fileName);
        if (!file_exists($path)) return;

        $data = json_decode(file_get_contents($path), true);

        // TRUNCATE is blocked by MySQL when the table is referenced by a foreign
        // key (e.g. events <- event_registrations / media_archives), so disable
        // constraint checks around it.
        Schema::disableForeignKeyConstraints();
        $modelClass::truncate();
        Schema::enableForeignKeyConstraints();

        $now = now();

        // The query-builder insert() used below bypasses Eloquent casts, so any
        // array value (json/array-cast columns) must be encoded to a string here.
        $prepare = function (array $row) use ($now) {
            foreach ($row as $key => $value) {
                if (\is_array($value)) {
                    $row[$key] = json_encode($value);
                }
            }
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        };

        if ($isSingleton) {
            $modelClass::insert($prepare($data));
        } else {
            $modelClass::insert(array_map($prepare, $data));
        }
    }
}
