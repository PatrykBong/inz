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
        Schema::create('tournament_team', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tournament::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_team');
    }
};
