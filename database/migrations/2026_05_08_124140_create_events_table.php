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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->string('location');
            $table->string('time')->nullable();
            $table->text('description')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_virtual')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('registration_url')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_label')->nullable();
            $table->string('attendee_count')->nullable();
            $table->json('stats')->nullable();
            $table->enum('status', ['upcoming', 'past'])->default('upcoming');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
