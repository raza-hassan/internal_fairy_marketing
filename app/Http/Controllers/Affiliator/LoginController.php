<?php

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;

class LoginController extends Controller
{

// use AuthenticatesUsers;


    public function showLoginForm()
    {
        return view('auth.affiliator.login');
    }

    public function login(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required',
        'password' => 'required'
      ]);


      $attempt=Auth::guard('affiliator')->attempt(['email' =>  $request->email, 'password' => $request->password]);

        if ($attempt==1)
        {
            return redirect()->intended(route('affiliator.dashboard'));
        }
        else
        {
            return redirect()->route('affiliator.login')->withErrors(['These credentials do not match our records.']);
        }

    }

    protected function guard()
    {
        return Auth::guard('affiliator');
    }


    public function logout()
    {
        $this->guard('affiliator')->logout();
        session()->flash('message', 'Just Logged Out!');
        return redirect('/affiliator/login');
    }
}
