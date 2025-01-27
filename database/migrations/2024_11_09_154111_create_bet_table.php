<?php

use App\Models\User;
use App\Models\Game;
use App\Models\Room;
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
        Schema::create('bet', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Game::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Room::class)->constrained()->cascadeOnDelete();
            $table->string('bet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bet');
    }
};
