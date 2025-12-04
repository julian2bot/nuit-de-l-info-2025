<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
