<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_warga')
                ->constrained('wargas')
                ->cascadeOnDelete();

            $table->foreignId('id_jabatan')
                ->constrained('jabatans')
                ->cascadeOnDelete();

            $table->foreignId('id_periode')
                ->constrained('periode')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['id_warga', 'id_jabatan', 'id_periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengurus');
    }
};
