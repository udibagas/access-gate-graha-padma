<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where(function ($q) use ($request) {
            $q->where('name', $request->email)
                ->orWhere('email', $request->email);
        })->first();

        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user);
            return response('', 204);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response('BYE!', 204);
    }

    public function me()
    {
        return Auth::user();
    }
}
