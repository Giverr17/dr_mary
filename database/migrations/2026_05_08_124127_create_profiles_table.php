<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('title_line');
            $table->string('hero_tagline');
            $table->text('bio_paragraph_1');
            $table->text('bio_paragraph_2');
            $table->text('bio_paragraph_3');
            $table->json('expertise_tags')->default('[]');
            $table->string('stat_years');
            $table->string('stat_focus');
            $table->string('stat_approach');
            $table->string('email');
            $table->string('location');
            $table->string('response_time');
            $table->string('website_url');
            $table->string('scholar_url');
            $table->string('linkedin_url');
            $table->string('booking_url');
            $table->json('social_links')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('speaker_kit_path')->nullable();
            $table->text('footer_tagline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
