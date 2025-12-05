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

Route::get('/logiciels/snake', function () {
    return view('logiciels.snake');
});
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
