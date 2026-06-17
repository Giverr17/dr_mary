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
        Schema::table('achievements', function (Blueprint $table) {
            $table->string('link_preview_title')->nullable()->after('link_label');
            $table->text('link_preview_description')->nullable()->after('link_preview_title');
            $table->text('link_preview_image')->nullable()->after('link_preview_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn(['link_preview_title', 'link_preview_description', 'link_preview_image']);
        });
    }
};
