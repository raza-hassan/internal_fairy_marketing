<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = User::find(Auth::user()->id);
        return view('profile.edit', compact('user'));
    }
    public function adminprofile()
    {
        $user = User::find(Auth::user()->id);
        return view('profile.admin-edit', compact('user'));
    }

    public function update(Request $request)
    {
        auth()->user()->update($request->all());
        return back()->withStatus(__('Profile successfully updated.'));
    }

    public function password(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed'],
        ]);
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);
        return back()->withStatus(__('Password successfully updated.'));
    }
}
