<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaultController;

Route::get('/faults', [FaultController::class, 'index']);
Route::post('/faults', [FaultController::class, 'store']);

// Route::post('/faults', function () {
//     return response()->json(['message' => 'API working']);
// });
