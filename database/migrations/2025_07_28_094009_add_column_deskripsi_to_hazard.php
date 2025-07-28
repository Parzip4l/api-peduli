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
        Schema::table('hazard_potentials', function (Blueprint $table) {
            $table->string('deskripsi')->nullable();
            $table->integer('klasifikasi_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazard', function (Blueprint $table) {
            //
        });
    }
};
