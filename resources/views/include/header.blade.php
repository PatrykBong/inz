<header>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href={{route("room")}}>Pokoje</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href={{route("bet")}}>Typy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href={{route("listResults")}}>Typy innych graczy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href={{route("table")}}>Tabela</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href={{route("bet.winner")}}>Typ na mistrza</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href={{route("rules")}}>Regulamin</a>
          </li>
          @if(in_array('Admin', session('user_roles', [])))
          <li class="nav-item">
            <a class="nav-link" href={{route("admin.user")}}>Panel administratora</a>
          </li>
          @endif
          <!--<li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li>-->
        </ul>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <!--<a class="btn btn-outline-success" href="">Add Task</a>-->
      </div>
    </div>
  </nav>
</header>