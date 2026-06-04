<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->foreignId('tematik_id')->nullable()->constrained('tematik')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->dropForeign(['tematik_id']);
            $table->dropColumn('tematik_id');
        });
    }
};