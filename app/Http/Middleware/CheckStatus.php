<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
use Illuminate\Http\Request;
class CheckStatus {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(auth()->user()->status == 1){
            return $next($request);
        }
        // elseif(Auth::user()->role == 9){
        //     return redirect('/outer/inventory');
        // }
        else{
            return redirect('/');
        }
    }
}
?>