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
        Schema::create('similarity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('new_topic_id')->constrained('research_topics')->cascadeOnDelete();
            $table->foreignId('existing_topic_id')->constrained('research_topics')->cascadeOnDelete();
            $table->float('similarity_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('similarity_logs');
    }
};
