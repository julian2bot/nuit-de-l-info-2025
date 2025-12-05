<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('os');
})->name("os");


Route::get('/auth', function () {
    return view('home');
})->name("auth.home");



Route::get('/truc', function () {
    return view('truc.index');
});


Route::get('/truc/machine', function () {
    return view('welcome');
})->name("trucMachine");

Route::get('/explorateur-fichier', function () {
    return view('explorateur_fichier');
})->name("explorateurFichier");
Route::get('/editeur-texte', function () {
    return view('editeur_texte');
})->name("editeurTexte");

Route::get('/editeur-texte-mdp', function () {
    return view('editeur_texte_mdp');
})->name("editeurTexte_mdp");


Route::get('/editeur-texte-secret', function () {
    return view('editeur_texte_secret');
})->name("editeur_texte_secret");


Route::get('/lecteur-audio', function () {
    return view('lecteur_audio');
})->name("lecteur_audio");


Route::get('/photo-nous', function () {
    return view('photo_nous');
})->name("photo_nous");



Route::get('/credit', function () {
    return view('credit');
})->name("credit");

Route::get('/logiciels/snake', function () {
    return view('logiciels.snake');
})->name("snake");;
Route::get('/discorde', function () {
    return view('discorde');
})->name('discorde');

Route::post('/auth/login', [AuthController::class,'loginOrRegister'])->name("auth.loginRegister");
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');



Route::get('/me', function () {
    return Auth::user();
})->name("eee");

Route::post('/score', [ScoreController::class, 'store'])
    ->middleware('auth')
    ->name('score.store');

Route::post('/score/load', [ScoreController::class, 'load'])
    ->name('score.load');


Route::get('/logiciels/score', function () {
    return view('score.dashboard');
  })->name("score.dashboard");

})->name("score.dashboard");

Route::get('/logiciels/nird/nird', function () {
    return view('logiciels.nird.nird');
})->name("logiciels.nird.nird");

Route::get('/logiciels/nird/linux', function () {
    return view('logiciels.nird.linux');
})->name("logiciels.nird.linux");

Route::get('/logiciels/nird/libres', function () {
    return view('logiciels.nird.libres');
})->name("logiciels.nird.libres");

Route::get('/logiciels/nird/recondition', function () {
    return view('logiciels.nird.recondition');
})->name("logiciels.nird.recondition");

Route::get('/logiciels/nird/serveur', function () {
    return view('logiciels.nird.serveur');
})->name("logiciels.nird.serveur");
