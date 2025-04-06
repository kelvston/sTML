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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('research_topics')->cascadeOnDelete();
            $table->enum('stage', ['similarity_check', 'supervisor_assignment', 'supervisor_approval', 'final_approval']);
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->foreignId('action_by')->constrained('users')->cascadeOnDelete();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
