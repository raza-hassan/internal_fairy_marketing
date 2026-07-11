<?php
namespace App\Http\Controllers;

use App\Http\Helpers\Helper as HelpersHelper;
use App\Mail\YourAccountIsActive;
use App\Models\User;
use App\Models\Target;
use App\Models\Designations;
use App\Models\Departments;
use App\Models\Offices;
use App\Models\AffiliatorTask;
use App\Models\Product;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Http\Helpers\Helper;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $model)
    {
        $users = $model->where('role', '!=', 0)->orderBy('id', 'desc')->paginate(50);
        return view('users.index', compact('users'));
    }

    public function staff(User $model)
    {
        if(Auth::user()->can('staff.view'))
        {
            // ====== Users With Helper======with pagination
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $response = Helper::users($data);
                $users = $response['users'];

                // Assuming $users is a collection, you can paginate it like this
                $perPage = 50;
                $page = request()->get('page', 1); // Get the current page from the request, default to 1
                $offset = ($page - 1) * $perPage;

                // Use slice to get the portion of the collection for the current page
                $paginatedItems = $users->slice($offset, $perPage)->all();

                // Create a LengthAwarePaginator instance
                $users = new LengthAwarePaginator($paginatedItems, $users->count(), $perPage, $page, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]);

            // ====== Users With Helper======

            // ======= manager With Helper=======
                $managers = $response['users']->filter(function ($user) {
                    return ($user['role'] == 5 || $user['role'] == 1 && $user['office_id'] == 1 );
                });
            // ======= manager With Helper=======

            $offices = Offices::orderBy('id', 'asc')->get();
            return view('users.staff', compact('users' , 'offices' , 'managers'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function inactiveStaff(User $model)
    {
        if(Auth::user()->can('staff.view'))
        {
            // ====== Users With Helper======with pagination
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $response = Helper::usersInactive($data);
                $users = $response['users'];

                // Assuming $users is a collection, you can paginate it like this
                $perPage = 50;
                $page = request()->get('page', 1); // Get the current page from the request, default to 1
                $offset = ($page - 1) * $perPage;

                // Use slice to get the portion of the collection for the current page
                $paginatedItems = $users->slice($offset, $perPage)->all();

                // Create a LengthAwarePaginator instance
                $users = new LengthAwarePaginator($paginatedItems, $users->count(), $perPage, $page, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]);

            // ====== Users With Helper======

            // ======= manager With Helper=======
                $managers = $response['users']->filter(function ($user) {
                    return ($user['role'] == 5 || $user['role'] == 1 && $user['office_id'] == 1 );
                });
            // ======= manager With Helper=======

            $offices = Offices::orderBy('id', 'asc')->get();
            return view('users.inactive-staff', compact('users' , 'offices' , 'managers'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
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

    public function create()
    {
        if(Auth::user()->can('staff.create'))
        {
            // ======= Users With Helper=======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' =>  Auth::user()->role,
                );
                $response = Helper::users($data);
                $managers = $response['users']->filter(function ($user) {
                    return ($user['role'] == 5 || $user['role'] == 1);
                });
            // ======= Users With Helper=======

            $designations = Designations::orderBy('sequence_menu', 'asc')->get();
            $departments = Departments::all();
            $offices = Offices::all();
            $roles = Role::all();
            return view('users.create', compact('managers', 'designations', 'departments' , 'offices' , 'roles'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function store(Request $request, User $model)
    {
        if(Auth::user()->can('staff.create'))
        {
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
                'office_id' => 'required',
                    // 'sales_target' => 'required',
                    // 'unit_target' => 'required',
                    ], [
                'dob.required' => 'please Select the Date Of Birth',
                'role.required' => 'please Select the Account Type',
                'parent.required' => 'please Select the Manager',
                'department_id.required' => 'please Select the Department',
                'designation_name.required' => 'please Fill Designation Name Field',
                'office_id.required' => 'please Select Office Name',
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
            //echo $request->get('role'); exit;
            $user = $model->create(
                        $request->merge(['password' => Hash::make($request->get('password')),
                        'profile' => $filePath,
                        'parent' => $parent])->all());
            $user->cnicf = $cnicf;
            $user->cnicb = $cnicb;
            // $user->sales_target = 100;
            // $user->unit_target = 40;
            $user->save();

            if ($request->assign_role)
            {
                $assign = (int)$request->assign_role;
                $user->assignRole($assign);
            }

            // ======Send Notification To Parent User====

                $data = array(
                    'type' => 'New Staff',
                    'msg_body' => base64_encode('New Staff Has Created By '.Auth::user()->name.' ID : <a href="'.url("/user/edit/$user->id/").'">'.$user->id.'</a>'),
                    'created_by' => Auth::user()->id,
                    'show_to' => Auth::user()->parent,
                    'show_to_role' => 0,
                    'redirect' => 'staff',
                );
                Helper::notification($data);

            // ======Send Notification To Role User====

                $roleUsers=User::where('role' , 5)->get();

                foreach($roleUsers as $roleUser)
                {
                    if(Auth::user()->parent != $roleUser->id)
                    {
                        $data = array(
                            'type' => 'New Staff',
                            'msg_body' => base64_encode('New Staff Has Created By '.Auth::user()->name.' ID : <a href="'.url("/user/edit/$user->id/").'">'.$user->id.'</a>'),
                            'created_by' => Auth::user()->id,
                            'show_to' => $roleUser->id, // Only Show to role Users as a id
                            'show_to_role' => 0,
                            'redirect' => 'staff',
                        );
                        Helper::notification($data);
                    }
                }
            // ==========Notification End==========

            return redirect('/staff')->withStatus(__('User successfully created.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function edit(User $user)
    {
        // echo "<pre>";print_r($user); exit;

        if(Auth::user()->can('staff.edit'))
        {
            $auth_user = Auth::user();
            if ($auth_user->designation->sequence_menu > $user->designation->sequence_menu)
            {
                return back()->withErrors(__('User doesn\'t have permission to access this resource'));
            }

            // $managers = User::where('role', '!=', 0)->where('role', '!=', 6)->where('role', '!=', 7)->where('role', '!=', 8)->get();

            // ======= Users With Helper=======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' =>  Auth::user()->role,
                );
                $response = Helper::users($data);
                $managers = $response['users']->filter(function ($user) {
                    return ($user['role'] == 5 || $user['role'] == 1);
                });
            // ======= Users With Helper=======

            $departments = Departments::all();
            $designations = Designations::orderBy('sequence_menu', 'asc')->get();
            $offices = Offices::all();
            $roles = Role::all();
            return view('users.edit', compact('user', 'managers', 'designations', 'departments' , 'offices' ,'roles'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function update(Request $request, User $user)
    {
        // echo "<pre>";print_r($request->all());exit;
        if(Auth::user()->can('staff.edit'))
        {
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
            //echo $filePath; exit;
            if ($request->file('cnicf')) {
                $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
                $cnicf = $request->file('cnicf')->storeAs('users', $fileName, 'public');
            } else {
                $cnicf = $request->input('oldcnicf');
            }
            //echo $cnicf; exit;
            if ($request->file('cnicb')) {
                //echo 'dfsdf'; exit;
                $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
                $cnicb = $request->file('cnicb')->storeAs('users', $fileName, 'public');
            } else {
                $cnicb = $request->input('oldcnicb');
            }
                //        echo $cnicb; exit;
            $parent = 0;
            if ($request->get('parent') > 0 && $request->get('parent') != 'Select Manager') {
                $parent = $request->get('parent');
            }
            $user->update($request->all());
            $user->cnicf = $cnicf;
            $user->cnicb = $cnicb;
            $user->profile = $filePath;
            $user->save();

            if ($request->assign_role)
            {
                $user->roles()->detach();
                $assign = (int)$request->assign_role;
                $user->assignRole($assign);
            }

            return back()->withStatus(__('User successfully updated.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function search(Request $request)
    {
        // echo '<pre>';print_r($request->all());exit;

        if(Auth::user()->can('staff.view'))
        {
            $condition = array();

            $condition[] = array('is_delete', 0);
            $condition[] = array('role', '!=', 0);

            if ($request->input('name') != '') {
                $condition[] = array('name', 'Like', '%'.$request->input('name').'%');
            }
            if ($request->input('email') != '') {
                $condition[] = array('email', 'Like', '%'.$request->input('email').'%' );
            }
            if ($request->input('telephone1') != '') {
                $condition[] = array('telephone1', 'Like', '%'.$request->input('telephone1').'%' );
            }

            // For Role 5 only
            if ($request->input('id_office') > 0) {
                $condition[] = array('office_id', '=', $request->input('id_office'));
            }
            if ($request->input('user_id') > 0) {
                $condition[] = array('parent', '=', $request->input('user_id'));
            }
            // For Role 5 only

            if (Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) {
                $condition[] = array('parent', '=', Auth::user()->id);
            }

            // Search in the title and body columns from the posts table
            $users = User::where($condition)->orderBy('id', 'asc')->paginate(50);

            $offices = Offices::orderBy('id', 'asc')->get();


            if($request->id_office){
                $office_id= $request->id_office;
            }else{
                $office_id = Auth::user()->office_id;
            }
            // ======= Users With Helper=======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' =>  Auth::user()->role,
                );
                $response = Helper::users($data);
                $managers = $response['users']->filter(function ($user) use ($office_id) {
                    return ($user['role'] == 5 || $user['role'] == 1) && $user['office_id'] == $office_id;
                });
            // ======= Users With Helper=======

            return view('users.staff', compact('users' , 'offices' , 'managers'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }
    public function status(Request $request, User $user) {
        //print_r($request->input()); exit;
        // echo $request->input('status'); exit;
        $status = 0;
        if ($request->input('status')) {
            $status = $request->input('status');
        }
        $user->status = $status;
        $user->save();
        $data = array('name' => $request->input('username'), 'status' => $status, 'useremail' => $request->input('useremail'));
        // echo $data['useremail']; exit;
        // $email = $request->input('useremail');
        Mail::to($data['useremail'])->cc('accounting@amaroni.com')->bcc('salessupport@amaroni.com')->send(new YourAccountIsActive());
        //          Mail::send('mail', $data, function($message) use ($data) {
        //             $message->to($data['useremail'])->cc('shahid.shahid34@gmail.com')->subject
        //                ('Account Activation');
        //             $message->from('info@accentra.in');
        //          });
        //   echo "HTML Email Sent. Check your inbox.";
        return back()->withStatus(__('User Status updated Successfully.'));
    }

    public function destroy(User $user)
    {
        if(Auth::user()->can('trashed.staff.delete'))
        {
            if ($user->leads_count()->orWhere("share_id",$user->id)->count() > 0){
                return back()->withErrors(__(' You have to transfer this user Leads to someone first'));
            }else{
                $user->delete();
                return back()->withStatus(__('User successfully deleted.'));
            }
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }


    // =======trash staf start=======

        public function trashStaff($id)
        {
            if(Auth::user()->can('staff.trash'))
            {
                // $user=User::where('id', $id)->first();
                // if ($user->leads_count()->orWhere("share_id",$user->id)->count() > 0) {
                //     return back()->withErrors(__(' You have to transfer this user Leads to someone first'));
                // }else{
                //     $user->is_delete=1;
                //     $user->save();
                    User::where('id', $id)->update([
                        'is_delete' => 1
                    ]);
                // }
                return redirect('/staff')->withStatus(__('User Trashed Successfully.'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function trashedStaff(User $model)
        {
            // echo"yes";exit;
            if(Auth::user()->can('trashed.staff.view'))
            {
                if(Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
                    $users = $model->where('is_delete', 1)->orderBy('id', 'asc')->paginate(50);
                }
                else{
                    // $users = $model->where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->where('is_delete', 1)->orderBy('id', 'asc')->paginate(50);

                    $users= $model->where('is_delete', 1) // First where clause
                            ->where(function ($query)
                            {
                                $query->where('id', Auth::user()->id)
                                    ->Orwhere('parent', Auth::user()->id);
                            })
                            ->orderBy('id', 'desc')->paginate(50);
                }

                $offices = Offices::orderBy('id', 'asc')->get();
                return view('users.trashed_staff', compact('users' , 'offices'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function unTrashStaff($id)
        {
            if(Auth::user()->can('trashed.staff.restore'))
            {
                User::where('id', $id)->update([
                    'is_delete' => 0
                ]);
                return redirect('trash-staff')->withStatus(__('User Active Successfully.'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function trashsearch(Request $request) {
            // Get the search value from the request

            if(Auth::user()->can('trashed.staff.view'))
            {
                $condition = array();

                $condition[] = array('is_delete', '=', 1);

                if ($request->input('name') != '') {
                    $condition[] = array('name', 'Like', '%'.$request->input('name').'%');
                }
                if ($request->input('email') != '') {
                    $condition[] = array('email', '=', $request->input('email'));
                }
                if ($request->input('telephone1') != '') {
                    $condition[] = array('telephone1', 'Like', '%'.$request->input('telephone1').'%');
                }
                if ($request->input('office_id') != '') {
                    $condition[] = array('office_id', 'Like', '%'.$request->input('office_id').'%');
                }
                if (Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) {
                    $condition[] = array('parent', '=', Auth::user()->id);
                }

                $users = User::where($condition)->orderBy('id', 'asc')->paginate(50);

                $offices = Offices::orderBy('id', 'DESC')->get();
                // $office_id = 0;
                return view('users.trashed_staff', compact('users' , 'offices'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function permanentlyDeleteStaff($id)
        {
            if(Auth::user()->can('trashed.staff.delete'))
            {
                $user=User::where('id', $id)->first();
                if ($user->leads_count()->orWhere("share_id",$user->id)->count() > 0) {
                    return back()->withErrors(__(' You have to transfer this user Leads to someone first'));
                }else{
                    $user->delete();
                }
                return redirect('trash-staff')->withStatus(__('User Deleted Successfully.'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

    // =======trash staf end=======

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

    // ======Targets Functions ======
        public function targets()
        {
            if(Auth::user()->can('target.view'))
            {
                // ====== Users With Helper======
                    $data = array(
                        'id' => Auth::user()->id,
                        'role' => Auth::user()->role,
                    );
                    $responce = Helper::users($data);
                    $users = $responce['users'];
                // ====== Users With Helper======

                $targets = Target::whereIn('user_id' , $users->pluck('id'))->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year)->orderBy('id', 'desc')->paginate(50);

                return view('users.targets', compact('targets' , 'users'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function repeat_targets()
        {
            if(Auth::user()->can('target.repeat'))
            {
                // ====== Users With Helper======
                    $data = array(
                        'id' => Auth::user()->id,
                        'role' => Auth::user()->role,
                    );
                    $responce = Helper::users($data);
                    $users = $responce['users'];
                // ====== Users With Helper======

                $previous_targets = Target::whereIn('user_id' , $users->pluck('id'))->whereBetween('start_date', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()])->get();

                if(count($previous_targets) > 0)
                {
                    $startOfMonth=Carbon::now()->startOfMonth();
                    $endOfMonth=Carbon::now()->endOfMonth();

                    foreach($previous_targets as $previous_target)
                    {
                        $target = Target::where('user_id' , $previous_target->user_id)->whereBetween('start_date', [$startOfMonth, $endOfMonth])->first();

                        if(!($target) || $target =='' )
                        {
                            Target::insert(
                            [
                                'user_id'                           => $previous_target->user_id,

                                'set_sales_target'                  => $previous_target->set_sales_target,
                                'set_unit_target'                   => $previous_target->set_unit_target,
                                'set_lead_target'                   => $previous_target->set_lead_target,
                                'set_affiliator_target'             => $previous_target->set_affiliator_target,
                                'set_verified_calls_target'         => $previous_target->set_verified_calls_target,
                                'set_client_meetings_target'        => $previous_target->set_client_meetings_target,
                                'set_dealer_meetings_target'        => $previous_target->set_dealer_meetings_target,
                                'set_freelancer_meetings_target'    => $previous_target->set_freelancer_meetings_target,
                                'set_site_visit_target'             => $previous_target->set_site_visit_target,

                                'perday_sales_target'               =>  $previous_target->perday_sales_target ,
                                'perday_unit_target'                =>  $previous_target->perday_unit_target ,
                                'perday_lead_target'                =>  $previous_target->perday_lead_target ,
                                'perday_affiliator_target'          =>  $previous_target->perday_affiliator_target ,
                                'perday_verified_calls_target'      =>  $previous_target->perday_verified_calls_target ,
                                'perday_client_meetings_target'     =>  $previous_target->perday_client_meetings_target ,
                                'perday_dealer_meetings_target'     =>  $previous_target->perday_dealer_meetings_target ,
                                'perday_freelancer_meetings_target' =>  $previous_target->perday_freelancer_meetings_target ,
                                'perday_site_visit_target'          =>  $previous_target->perday_site_visit_target ,

                                'start_date' => Carbon::now(),
                                'expiry_date'=> $endOfMonth,

                                'created_at' => Carbon::now(),

                            ]);
                        }
                    }

                }

                $targets = Target::whereIn('user_id' , $users->pluck('id'))->orderBy('start_date', 'desc')->paginate(50);
                return view('users.all_targets', compact('targets' , 'users'))->with('message','Previous Month Targets Repeated Successfully.');
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function setTargets(Request $request)
        {
            if(Auth::user()->can('target.create'))
            {
                $startDate = Carbon::create(date("Y-m-d", strtotime($request->startDate)). ' 00:00:00');
                $endDate   = Carbon::create(date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59');
                $period = CarbonPeriod::create($startDate, $endDate);

                $days_btwn_dates = 0;

                foreach ($period as $date)
                {
                    if ($date->dayOfWeek !== Carbon::SUNDAY)
                    {
                        $days_btwn_dates++;
                    }
                }

                if ($days_btwn_dates < 1)
                {
                    return redirect()->back()->withInput($request->input())
                            ->with('error' , 'Selected Date is not Correct. Also Check Today is not Sunday');
                }

                $month = date('m',strtotime($startDate));
                $year = date('Y',strtotime($startDate));
                $startOfMonth=Carbon::now()->month($month)->startOfMonth();
                $endOfMonth=Carbon::now()->month($month)->endOfMonth();

                if($request->user[0] == 0)
                {
                    // ===== Users With Helper=====
                        $data = array(
                            'id' => Auth::user()->id,
                            'role' => Auth::user()->role,
                        );
                        $responce = Helper::users($data);
                        $users = $responce['users'];
                    // ===== Users With Helper=====

                    foreach($users as $user)
                    {
                        $target = Target::where('user_id' , $user->id)->whereBetween('start_date', [$startOfMonth, $endOfMonth])->first();

                        if(!($target) || $target =='' )
                        {
                            $request->validate([
                                'set_sales_target'              => 'required|numeric',
                                'set_unit_target'               => 'required|numeric',
                                'set_lead_target'               => 'required|numeric',
                                'set_affiliator_target'         => 'required|numeric',
                                'set_verified_calls_target'     => 'required|numeric',
                                'set_client_meetings_target'    => 'required|numeric',
                                'set_dealer_meetings_target'    => 'required|numeric',
                                'set_freelancer_meetings_target'=> 'required|numeric',
                                'set_site_visit_target'         => 'required|numeric',
                            ],
                            [
                                'set_sales_target.required'               => 'Please Enter Sale Target',
                                'set_unit_target.required'                => 'Please Enter Unit Target',
                                'set_lead_target.required'                => 'Please Enter Lead Target',
                                'set_affiliator_target.required'          => 'Please Enter Affiliator Target',
                                'set_verified_calls_target.required'      => 'Please Enter Calls Target',
                                'set_client_meetings_target.required'     => 'Please Enter Client Target',
                                'set_dealer_meetings_target.required'     => 'Please Enter Dealer Target',
                                'set_freelancer_meetings_target.required' => 'Please Enter Freelancer Target',
                                'set_site_visit_target.required'          => 'Please Enter Site Visit Target',
                            ]);

                            if(is_numeric($request->set_sales_target) &&  is_numeric($request->set_unit_target) &&  is_numeric($request->set_lead_target) &&  is_numeric($request->set_affiliator_target) &&  is_numeric($request->set_verified_calls_target) &&  is_numeric($request->set_client_meetings_target) &&  is_numeric($request->set_dealer_meetings_target) &&  is_numeric($request->set_freelancer_meetings_target) &&  is_numeric($request->set_site_visit_target) )
                            {
                                Target::insert(
                                [
                                    'user_id'   => $user->id,

                                    'set_sales_target'              => $request->set_sales_target,
                                    'set_unit_target'               => $request->set_unit_target,
                                    'set_lead_target'               => $request->set_lead_target,
                                    'set_affiliator_target'         => $request->set_affiliator_target,
                                    'set_verified_calls_target'     => $request->set_verified_calls_target,
                                    'set_client_meetings_target'    => $request->set_client_meetings_target,
                                    'set_dealer_meetings_target'    => $request->set_dealer_meetings_target,
                                    'set_freelancer_meetings_target'=> $request->set_freelancer_meetings_target,
                                    'set_site_visit_target'         => $request->set_site_visit_target,

                                    'perday_sales_target'               =>  round($request->set_sales_target / $days_btwn_dates, 2) ,
                                    'perday_unit_target'                =>  round($request->set_unit_target / $days_btwn_dates, 2) ,
                                    'perday_lead_target'                =>  round($request->set_lead_target / $days_btwn_dates, 2) ,
                                    'perday_affiliator_target'          =>  round($request->set_affiliator_target / $days_btwn_dates, 2) ,
                                    'perday_verified_calls_target'      =>  round($request->set_verified_calls_target / $days_btwn_dates, 2) ,
                                    'perday_client_meetings_target'     =>  round($request->set_client_meetings_target / $days_btwn_dates, 2) ,
                                    'perday_dealer_meetings_target'     =>  round($request->set_dealer_meetings_target / $days_btwn_dates, 2) ,
                                    'perday_freelancer_meetings_target' =>  round($request->set_freelancer_meetings_target / $days_btwn_dates, 2) ,
                                    'perday_site_visit_target'          =>  round($request->set_site_visit_target / $days_btwn_dates, 2) ,

                                    'start_date' => $startDate,
                                    'expiry_date'=> $endDate,
                                    'created_at' => Carbon::now(),

                                ]);
                            }
                            else
                            {
                                $users = User::orderBy('id', 'asc')->get();
                                $targets = Target::whereMonth('created_at', Carbon::now()->month)->orderBy('id', 'desc')->paginate(50);
                                return view('users.targets', compact('targets' , 'users'))->with('error_message','Please Enter Numaric Values.');
                            }
                        }
                        else
                        {
                            Target::where('id' , $target->id)->update(
                            [
                                'set_sales_target'              => $request->set_sales_target,
                                'set_unit_target'               => $request->set_unit_target,
                                'set_lead_target'               => $request->set_lead_target,
                                'set_affiliator_target'         => $request->set_affiliator_target,
                                'set_verified_calls_target'     => $request->set_verified_calls_target,
                                'set_client_meetings_target'    => $request->set_client_meetings_target,
                                'set_dealer_meetings_target'    => $request->set_dealer_meetings_target,
                                'set_freelancer_meetings_target'=> $request->set_freelancer_meetings_target,
                                'set_site_visit_target'         => $request->set_site_visit_target,

                                'perday_sales_target'               =>  round($request->set_sales_target / $days_btwn_dates, 2) ,
                                'perday_unit_target'                =>  round($request->set_unit_target / $days_btwn_dates, 2) ,
                                'perday_lead_target'                =>  round($request->set_lead_target / $days_btwn_dates, 2) ,
                                'perday_affiliator_target'          =>  round($request->set_affiliator_target / $days_btwn_dates, 2) ,
                                'perday_verified_calls_target'      =>  round($request->set_verified_calls_target / $days_btwn_dates, 2) ,
                                'perday_client_meetings_target'     =>  round($request->set_client_meetings_target / $days_btwn_dates, 2) ,
                                'perday_dealer_meetings_target'     =>  round($request->set_dealer_meetings_target / $days_btwn_dates, 2) ,
                                'perday_freelancer_meetings_target' =>  round($request->set_freelancer_meetings_target / $days_btwn_dates, 2) ,
                                'perday_site_visit_target'          =>  round($request->set_site_visit_target / $days_btwn_dates, 2) ,

                                'start_date' => $startDate,
                                'expiry_date'=> $endDate,
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                    }
                }

                if($request->user[0] != 0)
                {
                    $user_ids = $request->input('user');
                    $users = User::whereIn('id' , $user_ids)->orderBy('id', 'asc')->get();

                    foreach($users as $user)
                    {
                        $target = Target::where('user_id' , $user->id)->whereBetween('start_date', [$startOfMonth, $endOfMonth])->first();

                        if(!($target) || $target =='')
                        {
                            $request->validate([
                                'set_sales_target'              => 'required|numeric',
                                'set_unit_target'               => 'required|numeric',
                                'set_lead_target'               => 'required|numeric',
                                'set_affiliator_target'         => 'required|numeric',
                                'set_verified_calls_target'     => 'required|numeric',
                                'set_client_meetings_target'    => 'required|numeric',
                                'set_dealer_meetings_target'    => 'required|numeric',
                                'set_freelancer_meetings_target'=> 'required|numeric',
                                'set_site_visit_target'         => 'required|numeric',
                            ],
                            [
                                'set_sales_target.required'               => 'Please Enter Sale Target',
                                'set_unit_target.required'                => 'Please Enter Unit Target',
                                'set_lead_target.required'                => 'Please Enter Lead Target',
                                'set_affiliator_target.required'          => 'Please Enter Affiliator Target',
                                'set_verified_calls_target.required'      => 'Please Enter Calls Target',
                                'set_client_meetings_target.required'     => 'Please Enter Client Target',
                                'set_dealer_meetings_target.required'     => 'Please Enter Dealer Target',
                                'set_freelancer_meetings_target.required' => 'Please Enter Freelancer Target',
                                'set_site_visit_target.required'          => 'Please Enter Site Visit Target',
                            ]);

                            if(is_numeric($request->set_sales_target) &&  is_numeric($request->set_unit_target) &&  is_numeric($request->set_lead_target) &&  is_numeric($request->set_affiliator_target) &&  is_numeric($request->set_verified_calls_target) &&  is_numeric($request->set_client_meetings_target) &&  is_numeric($request->set_dealer_meetings_target) &&  is_numeric($request->set_freelancer_meetings_target) &&  is_numeric($request->set_site_visit_target) )
                            {
                                Target::insert(
                                    [

                                    'user_id'   => $user->id,

                                    'set_sales_target'              => $request->set_sales_target,
                                    'set_unit_target'               => $request->set_unit_target,
                                    'set_lead_target'               => $request->set_lead_target,
                                    'set_affiliator_target'         => $request->set_affiliator_target,
                                    'set_verified_calls_target'     => $request->set_verified_calls_target,
                                    'set_client_meetings_target'    => $request->set_client_meetings_target,
                                    'set_dealer_meetings_target'    => $request->set_dealer_meetings_target,
                                    'set_freelancer_meetings_target'=> $request->set_freelancer_meetings_target,
                                    'set_site_visit_target'         => $request->set_site_visit_target,

                                    'perday_sales_target'               =>  round($request->set_sales_target / $days_btwn_dates, 2) ,
                                    'perday_unit_target'                =>  round($request->set_unit_target / $days_btwn_dates, 2) ,
                                    'perday_lead_target'                =>  round($request->set_lead_target / $days_btwn_dates, 2) ,
                                    'perday_affiliator_target'          =>  round($request->set_affiliator_target / $days_btwn_dates, 2) ,
                                    'perday_verified_calls_target'      =>  round($request->set_verified_calls_target / $days_btwn_dates, 2) ,
                                    'perday_client_meetings_target'     =>  round($request->set_client_meetings_target / $days_btwn_dates, 2) ,
                                    'perday_dealer_meetings_target'     =>  round($request->set_dealer_meetings_target / $days_btwn_dates, 2) ,
                                    'perday_freelancer_meetings_target' =>  round($request->set_freelancer_meetings_target / $days_btwn_dates, 2) ,
                                    'perday_site_visit_target'          =>  round($request->set_site_visit_target / $days_btwn_dates, 2) ,

                                    'start_date' => $startDate,
                                    'expiry_date'=> $endDate,
                                    'created_at' => Carbon::now(),

                                ]);
                            }
                            else
                            {
                                $users = User::orderBy('id', 'asc')->get();
                                $targets = Target::whereMonth('created_at', Carbon::now()->month)->orderBy('id', 'desc')->paginate(50);
                                return view('users.targets', compact('targets' , 'users'))->with('error_message','Please Enter Numaric Values Only.');
                            }
                        }
                        else
                        {
                            Target::where('id' , $target->id)->update(
                            [
                                'set_sales_target'              => $request->set_sales_target,
                                'set_unit_target'               => $request->set_unit_target,
                                'set_lead_target'               => $request->set_lead_target,
                                'set_affiliator_target'         => $request->set_affiliator_target,
                                'set_verified_calls_target'     => $request->set_verified_calls_target,
                                'set_client_meetings_target'    => $request->set_client_meetings_target,
                                'set_dealer_meetings_target'    => $request->set_dealer_meetings_target,
                                'set_freelancer_meetings_target'=> $request->set_freelancer_meetings_target,
                                'set_site_visit_target'         => $request->set_site_visit_target,

                                'perday_sales_target'               =>  round($request->set_sales_target / $days_btwn_dates, 2) ,
                                'perday_unit_target'                =>  round($request->set_unit_target / $days_btwn_dates, 2) ,
                                'perday_lead_target'                =>  round($request->set_lead_target / $days_btwn_dates, 2) ,
                                'perday_affiliator_target'          =>  round($request->set_affiliator_target / $days_btwn_dates, 2) ,
                                'perday_verified_calls_target'      =>  round($request->set_verified_calls_target / $days_btwn_dates, 2) ,
                                'perday_client_meetings_target'     =>  round($request->set_client_meetings_target / $days_btwn_dates, 2) ,
                                'perday_dealer_meetings_target'     =>  round($request->set_dealer_meetings_target / $days_btwn_dates, 2) ,
                                'perday_freelancer_meetings_target' =>  round($request->set_freelancer_meetings_target / $days_btwn_dates, 2) ,
                                'perday_site_visit_target'          =>  round($request->set_site_visit_target / $days_btwn_dates, 2) ,

                                'start_date' => $startDate,
                                'expiry_date'=> $endDate,
                                'updated_at' => Carbon::now(),
                            ]);

                        }
                    }
                }

                // ====== Users With Helper======
                    $data = array(
                        'id' => Auth::user()->id,
                        'role' => Auth::user()->role,
                    );
                    $responce = Helper::users($data);
                    $users = $responce['users'];
                // ====== Users With Helper======

                $targets = Target::whereIn('user_id' , $users->pluck('id'))->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year)->orderBy('id', 'desc')->paginate(50);
                return view('users.targets', compact('targets' , 'users'))->with('message','Targets Set Successfully.');
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function all_targets()
        {
            if(Auth::user()->can('target.view'))
            {
                // ====== Users With Helper======
                    $data = array(
                        'id' => Auth::user()->id,
                        'role' => Auth::user()->role,
                    );
                    $responce = Helper::users($data);
                    $users = $responce['users'];
                // ====== Users With Helper======

                $targets = Target::whereIn('user_id' , $users->pluck('id'))->orderBy('start_date', 'desc')->paginate(50);
                return view('users.all_targets', compact('targets' , 'users'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function destory_target(Target $target)
        {
            if(Auth::user()->can('target.delete'))
            {
                $target->delete();
                return back()->withStatus(__('Target Deleted Successfully.'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function edit_target($id)
        {
            if(Auth::user()->can('target.edit'))
            {
                $target = Target::findOrFail($id);
                return view('users.edit_target', compact('target'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function update_target(Request $request , Target $target )
        {
            if(Auth::user()->can('target.edit'))
            {
                $request->validate([
                    'set_sales_target'              => 'required|numeric',
                    'set_unit_target'               => 'required|numeric',
                    'set_lead_target'               => 'required|numeric',
                    'set_affiliator_target'         => 'required|numeric',
                    'set_verified_calls_target'     => 'required|numeric',
                    'set_client_meetings_target'    => 'required|numeric',
                    'set_dealer_meetings_target'    => 'required|numeric',
                    'set_freelancer_meetings_target'=> 'required|numeric',
                    'set_site_visit_target'         => 'required|numeric',
                ],
                [
                    'set_sales_target.required'               => 'Please Enter Sale Target',
                    'set_unit_target.required'                => 'Please Enter Unit Target',
                    'set_lead_target.required'                => 'Please Enter Lead Target',
                    'set_affiliator_target.required'          => 'Please Enter Affiliator Target',
                    'set_verified_calls_target.required'      => 'Please Enter Calls Target',
                    'set_client_meetings_target.required'     => 'Please Enter Client Target',
                    'set_dealer_meetings_target.required'     => 'Please Enter Dealer Target',
                    'set_freelancer_meetings_target.required' => 'Please Enter Freelancer Target',
                    'set_site_visit_target.required'          => 'Please Enter Site Visit Target',
                ]);

                $startDate = Carbon::create(date("Y-m-d", strtotime($request->startDate)). ' 00:00:00');
                $endDate   = Carbon::create(date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59');
                $period = CarbonPeriod::create($startDate, $endDate);

                $days_btwn_dates = 0;

                foreach ($period as $date)
                {
                    if ($date->dayOfWeek !== Carbon::SUNDAY)
                    {
                        $days_btwn_dates++;
                    }
                }

                if ($days_btwn_dates < 1)
                {
                return redirect()->back()->withInput($request->input())
                            ->with('error' , 'Selected Date is not Correct. Also Check Today is not Sunday');
                }

                if(is_numeric($request->set_sales_target) &&  is_numeric($request->set_unit_target) &&  is_numeric($request->set_lead_target) &&  is_numeric($request->set_affiliator_target) &&  is_numeric($request->set_verified_calls_target) &&  is_numeric($request->set_client_meetings_target) &&  is_numeric($request->set_dealer_meetings_target) &&  is_numeric($request->set_freelancer_meetings_target) &&  is_numeric($request->set_site_visit_target) )
                {

                    Target::where('id' , $target->id)->update(
                    [
                        'set_sales_target'              => $request->set_sales_target,
                        'set_unit_target'               => $request->set_unit_target,
                        'set_lead_target'               => $request->set_lead_target,
                        'set_affiliator_target'         => $request->set_affiliator_target,
                        'set_verified_calls_target'     => $request->set_verified_calls_target,
                        'set_client_meetings_target'    => $request->set_client_meetings_target,
                        'set_dealer_meetings_target'    => $request->set_dealer_meetings_target,
                        'set_freelancer_meetings_target'=> $request->set_freelancer_meetings_target,
                        'set_site_visit_target'         => $request->set_site_visit_target,

                        'perday_sales_target'               =>  round($request->set_sales_target / $days_btwn_dates, 2) ,
                        'perday_unit_target'                =>  round($request->set_unit_target / $days_btwn_dates, 2) ,
                        'perday_lead_target'                =>  round($request->set_lead_target / $days_btwn_dates, 2) ,
                        'perday_affiliator_target'          =>  round($request->set_affiliator_target / $days_btwn_dates, 2) ,
                        'perday_verified_calls_target'      =>  round($request->set_verified_calls_target / $days_btwn_dates, 2) ,
                        'perday_client_meetings_target'     =>  round($request->set_client_meetings_target / $days_btwn_dates, 2) ,
                        'perday_dealer_meetings_target'     =>  round($request->set_dealer_meetings_target / $days_btwn_dates, 2) ,
                        'perday_freelancer_meetings_target' =>  round($request->set_freelancer_meetings_target / $days_btwn_dates, 2) ,
                        'perday_site_visit_target'          =>  round($request->set_site_visit_target / $days_btwn_dates, 2) ,

                        'start_date' => $startDate,
                        'expiry_date'=> $endDate,
                        'updated_at' => Carbon::now(),
                    ]);

                }

                return redirect()->to('all-targets')->withStatus(__('Target Updated Successfully.'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

        public function target_search(Request $request)
        {
            if(Auth::user()->can('target.view'))
            {
                $condition = array();

                if ($request->input('user_id') > 0) {
                    $condition[] = array('user_id', '=', $request->input('user_id'));
                }

                if ($request->input('target_id') != '') {
                    $condition[] = array('id', '=', $request->input('target_id'));
                }

                if ($request->input('startDate') != '' && $request->input('endDate') != '')
                {
                    $condition[] = array('start_date', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
                    $condition[] = array('expiry_date', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:59');
                }

                if ($request->input('startDate') != '' && $request->input('endDate') == '')
                {
                    $condition[] = array('start_date', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
                }

                if ($request->input('startDate') == '' && $request->input('endDate') != '')
                {
                    $condition[] = array('expiry_date', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:59');
                }

                // ====== Users With Helper======
                    $data = array(
                        'id' => Auth::user()->id,
                        'role' => Auth::user()->role,
                    );
                    $responce = Helper::users($data);
                    $users = $responce['users'];
                // ====== Users With Helper======

                $targets = Target::whereIn('user_id' , $users->pluck('id'))->where($condition)->orderBy('created_at', 'desc')->paginate(50);
                return view('users.all_targets', compact('targets' , 'users'));
            }
            else{
                return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
            }
        }

    // ==========Targets Functions ====================


    // ==========Auth User Graph=============
    public function PerformanceGraph()
    {
        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $graph_users = $responce['users'];
        // ====== Users With Helper======

        $id = Auth::user()->id;

        // Today's Record
        $record = Target::where('user_id', $id)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year)->get();
        $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereDate('created_at', Carbon::today())
                                        ->count();



        $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $id)
                                        ->where('affiliatortask.type', '=', 'Meetings')
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.created_at', Carbon::today())
                                        ->where('affiliators.type', '=', 'Dealer')
                                        // ->get(['affiliatortask.*']);
                                        ->count();


        $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $id)
                                        ->where('affiliatortask.type', '=', 'Meetings')
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.created_at', Carbon::today())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count();

        $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();
        $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereDate('created_at', Carbon::today())->count();
        $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                    ->where('product.status', '=', 'Sold')
                                    ->whereDate('product.sold_at', Carbon::today())
                                    ->where('leads.user_id', '=', $id)
                                    ->sum('price');


        $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                    ->where('product.status', '=', 'Sold')
                                    ->whereDate('product.sold_at', Carbon::today())
                                    ->where('leads.user_id', '=', $id)
                                    ->count();

        // echo '<pre>';print_r($achive_unit_target); exit;


        $set_sales_target               = 0;
        $set_unit_target                = 0;
        $set_verified_calls_target      = 0;
        $set_client_meetings_target     = 0;
        $set_dealer_meetings_target     = 0;
        $set_freelancer_meetings_target = 0;
        $set_site_visit_target          = 0;


        // if( count($record) > 0)
        // {
        //     $month = date('m',strtotime(Carbon::now()));
        //     $days_in_month=Carbon::now()->month($month)->daysInMonth;
        //     // echo '<pre>'; print_r(count($record));exit;
        //     $set_sales_target               = $record->sum('set_sales_target') / $days_in_month ;
        //     $set_unit_target                = $record->sum('set_unit_target') / $days_in_month ;
        //     $set_verified_calls_target      = $record->sum('set_verified_calls_target') / $days_in_month;
        //     $set_client_meetings_target     = $record->sum('set_client_meetings_target') / $days_in_month ;
        //     $set_dealer_meetings_target     = $record->sum('set_dealer_meetings_target') / $days_in_month ;
        //     $set_freelancer_meetings_target = $record->sum('set_freelancer_meetings_target') / $days_in_month ;
        //     $set_site_visit_target          = $record->sum('set_site_visit_target') / $days_in_month ;
        // }


        if( count($record) > 0)
        {
            $set_sales_target               = $record->sum('perday_sales_target');
            $set_unit_target                = $record->sum('perday_unit_target');
            $set_verified_calls_target      = $record->sum('perday_verified_calls_target');
            $set_dealer_meetings_target     = $record->sum('perday_dealer_meetings_target');
            $set_freelancer_meetings_target = $record->sum('perday_freelancer_meetings_target');
            $set_client_meetings_target     = $record->sum('perday_client_meetings_target');
            $set_site_visit_target          = $record->sum('perday_site_visit_target');
        }

        $user_target = array();

        $user_target =[
            'set_sales_target'                  => $set_sales_target,
            'set_unit_target'                   => $set_unit_target,
            'set_verified_calls_target'         => $set_verified_calls_target,
            'set_client_meetings_target'        => $set_client_meetings_target,
            'set_dealer_meetings_target'        => $set_dealer_meetings_target,
            'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
            'set_site_visit_target'             => $set_site_visit_target,

            'achive_sales_target'               => $achive_sales_target,
            'achive_unit_target'                => $achive_unit_target,
            'achive_verified_calls_target'      => $achive_verified_calls_target,
            'achive_client_meetings_target'     => $achive_client_meetings_target,
            'achive_dealer_meetings_target'     => $achive_dealer_meetings_target,
            'achive_freelancer_meetings_target' => $achive_freelancer_meetings_target,
            'achive_site_visit_target'          => $achive_site_visit_target,
        ];

        // echo "<pre>"; print_r($set_sales_target);exit;
        return view("users.user_graph", compact('graph_users' , 'user_target' ));
    }

    // =========All Graphs Only for Manager=============

    public function UserSortingGraph(Request $request)
    {
        // echo '<pre>'; print_r($request->all());exit;

        $id          = $request->input('users');
        $time_period = $request->input('time_period');

        $set_sales_target               = 0;
        $set_unit_target                = 0;
        $set_verified_calls_target      = 0;
        $set_client_meetings_target     = 0;
        $set_dealer_meetings_target     = 0;
        $set_freelancer_meetings_target = 0;
        $set_site_visit_target          = 0;


        if($request->time_period == 'custom_date' && $request->startDate != '' && $request->endDate != '')
        {
            // $startDate= date("Y-m-d", strtotime($request->startDate)). ' 00:00:00';
            // $endDate =  date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59';

            $startDate = Carbon::create(date("Y-m-d", strtotime($request->startDate)). ' 00:00:00');
            $endDate   = Carbon::create(date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59');

            $start = strtotime($startDate);
            $end = strtotime($endDate);

            if($start > $end) // if user enter start_Date into end_Date and end_Date into Start_Date
            {
                return redirect('/')->withErrors(__('Wrong Selection Of Date. Start Date Will Not Be Grater Than End Date.!'));
            }

            if($end > $start)
            {
                // $datediff = $end - $start;
                // $days_count= round($datediff / (60 * 60 * 24));

                $period = CarbonPeriod::create($startDate, $endDate);
                $days_btwn_dates = 0;
                foreach ($period as $date)
                {
                    if ($date->dayOfWeek !== Carbon::SUNDAY)
                    {
                        $days_btwn_dates++;
                    }
                }
            }


            // $start_month = date('m Y',strtotime($startDate));
            // $end_month = date('m Y',strtotime($endDate));
            // // $days_in_month=Carbon::now()->month($start_month)->daysInMonth;

            $startOfMonth= $startDate->startOfMonth();
            $endOfMonth  = $endDate->endOfMonth();


            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
            $total_days = 0;
            foreach ($period as $date)
            {
                if ($date->dayOfWeek !== Carbon::SUNDAY)
                {
                    $total_days++;
                }
            }

            // echo '<pre>'; print_r($days_btwn_dates);
            // echo '<pre>'; print_r($total_days);
            // exit;

            // use again Because above startOfMonth and endOfMonth chnage the dates
            $startDate = date("Y-m-d", strtotime($request->startDate)). ' 00:00:00';
            $endDate   = date("Y-m-d", strtotime($request->endDate)) . ' 23:59:59';


            $record = Target::where('user_id', $id)->whereBetween('start_date', [$startOfMonth , $endOfMonth])->get();

            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                            ->where('type', 'Calls')
                                            ->where(function ($query)
                                            {
                                                $query->where('subtype', 'Contacted Client')
                                                    ->orWhere('subtype', 'Whatsapp Call');
                                            })
                                            ->whereDate('created_at', '>=', $startDate)
                                            ->whereDate('created_at', '<=', $endDate)
                                            ->count();

            $achive_client_meetings_target  = Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
            $achive_dealer_meetings_target  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', '>=', $startDate)
                                                ->whereDate('affiliatortask.created_at', '<=', $endDate)
                                                ->where('affiliators.type', '=', 'Dealer')
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', '>=', $startDate)
                                                ->whereDate('affiliatortask.created_at', '<=', $endDate)
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();

            $achive_site_visit_target       = Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();
            $achive_sales_target            =Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereDate('product.sold_at', '>=', $startDate)->whereDate('product.sold_at', '<=', $endDate)->where('leads.user_id', '=', $id)->sum('price');
            $achive_unit_target             =Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereDate('product.sold_at', '>=', $startDate)->whereDate('product.sold_at', '<=', $endDate)->where('leads.user_id', '=', $id)->count();

        }

        if ($time_period == 'today')
        {
            // current Month
            $record = Target::where('user_id', $id)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year)->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereDate('created_at', Carbon::today())->count();
            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                            ->where('type', 'Calls')
                                            ->where(function ($query)
                                            {
                                                $query->where('subtype', 'Contacted Client')
                                                    ->orWhere('subtype', 'Whatsapp Call');
                                            })
                                            ->whereDate('created_at', Carbon::today())
                                            ->count();


            $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereDate('created_at', Carbon::today())->count();

            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', Carbon::today())
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.created_at', Carbon::today())
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();

            $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereDate('created_at', Carbon::today())->count();

            $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereDate('product.sold_at', Carbon::today())
                                                ->where('leads.user_id', '=', $id)
                                                ->sum('price');

            $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereDate('product.sold_at', Carbon::today())
                                                ->where('leads.user_id', '=', $id)
                                                ->count();

            // echo '<pre>';print_r($achive_unit_target); exit;
        }

        if ($time_period == 'this_month')
        {
            // current Month
            $record = Target::where('user_id', $id)->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->format('Y'))->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();

            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))
                                        ->count();


            $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereMonth('affiliatortask.created_at', Carbon::now()->month)
                                                ->whereYear('affiliatortask.created_at', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereMonth('affiliatortask.created_at', Carbon::now()->month)
                                                ->whereYear('affiliatortask.created_at', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->format('Y'))->count();
            $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereMonth('product.sold_at', Carbon::now()->month)
                                                ->whereYear('product.sold_at', Carbon::now()->format('Y'))
                                                ->where('leads.user_id', '=', $id)
                                                ->sum('price');

            $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')
                                                ->where('product.status', '=', 'Sold')
                                                ->whereMonth('product.sold_at', Carbon::now()->month)
                                                ->whereYear('product.sold_at', Carbon::now()->format('Y'))
                                                ->where('leads.user_id', '=', $id)
                                                ->count();
        }

        if($time_period == 'last_month')
        {
            // Previous Month
            // $record = Target::where('user_id', $id)->whereMonth('start_date', Carbon::now()->subMonth(1))->whereYear('start_date', Carbon::now()->format('Y'))->get();
            $record = Target::where('user_id', $id)->whereBetween('start_date', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();

            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                        ->count();

            $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            // last_month Sale and Units
            $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereBetween('product.sold_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('leads.user_id', '=', $id)->sum('price');
            $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereBetween('product.sold_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('leads.user_id', '=', $id)->count();

        }

        if($time_period == 'three_month')
        {
            // Previous 3 Months
            $record = Target::where('user_id', $id)->whereBetween('start_date', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
            // $achive_verified_calls_target = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                        ->count();

            $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereBetween('product.sold_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('leads.user_id', '=', $id)->sum('price');
            $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereBetween('product.sold_at', [Carbon::now()->subMonth(3)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('leads.user_id', '=', $id)->count();
        }

        if($time_period == 'six_month')
        {
            // Previous 6 Months
            $record = Target::where('user_id', $id)->whereBetween('start_date', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
            // $achive_verified_calls_target   = Task::where('added_by', $id)->where('type', 'Calls')->where('subtype', 'Contacted Client')->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();

            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                                ->where('type', 'Calls')
                                                ->where(function ($query)
                                                {
                                                    $query->where('subtype', 'Contacted Client')
                                                        ->orWhere('subtype', 'Whatsapp Call');
                                                })
                                                ->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->count();

            $achive_client_meetings_target  = Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            $achive_dealer_meetings_target  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Dealer')
                                                // ->get(['affiliatortask.*']);
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereBetween('affiliatortask.created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();
            $achive_site_visit_target       = Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereBetween('created_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
            // six_month Sale And Unit
            $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereBetween('product.sold_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('leads.user_id', '=', $id)->sum('price');
            $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereBetween('product.sold_at', [Carbon::now()->subMonth(6)->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->where('leads.user_id', '=', $id)->count();
        }

        if($time_period == 'this_year')
        {
            // This Year
            $record = Target::where('user_id', $id)->whereYear('start_date', Carbon::now()->format('Y'))->get();
            $achive_verified_calls_target=Task::where('added_by', $id) // First where clause
                                        ->where('type', 'Calls')
                                        ->where(function ($query)
                                        {
                                            $query->where('subtype', 'Contacted Client')
                                                ->orWhere('subtype', 'Whatsapp Call');
                                        })
                                        ->whereYear('created_at', Carbon::now()->format('Y'))
                                        ->count();

            $achive_client_meetings_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->whereYear('created_at', Carbon::now()->format('Y'))->count();
            $achive_dealer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereYear('affiliatortask.created_at', '=', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Dealer')
                                                ->count();

            $achive_freelancer_meetings_target=AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $id)
                                                ->where('affiliatortask.type', '=', 'Meetings')
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereYear('affiliatortask.created_at', '=', Carbon::now()->format('Y'))
                                                ->where('affiliators.type', '=', 'Freelancer')
                                                ->count();

            $achive_site_visit_target= Task::where('added_by', $id)->where('type', 'Meetings')->where('subtype', 'Meeting (Done)')->where('location', 'Site Office')->whereYear('created_at', Carbon::now()->format('Y'))->count();
            $achive_sales_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereYear('product.sold_at', '=', Carbon::now()->format('Y'))->where('leads.user_id', '=', $id)->sum('price');
            $achive_unit_target=Product::join('leads', 'leads.item_id', '=', 'product.unitid')->where('product.status', '=', 'Sold')->whereYear('product.sold_at', '=', Carbon::now()->format('Y'))->where('leads.user_id', '=', $id)->count();
        }

        // echo '<pre>'; print_r(count($record));exit;

        if( count($record) > 0)
        {
            if($request->time_period == 'custom_date' && ($request->startDate != '' || $request->endDate != ''))
            {
                // echo '<pre>'; print_r($days_btwn_dates);exit;
                $set_sales_target                = ( $record->sum('set_sales_target')                / $total_days) * $days_btwn_dates;
                $set_unit_target                 = ( $record->sum('set_unit_target')                 / $total_days) * $days_btwn_dates;
                $set_verified_calls_target       = ( $record->sum('set_verified_calls_target')       / $total_days) * $days_btwn_dates;
                $set_client_meetings_target      = ( $record->sum('set_client_meetings_target')      / $total_days) * $days_btwn_dates;
                $set_dealer_meetings_target      = ( $record->sum('set_dealer_meetings_target')      / $total_days) * $days_btwn_dates;
                $set_freelancer_meetings_target  = ( $record->sum('set_freelancer_meetings_target')  / $total_days) * $days_btwn_dates;
                $set_site_visit_target           = ( $record->sum('set_site_visit_target')           / $total_days) * $days_btwn_dates;
            }
            elseif($request->time_period == 'today')
            {
                $set_sales_target                   = $record->sum('perday_sales_target');
                $set_unit_target                    = $record->sum('perday_unit_target');
                $set_verified_calls_target          = $record->sum('perday_verified_calls_target');
                $set_client_meetings_target         = $record->sum('perday_client_meetings_target');
                $set_dealer_meetings_target         = $record->sum('perday_dealer_meetings_target');
                $set_freelancer_meetings_target     = $record->sum('perday_freelancer_meetings_target');
                $set_site_visit_target              = $record->sum('perday_site_visit_target') ;
            }
            else
            {
                $set_sales_target               = $record->sum('set_sales_target');
                $set_unit_target                = $record->sum('set_unit_target');
                $set_verified_calls_target      = $record->sum('set_verified_calls_target');
                $set_client_meetings_target     = $record->sum('set_client_meetings_target');
                $set_dealer_meetings_target     = $record->sum('set_dealer_meetings_target');
                $set_freelancer_meetings_target = $record->sum('set_freelancer_meetings_target');
                $set_site_visit_target          = $record->sum('set_site_visit_target');
            }
        }

        $user_target = array();

        $user_target =[
            'set_sales_target'                  => $set_sales_target,
            'set_unit_target'                   => $set_unit_target,
            'set_verified_calls_target'         => $set_verified_calls_target,
            'set_client_meetings_target'        => $set_client_meetings_target,
            'set_dealer_meetings_target'        => $set_dealer_meetings_target,
            'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
            'set_freelancer_meetings_target'    => $set_freelancer_meetings_target,
            'set_site_visit_target'             => $set_site_visit_target,

            'achive_sales_target'               => $achive_sales_target,
            'achive_unit_target'                => $achive_unit_target,
            'achive_verified_calls_target'      => $achive_verified_calls_target,
            'achive_client_meetings_target'     => $achive_client_meetings_target,
            'achive_dealer_meetings_target'     => $achive_dealer_meetings_target,
            'achive_freelancer_meetings_target' => $achive_freelancer_meetings_target,
            'achive_site_visit_target'          => $achive_site_visit_target,
        ];

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $graph_users = $responce['users'];
        // ====== Users With Helper======

        return view("users.user_graph", compact('graph_users' , 'user_target' ));
    }

    public function userpassword(Request $request, User $user){
        $validated = $request->validate([
            'password' => ['required', 'confirmed'],
        ]);
        $user->update(
                $request->merge(['password' => Hash::make($request->get('password'))])
                        ->except([$request->get('password') ? '' : 'password']
        ));
        return back()->withStatus(__('Password successfully updated.'));
    }

    public function viewTree()
    {
        if(Auth::user()->can('staff.tree.view'))
        {
            $coming_user = Auth::user();
            // $childUsers = User::where('parent', 0)->get();
            $childUsers = User::where('parent',$coming_user->id)->get();
            $parentUsers = $this->reverseParentTree($coming_user);
            $offices = Offices::orderBy('id', 'asc')->get();

            // $user = Auth::user();
            // if ($user){
            //     $reverseParentTree = $this->reverseParentTree($user);
            //     foreach ($reverseParentTree as $parent) {
            //         echo $parent->name . '<br>';
            //     }
            // }
            // exit;
            // echo "<pre>"; print_r($parentUsers); exit;

            // ======= Users With Helper=======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' =>  Auth::user()->role,
                );
                $response = Helper::users($data);
                $users = $response['users']->filter(function ($user) {
                    return ($user['role'] == 5 || $user['role'] == 1 && $user['office_id'] == 1 );
                });
            // ======= Users With Helper=======
            return view("users.tree.index", compact('coming_user','childUsers' , 'parentUsers' , 'users' , 'offices' ));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function reverseParentTree($user)
    {
        $tree = [];
        $currentUser = $user;

        while ($currentUser->parentname)
        {
            $tree[] = $currentUser->parentname;
            $currentUser = $currentUser->parentname;
        }

        return collect(array_reverse($tree));
    }

    public function searchTree(Request $request)
    {
        $office_id= $request->id_office;
        $coming_user = User::where('id',$request->user_id)->first();
        $childUsers  = User::where('parent',$request->user_id)->get();
        $parentUsers = $this->reverseParentTree($coming_user);
        $offices = Offices::orderBy('id', 'asc')->get();

        // ======= Users With Helper=======
            $data = array(
                'id' => Auth::user()->id,
                'role' =>  Auth::user()->role,
            );
            $response = Helper::users($data);
            $users = $response['users']->filter(function ($user) use ($office_id) {
                return ($user['role'] == 5 || $user['role'] == 1) && $user['office_id'] == $office_id;
            });
        // ======= Users With Helper=======

        return view("users.tree.index", compact('coming_user','childUsers' , 'parentUsers' , 'users' , 'offices' ));
    }

    public function getOfficeMagers($office_id)
    {
        $html = '';
        if($office_id > 0){
            $users=User::where('office_id', $office_id)
                            ->where(function ($query)
                            {
                                $query->where('role', '=', 1)
                                        ->Orwhere('role', '=', 5);
                            })->orderBy('id', 'asc')
                            ->get();
        }else{
            $users=User::where('role', 1)->Orwhere('role', 5)->orderBy('id', 'asc')->get();
        }

        if(count($users) > 0){
            foreach ($users as $user)
            {
                $html .= '<option value="'.$user->id.'"> '.$user->name.'</option>';
            }
        }else
        {
                $html.= '<p>Sorry No Record Found...!</p>';
        }
        return response()->json(['html' => $html]);
    }

    public function getAllOfficeMagers($office_id)
    {
        $html = '';
        if($office_id > 0){
            $users=User::where('office_id', $office_id)
                            ->where(function ($query)
                            {
                                $query->where('role', '=', 1)
                                        ->Orwhere('role', '=', 5);
                            })->orderBy('id', 'asc')
                            ->get();
        }else{
            $users=User::where('role', 1)->Orwhere('role', 5)->orderBy('id', 'asc')->get();
        }

        if(count($users) > 0)
        {
            $html .= '<option value="0">All</option>';
            foreach ($users as $user)
            {
                $html .= '<option value="'.$user->id.'"> '.$user->name.'</option>';
            }
        }else
        {
                $html.= '<p>Sorry No Record Found...!</p>';
        }
        return response()->json(['html' => $html]);
    }

}
