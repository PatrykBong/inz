@extends('layouts.auth')
@section('title',"Register")
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
    <form method="POST" action="{{route("register.post")}}">
        @csrf
        <img class="mb-5" src="{{asset("logo.png")}}" alt="logo" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Załóż konto</h1>
    
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="floatingInput" placeholder="imię">
            <label for="floatingInput">Imię</label>
            @error('name')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="surname" type="text" class="form-control" id="floatingInput" placeholder="nazwisko">
            <label for="floatingInput">Nazwisko</label>
            @error('surname')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="floatingInput" placeholder="nazwa@gmail.com">
            <label for="floatingInput">Adres E-mail</label>
            @error('email')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="hasło">
            <label for="floatingPassword">Hasło</label>
            @error('password')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating" style="margin-bottom: 10px">
            <input name="password2" type="password" class="form-control" id="floatingPassword2" placeholder="powtórz hasło">
            <label for="floatingPassword2">Powtórz hasło</label>
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
        <button class="btn btn-primary w-100 py-2" type="submit">Register</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>
    </form>
</main>

@endsection