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
        Schema::create('documentasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('program_kerjas_id')
                ->constrained('program_kerjas')
                ->cascadeOnDelete();

            $table->string('link_drive')->nullable();

            $table->string('thumbnail_portrait')->nullable();
            $table->string('thumbnail_landscape')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentasis');
    }
};
