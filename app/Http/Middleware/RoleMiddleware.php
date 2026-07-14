<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            // Redirect to appropriate dashboard based on role
            return match($userRole) {
                'admin' => redirect()->route('admin.dashboard'),
                'saleshead' => redirect()->route('saleshead.dashboard'),
                'deliveryboy' => redirect()->route('deliveryboy.dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}
