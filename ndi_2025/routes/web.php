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


