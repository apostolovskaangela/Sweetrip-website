<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user exists and has ceo or manager role
        if (!$user || (! $user->hasRole('ceo') && ! $user->hasRole('manager') && ! $user->hasRole('admin'))) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
