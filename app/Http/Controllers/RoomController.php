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

class RoomController extends Controller
{
    function room(){
        $userId = Auth::id();
        $room_id = session('current_room_id');
        if(isset($room_id)) {
            $playerRoleOb = Role::where('name',"Player")->first();
            $roomAdminrRoleOb = Role::where('name',"RoomAdmin")->first();
            $players = DB::table('users')
                            ->join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->where('role_user.room_id', $room_id)
                            ->where('role_user.role_id',$playerRoleOb->id)
                            ->get();
            $roomAdmin = DB::table('users')
                            ->join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->where('role_user.room_id', $room_id)
                            ->where('role_user.role_id',$roomAdminrRoleOb->id)
                            ->first();
            return view("menu.room", compact("players","roomAdmin"));
        }

        $rooms = Room::join('tournament', 'room.tournament_id', '=', 'tournament.id')
                    ->select('room.id','room.name as room_name', 'room.tournament_id', 'tournament.name as tournament_name')
                    ->get();
        $tournaments = Tournament::get();
        $uplRooms = Room::whereHas('roles', function ($query) use ($userId) {
            $query->where('name', 'Player')
                  ->where('user_id', $userId);
        })->get();
        return view("menu.room", compact("rooms", "tournaments", "uplRooms"));
    }

    function addRoom(){
        $tournaments = Tournament::get();
        return view("menu.roomAdd", compact("tournaments"));
    }

    function addRoomPost(Request $request){
        $request->validate([
            "name" => "required",
            "password" => "required|min:6|same:password2",
            "password2" => "required|min:6",
            "tournament_id" => "required|exists:tournament,id",
        ]);

        $room = new Room();
        $room->name = $request->name;
        $room->tournament_id = $request->tournament_id;
        $room->password = Hash::make($request->password);

        if($room->save()){
            $roomAdminRole = Role::where('name', 'RoomAdmin')->first();
            $playerRole = Role::where('name', 'Player')->first();

            if ($roomAdminRole && $playerRole) {
                DB::table('role_user')->insert([
                    'user_id' => Auth::id(),
                    'role_id' => $roomAdminRole->id,
                    'room_id' => $room->id,
                ]);

                DB::table('role_user')->insert([
                    'user_id' => Auth::id(),
                    'role_id' => $playerRole->id,
                    'room_id' => $room->id,
                ]);
            } else {
                return redirect(route("room"))->with("error", "Pomyślnie dodano pokój! Ale nastąpiły komplikacje z uprawnieniami!!!");
            }

            return redirect(route("room"))->with("success", "Pomyślnie dodano pokój!");
        }

        return redirect()->back()->with("error", "Błąd dodawania pokoju.");
    }

    public function joinRoom(Request $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->back()->with('error', 'Pokój nie istnieje.');
        }
        return view('menu.roomJoin', compact('room'));
    }

    public function joinRoomPost(Request $request, $id)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $room = Room::findOrFail($id);

        if (!Hash::check($request->password, $room->password)) {
            return redirect()->back()->with('error', 'Niepoprawne hasło!');
        }

        RoleUser::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'role_id' => Role::where('name', 'Player')->first()->id,
            'points' => 0,
        ]);

        return redirect(route('room'))->with('success', 'Dołączono do pokoju!');
    }

    public function enterRoom(Request $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->back()->with('error', 'Pokój nie istnieje.');
        }
        session(['current_room_id' => $id]);
        session(['current_room_name' => $room->name]);
        session(['current_room_tournament_id' => $room->tournament_id]);
        return redirect(route('room'))->with('success', 'Wszedłeś do pokoju.');
    }

    public function leaveRoom()
    {
        session()->forget('current_room_id');
        session()->forget('current_room_name');
        return redirect(route('room'))->with('success', 'Wyszedłeś z pokoju.');
    }
}
