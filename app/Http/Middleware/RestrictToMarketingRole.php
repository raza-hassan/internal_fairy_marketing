<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Gates the Compain module: Digital Marketing role only.
 */
class RestrictToMarketingRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->hasRole('Digital Marketing')) {
            return $next($request);
        }

        return redirect('/');
    }
}
