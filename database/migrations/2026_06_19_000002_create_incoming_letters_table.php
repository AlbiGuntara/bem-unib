<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('correspondences');

        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();

            $table->string('letter_number');
            $table->string('subject');
            $table->string('sender');
            $table->date('date');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();

            $table->string('letter_number');
            $table->string('subject');
            $table->string('receiver');
            $table->date('date');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_letters');
        Schema::dropIfExists('incoming_letters');
    }
};
