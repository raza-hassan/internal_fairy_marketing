<?php

namespace App\Http\Controllers\outer;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Affiliator;
use App\Models\User;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Project;
use App\Models\AffiliatorTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class AffiliatorsController extends Controller {

    public function index() {
        $id = 0;
        $search_person = 'user';
        $affiliators = Affiliator::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users'));
    }

    public function All_Affiliator(Request $request, $id) {
        $search_person = 'manager';
        $affiliators = Affiliator::where('user_id', $id)->orderBy('id', 'desc')->paginate(30);
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users'));
    }

    public function dealors() {
        $search_person = 'user';
        $affiliators = Affiliator::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->where('type', 'Dealer')->paginate(30);
        $id = 0;
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users'));
    }

    public function freeliencer() {
        $search_person = 'user';
        $affiliators = Affiliator::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->where('type', 'Freelancer')->paginate(30);
        $id = 0;
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users'));
    }

    public function affilitor_leads(Affiliator $affiliator) {
        $search_person = 'user';
        $id = 0;
        $find_result = 0;

        if ($affiliator->status == 1) {
            $sources = LeadSource::where('subtype', 'user')->get();
            $users = User::where('parent', Auth::user()->id)->get();
            $projects = Project::orderBy('id', 'ASC')->get();
            $leads = Leads::where('afflilate_id', $affiliator->id)->orderBy('id', 'desc')->paginate(30);
            return view('leads.index', compact('find_result','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
        } else {
            return redirect('affiliators')->withErrors(__('Affiliator does not exist or approved'));
        }
    }

    public function create() {
        return view('affiliators.create');
    }


    public function store(Request $request) {

        $phone = $request->input('countryCode') . $request->input('phone');
        $record = Affiliator::where('phone', $phone)->pluck('phone')->first();
        if (empty($record)) {
            if ($request->type == 'Dealer') {
                $validated = $request->validate([
                    // 'countryCode' => 'required',
                    'phone' => 'required|unique:affiliators',
                    'name' => 'required',
                    'address' => 'required',
                    'offinspic' => 'required',
                    'offoutspic' => 'required',
                    'offboardpic' => 'required',
                ]);
            } else {
                $validated = $request->validate([
                    // 'countryCode' => 'required',
                    'phone' => 'required|unique:affiliators',
                    'name' => 'required'
                ]);
            }

            $cnicf = '';
            if ($request->file('cnicf')) {
                $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
                $cnicf = $request->file('cnicf')->storeAs('affiliators', $fileName, 'public');
            }
            $cnicb = '';
            if ($request->file('cnicb')) {
                $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
                $cnicb = $request->file('cnicb')->storeAs('affiliators', $fileName, 'public');
            }
            $offinspic = '';
            if ($request->file('offinspic')) {
                $fileName = time() . '_' . $request->file('offinspic')->getClientOriginalName();
                $offinspic = $request->file('offinspic')->storeAs('affiliators', $fileName, 'public');
            }
            $offoutspic = '';
            if ($request->file('offoutspic')) {
                $fileName = time() . '_' . $request->file('offoutspic')->getClientOriginalName();
                $offoutspic = $request->file('offoutspic')->storeAs('affiliators', $fileName, 'public');
            }
            $offboardpic = '';
            if ($request->file('offboardpic')) {
                $fileName = time() . '_' . $request->file('offboardpic')->getClientOriginalName();
                $offboardpic = $request->file('offboardpic')->storeAs('affiliators', $fileName, 'public');
            }
            $offvisitpic = '';
            if ($request->file('offvisitpic')) {
                $fileName = time() . '_' . $request->file('offvisitpic')->getClientOriginalName();
                $offvisitpic = $request->file('offvisitpic')->storeAs('affiliators', $fileName, 'public');
            }


            if ($request->telephone != '') {
                $telephone = $request->countryCode1 . $request->telephone;
            } else {
                $telephone = '';
            }

            if ($request->telephone1 != '') {
                $telephone1 = $request->countryCode2 . $request->telephone1;
            } else {
                $telephone1 = '';
            }



            $affiliator = Affiliator::firstOrCreate(
                            [
                        'name' => $request->name,
                        'email' => $request->email,
                        'business' => $request->business,
                        // 'phone' => $request->countryCode . $request->phone,
                        'phone' => $request->full_number,
                        'telephone' => $telephone,
                        'telephone1' => $telephone1,
                        'address' => $request->address,
                        'user_id' => $request->user_id,
                        'type' => $request->type,
                        'cnic' => $request->cnic,
                        'cnicf' => $cnicf,
                        'cnicb' => $cnicb,
                        'offinspic' => $offinspic,
                        'offoutspic' => $offoutspic,
                        'offboardpic' => $offboardpic,
                        'offvisitpic' => $offvisitpic,
                            ], [
                        // 'phone' => $request->countryCode . $request->phone
                        // 'phone' => $request->full_number,
                            ]
            );


        // =========Email Notify=========
            $data = array(
                'id' => $affiliator->id,
                'name' => $request->name,
                'email' => $request->email,
                // 'phone' => $request->countryCode . $request->phone,
                'phone' => $request->full_number,
                'status' => 'Under Progress',
                'note' => 'Under Progress',
                'user' => $affiliator->assigned->name,
            );

            $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
            $to_name = Auth::user()->name;

            Mail::send('affiliators.affiliator-mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Affiliator Create Notification');
                $message->from(config('mail.from.address'), Config('mail.from.name'));
            });
        // =========Email Notify=========




            return redirect('affiliators')->withStatus(__('Affiliator Created Successfully.'));
        } else {
            return back()->withInput()->withErrors(['Affiliator already exist']);
        }
    }


    public function show(Affiliator $affiliator) {
        //
    }

    public function edit(Affiliator $affiliator) {
        if ($affiliator->user_id != Auth::user()->id && Auth::user()->role != 1 && Auth::user()->role != 5) {
            return back()->withErrors(__('Not Allowed!'));
        }
        $managers = User::where('role', '=', 2)->get();
        return view('affiliators.edit', compact('affiliator', 'managers'));
    }

    public function update(Request $request, Affiliator $affiliator)
    {

        $cnicf = '';

        if ($request->file('cnicf')) {
            $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
            $cnicf = $request->file('cnicf')->storeAs('affiliators', $fileName, 'public');
        } else {
            $cnicf = $request->input('oldcnicf');
        }

        $cnicb = '';
        if ($request->file('cnicb')) {
            $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
            $cnicb = $request->file('cnicb')->storeAs('affiliators', $fileName, 'public');
        } else {
            $cnicb = $request->input('oldcnicb');
        }

        $offinspic = '';
        if ($request->file('offinspic')) {
            $fileName = time() . '_' . $request->file('offinspic')->getClientOriginalName();
            $offinspic = $request->file('offinspic')->storeAs('affiliators', $fileName, 'public');
        } else {
            $offinspic = $request->input('oldoffinspic');
        }
        $offoutspic = '';
        if ($request->file('offoutspic')) {
            $fileName = time() . '_' . $request->file('offoutspic')->getClientOriginalName();
            $offoutspic = $request->file('offoutspic')->storeAs('affiliators', $fileName, 'public');
        } else {
            $offoutspic = $request->input('oldoffoutspic');
        }
        $offboardpic = '';
        if ($request->file('offboardpic')) {
            $fileName = time() . '_' . $request->file('offboardpic')->getClientOriginalName();
            $offboardpic = $request->file('offboardpic')->storeAs('affiliators', $fileName, 'public');
        } else {
            $offboardpic = $request->input('oldoffboardpic');
        }
        $offvisitpic = '';
        if ($request->file('offvisitpic')) {
            $fileName = time() . '_' . $request->file('offvisitpic')->getClientOriginalName();
            $offvisitpic = $request->file('offboardpic')->storeAs('affiliators', $fileName, 'public');
        } else {
            $offvisitpic = $request->input('oldoffboardpic');
        }

        if ($request->telephone != '') {
            $telephone = $request->countryCode1 . $request->telephone;
        } else {
            $telephone = '';
        }

        if ($request->telephone1 != '') {
            $telephone1 = $request->countryCode2 . $request->telephone1;
        } else {
            $telephone1 = '';
        }


        if ($request->note != '') {
            $note = $request->note;
        } else {
            $note = '';
        }

        $affiliator->update($request->all());

        $affiliator->cnicf = $cnicf;
        $affiliator->cnicb = $cnicb;
        $affiliator->offinspic = $offinspic;
        $affiliator->offoutspic = $offoutspic;
        $affiliator->offboardpic = $offboardpic;
        $affiliator->offvisitpic = $offvisitpic;
        $affiliator->telephone = $telephone;
        $affiliator->telephone1 = $telephone1;
        $affiliator->note = $note;
        $affiliator->phone = $request->full_number;
        $affiliator->save();

        // ==========Email Notify==========
            $data = array(
                'id' => $affiliator->id,
                'name' => $request->name,
                'email' => $request->email,
                // 'phone' => $request->countryCode . $request->phone,

                'phone' => $request->full_number,

                'status' => $request->status,
                'note' => $request->note,
            );

            $to_email = [Auth::user()->email, 'shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
            $to_name = Auth::user()->name;

            Mail::send('affiliators.affiliator-mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Affiliator Status Notification');
                $message->from(config('mail.from.address'), Config('mail.from.name'));
            });
        // ==========Email Notify==========

        return back()->withStatus(__('Affiliator Updated Successfully.'));
    }


    public function todolist() {
        $tasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(30);

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }

        return view('affiliators.todos', compact('tasks', 'users'));
    }

    public function todos($todo) {
        // Overdue date
        $tdate = strtotime(date('Y-m-d'));
        $tdate = strtotime("+1 day", $tdate);

        $today = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->count();
        $tomorrow = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', date('Y-m-d', $tdate))->where('status', 0)->count();
        $overdue = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->count();
        // Week Tasks count
        $date = strtotime(date('Y-m-d'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        $week = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->count();

        $date = strtotime(date('Y-m-d'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");
        $alltasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->count();
        if ($todo == 'today') {
            $tasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'overdue') {
            $tasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'tomorrow') {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+1 day", $date);
            $tasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', date('Y-m-d', $date))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'week') {
            $date = strtotime(date('Y-m-d'));
            $date1 = strtotime("+1 day", $date);
            $date = strtotime("+7 day", $date);
            $tasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } else {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+8 day", $date);
            $future_timestamp = strtotime("+1 month");
            $tasks = AffiliatorTask::where('user_id', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        }

        $users = User::where('parent', Auth::user()->id)->get();
        return view('affiliators.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

    public function taskstore(Request $request) {

        $validated = $request->validate([
            'type' => 'required',
            'subtype' => 'required',
            'completion_date' => 'required'
        ]);
        $deadline = date("Y-m-d", strtotime($request->deadline));
        $completion_date = date("Y-m-d", strtotime($request->completion_date));
        AffiliatorTask::where('affliator_id', $request->input('affliator_id'))->where('status', 0)->update(['status' => 1]);
        $task = AffiliatorTask::create($request->merge(['deadline' => $deadline, 'completion_date' => $completion_date])->all());
        $filePath = '';
        $file_location = '';
        if ($request->file) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('task', $fileName, 'public');
            $task->file = $filePath;
        }

        $task->save();


        return redirect()->back()->withStatus(__('Task Created Successfully.'));
    }

    public function getafitasksubtype(Request $request) {
        $html = '';
        if ($request->task == 'Meetings') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                    <option>Do Nothing</option>
                    <option value="Video Meeting">Video Meeting</option>
                    <option value="Walk-In">Walk-In</option>
                    <option value="Meeting (Done)">Meeting (Done)</option>
                    <option value="Meeting (Arranged)">Meeting (Arranged)</option>
                    <option value="Meeting (Attempt)">Meeting (Attempt)</option>
                    <option value="Meeting (Pushed)">Meeting (Pushed)</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Calls') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>
                    <option value="Contact Client">Contact Client (Call)</option>
                    <option value="Call Attempt">Call Attempt</option>
                    <option value="Followed Up">Followed Up</option>
                    <option value="Whatsapp Call">Whatsapp Call</option>
                    <option value="Whatsapp (Message)">Whatsapp (Message)</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Emails') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>
                    <option value="Contacted Client">Contacted Client</option>
                    <option value="Followed Up">Followed Up</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Complaint') {
            $html .='<label>Sub Type<sup>*</sup></label>
                    <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                        <option value=""> Do Nothing </option>
                        <option value="New Project Lead">New Project Lead</option>
                        <option value="Lack of visit from the BDA side">Lack of visit from the BDA side</option>
                        <option value="Behavioral Issue">Behavioral Issue</option>
                        <option value="Comission Delay">Comission Delay</option>
                        <option value="Do not work with us">Do not work with us</option>
                        <option value="Others">Others</option>
                        <option value="Resolved">Resolved</option>
                    </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        }

        return response()->json(['html' => $html]);
    }

    public function get_affiliator_task_history(Request $request) {
        $tasks = AffiliatorTask::where('affliator_id', $request->affiliator_id)->orderby('id', 'desc')->get();
        $rows = '';
        if (count($tasks) > 0) {

            foreach ($tasks as $task) {


                $rows .=''
                        . '<tr>'
                        . '<td>#' . $task->id . '</td>'
                        . '<td>' . $task->type . ' - ' . $task->subtype . '<br>' . date('M d, Y', strtotime($task->created_at)) . '</td>'
                        . '<td>'
                        . '<strong>' . $task->addedBy->name . '</strong> - '
                        . '' . date('M d, Y', strtotime($task->completion_date)) . '</br>';
                $rows .= $task->note;
                if ($task->file != '') {
                    $rows .= '<br><a href="' . url('storage/app/public/' . $task->file) . '" download>Download</a>';
                }
                $rows .='</td>'
                        . '<td>#' . date('M d, Y', strtotime($task->created_at)) . '</td>'
                        . '</tr>';
            }
        } else {
            $rows .=''
                    . '<tr>'
                    . '<td>No Task Found!</td>'
                    . '</tr>';
        }
        return response()->json(['html' => $rows]);
    }

    public function destroy(Affiliator $affiliator) {
        return redirect('affiliators')->withStatus(__('Affiliator Updated Successfully.'));
    }

    public function search(Request $request) {

        $condition = array();

        if ($request->input('name') != '') {
            $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
        }

        if ($request->input('type') > 0) {
            $condition[] = array('type', $request->input('type'));
        }
        if ($request->input('status') != '') {
            $condition[] = array('status', $request->input('status'));
        }

        if ($request->input('email') != '') {
            $condition[] = array('email', '=', '%' . $request->input('email') . '%');
        }

        if ($request->input('phone') != '') {
            $condition[] = array('phone', 'Like', '%' . $request->input('phone') . '%');
        }

        if ($request->input('user_id') > 0) {
            $condition[] = array('user_id', $request->input('user_id'));
        }

        $affiliators = array();
        if (!empty($condition)) {
            $affiliators = Affiliator::where($condition)->orderBy('id', 'desc')->paginate(30);
        } else {
            if (Auth::user()->role == 5) {
                $affiliators = Affiliator::orderBy('id', 'desc')->paginate(30);
            } else {
                $users = User::where('parent', Auth::user()->id)->pluck('id');
                $affiliators = Affiliator::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
            }
        }
        $search_person = $request->input('search_person');
        $id = $request->input('current_user');
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users'));
    }

    public function todossearch(Request $request) {

        $condition = array();


        if ($request->input('tasktype') > 0) {
            $condition[] = array('ntype', $request->input('tasktype'));
        }

        if ($request->input('user_id') > 0) {
            $condition[] = array('user_id', $request->input('user_id'));
        }
        $tasks = AffiliatorTask::where($condition)->paginate(30);

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
        // ====== Users With Helper======

        return view('affiliators.todos', compact('tasks', 'users'));
    }

}
