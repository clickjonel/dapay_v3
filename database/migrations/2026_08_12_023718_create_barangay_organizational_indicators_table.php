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
        Schema::create('barangay_organizational_indicators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('barangay_id')
                ->constrained('barangays', 'id');

            $table->foreignId('organizational_indicator_id')
                ->constrained('organizational_indicators', 'id', 'boi_org_indicator_foreign');

            $table->integer('total')->nullable();
            $table->integer('community_based')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_organizational_indicators');
    }
};
