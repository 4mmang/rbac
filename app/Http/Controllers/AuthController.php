<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AuthController extends Controller
{

    public function formLogin()
    {
        return Inertia::render('Auth/FormLogin');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // public function login(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $credentials = $request->only(['email', 'password']);
    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         return response()->json([
    //             'message' => 'login succesfully',
    //             'data' => $user
    //         ], 200);
    //     }

    //     return response()->json([
    //         'message' => 'email or password invalid'
    //     ], 401);


    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
           'message' => 'Email or password invalid'
        ]); 
    }
}
