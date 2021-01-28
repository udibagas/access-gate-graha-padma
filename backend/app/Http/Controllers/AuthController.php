<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $auth = Auth::attempt($request->only(['email', 'password']));

        if (!$auth) {
            return response(['message' => 'Invalid email or password'], 401);
        }

        return response('', 204);
    }

    public function logout()
    {
        Auth::logout();
        return response('', 204);
    }

    public function me()
    {
        return Auth::user();
    }
}
