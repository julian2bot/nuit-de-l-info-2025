<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('os');
});



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
