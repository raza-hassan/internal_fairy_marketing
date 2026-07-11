<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Role as Middleware;
use Illuminate\Support\Facades\Auth;
class NotRoleTwelve
{
    public function handle($request, Closure $next)
    {

        if(Auth::user()->role != 12 )
        {
            return $next($request);
        }

        return redirect('/');

    }
}
