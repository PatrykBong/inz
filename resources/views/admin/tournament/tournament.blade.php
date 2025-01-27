@extends('layouts.admin')

@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Dodaj turniej</h3>
            
            <form method="POST" action="{{route("admin.tournament.add")}}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa turnieju</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Wprowadź nazwę turnieju" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Dodaj turniej</button>
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
            <h3 class="border-bottom pb-2 mb-3">Lista drużyn</h3>

            <table class="table table-striped table-bordered table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tournaments as $tournament)
                        <tr>
                            <td>{{ $tournament->id }}</td>
                            <td>{{ $tournament->name }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.tournament.editFrom', $tournament->id) }}" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Edytuj</button>
                                </form>
                                <form method="POST" action="{{ route('admin.tournament.delete', $tournament->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno usunąć wybraną drużynę?')">Usuń</button>
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
