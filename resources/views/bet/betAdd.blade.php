@extends('layouts.default')
@section('title',"add bet")
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

<<main class="form-signin w-100 m-auto">
    <form method="POST" action="{{route("bet.add.post", $id)}}">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Add bet</h1>
    
        <div class="form-floating">
            @if($bet)
                <input name="bet" type="text" class="form-control" id="floatingInput" value="{{$bet->bet}}">
            @else
                <input name="bet" type="text" class="form-control" id="floatingInput" placeholder="enter bet">
            @endif
            <label for="floatingInput">Typ</label>
            @error('bet')
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
        
    </form>
    @if($bet)
    <form action="{{ route('bet.del.post', $id) }}" method="POST" style="display:inline;">
        @csrf
        @method('POST') <!-- Laravel traktuje to jako POST -->
        <button type="submit" class="btn bg-danger">
            <img src="{{ asset('img/1/trash.png') }}" width="24" height="24" alt="Trash Icon">
        </button>
    </form>
    @endif
    <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>
</main>

@endsection