<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
class Marketing {


    public function handle($request, Closure $next)
    {

        if(Auth::user()->role == 10)
        {
            return $next($request);
        }

       return redirect('/');


    }

}

?>
