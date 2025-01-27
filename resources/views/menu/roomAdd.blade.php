@extends("layouts.auth")
@section("style")
    <style>
        html,
        body {
        height: 100%;
        }

        .form-signin {
        max-width: 330px;
        padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
        z-index: 2;
        }

        .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }
    </style>
@endsection
@section('content')
<main class="form-signin w-100 m-auto">
    <form method="POST" action="{{route("add.room.post")}}">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Załóż pokój</h1>
    
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Nazwa pokoju</label>
            @error('name')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <select name="tournament_id" class="form-control" id="floatingTournament">
                <option value="" disabled selected>Wybierz turniej</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                @endforeach
            </select>
            <label for="floatingTournament">Turniej</label>
            @error('tournament_id')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Podaj hasło">
            <label for="floatingPassword">Hasło</label>
            @error('password')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="password2" type="password" class="form-control" id="floatingPassword" placeholder="Powtórz hasło">
            <label for="floatingPassword">Powtórz hasło</label>
            @error('password2')
                <span class="text-danger">{{$message}}</span>
            @enderror
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
        <button class="btn btn-primary w-100 py-2" type="submit">Załóż pokój</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>
    </form>
</main>
@endsection
