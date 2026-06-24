<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'address', 'instagram_url', 'tiktok_url', 'youtube_url', 'facebook_url', 'maps_embed']);
            $table->string('type');
            $table->text('value');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['type', 'value']);
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->text('maps_embed')->nullable();
        });
    }
};
