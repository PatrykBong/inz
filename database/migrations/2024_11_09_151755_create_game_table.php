<?php

use App\Models\Team;
use App\Models\Tournament;
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
        Schema::create('game', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, "team1_id")->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Team::class, "team2_id")->constrained()->cascadeOnDelete();
            $table->string('result')->nullable();
            $table->foreignIdFor(Tournament::class)->constrained()->cascadeOnDelete();
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /** database\migrations\2024_11_09_151755_create_game_table.php
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game');
    }
};
