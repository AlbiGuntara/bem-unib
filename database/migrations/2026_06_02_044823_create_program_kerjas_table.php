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
        Schema::create('program_kerjas', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('time')->nullable();
            $table->string('location')->nullable();

            $table->foreignId('departemen_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('pengurus_id')->nullable()->constrained('pengurus')->nullOnDelete();

            $table->integer('participants')->nullable()->comment('Jumlah peserta');
            $table->decimal('budget', 15, 2)->default(0);

            $table->integer('realization_participants')->nullable()->comment('Realisasi peserta');
            $table->decimal('realization_budget', 15, 2)->nullable()->comment('Realisasi anggaran');

            $table->boolean('is_public')->default(true);
            $table->enum('status', ['rencana','berjalan','selesai','batal'])->default('rencana');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kerjas');
    }
};
