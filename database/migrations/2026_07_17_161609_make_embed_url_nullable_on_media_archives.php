<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Makes embed_url nullable so that audio-only media items (e.g. Apple Podcasts,
     * Spotify podcast links) can be saved with only an audio_url and no video embed.
     */
    public function up(): void
    {
        Schema::table('media_archives', function (Blueprint $table) {
            $table->text('embed_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_archives', function (Blueprint $table) {
            $table->text('embed_url')->nullable(false)->change();
        });
    }
};
