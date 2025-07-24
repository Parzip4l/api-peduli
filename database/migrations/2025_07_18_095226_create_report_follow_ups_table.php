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
        Schema::create('report_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('pic_id')->constrained('users')->onDelete('cascade'); // PIC yang mengisi TL
            $table->text('description');
            $table->string('attachment')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // QSHE
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('is_approved')->nullable(); // null = belum dinilai QSHE
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_follow_ups');
    }
};
