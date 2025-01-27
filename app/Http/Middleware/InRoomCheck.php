<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InRoomCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $room_id = session('current_room_id');
        if(isset($room_id)) {
            return $next($request);
        }
        return redirect(route("room"))->with("error","Nie jesteś obecnie w żadny pokoju. Nie możesz więc kożystać z Typy, Typy innych graczy, Tabela i Typ na mistrza");
    }
}
