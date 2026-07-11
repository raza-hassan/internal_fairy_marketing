<?php

namespace App\Http\Controllers\outer;

use App\Mail\YourAccountIsActive;
use App\Models\User;
use App\Models\Designations;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Auth;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(User $model) {
        $users = $model->where('role', '!=', 0)->orderBy('id', 'desc')->paginate(50);
        return view('users.index', compact('users'));
    }

    public function staff(User $model) {
        if (Auth::user()->role == 5) {
            $users = $model->where('role', '!=', 0)->orderBy('id', 'asc')->paginate(50);
        } else {
            $users = $model->where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->paginate(50);
        }
        return view('users.staff', compact('users'));
    }

    public function teams(User $model) {
        $users = $model->where('parent', Auth::user()->id)->get();
        return view('users.employee', compact('users'));
    }

    public function accountant(User $model) {
        $users = $model->where('role', '=', 1)->where('status', '=', 1)->orderBy('id', 'desc')->paginate(25);
        return view('users.index', compact('users'));
    }

    public function employees(User $model) {
        $users = $model->where('role', '=', 2)->where('status', '=', 1)->orderBy('id', 'desc')->paginate(25);
        return view('users.employee', compact('users'));
    }

    public function create() {
        $managers = User::where('role', '!=', 0)->where('role', '!=', 6)->where('role', '!=', 7)->where('role', '!=', 8)->get();
        $designations = Designations::all();
        $departments = Departments::all();
        return view('users.create', compact('managers', 'designations', 'departments'));
    }

    public function store(Request $request, User $model) {

        $validated = $request->validate([
            'email' => 'required|unique:users',
            'cnic' => 'required|unique:users',
            'dob' => 'required',
            'name' => 'required',
            'telephone1' => 'required',
            'password' => ['required', 'confirmed'],
            'role' => 'required',
            'designation_name' => 'required',
            'department_id' => 'required',
            'parent' => 'required',
                // 'sales_target' => 'required',
                // 'unit_target' => 'required',
                ], [
            'dob.required' => 'please Select the Date Of Birth',
            'role.required' => 'please Select the Account Type',
            'parent.required' => 'please Select the Manager',
            'department_id.required' => 'please Select the Department',
            'designation_name.required' => 'please Fill Designation Name Field',

        ]);


        $filePath = '';
        if ($request->file) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('users', $fileName, 'public');
        }
        $cnicf = '';
        if ($request->file('cnicf')) {
            $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
            $cnicf = $request->file('cnicf')->storeAs('users', $fileName, 'public');
        }
        $cnicb = '';
        if ($request->file('cnicb')) {
            $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
            $cnicb = $request->file('cnicb')->storeAs('users', $fileName, 'public');
        }

        $parent = 0;
        if ($request->get('parent') > 0 && $request->get('parent') != 'Select Manager') {
            $parent = $request->get('parent');
        }

        $user = $model->create(
                    $request->merge(['password' => Hash::make($request->get('password')),
                    'profile' => $filePath,
                    'parent' => $parent])->all());

        $user->cnicf = $cnicf;
        $user->cnicb = $cnicb;

        $user->sales_target = 100;
        $user->unit_target = 40;

        $user->save();
        return redirect('/staff')->withStatus(__('User successfully created.'));

    }

    public function edit(User $user)
    {
        $managers = User::where('role', '!=', 0)->where('role', '!=', 6)->where('role', '!=', 7)->where('role', '!=', 8)->get();
        $designations = Designations::all();
        $departments = Departments::all();
        return view('users.edit', compact('user', 'managers', 'designations', 'departments'));
    }

    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'cnic' => 'required',
            'name' => 'required',
            'email' => 'required',
            'telephone1' => 'required',
        ]);

        if ($request->file('file')) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('users', $fileName, 'public');
        } else {
            $filePath = $request->input('oldfile');
        }
        if ($request->file('cnicf')) {
            $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
            $cnicf = $request->file('cnicf')->storeAs('users', $fileName, 'public');
        } else {
            $cnicf = $request->input('oldcnicf');
        }
        if ($request->file('cnicb')) {
            $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
            $cnicb = $request->file('cnicb')->storeAs('users', $fileName, 'public');
        } else {
            $cnicb = $request->input('oldcnicb');
        }
        $parent = 0;
        if ($request->get('parent') > 0 && $request->get('parent') != 'Select Manager') {
            $parent = $request->get('parent');
        }

        $user->update($request->all());

        $user->cnicf = $cnicf;
        $user->cnicb = $cnicb;
        $user->profile = $filePath;
        $user->save();

        return back()->withStatus(__('User successfully updated.'));
    }

    public function status(Request $request, User $user) {
        $status = 0;
        if ($request->input('status')) {
            $status = $request->input('status');
        }
        $user->status = $status;
        $user->save();

        $data = array('name' => $request->input('username'), 'status' => $status, 'useremail' => $request->input('useremail'));
        Mail::to($data['useremail'])->cc('accounting@amaroni.com')->bcc('salessupport@amaroni.com')->send(new YourAccountIsActive());

//          Mail::send('mail', $data, function($message) use ($data) {
//             $message->to($data['useremail'])->cc('shahid.shahid34@gmail.com')->subject
//                ('Account Activation');
//             $message->from('info@accentra.in');
//          });
        //   echo "HTML Email Sent. Check your inbox.";
        return back()->withStatus(__('User Status updated Successfully.'));
    }

    public function destroy(User $user) {
        $user->delete();
        return back()->withStatus(__('User successfully deleted.'));
    }

    public function password(Request $request, User $user) {
        $validated = $request->validate([
            'password' => ['required', 'confirmed'],
        ]);

        $user->update(
                $request->merge(['password' => Hash::make($request->get('password'))])
                        ->except([$request->get('password') ? '' : 'password']
        ));

        return back()->withStatus(__('Password successfully updated.'));
    }

    public function verification() {
        return view('auth.verifystatus');
    }

    public function search(Request $request) {

        $condition = array();

        if ($request->input('name') != '') {
            $condition[] = array('name', 'Like', '%'.$request->input('name').'%');
        }


        if ($request->input('email') != '') {
            $condition[] = array('email', '=', $request->input('email'));
        }

        if ($request->input('telephone1') != '') {
            $condition[] = array('telephone1', '=', $request->input('telephone1'));
        }
        if (Auth::user()->role != 1 && Auth::user()->role != 5) {
            $condition[] = array('parent', '=', Auth::user()->id);
        }
        $users = User::where($condition)->where('role', '!=', 0)->orderBy('id', 'asc')
                ->paginate(50);
        return view('users.staff', compact('users'));
    }

    public function PerformanceGraph() {
        $users = User::paginate(20);
        $students = User::where('id', Auth::user()->id)->get();

        $dataPoints = [];

        foreach ($students as $student) {
            $dataPoints[] = array(
                "name" => $student['name'],
                "data" => [
                    intval($student['sales_target']),
                    intval($student['unit_target']),
                ],
            );
        }

        return view("users.user_graph", compact('users'), [
            "data" => json_encode($dataPoints),
            "terms" => json_encode(array(" Sales Target : ", " Unit Target : ",)),
        ]);
    }

    public function UserSortingGraph(Request $request) {
        $users = User::paginate(20);
        $graph_user = $request->input('users');
        $students = User::where('id', $graph_user)->get();

        $dataPoints = [];

        foreach ($students as $student) {
            $dataPoints[] = array(
                "name" => $student['name'],
                "data" => [
                    intval($student['sales_target']),
                    intval($student['unit_target']),
                ],
            );
        }

        return view("users.user_graph", compact('users'), [
            "data" => json_encode($dataPoints),
            "terms" => json_encode(array(" Sales Target : ", " Unit Target : ",)),
        ]);
    }
}
