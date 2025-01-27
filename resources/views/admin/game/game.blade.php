@extends('layouts.admin')

@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Dodaj mecz</h3>
            <form method="POST" action="{{route("admin.game.add")}}">
                @csrf

                <div class="mb-3">
                    <label for="team1" class="form-label">Drużyna 1</label>
                    <select class="form-select @error('team1') is-invalid @enderror" id="team1" name="team1" required>
                        <option value="">Wybierz drużynę 1</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team1_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team1' || 'team1_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            
                <div class="mb-3">
                    <label for="team2" class="form-label">Drużyna 2</label>
                    <select class="form-select @error('team2') is-invalid @enderror" id="team2" name="team2" required>
                        <option value="">Wybierz drużynę 2</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team2_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team2' || 'team2_id')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tournament" class="form-label">Turniej</label>
                    <select class="form-select @error('tournament') is-invalid @enderror" id="tournament" name="tournament" required>
                        <option value="">Wybierz turniej</option>
                        @foreach ($tournaments as $tournament)
                            <option value="{{ $tournament->id }}" {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                {{ $tournament->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tournament' || 'tournament_id')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Data meczu</label>
                    <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" required>
                    @error('date')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Dodaj mecz</button>
            </form>
        </div>
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
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <a class="btn btn-primary" href={{route("admin.updateResults")}}>POBIERZ WYNIKI</a>
        </div>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Lista meczów</h3>

            <table class="table table-striped table-bordered table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Drużyna 1</th>
                        <th>Wynik</th>
                        <th>Drużyna 2</th>
                        <th>Tournament</th>
                        <th>Data</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($games as $game)
                        <tr>
                            <td>{{ $game->id }}</td>
                            <td>{{ $game->team1_name }}</td>
                            <td>{{ $game->result }}</td>
                            <td>{{ $game->team2_name }}</td>
                            <td>{{ $game->name }}</td>
                            <td>{{ $game->date }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.game.editForm', $game->id) }}" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Edytuj</button>
                                </form>
                                <form method="POST" action="{{ route('admin.game.delete', $game->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno usunąć wybrany mecz?')">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
