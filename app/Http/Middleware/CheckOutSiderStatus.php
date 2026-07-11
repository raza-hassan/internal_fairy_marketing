<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Illuminate\Http\Request;
class CheckOutSiderStatus {
    public function handle($request, Closure $next)
    {
        if(auth()->user()->status == 1 && Auth::user()->role == 9)
        {
            return $next($request);
        }
        return redirect('/');
    }
}
?>
