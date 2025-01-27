<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BetController;
use App\Http\Controllers\SimplemenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomController;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\InRoomCheck;

Route::middleware("auth")->group(function(){
    Route::view('/', "welcome")->name("home");
    Route::get("/room", [RoomController::class, "room"])->name("room");
    Route::get("/room/add", [RoomController::class, "addRoom"])->name("add.room");
    Route::post("/room/add", [RoomController::class, "addRoomPost"])->name("add.room.post");
    Route::get("/room/join/{id}", [RoomController::class, "joinRoom"])->name("join.room");
    Route::post("/room/join/{id}", [RoomController::class, "joinRoomPost"])->name("join.room.post");
    Route::get("/room/enter/{id}", [RoomController::class, "enterRoom"])->name("enter.room");
    Route::get("/room/leave", [RoomController::class, "leaveRoom"])->name("leave.room");
    Route::get("/rules", [SimplemenuController::class, "rules"])->name("rules");
    Route::middleware(InRoomCheck::class)->group(function(){
        Route::get("/bet", [BetController::class, "bet"])->name("bet");
        Route::get("/bet/add/{id}", [BetController::class, "betAdd"])->name("bet.add");
        Route::post("/bet/add/{id}", [BetController::class, "betAddPost"])->name("bet.add.post");
        Route::post("/bet/del/{id}", [BetController::class, "betDelPost"])->name("bet.del.post");
        Route::get("/bet/winner", [BetController::class, "betWinner"])->name("bet.winner");
        Route::post("/bet/winner", [BetController::class, "betWinnerPost"])->name("bet.winner.post");
        Route::get("/table", [SimplemenuController::class, "table"])->name("table");
        Route::get("/listResults", [SimplemenuController::class, "listResults"])->name("listResults");
    });
});

Route::get("/admin/team", [AdminController::class, "team"])->name("admin.team")->middleware(AdminCheck::class);
Route::post("/admin/team/add", [AdminController::class, "teamAdd"])->name("admin.team.add")->middleware(AdminCheck::class);
Route::delete('/admin/team/delete/{id}', [AdminController::class, 'teamDelete'])->name('admin.team.delete')->middleware(AdminCheck::class);
Route::post('/admin/team/editForm/{id}', [AdminController::class, 'teamEditForm'])->name('admin.team.editFrom')->middleware(AdminCheck::class);
Route::post('/admin/team/edit/{id}', [AdminController::class, 'teamEdit'])->name('admin.team.edit')->middleware(AdminCheck::class);
Route::get("/admin/game", [AdminController::class, "game"])->name("admin.game")->middleware(AdminCheck::class);
Route::post("/admin/game/add", [AdminController::class, "gameAdd"])->name("admin.game.add")->middleware(AdminCheck::class);
Route::delete('/admin/game/delete/{id}', [AdminController::class, 'gameDelete'])->name('admin.game.delete')->middleware(AdminCheck::class);
Route::any('/admin/game/editForm/{id}', [AdminController::class, 'gameEditForm'])->name('admin.game.editForm')->middleware(AdminCheck::class);
Route::post('/admin/game/edit/{id}', [AdminController::class, 'gameEdit'])->name('admin.game.edit')->middleware(AdminCheck::class);
Route::get("/admin/user", [AdminController::class, "user"])->name("admin.user")->middleware(AdminCheck::class);
Route::delete('/admin/user/delete/{id}', [AdminController::class, 'userDelete'])->name('admin.user.delete')->middleware(AdminCheck::class);
Route::post('/admin/user/editForm/{id}', [AdminController::class, 'userEditForm'])->name('admin.user.editFrom')->middleware(AdminCheck::class);
Route::post('/admin/user/edit/{id}', [AdminController::class, 'userEdit'])->name('admin.user.edit')->middleware(AdminCheck::class);
Route::get('/admin/updateResults', [AdminController::class, 'updateResults'])->name('admin.updateResults')->middleware(AdminCheck::class);
Route::get('/admin/updateGames', [AdminController::class, 'updateGames'])->name('admin.updateGames')->middleware(AdminCheck::class);
Route::get("/admin/tournament", [AdminController::class, "tournament"])->name("admin.tournament")->middleware(AdminCheck::class);
Route::post("/admin/tournament/add", [AdminController::class, "tournamentAdd"])->name("admin.tournament.add")->middleware(AdminCheck::class);
Route::delete('/admin/tournament/delete/{id}', [AdminController::class, 'tournamentDelete'])->name('admin.tournament.delete')->middleware(AdminCheck::class);
Route::get('/admin/tournament/editForm/{id}', [AdminController::class, 'tournamentEditForm'])->name('admin.tournament.editFrom')->middleware(AdminCheck::class);
Route::post('/admin/tournament/editForm/{id}', [AdminController::class, 'tournamentEditForm'])->name('admin.tournament.editFrom')->middleware(AdminCheck::class);
Route::post('/admin/tournament/addTo/{id}/{id2}', [AdminController::class, 'tournamentEditFormAddTo'])->name('admin.tournament.editFromAddTo')->middleware(AdminCheck::class);
Route::delete('/admin/tournament/deleteFrom/{id}/{id2}', [AdminController::class, 'tournamentEditFormDeleteFrom'])->name('admin.tournament.editFromDeleteFrom')->middleware(AdminCheck::class);
Route::post('/admin/team/edit/{id}', [AdminController::class, 'tournamentEdit'])->name('admin.tournament.edit')->middleware(AdminCheck::class);
//Route::match(['get', 'tournament'], '/admin/team/edit/{id}', [AdminController::class, 'teamEdit'])->name('admin.team.edit')->middleware(AdminCheck::class);

Route::get("/login", [AuthController::class, "login"])->name("login");
Route::post("/login", [AuthController::class, "loginPost"])->name("login.post");
Route::get("/register", [AuthController::class, "register"])->name("register");
Route::post("/register", [AuthController::class, "registerPost"])->name("register.post");
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/moje', [AdminController::class, 'moje'])->name('moje');