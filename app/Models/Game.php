<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;
    protected $table = "game";

    //protected $fillable = [
    //    'user_id',
    //    'bet',
    //];

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
}
