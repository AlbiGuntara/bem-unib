<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim')->nullable();
            $table->string('email');
            $table->enum('category', ['saran', 'kritik', 'aspirasi', 'aduan']);
            $table->string('subject');
            $table->text('content');
            $table->enum('status', ['unread', 'read', 'replied', 'archived'])->default('unread');
            $table->string('attachment')->nullable();
            $table->text('reply_content')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
