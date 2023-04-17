<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\TagController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

# Route::middleware(['api'])->get('tags', [TagController::class, 'index'])->name('tags');
