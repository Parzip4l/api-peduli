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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_laporan')->unique();
            $table->string('judul');
            $table->timestamp('tanggal_laporan');
            
            $table->foreignId('observation_type_id')->constrained('observation_types');
            $table->foreignId('location_id')->constrained('locations');
            $table->string('detail_lokasi');
            
            $table->text('keterangan');
            $table->foreignId('hazard_potential_id')->nullable()->constrained('hazard_potentials');
            
            $table->boolean('perlu_tindak_lanjut')->default(false);
            $table->enum('status', ['open', 'rejected_by_qshe', 'assigned_to_division', 'rejected_by_pic', 'closed','follow_up_submitted','under_review_by_qshe','follow_up_rejected'])->default('open');

            $table->foreignId('division_id')->nullable()->constrained();
            $table->foreignId('assigned_to')->nullable()->constrained('employee');
            $table->string('foto')->nullable();
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
