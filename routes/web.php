<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia; 

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [AuthController::class, 'formLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::prefix('/master')
    ->name('master.')
    ->middleware(['auth'])
    ->group(function () {
        Route::resource('role', RoleController::class);
    }); 
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::resource('/role', RoleController::class)->middleware('auth');
    
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/dashboard', function(){
//     if (!Auth::check()) {
//         return "unauthorized";
//     }
//     $user = Auth::user();
//     if ($user->isAbleTo('view user')) {
//         return "you can  access manage role";
//     }

//     return "forbidden";
// });