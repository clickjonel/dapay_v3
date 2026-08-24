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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_clients');
            $table->integer('total_returning_clients');
            $table->foreignId('barangay_id')->constrained('barangays','id');
            $table->string('remarks');
            $table->string('status')->nullable();
            $table->string('action_by');
            // $table->foreignId('pk_activity_id')->nullable();
            $table->timestamps();
        });

        Schema::create('report_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports','id');
            $table->foreignId('indicator_id')->constrained('programmatic_indicators','id');
            $table->foreignId('program_id')->constrained('programs','id');
            $table->integer('total');
            $table->timestamps();
        });

        Schema::create('report_value_disaggregations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_value_id')->constrained('report_values','id');
            $table->foreignId('disaggregation_id')->constrained('disaggregations','id');
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
