<?php

use App\Http\Controllers\AuthController;
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

Route::get('/discorde', function () {
    return view('discorde');
})->name('discorde');

Route::post('/auth/login', [AuthController::class,'loginOrRegister'])->name("auth.loginRegister");
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');



Route::get('/me', function () {
    return Auth::user();
})->name("eee");
