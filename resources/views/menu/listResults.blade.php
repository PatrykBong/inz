@extends("layouts.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <table class="table table-striped table-bordered table-hover mt-3">
                <thead>
                    <tr>
                        <th></th>
                        @foreach ($games as $game)
                            <th>{{$game->team1_name}} vs {{$game->team2_name}}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>data</th>
                        @foreach ($games as $game)
                            <th>{{$game->date}}</th>
                        @endforeach
                    </tr>
                    <th>wynik</th>
                    @foreach ($games as $game)
                        <th>{{$game->result}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}} {{$user->surname}}</td>
                            @foreach ($games as $game)
                                <td>{{$results[$game->id_game][$user->id]}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginacja -->
            <div class="d-flex justify-content-center mt-3">
                {{ $games->links() }}
            </div>
        </div>
    </div>
</main>
@endsection
