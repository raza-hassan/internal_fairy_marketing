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
        $selfRestricted = !$user->isTopLevelManager();
        return view('profile.edit', compact('user', 'selfRestricted'));
    }
    public function adminprofile()
    {
        $user = User::find(Auth::user()->id);
        $selfRestricted = !$user->isTopLevelManager();
        return view('profile.admin-edit', compact('user', 'selfRestricted'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        if ($user->isTopLevelManager()) {
            $user->update($request->all());
        } else {
            $user->update($request->only(User::SELF_EDIT_FIELDS));
        }
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
