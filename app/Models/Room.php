<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    protected $table = "room";

    protected $fillable = [
        'name',
        'tournament_id',
        'password',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'room_id', 'role_id')
            ->withPivot('user_id', 'points')
            ->withTimestamps();
    }
}
