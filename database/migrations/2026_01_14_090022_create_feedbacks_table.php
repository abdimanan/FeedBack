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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('statement_1_rating')->comment('Service quality met expectations (1-5)');
            $table->unsignedTinyInteger('statement_2_rating')->comment('Communication was clear and professional (1-5)');
            $table->unsignedTinyInteger('statement_3_rating')->comment('Delivery time was satisfactory (1-5)');
            $table->text('likes_text')->nullable();
            $table->text('dislikes_text')->nullable();
            $table->unsignedTinyInteger('overall_rating')->comment('Overall rating (1-5)');
            $table->timestamp('created_at');

            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
