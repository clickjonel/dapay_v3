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
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();

            // details
            $table->string('psgc_code')->nullable();
            $table->string('name');
            $table->foreignId('province_id')->constrained('provinces','id');
            $table->foreignId('municipality_id')->constrained('municipalities','id');
            $table->text('mov_link')->nullable();

            //pk profile
            $table->string('pk_status')->nullable();
            $table->boolean('pk_site')->nullable();

            //geography
            $table->decimal('latitude', 20, 15)->nullable();
            $table->decimal('longitude', 20, 15)->nullable();
            $table->boolean('is_gida')->nullable();

            //populations
            $table->integer('total_population')->nullable();
            $table->integer('target_population')->nullable();
            $table->integer('total_puroks')->nullable();
            $table->integer('target_puroks')->nullable();
            $table->integer('total_households')->nullable();
            $table->integer('target_households')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};
