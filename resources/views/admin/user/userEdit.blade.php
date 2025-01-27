@extends('layouts.admin')
@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Edytuj użytkownika</h3>
            
            <form method="POST" action="{{ route('admin.user.edit', $user->id) }}">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="name" class="form-label">Imię</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="surname" class="form-label">Nazwisko</label>
                    <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $user->surname) }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="roles" class="form-label">Role</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role_user" name="player" value="Gracz" 
                        @if ($roles->contains('name', 'Player'))
                            checked
                        @endif>
                        <label class="form-check-label" for="role_user">Gracz</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role_admin" name="admin" value="Administrator" 
                        @if ($roles->contains('name', 'Admin'))
                            checked
                        @endif>
                        <label class="form-check-label" for="role_admin">Admin</label>
                    </div>
                    @error('roles')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                <a href={{route("admin.user")}} class="btn btn-danger">Anuluj</a>
            </form>
        </div>
    </div>
</main>
@endsection