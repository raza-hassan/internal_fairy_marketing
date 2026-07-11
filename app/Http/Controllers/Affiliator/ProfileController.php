<?php
namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use App\Models\Affiliator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('affiliator');
    }

    public function edit()
    {
        $user = Affiliator::find(Auth::guard('affiliator')->user()->id);
        return view('freelancer.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        auth()->guard('affiliator')->user()->update($request->all());


        $user = Affiliator::find(auth()->guard('affiliator')->user()->id);

        if ($request->file('cnicf')){
            $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
            $user->cnicf = $request->file('cnicf')->storeAs('affiliators', $fileName, 'public');
        }else{
            $user->cnicf=$request->oldcnicf;
        }
        if ($request->file('cnicb')){
            $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
            $user->cnicb = $request->file('cnicb')->storeAs('affiliators', $fileName, 'public');
        }else{
            $user->cnicb=$request->oldcnicb;
        }

        $user->save();

        return back()->withStatus(__('Profile successfully updated.'));
    }


    public function password(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed'],
        ]);
        auth()->guard('affiliator')->user()->update(['password' => Hash::make($request->get('password'))]);
        return back()->withStatus(__('Password successfully updated.'));
    }
}
