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
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table for diseases and symptoms (many-to-many)
        Schema::create('disease_symptom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['disease_id', 'symptom_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_symptom');
        Schema::dropIfExists('diseases');
    }
};
