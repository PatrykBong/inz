@extends("layouts.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <h1 class="mt-5">Strona tytułowa</h1>
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
        <p class="lead">Przykładowy tekst, przykładowy kod kodu <code class="small">padding-top: 60px;</code> on the <code class="small">main &gt; .container</code>.</p>
    </div>
</main>
@endsection