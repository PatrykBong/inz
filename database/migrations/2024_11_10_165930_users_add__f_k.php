<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_bet')->nullable()->after('points');
            $table->foreign('winner_bet')->references('id')->on('team')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['winner_bet']);
            $table->dropColumn('winner_bet');
        });
    }
};
