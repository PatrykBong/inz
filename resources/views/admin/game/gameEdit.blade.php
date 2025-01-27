@extends('layouts.admin')
@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Edit Team</h3>
            
            <form method="POST" action="{{ route('admin.game.edit', $game->id) }}">
                @csrf
                
                <div class="mb-3">
                    <label for="team1" class="form-label">Drużyna 1</label>
                    <select class="form-select @error('team1') is-invalid @enderror" id="team1" name="team1" required>
                        <option value="{{ $game->team1_id }}">{{ $game->team1->name }}</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">
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
                        <option value="{{ $game->team2_id }}">{{ $game->team2->name }}</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">
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
                    <label for="result" class="form-label">Wynik</label>
                    <input type="text" class="form-control @error('result') is-invalid @enderror" id="result" name="result" value="{{ $game->result }}">
                    @error('result')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tournament" class="form-label">Turniej</label>
                    <select class="form-select @error('tournament') is-invalid @enderror" id="tournament" name="tournament" required>
                        <option value="{{ $game->tournament_id }}">{{ $game->tournament->name }}</option>
                        @foreach ($tournaments as $tournament)
                            <option value="{{ $tournament->id }}">
                                {{ $tournament->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tournament' || 'tournament_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Data</label>
                    <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ $game->date }}" required>
                    @error('date')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                <a href={{route("admin.game")}} class="btn btn-danger">Anuluj</a>
            </form>
        </div>
    </div>
</main>
@endsection