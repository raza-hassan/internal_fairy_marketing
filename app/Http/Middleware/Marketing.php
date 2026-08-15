<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
class Marketing {


    public function handle($request, Closure $next)
    {

        if(Auth::user()->hasRole('Digital Marketing'))
        {
            return $next($request);
        }

       return redirect('/');


    }

}

?>
