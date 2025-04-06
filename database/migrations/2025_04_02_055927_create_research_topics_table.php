<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('research_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('title');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->float('similarity_score')->nullable();
            $table->foreignId('similar_to_id')->nullable()->constrained('research_topics')->nullOnDelete();
            $table->timestamps();

            $table->unique(['title']);
        });

        // Add the tsvector column separately
        DB::statement('ALTER TABLE research_topics ADD COLUMN title_vector tsvector');

        // Add PostgreSQL GIN index for full-text search
        DB::statement('CREATE INDEX research_topics_title_vector_idx ON research_topics USING GIN(title_vector)');
    }

    public function down(): void
    {
        Schema::dropIfExists('research_topics');
    }
};
