@extends('layouts.admin')
@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Edytuj turniej</h3>
            
            <form method="POST" action="{{ route('admin.tournament.edit', $tournament->id) }}">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa turnieju</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tournament->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                <a href={{route("admin.tournament")}} class="btn btn-danger">Anuluj</a>
            </form>    
                <br>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Drużyny w turnieju</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Drużyny</th>
                                        <th>Nazwa Drużyny</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teamsIn as $teamIn)
                                        <tr>
                                            <td>{{ $teamIn->id }}</td>
                                            <td>{{ $teamIn->name }}</td>
                                            <td>
                                                <form method="POST" action="{{route('admin.tournament.editFromDeleteFrom', ['id' => $tournament->id, 'id2' => $teamIn->id])}}" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno usunąć wybraną drużynę?')">Usuń</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Brak drużyn w turnieju</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                
                        <div class="col-md-6">
                            <h3>Drużyny poza turniejem</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Drużyny</th>
                                        <th>Nazwa Drużyny</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teams as $team)
                                        <tr>
                                            <td>{{ $team->id }}</td>
                                            <td>{{ $team->name }}</td>
                                            <td>
                                                <form method="POST" action="{{route('admin.tournament.editFromAddTo', ['id' => $tournament->id, 'id2' => $team->id])}}" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">Dodaj</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Brak drużyn poza turniejem</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</main>
@endsection