<?php

namespace App\Http\Middleware;

use App\Support\PageAccessManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePageAccess
{
    public function handle(Request $request, Closure $next, string $pageKey): Response
    {
        $roleId = (int) data_get($request->session()->get('user'), 'role', 0);

        if (! PageAccessManager::canAccess($roleId, $pageKey)) {
            abort(403);
        }

        return $next($request);
    }
}
