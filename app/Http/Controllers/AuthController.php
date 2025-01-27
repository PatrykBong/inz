<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(){
        return view("auth.login");
    }

    function loginPost(Request $request){
        $request->validate([
            "email" => "required",
            "password" => "required",
        ]);

        $data = $request->only("email", "password");
        if(Auth::attempt($data)){
            $user = Auth::user();
            //$roles = $user->roles()->pluck('name')->toArray();
            
            $roles = DB::table('role_user')
                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                    ->where('role_user.user_id', 1)
                    ->pluck('roles.name');

            $roles2 = [];
            foreach($roles as $role){
                $roles2[] = $role;
            }
            session(['user_roles' => $roles2]);

            return redirect()->intended(route("home"));
        }
        return redirect(route("login"))->with("error", "login failed");
    }

    function register(){
        return view("auth.register");
    }

    function registerPost(Request $request){
        $request->validate([
            "name" => "required",
            "surname" => "required",
            "email" => "required|unique:users",
            "password" => "required|min:6|same:password2",
            "password2" => "required|min:6",
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if($user->save()){
            return redirect(route("login"))->with("success", "User created succesfully");
        }
        return redirect(route("register"))->with("error", "Failed to create account");
    }

    public function logout() {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect(route('login'))->with('success', 'You have been logged out successfully.');
    }

}
