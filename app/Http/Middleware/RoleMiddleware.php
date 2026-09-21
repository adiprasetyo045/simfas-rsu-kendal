<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:admin,teknisi')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role?->name;

        // Flatten array in case hosting passes 'admin,supervisor' as a single string
        $parsedRoles = [];
        foreach ($roles as $r) {
            $parsedRoles = array_merge($parsedRoles, explode(',', $r));
        }
        $parsedRoles = array_map('trim', $parsedRoles);

        if (!in_array($userRole, $parsedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
