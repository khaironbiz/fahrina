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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 100);
            $table->string('email', 150)->index();
            $table->unsignedTinyInteger('age');
            $table->string('gender', 10);
            $table->string('department', 50);
            $table->string('work_tenure', 50);
            $table->string('screen_time', 50);
            
            $table->json('nbm_responses');
            $table->json('rosa_responses');
            $table->unsignedSmallInteger('nbm_total_score');
            $table->unsignedSmallInteger('rosa_total_score');
            $table->unsignedSmallInteger('total_score');
            
            $table->string('risk_level', 30)->index();
            $table->string('risk_label', 100);
            $table->text('risk_description')->nullable();
            $table->json('smart_recommendations')->nullable();
            
            $table->string('pdf_path', 255)->nullable();
            $table->string('email_status', 20)->default('pending');
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
