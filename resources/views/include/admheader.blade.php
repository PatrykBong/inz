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
              <a class="nav-link active" aria-current="page" href={{route("home")}}>Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href={{route("admin.user")}}>Użytkownicy</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href={{route("admin.game")}}>Mecze</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href={{route("admin.team")}}>Druzyny</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href={{route("admin.tournament")}}>Turnieje</a>
            </li>
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