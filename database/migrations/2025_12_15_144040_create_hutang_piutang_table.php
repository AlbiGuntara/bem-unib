<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hutang_piutang', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['Hutang', 'Piutang']);
            $table->date('tanggal');

            $table->string('nama');
            $table->string('foto')->nullable();
            $table->string('kontak')->nullable();

            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->date('jatuh_tempo')->nullable();
            $table->string('bukti_transaksi')->nullable();
            $table->enum('status', ['Belum', 'Lunas'])->default('Belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hutang_piutang');
    }
};
