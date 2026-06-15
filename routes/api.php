<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/categories', [App\Http\Controllers\api\CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [App\Http\Controllers\api\CategoryController::class, 'create'])->name('categories.create');
