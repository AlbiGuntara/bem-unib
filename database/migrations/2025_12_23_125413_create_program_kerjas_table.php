<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->text('tujuan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            $table->foreignId('jabatan_id')
                ->nullable()
                ->constrained('jabatans')
                ->nullOnDelete();

            $table->string('peserta')->nullable();
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kerjas');
    }
};
