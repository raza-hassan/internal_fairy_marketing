<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Gates the /admin/* panel: the legacy Admin sentinel account (role=0,
 * which has no row in the `designation` table and can't cleanly map to a
 * Spatie role) plus Manager/Manager-1.
 */
class EnsureAdminAccess
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if ($user->role == 0 || $user->hasAnyRole(['Manager', 'Manager-1'])) {
            return $next($request);
        }

        return redirect('/');
    }
}
