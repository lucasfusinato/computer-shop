<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('products', 'ProductController')->except('show');
Route::resource('clients', 'ClientController')->except('show');