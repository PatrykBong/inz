@extends('layouts.admin')

@section('content')
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h3 class="border-bottom pb-2 mb-3">Lista użytkowników</h3>

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

            <table class="table table-striped table-bordered table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imie</th>
                        <th>Nazwisko</th>
                        <th>Emial</th>
                        <th>Punkty</th>
                        <th>Typ na zwycięzcę</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->surname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->points }}</td>
                            <td>{{ $user->winner_bet }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.user.editFrom', $user->id) }}" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">Edytuj</button>
                                </form>
                                <form method="POST" action="{{ route('admin.user.delete', $user->id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno usunąć wybranego użytkownika?')">Usuń</button>
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
