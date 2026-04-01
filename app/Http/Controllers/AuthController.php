<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->has('user')) {
            return redirect()->route(
                $this->defaultRouteForRole((int) data_get($request->session()->get('user'), 'role', 0))
            );
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

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('user', [
            'id_user' => $user->id_user,
            'username' => $user->username,
            'email' => $user->email,
            'role' => (int) $user->role,
        ]);

        return redirect()->route($this->defaultRouteForRole((int) $user->role));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function defaultRouteForRole(int $role): string
    {
        return match ($role) {
            1, 2, 3, 4 => 'home',
            default => 'login',
        };
    }
}
