<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */
use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        //        $role = Auth::user()->status;

        $role = Auth::user()->role;

        switch ($role)
        {
            case '0':
                return '/admin';
                break;
            case '1':
                return '/';
                break;
            default:
                return '/';
                break;
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated($request, $user)
    {

        Helper::meetings();

        if (Auth::user()->status == 0)
        {
            Auth::logout();
            return redirect('login')->withErrors(['Your account is inactive']);
        }


        $role = Auth::user()->role;

        switch ($role)
        {
            case '0':
                return redirect('/admin');
                break;
            case '1':
                return redirect('/');
                break;
            case '2':
                return redirect('/');
                break;
            case '3':
                return redirect('/');
                break;
            case '4':
                return redirect('/');
                break;
            case '5':
                return redirect('/');
                break;
            case '6':
                return redirect('/');
                break;
            case '7':
                return redirect('/');
                break;
            case '8':
                return redirect('/');
                break;
            case '10':
                    return redirect('/compain');
                    break;
            case '11':
                return redirect('/inventory');
                break;
            default:
                return redirect('/login');
                break;
        }
    }
    public function vip_login()
    {
        return view('auth/login');
    }
    public function logout(Request $request) {
        if (Auth::check())
        {
            $role = Auth::user()->role;
            if ($role == 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/admin/login');
            }
            else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login');
            }
        }
        else{
            return redirect('/login');
        }
    }
}
