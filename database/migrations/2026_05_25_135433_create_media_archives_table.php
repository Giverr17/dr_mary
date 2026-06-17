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
        Schema::create('media_archives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('media_type', ['video', 'audio']);
            $table->string('platform'); // 'youtube', 'spotify', 'vimeo', etc.
            $table->text('embed_url'); // Store full pasted URL
            $table->string('thumbnail_url')->nullable();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('duration')->nullable(); // e.g. "45:10" or "1 hr 15 mins"
            $table->date('recorded_at')->nullable();
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
        Schema::dropIfExists('media_archives');
    }
};
