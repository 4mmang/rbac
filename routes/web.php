<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', function(){
    if (!Auth::check()) {
        return "unauthorized";
    }
    $user = Auth::user();
    if ($user->isAbleTo('view user')) {
        return "you can  access manage role";
    }

    return "forbidden";
});