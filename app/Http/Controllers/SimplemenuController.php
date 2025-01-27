<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bet;
use App\Models\Room;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SimplemenuController extends Controller
{
    function table(){
        $plid = Role::where('name','Player')->first();
        $winners = RoleUser::join('users', 'role_user.user_id', '=', 'users.id')
                            ->orderBy('role_user.id', 'desc')
                            ->select('role_user.*', 'users.*')
                            ->where('role_user.room_id', session('current_room_id'))
                            ->where('role_user.role_id', $plid->id)
                            ->get();
        return view("menu.table", compact("winners"));
    }

    function rules(){
        return view("menu.rules");
    }

    function listResults() {
        $playerRoleOb = Role::where('name',"Player")->first();
        $room_id = session('current_room_id');
        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                    ->where('role_user.room_id', $room_id)
                    ->where('role_user.role_id', $playerRoleOb->id)
                    ->orderBy('users.id', 'desc')
                    ->get();
        $results = []; // Dwuwymiarowa tabela: $results[game_id][user_id] = wynik
        
        $rid = session('current_room_id');
        $tid = Room::where('room.id', $rid)->first();
        $games = DB::table('game')
            ->join('team as team1', 'game.team1_id', '=', 'team1.id')
            ->join('team as team2', 'game.team2_id', '=', 'team2.id')
            ->select('game.*', 'game.id as id_game', 'team1.name as team1_name', 'team2.name as team2_name')
            ->where('game.tournament_id', $tid->tournament_id)
            ->orderBy('game.date')
            ->orderBy('game.id')
            ->paginate(8);
        
        foreach ($games as $game) {
            $results[$game->id_game] = []; // Inicjalizujemy wiersz dla danego meczu
    
            foreach ($users as $user) {
                $userResult = DB::table('bet')
                    ->where('game_id', $game->id_game)
                    ->where('user_id', $user->id)
                    ->where('room_id', $rid)
                    ->select('bet')
                    ->first();
    
                $results[$game->id_game][$user->id] = $userResult->bet ?? "_-_";
            }
    
            // Dodajemy dane meczu jako klucz `game_info`
            $results[$game->id_game]['game_info'] = $game;
        }
    
        return view("menu.listResults", compact("results", "users", "games"));
    }
}
