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
        Schema::create('pk_activities', function (Blueprint $table) {
            $table->id();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('activity_name');
            $table->string('type');
            $table->integer('total_clients');
            $table->foreignId('submitted_by')->nullable()->constrained('users','id');
            $table->timestamps();
        });

        Schema::create('pk_activity_barangays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pk_activity_id')->constrained('pk_activities','id');
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->boolean('host');
            $table->timestamps();
        });

        Schema::create('pk_activity_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pk_activity_id')->constrained('pk_activities','id');
            $table->foreignId('program_id')->constrained('programs','id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_k_activities');
    }
};
