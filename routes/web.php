<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaultController;

Route::get('/faults', [FaultController::class, 'index']);

// Route::get('/', function () {
//     return view('welcome');
// });
