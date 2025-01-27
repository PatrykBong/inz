@extends("layouts.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <table class="table table-striped table-bordered table-hover mt-3">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Punkty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($winners as $winner)
                    <tr>
                        <td>{{ $winner->name }}</td>
                        <td>{{ $winner->surname }}</td>
                        <td>{{ $winner->points }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
