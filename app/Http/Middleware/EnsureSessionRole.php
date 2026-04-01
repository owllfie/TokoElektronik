<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->session()->get('user');

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array((string) ($user['role'] ?? ''), $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
