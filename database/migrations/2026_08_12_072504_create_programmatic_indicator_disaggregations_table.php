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
        Schema::create('programmatic_indicator_disaggregations', function (Blueprint $table) {
            $table->id();
 
            $table->foreignId('programmatic_indicator_id')
                ->constrained('programmatic_indicators', 'id', 'pid_disagg_indicator_id_foreign');
 
            $table->foreignId('disaggregation_id')
                ->constrained('disaggregations', 'id', 'pid_disagg_disaggregation_id_foreign');
 
            $table->boolean('totalable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmatic_indicator_disaggregations');
    }
};
