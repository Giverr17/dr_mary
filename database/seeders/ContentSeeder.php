<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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

        $modelClass::truncate();

        $now = now();

        if ($isSingleton) {
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $modelClass::insert($data);
        } else {
            $rows = array_map(function ($item) use ($now) {
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
                return $item;
            }, $data);

            $modelClass::insert($rows);
        }
    }
}
