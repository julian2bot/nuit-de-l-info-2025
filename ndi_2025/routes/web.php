<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/discorde', function () {
    return view('discorde');
})->name('discorde');
