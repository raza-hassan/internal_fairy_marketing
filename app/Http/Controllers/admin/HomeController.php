<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use DB;

class HomeController extends Controller {

    public function index() {
        $users = User::where('role', '=', 2)->get();
        return view('admin.dashboard', compact('users'));
    }


    public function logout() {
        Auth::logout();
        return redirect('admin/login');
    }

}
