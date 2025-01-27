<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Game;
use App\Models\Role;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function team() {
        $teams = Team::orderBy('id', 'asc')->get();
        return view('admin.team.team', compact('teams'));
    }
    
    public function teamAdd(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
            "active" => "required|boolean",
            "code" => "required|max:255",
        ]);

        $team = new Team();

        $team->name = $request->name;
        $team->in_game = $request->active;
        $team->code = $request->code;
        if($team->save()){
            return redirect(route("admin.team"))->with("success", "Pomyślnie dodano drużynę");
        }
        return redirect(route("admin.team"))->with("error", "Błąd w dodawaniu druzyny");
    }

    public function teamDelete($id) {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->route('admin.team')->with('success', 'Pomyślnie usunięto druzynę');
    }

    public function teamEditForm(Request $request, $id) {
        $team = Team::findOrFail($id);

        return view('admin.team.teamEdit', compact('team'));
    }

    public function teamEdit(Request $request, $id){
        $team = Team::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $team->name = $request->input('name');
        $team->in_game = $request->input('active');
        $team->save();

        return redirect()->route('admin.team')->with('success', 'Pomyślnie edytowano druzynę');
    }

    public function game() {
        $teams = Team::orderBy('name', 'asc')->get();
        $tournaments = Tournament::orderBy('name', 'asc')->get();
        $games = DB::table('game')
                ->join('team as team1', 'game.team1_id', '=', 'team1.id')
                ->join('team as team2', 'game.team2_id', '=', 'team2.id')
                ->join('tournament', 'game.tournament_id', '=', 'tournament.id')
                ->select('game.*', 'tournament.name','team1.name as team1_name', 'team2.name as team2_name')
                ->orderBy('game.date','desc')
                ->get();
        return view('admin.game.game', compact('games', 'teams', 'tournaments'));
    }

    public function gameAdd(Request $request) {
        $request->validate([
            "team1" => "required|string|max:255",
            "team2" => "required|string|max:255",
            "date" => "required",
            "tournament" => "required",
        ]);

        $game = new Game();

        $game->team1_id = $request->team1;
        $game->team2_id = $request->team2;
        $game->date = $request->date;
        $game->tournament_id = $request->tournament;
        if($game->save()){
            return redirect(route("admin.game"))->with("success", "Pomyślnie dodano mecz");
        }
        return redirect(route("admin.game"))->with("error", "Błąd w dodawaniu meczu");
    }

    public function gameDelete($id) {
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect()->route('admin.game')->with('success', 'Pomyślnie usunięto mecz');
    }

    public function gameEditForm(Request $request, $id) {
        $game = Game::findOrFail($id);
        $teams = Team::orderBy('name', 'asc')->get();
        $tournaments = Tournament::orderBy('name', 'asc')->get();

        return view('admin.game.gameEdit', compact('game','teams', 'tournaments'));
    }

    public function gameEdit(Request $request, $id){
        $game = Game::find($id);

        $request->validate([
            "team1" => "required|string|max:255",
            "team2" => "required|string|max:255",
            "date" => "required",
            "tournament" => "required",
            "result" => ['nullable', 'regex:/^\d+-\d+$/'],
        ]);

        try{
            $game->team1_id = $request->team1;
            $game->team2_id = $request->team2;
            $game->date = $request->date;
            $game->result = $request->result;
            $game->tournament_id = $request->tournament;
            $game->save();
            
            return redirect()->route('admin.game')->with('success', 'Pomyślnie edytowano mecz');
        }catch(\Exception $e){
            //return back()->with('error', 'Wystąpił błąd podczas edytowania meczu: ' . $e->getMessage());
            return redirect()->route('admin.game')->with('error', 'ERR');
        }

    }

    public function user() {
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.user.user', compact('users'));
    }

    public function userDelete($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user')->with('success', 'Pomyślnie usunięto druzynę');
    }

    public function userEditForm(Request $request, $id) {
        $user = User::findOrFail($id);
        $roles = DB::table('role_user')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $id)
                ->get();

        return view('admin.user.userEdit', compact('user', 'roles'));
    }

    public function userEdit(Request $request, $id){
        $user = User::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'player' => 'nullable',
            'admin' => 'nullable',
        ]);
        $adm_id = Role::where('name','Admin')->first();
        $adm = DB::table('role_user')->where('role_id',$adm_id->id)->where('user_id',$id)->first();
        $play_id = Role::where('name','Player')->first();
        $play = DB::table('role_user')->where('role_id',$play_id->id)->where('user_id',$id)->first();
        if(isset($request->admin)){
            if(empty($adm)){
                DB::table('role_user')->insert([
                    'role_id' => $adm_id->id,
                    'user_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }else{
            if(!empty($adm)){
                DB::table('role_user')->where('role_id', $adm_id->id)->where('user_id', $id)->delete();
            }
        }
        if(isset($request->player)){
            if(empty($play)){
                DB::table('role_user')->insert([
                    'role_id' => $play_id->id,
                    'user_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }else{
            if(!empty($play)){
                DB::table('role_user')->where('role_id', $play_id->id)->where('user_id', $id)->delete();
            }
        }

        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('admin.user')->with('success', "Pomyślnie edytowano użytkownika");
    }

    public function updateResults(){
        $games = DB::table('game')
                    ->join('team as team1', 'game.team1_id', '=', 'team1.id')
                    ->join('team as team2', 'game.team2_id', '=', 'team2.id')
                    ->select(
                        'game.*',
                        'team1.name as name1',
                        'team2.name as name2'
                    )
                    ->where('game.date', '>=', Carbon::now()->subMonth())
                    ->whereNull('game.result')
                    ->get();
        $service = app('simplescraper');
        //return $service->getData('https://www.meczyki.pl/liga/premier-league/8/terminarz');
        //return $message = $service->getData('https://www.mwyniki.pl/anglia/premier-league/',
        //    '/(datetime="[a-zA-Z0-9:+-]*")|(span[a-zA-Z"=&; ]+\/span strong[0-9-]+\/strong span[a-zA-Z"=&; ]+\/span)/',
        //    ['<','>']);
        //try later -> https://www.theguardian.com/football/premierleague/results https://www.theguardian.com/football/premierleague/fixtures
        $message =  $service->getData('https://www.skysports.com/premier-league-scores-fixtures',
            '/(([1-9]|[12][0-9]|3[01])(nd|rd|st|th) (January|February|March|April|May|June|July|August|September|October|November|December),time:([01]?[0-9]|2[0-3]):[0-5][0-9]|audioDescription:[a-zA-Z0-9., ]*Full time.)/',
            ['&quot;','<','>']);
        $ms = sizeof($message);
        $m = "Brak zapisanych wyników";
        for($i=0;$i<$ms;$i++){
            if (preg_match('/^audio/', $message[$i])){ //https://www.skysports.com/premier-league-scores-fixtures
                $mod = substr($message[$i], 17);
                $commaPosition = strpos($mod, ',');
                $t1 = substr($mod, 0, $commaPosition);
                $mod = substr($mod, $commaPosition + 2);
                $dotPosition = strpos($mod, '.');
                $r = substr($mod, 0, $dotPosition);
                $mod = substr($mod, $dotPosition + 2);
                $commaPosition = strpos($mod, ',');
                $t2 = substr($mod, 0, $commaPosition);
                $commaPosition = strpos($mod, ',');
                $mod = substr($mod, $commaPosition + 2);
                $dotPosition = strpos($mod, '.');
                $r = $r."-".substr($mod, 0, $dotPosition);
                $message[$i] = [$t1, $r, $t2];
            } elseif(preg_match('/([1-9]|[12][0-9]|3[01])/', $message[$i])) {
                $dateString = str_replace(['st','nd','rd','th', 'time:'], '', $message[$i]);
                $dateString = trim($dateString);
                
                $timestamp = strtotime($dateString);
                $message[$i] = date('Y-m-d H:i:s', $timestamp);
            }
            /**if(preg_match('/datetime/', $message[$i])){ //https://www.mwyniki.pl/anglia/premier-league/
                $dateString = str_replace("datetime=", '', $message[$i]);
                $dateString = substr($dateString, 0, -1);
                $dateString = substr($dateString, 1);
                $timestamp = strtotime($dateString);
                $message[$i] = date('Y-m-d H:i:s', $timestamp);
            } elseif(preg_match('/span/', $message[$i])) {
                $mod = substr($message[$i], 4);
                $quotPosition = strpos($mod, '"');
                if($quotPosition == 7){
                    $mod = substr($mod, $quotPosition + 1);
                    $quotPosition = strpos($mod, '"');
                    $mod = substr($mod, $quotPosition + 1);
                }
                $slPosition = strpos($mod, '/');
                $t1 = substr($mod, 0, $slPosition-1);
                $mod = substr($mod, $slPosition+12);
                $slPosition = strpos($mod, '/');
                $r = substr($mod, 0, $slPosition);
                $mod = substr($mod, $slPosition);
                $quotPosition = strpos($mod, '"');
                $mod = substr($mod, $quotPosition + 1);
                $quotPosition = strpos($mod, '"');
                $mod = substr($mod, $quotPosition + 2);
                $mod = substr($mod, 0, -5);
                $t2 = $mod;
                $t1 = str_replace(["FC ","AFC "], '', $t1);
                $t2 = str_replace(["FC ","AFC "], '', $t2);
                $t1 = str_replace('&', 'and', $t1);
                $t2 = str_replace('&', 'and', $t2);
                $message[$i] = [$t1, $r, $t2];
            }**/
        }
        
        foreach($games as $game){
            for($i=0;$i<$ms;$i++){
                if(is_array($message[$i])){
                    if($game->name1 == $message[$i][0] && $game->name2 == $message[$i][2] && $game->date == $message[$i-1]){
                        $m = "Pomyślnie zapisano wyniki";
                        DB::table('game')
                            ->where('id', $game->id)
                            ->update(['result' => $message[$i][1], 'date' => $message[$i-1]]);
                    }
                }
            }
        }
        
        return redirect()->route('admin.game')->with('success', $m);
    }

    public function updateGames(){
        $service = app('simplescraper');
        $message = $service->getData('https://www.skysports.com/premier-league-scores-fixtures/2025-02-01',
            '/(([1-9]|[12][0-9]|3[01])(nd|rd|st|th) (January|February|March|April|May|June|July|August|September|October|November|December),time:([01]?[0-9]|2[0-3]):[0-5][0-9])|audioDescription:[a-zA-Z0-9., ]*Kick-off|audioDescription:[a-zA-Z0-9., ]*Full time./',
            ['&quot;','<','>']);
        //$message = $service->getData('https://www.theguardian.com/football/premierleague/fixtures', //https://www.theguardian.com/football/premierleague/results
        //    '/(datetime="[a-zA-Z0-9:+-]*")|(team-name__long"[a-zA-Z0-9 ]*\/span)/',
        //    ['<','>']);
        $ms = sizeof($message);
        $m = "Brak zapisanych wyników";
        //$j = 0;
        //$message2 = [];
        for($i=0;$i<$ms;$i++){
            if (preg_match('/audio.*Kick-off/', $message[$i])){ //https://www.skysports.com/premier-league-scores-fixtures
                $mod = substr($message[$i], 17);
                $vsPosition = strpos($mod, " vs ");
                $t1 = substr($mod, 0, $vsPosition);
                $mod = substr($mod, $vsPosition+4);
                $dotPosition = strpos($mod, '.');
                $t2 = substr($mod, 0, $dotPosition);
                $message[$i] = [$t1,$t2];
            }elseif(preg_match('/audio.*Full time./',$message[$i])) {
                $mod = substr($message[$i], 17);
                $commaPosition = strpos($mod, ',');
                $t1 = substr($mod, 0, $commaPosition);
                $mod = substr($mod, $commaPosition + 2);
                $dotPosition = strpos($mod, '.');
                $r = substr($mod, 0, $dotPosition);
                $mod = substr($mod, $dotPosition + 2);
                $commaPosition = strpos($mod, ',');
                $t2 = substr($mod, 0, $commaPosition);
                $commaPosition = strpos($mod, ',');
                $mod = substr($mod, $commaPosition + 2);
                $dotPosition = strpos($mod, '.');
                $r = $r."-".substr($mod, 0, $dotPosition);
                $message[$i] = [$t1, $t2];
            }elseif(preg_match('/([1-9]|[12][0-9]|3[01])(nd|rd|st|th)/', $message[$i])) {
                $dateString = str_replace(['th', 'time:'], '', $message[$i]);
                $dateString = trim($dateString);
                
                $timestamp = strtotime($dateString);
                $message[$i] = date('Y-m-d H:i:s', $timestamp);
            }
            /**$date = substr($message[$i], 10); //https://www.theguardian.com/football/premierleague/fixtures
            $date = substr($date, 0, -1);       //tutaj i+=3
            $date = strtotime($date);
            $date = date('Y-m-d H:i:s', $date);
            $t1 = substr($message[$i+1], 16);
            $t1 = substr($t1, 0, -5);
            $t2 = substr($message[$i+2], 16);
            $t2 = substr($t2, 0, -5);
            $message2[$j] = [$t1, $t2, $date];
            $j++;**/
        }
        return $message;
        for($i=0;$i<$ms;$i++){
            if (is_array($message[$i])){
                $team1 = Team::where("code",$message[$i][0])->first();
                $team2 = Team::where("code",$message[$i][1])->first();
                if(isset($team1->id) && isset($team2->id))
                    $game = Game::where("team1_id", $team1->id)->where("team2_id", $team2->id)->where("date", $message[$i-1])->first();
                if((!isset($game)) && isset($team1->id) && isset($team2->id)){
                    DB::table('game')->insert([
                        'team1_id' => $team1->id,
                        'team2_id' => $team2->id,
                        'result' => null,
                        'tournament_id' => 2,
                        'date' => $message[$i-1],
                    ]);
                    $m = "Pomyślnie dodano mecze";
                }
            }
        }
        return redirect()->route('admin.game')->with('success', $m);
    }

    public function tournament() {
        $tournaments = Tournament::orderBy('id', 'desc')->get();
        return view('admin.tournament.tournament', compact('tournaments'));
    }

    public function tournamentAdd(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
        ]);

        $tournament = new Tournament();

        $tournament->name = $request->name;
        if($tournament->save()){
            return redirect(route("admin.tournament"))->with("success", "Pomyślnie dodano turniej");
        }
        return redirect(route("admin.tournament"))->with("error", "Błąd w dodawaniu turnieju");
    }

    public function tournamentDelete($id) {
        $tournament = Tournament::findOrFail($id);
        $tournament->delete();

        return redirect()->route('admin.tournament')->with('success', 'Pomyślnie usunięto turniej');
    }

    public function tournamentEditForm(Request $request, $id) {
        $tournament = Tournament::findOrFail($id);
        $teams = DB::table('team')
                    ->leftJoin('tournament_team', function ($join) use ($id) {
                        $join->on('team.id', '=', 'tournament_team.team_id')
                            ->where('tournament_team.tournament_id', $id);
                    })
                    ->whereNull('tournament_team.tournament_id')
                    ->select('team.*')
                    ->get();
        $teamsIn = DB::table('tournament_team')
                    ->join('team', 'team.id', '=', 'tournament_team.team_id')
                    ->where('tournament_team.tournament_id',$id)
                    ->get();

        return view('admin.tournament.tournamentEdit', compact('tournament','teams','teamsIn'));
    }

    public function tournamentEdit(Request $request, $id){
        $tournament = Tournament::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tournament->name = $request->input('name');
        $tournament->save();

        return redirect()->route('admin.tournament')->with('success', 'Pomyślnie edytowano turniej');
    }

    public function tournamentEditFormAddTo(Request $request, $id, $id2){
        DB::table('tournament_team')->insert([
            'team_id' => $id2,
            'tournament_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return redirect()->back()->with('success', 'Drużyna została dodana do turnieju.');
    }

    public function tournamentEditFormDeleteFrom($id, $id2){
        DB::table('tournament_team')
        ->where('team_id', $id2)
        ->where('tournament_id', $id)
        ->delete();

        return redirect()->back()->with('success', 'Drużyna została usunięta z turnieju.');
    }

    public function moje(){

    }
}

    /**protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            app(AdminController::class)->updateResults();
        })
        ->everyThirtyMinutes()
        ->when(function () {
            $warunek = DB::table('games')->whereNull('result')
                                        ->where('date', '<', Carbon::now()->addHours(2))
                                        ->orderBy('date','desc');
                                        ->limit(1)
                                        ->first();
            if($warunek === null)  
                return false;
            return true; 
        });
    }**/

    //return $service->getData('https://www.skysports.com/premier-league-scores-fixtures',
    //        '/(([1-9]|[12][0-9]|3[01])(nd|rd|st|th) (January|February|March|April|May|June|July|August|September|October|November|December),/'
    //      '/time:([01]?[0-9]|2[0-3]):[0-5][0-9])|audioDescription:[a-zA-Z0-9., ]*Kick-off|audioDescription:[a-zA-Z0-9., ]*Full time./',
    //        ['&quot;','<','>']);