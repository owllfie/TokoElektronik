<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->has('user')) {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || $request->input('password') !== $user->password) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('user', [
            'id_user' => $user->id_user,
            'username' => $user->username,
            'email' => $user->email,
        ]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
