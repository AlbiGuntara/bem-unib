<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wargas', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nama');
            $table->string('nis')->unique();
            $table->string('asrama')->nullable();

            $table->enum('pendidikan_pagi', ['MI', 'MTs', 'MA', 'MTI', 'MDI', 'Maly', 'Lulus'])->nullable();
            $table->string('no_induk_pendidikan_pagi')->nullable();
            $table->string('kelas_pendidikan_pagi')->nullable();

            $table->enum('pendidikan_sore', ['SD', 'SMP', 'SMA', 'SMK', 'PT', 'Lulus'])->nullable();
            $table->string('no_induk_pendidikan_sore')->nullable();
            $table->string('kelas_pendidikan_sore')->nullable();

            $table->enum('pendidikan_malam', ['Qiraati', 'Amtsilati', 'Pengajian'])->nullable();
            $table->string('no_induk_pendidikan_malam')->nullable();
            $table->string('kelas_pendidikan_malam')->nullable();

            $table->string('wali')->nullable();
            $table->string('no_telp_wali')->nullable();
            $table->string('email')->nullable();
            $table->string('ig')->nullable();
            $table->string('wa')->nullable();

            $table->string('kampung')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('status', ['Santri', 'Alumni', 'Tidak Jelas'])->default('Santri');
            $table->string('tahun_mondok')->nullable();
            $table->string('tahun_lulus')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wargas');
    }
};
