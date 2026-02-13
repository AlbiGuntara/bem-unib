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
        Schema::create('hutang_piutang_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hutang_piutang_id')
                ->constrained('hutang_piutang')
                ->cascadeOnDelete();

            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_piutang_pembayaran');
    }
};
