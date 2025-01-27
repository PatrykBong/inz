<?php

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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nazwa roli, np. "Admin", "User"
            $table->boolean('active')->default(true); // Czy rola jest aktywna
            $table->timestamps();
        });
        //dodaje role
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'active' => true,
            ],
            [
                'name' => 'Player',
                'active' => true,
            ],
            [
                'name' => 'RoomAdmin',
                'active' => true,
            ],
        ]);
        //tabela asoc
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->integer('points')->default(0);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('room')->onDelete('set null');

            $table->unique(['role_id', 'user_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
