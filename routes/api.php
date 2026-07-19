<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 





Route::post('/register', [\App\Http\Controllers\api\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\api\AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [\App\Http\Controllers\api\AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [\App\Http\Controllers\api\AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/recovery-account', [\App\Http\Controllers\api\AuthController::class, 'recoveryAccount'])->name('recovery.account');
Route::post('/recovery-account-by-phone', [\App\Http\Controllers\api\AuthController::class, 'recoveryAccountByPhone'])->name('recovery.account.phone');


    //route for roles
    Route::get('/roles', [\App\Http\Controllers\api\RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/{id}', [\App\Http\Controllers\api\RoleController::class, 'show'])->name('roles.show');
    Route::post('/roles', [\App\Http\Controllers\api\RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{id}', [\App\Http\Controllers\api\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [\App\Http\Controllers\api\RoleController::class, 'destroy'])->name('roles.destroy');
     //route for user roles
    Route::get('/roles-user', [\App\Http\Controllers\api\Role_userController::class, 'index'])->name('roles-user.index');
    Route::get('/roles-user/{id}', [\App\Http\Controllers\api\Role_userController::class, 'show'])->name('roles-user.show');
    Route::post('/roles-user', [\App\Http\Controllers\api\Role_userController::class, 'store'])->name('roles-user.store');
    Route::put('/roles-user/{id}', [\App\Http\Controllers\api\Role_userController::class, 'update'])->name('roles-user.update');
    Route::delete('/roles-user/{id}', [\App\Http\Controllers\api\Role_userController::class, 'destroy'])->name('roles-user.destroy');



//route for admin roles
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    //route here for admin role
    Route::get('logout', [\App\Http\Controllers\api\AuthController::class, 'logout'])->name('logout');
     
    //route for user management
    Route::get('/users', [\App\Http\Controllers\api\UserController::class, 'getAllUsers'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\api\UserController::class, 'createUser'])->name('users.store');
    Route::get('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'getUserById'])->name('users.show');
    Route::put('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\api\UserController::class, 'deleteUser'])->name('users.destroy');
});

//Route for editor roles
// Route::middleware(['auth:sanctum', 'editor'])->group(function () {
//     // Define routes for editor role here
//     Route::get('/editor-dashboard', [\App\Http\Controllers\api\EditorController::class, 'dashboard'])->name('editor.dashboard');
//     // Add more editor-specific routes as needed
// });


//route for user roles
Route::middleware(['auth:sanctum', 'user'])->group(function () {
    // Define routes for user role here
    Route::get('/logout', [\App\Http\Controllers\api\AuthController::class, 'logout'])->name('logout');
    
});
  