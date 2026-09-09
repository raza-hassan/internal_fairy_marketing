<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Blocks the Digital Marketing role from the main Leads/Clients/Staff/
 * Inventory route tree — that role gets its own Compain module instead
 * (see RestrictToMarketingRole).
 */
class ExcludeMarketingRole
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->hasRole('Digital Marketing')) {
            return $next($request);
        }

        return redirect('/');
    }
}
