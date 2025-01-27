<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use App\Models\User;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find(Auth::user()->id);
        $roles = $user->roles;
        if($roles->contains('name', 'Admin')){
            return $next($request);
        }
        return redirect(route("home"))->with("error","Nie masz odpowiednich uprawnień do tej strony");
    }
}
