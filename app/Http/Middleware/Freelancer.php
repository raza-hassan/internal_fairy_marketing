<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Freelancer
{

    public function handle(Request $request, Closure $next)
    {

        $user = Auth::guard('affiliator')->user();

        if($user->type == 'Freelancer')
        {
            return $next($request);
        }

        return redirect('/affiliator');

    }
}
