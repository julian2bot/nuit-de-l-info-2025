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

Route::get('/truc/testSnake', function () {
    return view('truc.testSnake');
});


Route::get('/3d/three', function () {
    return view('3d.three');
})->name("threejs");