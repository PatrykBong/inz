<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetController extends Controller
{
    public function bet(){
        $rid = session('current_room_id');
        $tid = Room::where('room.id', $rid)->first();
        $cond = true;
        $bets = DB::table('game')
                ->join('team as team1', 'game.team1_id', '=', 'team1.id')
                ->join('team as team2', 'game.team2_id', '=', 'team2.id')
                ->leftJoin('bet', function ($join) use ($cond){
                    $join->on('game.id', '=', 'bet.game_id');
                    if($cond){
                        $join->where('bet.room_id', session('current_room_id'))
                                ->where('bet.user_id', auth()->user()->id);
                    }
                })
                ->where('game.tournament_id', $tid->tournament_id)
                ->select('game.*', 'team1.code as code1', 'team2.code as code2','game.id as id_game', 'team1.name as team1_name', 'team2.name as team2_name', 'bet.*')
                ->orderBy('game.date','desc')
                ->paginate(16);
        //dd($bets);
        return view("bet.bet", compact("bets"));
        /* $bets = DB::table('game')
                ->join('team as team1', 'game.team1_id', '=', 'team1.id')
                ->join('team as team2', 'game.team2_id', '=', 'team2.id')
                ->leftJoin('bet', 'game.id', '=', 'bet.game_id')
                ->where('game.tournament_id', $tid->tournament_id)
                ->where(function ($query) {
                    $query->where('bet.user_id', auth()->user()->id)
                            ->orWhereNull('bet.user_id');
                })
                ->select('game.*', 'game.id as id_game', 'team1.name as team1_name', 'team2.name as team2_name', 'bet.*')
                ->paginate(8);
                
            $bets = DB::table('game')
                ->join('team as team1', 'game.team1_id', '=', 'team1.id')
                ->join('team as team2', 'game.team2_id', '=', 'team2.id')
                ->leftJoin('bet', function ($join) use ($cond){
                    $join->on('game.id', '=', 'bet.game_id');
                    if($cond){
                        $join->where('bet.room_id', session('current_room_id'));
                    }
                })
                ->where('game.tournament_id', $tid->tournament_id)
                ->where(function ($query) {
                    $query->where('bet.user_id', auth()->user()->id)
                            ->orWhereNull('bet.user_id');
                })
                ->select('game.*', 'team1.code as code1', 'team2.code as code2','game.id as id_game', 'team1.name as team1_name', 'team2.name as team2_name', 'bet.*')
                ->orderBy('game.date','desc')
                ->paginate(16);
                
                $bets = DB::table('game')
                ->join('team as team1', 'game.team1_id', '=', 'team1.id')
                ->join('team as team2', 'game.team2_id', '=', 'team2.id')
                ->leftJoin('bet', function ($join) use ($cond){
                    $join->on('game.id', '=', 'bet.game_id');
                    if($cond){
                        $join->where('bet.room_id', session('current_room_id'))
                                ->where('bet.user_id', auth()->user()->id);
                    }
                })
                ->where('game.tournament_id', $tid->tournament_id)
                ->select('game.*', 'team1.code as code1', 'team2.code as code2','game.id as id_game', 'team1.name as team1_name', 'team2.name as team2_name', 'bet.*')
                ->orderBy('game.date','desc')
                ->paginate(16);*/
    }
    
    function betAdd($id){
        
        $bet = Bet::where('user_id', auth()->user()->id)
                    ->where('game_id', $id)
                    ->where('room_id', session('current_room_id'))
                    ->first();
        return view("bet.betAdd", compact("bet", "id"));
    }

    function betAddPost($id, Request $request){
        $validated = $request->validate([
            'bet' => ['required', 'regex:/^([1-9]\d*|0)-([1-9]\d*|0)$/'],
        ],[
            'regex' => 'Niepoprawny format! poprawny format to liczba-liczba np. 0-3'
        ]);

        $exist = Bet::where('user_id', auth()->user()->id)
                    ->where('game_id', $id)
                    ->where('room_id', session('current_room_id'))
                    ->first();

        if($exist){
            $exist->bet = $validated['bet'];
            if($exist->save()){
                return redirect(route("bet"))->with("success", "Bet updated succesfully");
            }
            return redirect(route("bet.add"))->with("error", "Failed to update bet");
        }
        
        $bet = new Bet();

        $bet->user_id = auth()->user()->id;
        $bet->game_id = $id;
        $bet->bet = $request->bet;
        $bet->room_id = session('current_room_id');
        if($bet->save()){
            return redirect(route("bet"))->with("success", "Bet added succesfully");
        }
        return redirect(route("bet.add"))->with("error", "Failed to add bet");
    }

    function betDelPost($id, Request $request){
        $bet = Bet::where('game_id', $id)
                    ->first();

        if ($bet) {
            $bet->delete();
            return redirect()->route('bet')->with('success', 'Zakład został usunięty.');
        }
        return redirect()->route('bet.del.post',$id)->with('error', 'Nie znaleziono zakładu do usunięcia.');
    }

    public function betWinner(Request $request)
    {
        $room = Room::where("id", session('current_room_id'))->first();
        if ($request->has('query')) {
            $query = $request->input('query');
            $teams = Team::join('tournament_team', 'team.id', '=', 'tournament_team.team_id')
                            ->where('name', 'LIKE', "%{$query}%")
                            ->where("tournament_team.tournament_id", 2)->get();
            return response()->json($teams);
        }
        $teams = Team::join('tournament_team', 'team.id', '=', 'tournament_team.team_id')
                        ->where("tournament_team.tournament_id", $room->tournament_id)->get();
        return view("bet.betWinner", compact("teams"));
    }

    public function betWinnerPost(Request $request)
    {
        $validated = $request->validate([
            'bet' => ['required'],
        ]);

        $team = Team::where('name', $validated['bet'])->first();

        if (isset($team)) {
            $id = $team->id;
            $room = Room::where('room.id', session('current_room_id'))->first();
            $inTournament = DB::table('tournament_team')->where('team_id', $id)->where('tournament_id',$room->tournament_id)->first();
            if(!isset($inTournament)) return redirect()->route('bet.winner')->with('error', 'Wkazana drużyna nie należy do turnieju');
            //zmienic
            $usr = auth()->user()->id;
            $usr->winner_bet = $id;
            if($usr->save()){
                return redirect()->route('bet.winner')->with('success', 'Pomyślnie oddano typ');
            }
            //do tad
            return redirect()->route('bet.winner')->with('error', 'Nastąpił błąd');
        }
        return redirect()->route('bet.winner')->with('error', 'Wkazana drużyna nie istnieje');
    }
}
