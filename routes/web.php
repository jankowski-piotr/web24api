<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/v1/openapi.json', function () {
    return response()->file(public_path('docs/api/v1/openapi.json'));
});