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
        Schema::create('barangay_priority_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->foreignId('program_id')->constrained('programs','id');
            $table->integer('target');
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_priority_programs');
    }
};
