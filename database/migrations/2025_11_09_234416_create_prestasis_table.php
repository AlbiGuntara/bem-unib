<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_warga')->constrained('wargas')->cascadeOnDelete();
            $table->string('prestasi');
            $table->string('kategori')->nullable();
            $table->string('tingkat')->nullable();
            $table->string('tempat')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('dokumentasi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
