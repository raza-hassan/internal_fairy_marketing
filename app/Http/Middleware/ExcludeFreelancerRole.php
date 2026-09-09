<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Blocks the Freelancer role from the Staff/Affiliators/Targets/Role
 * management route tree.
 */
class ExcludeFreelancerRole
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user()->hasRole('Freelancer')) {
            return $next($request);
        }

        return redirect('/');
    }
}
