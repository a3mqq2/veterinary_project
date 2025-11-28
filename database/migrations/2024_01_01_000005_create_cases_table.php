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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();

            // Farm Owner Information
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();

            // Farm Information
            $table->string('farm_location')->nullable();
            $table->enum('farm_type', ['extensive', 'semi_intensive', 'intensive'])->nullable();
            $table->integer('flock_size')->nullable();
            $table->text('other_animals')->nullable();

            // Animal Case Information
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->enum('breed', ['local', 'imported', 'mixed'])->nullable();
            $table->integer('milking_ewes')->nullable();
            $table->integer('dry_ewes')->nullable();

            // Nutrition Program for Milking Ewes
            $table->string('milking_feed_type')->nullable();
            $table->string('milking_daily_consumption')->nullable();
            $table->string('milking_feeding_schedule')->nullable();
            $table->boolean('milking_mineral_vitamin')->nullable();

            // Nutrition Program for Dry Ewes
            $table->text('dry_ewes_nutrition')->nullable();

            // Lambs
            $table->text('lambs_health_problems')->nullable();

            // Vaccination History
            $table->text('vaccination_history')->nullable();

            // Medication Programs
            $table->text('medication_programs')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // Pivot table for case-symptom relationship
        Schema::create('case_symptom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['case_id', 'symptom_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_symptom');
        Schema::dropIfExists('cases');
    }
};
