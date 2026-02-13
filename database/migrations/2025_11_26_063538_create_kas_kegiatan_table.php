<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kas_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->date('tanggal');
            $table->string('uraian');
            $table->enum('jenis', ['Debet', 'Kredit']);

            $table->foreignId('kategori_id')->constrained('kas_kategori')->cascadeOnDelete();

            $table->decimal('nominal', 15, 2);
            $table->string('bukti_transaksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_kegiatan');
    }
};
