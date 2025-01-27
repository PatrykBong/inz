@extends("layouts.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Twoje typy</h6>
            @if(session()->has("success"))
                <div class="alert alert-success">
                    {{session()->get("success")}}
                </div>
            @endif
            @if(session("error"))
                <div class="alert alert-danger">
                    {{session("error")}}
                </div>
            @endif
            @foreach ($bets as $bet)
            <div class="d-flex text-body-secondary pt-3">
                <img class="mb-4" src="{{asset("img/flags/$bet->code1.png")}}" alt="logo" width="67" height="40"> VS
                <img class="mb-4" src="{{asset("img/flags/$bet->code2.png")}}" alt="logo" width="67" height="40">
                <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                    <div class="d-flex justify-content-between">
                        <strong class="text-gray-dark">{{$bet->team1_name}} || {{$bet->team2_name}}</strong>
                        <!--<a href="bet/add/{{$bet->id}}">Typuj</a>-->
                        @if ($bet->date > \Carbon\Carbon::now()->addHour())
                            <a class="btn btn-primary" href="{{route('bet.add', $bet->id_game)}}">Typuj</a>
                        @else
                            <p>Mecz się już odbył</p>
                        @endif
                    </div>
                    <span class="d-block">
                    @if($bet->game_id)
                        <p>Obecny zakład: {{ $bet->bet }}</p>
                    @else
                        <p>Brak zakładu dla tej gry.</p>
                    @endif
                    </span>
                    <span class="d-block">{{$bet->result}} || {{$bet->date}}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</main>
@endsection