<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Role as Middleware;
use Illuminate\Support\Facades\Auth;
class NotRoleEleven
{
    public function handle($request, Closure $next)
    {

        if(!Auth::user()->hasRole('Dealor'))
        {
            return $next($request);
        }

        return redirect('/');

    }
}
