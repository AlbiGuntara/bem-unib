<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_warga')->constrained('wargas')->cascadeOnDelete();
            $table->foreignId('id_program_kerja')->constrained('program_kerjas')->cascadeOnDelete();

            $table->enum('keterangan', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');
            $table->integer('skor')->default(0);
            $table->enum('pengurusan', ['sudah', 'belum'])->default('belum');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
