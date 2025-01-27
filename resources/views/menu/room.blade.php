@extends("layouts.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container">
        @if(session('success'))
        <form action="{{ route('add.room') }}" method="GET">
            @csrf
            <button class="btn btn-primary mb-3" type="submit">Dodaj pokój</button>
        </form>
        @endif
        <h3>Obecny pokój:</h3>
        @if(session('current_room_id'))
            <p>Obecny pokój: <strong>{{ session('current_room_name') }}</strong></p>
            <form action="{{ route('leave.room') }}" method="GET">
                @csrf
                <button class="btn btn-danger mb-3" type="submit">Opuśc pokój</button>
            </form>
        @else
            <p>Nie jesteś w żadnym pokoju.</p>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('current_room_id'))
            <h2>Administrator pokoju</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $roomAdmin->name }}</td>
                        <td>{{ $roomAdmin->surname }}</td>
                    </tr>
                </tbody>
            </table>
            <h2>Gracze obecni w pokoju</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Punkty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $player)
                        <tr>
                            <td>{{ $player->name }}</td>
                            <td>{{ $player->surname }}</td>
                            <td>{{ $player->points }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Brak graczy w pokoju</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <h2>Pokoje do których należysz</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nazwa Pokoju</th>
                        <th scope="col">Moduł pokoju</th>
                        <th scope="col">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uplRooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->tournament->name }}</td>
                            <td>
                                <a href="{{ route('enter.room', ['id' => $room->id]) }}" class="btn btn-success">Wejdź</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h2>Dołącz do nowych pokoi</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nazwa Pokoju</th>
                        <th scope="col">Moduł pokoju</th>
                        <th scope="col">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr>
                            <td>{{ $room->room_name }}</td>
                            <td>{{ $room->tournament_name }}</td>
                            <td>
                                <a href="{{ route('join.room', ['id' => $room->id]) }}" class="btn btn-primary">Dołącz</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</main>
@endsection
