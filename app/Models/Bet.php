<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bet extends Model
{
    use HasFactory;
    protected $table = "bet";

    //protected $fillable = [
    //    'user_id',
    //    'bet',
    //];
}
