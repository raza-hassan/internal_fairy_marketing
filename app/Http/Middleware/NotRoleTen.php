<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Role as Middleware;
use Illuminate\Support\Facades\Auth;
class NotRoleTen
{
    public function handle($request, Closure $next)
    {

        if(Auth::user()->role != 10 )
        {
            return $next($request);
        }

        return redirect('/');

    }
}
