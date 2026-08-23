<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Blocks the Dealor role from the Clients/Leads/Tasks/Reports route tree.
 */
class ExcludeDealerRole
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->hasRole('Dealor')) {
            return $next($request);
        }

        return redirect('/');
    }
}
