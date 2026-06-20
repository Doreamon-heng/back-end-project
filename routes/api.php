<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [\App\Http\Controllers\api\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\api\AuthController::class, 'login'])->name('login');

//middleware assignment for protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\api\AuthController::class, 'logout'])->name('logout');
    Route::get('/company-info', [\App\Http\Controllers\api\Company_infoController::class, 'index'])->name('company-info');
    Route::get('/order-details', [\App\Http\Controllers\api\Order_detailController::class, 'index'])->name('order-details');
    Route::get('/banks', [\App\Http\Controllers\api\BankController::class, 'index'])->name('banks');
});


//route for admin roles
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    //route here for admin role
    Route::get('logout', [\App\Http\Controllers\api\AuthController::class, 'logout'])->name('logout');
     
    //route for user management
    Route::get('/users', [\App\Http\Controllers\api\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\api\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'destroy'])->name('users.destroy');
});

//route for user roles
Route::middleware(['auth:sanctum', 'user'])->group(function () {
    // Define routes for user role here
});
  

Route::get('/categories', [App\Http\Controllers\api\CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [App\Http\Controllers\api\CategoryController::class, 'create'])->name('categories.create');