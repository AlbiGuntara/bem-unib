<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreignId('department_id')->nullable()->change();
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->cascadeOnDelete();

            $table->string('position_type', 20)
                ->default('ministry')
                ->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreignId('department_id')->nullable(false)->change();
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->cascadeOnDelete();

            $table->dropColumn('position_type');
        });
    }
};
