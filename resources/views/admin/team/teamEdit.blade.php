@extends('layouts.admin')
@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Edit Team</h3>
            
            <form method="POST" action="{{ route('admin.team.edit', $team->id) }}">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa druzyny</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $team->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Kod druzyny</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $team->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="active" class="form-label">Status</label>
                    <select class="form-select @error('active') is-invalid @enderror" id="active" name="active">
                        <option value="1" {{ old('active', $team->active) == 1 ? 'selected' : '' }}>w grze</option>
                        <option value="0" {{ old('active', $team->active) == 0 ? 'selected' : '' }}>odpadła</option>
                    </select>
                    @error('active')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                <a href={{route("admin.team")}} class="btn btn-danger">Anuluj</a>
            </form>
        </div>
    </div>
</main>
@endsection