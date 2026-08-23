<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Illuminate\Http\Request;
class CheckOutSiderStatus {
    public function handle($request, Closure $next)
    {
        if(auth()->user()->status == 1 && Auth::user()->hasRole('Out Sider'))
        {
            return $next($request);
        }
        return redirect('/');
    }
}
?>
