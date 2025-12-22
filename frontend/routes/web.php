<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DiagnosaController::class, 'index']);
Route::post('/diagnosa/submit', [DiagnosaController::class, 'submit']);
