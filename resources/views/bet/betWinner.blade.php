@extends("layouts.default")

@section("content")
<main class="flex-shrink-0 mt-5">
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Wybierz zwycięzcę turnieju</h6>
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
            <div class="mb-3">
                <label for="teamInput" class="form-label">Wyszukaj drużynę</label>
                <form method="POST" action="{{route("bet.winner.post")}}">
                @csrf
                <div class="input-group">
                    <input type="text" name="bet" class="form-control" id="teamInput" placeholder="podaj nazwę druzyny" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" id="clearInput">
                        <img src="{{ asset('img/1/trash.png') }}" width="24" height="24" alt="X Icon">
                    </button>
                </div>
                <button class="btn btn-primary w-100 py-2" type="submit">Oddaj typ</button>
                </form>
                <div id="teamSuggestions" class="list-group mt-2" style="display: none;">
                    <!-- Podpowiedzi będą wyświetlane tutaj -->
                </div>
            </div>

            <div class="row row-cols-2 row-cols-sm-4 row-cols-md-6 row-cols-lg-8 g-8">
                @foreach($teams as $team)
                    <div class="col">
                        <div class="card h-100">
                            <img class="card-img-top flag-image" src="{{ asset("img/flags/$team->code.png") }}" alt="logo" data-team-name="{{ $team->name }}" width="134" height="80">
                            <div class="card-body">
                                <h5 class="card-title">{{ $team->name }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

@endsection

@section("scripts")
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const teamInput = document.getElementById("teamInput");
        const suggestionsContainer = document.getElementById("teamSuggestions");
        const clearButton = document.getElementById("clearInput");

        teamInput.addEventListener("input", async () => {
            const query = teamInput.value;

            if (query.length > 2) {
                try {
                    const response = await fetch(`/bet/winner?query=${query}`);
                    const data = await response.json();

                    suggestionsContainer.innerHTML = "";

                    if (data.length > 0) {
                        suggestionsContainer.style.display = "block";
                        data.forEach(team => {
                            const suggestionItem = document.createElement("a");
                            suggestionItem.href = "#";
                            suggestionItem.classList.add("list-group-item", "list-group-item-action");
                            suggestionItem.textContent = team.name;
                            suggestionItem.onclick = () => {
                                teamInput.value = team.name;
                                suggestionsContainer.innerHTML = "";
                                suggestionsContainer.style.display = "none";
                            };
                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    } else {
                        suggestionsContainer.style.display = "none";
                    }
                } catch (error) {
                    console.error("Error fetching data:", error);
                }
            } else {
                suggestionsContainer.innerHTML = "";
                suggestionsContainer.style.display = "none";
            }
        });

        clearButton.addEventListener("click", () => {
            teamInput.value = "";
            suggestionsContainer.innerHTML = "";
            suggestionsContainer.style.display = "none";
        });

        const flagImages = document.querySelectorAll(".flag-image");

        flagImages.forEach(image => {
            image.addEventListener("click", function() {
                // Ustawienie wartości inputa na nazwę drużyny
                const teamName = this.getAttribute("data-team-name");
                const teamInput = document.getElementById("teamInput");
                teamInput.value = teamName;
            });
        });
    });
</script>
@endsection
