<?php

use App\Enums\AchievementCategory;
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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('year');
            $table->string('category')->default(AchievementCategory::Award->value); // backed by App\Enums\AchievementCategory
            $table->string('issuing_body')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_label')->nullable();
            $table->string('link_preview_title')->nullable();
            $table->text('link_preview_description')->nullable();
            $table->text('link_preview_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
