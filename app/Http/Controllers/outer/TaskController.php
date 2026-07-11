<?php

namespace App\Http\Controllers\outer;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Taskmeta;
use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use App\Models\Clients;
use App\Models\Leads;
use App\Models\User;
use App\Models\LeadNotes;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller {


    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function todolist()
    {

        $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(30);

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }

        // Overdue date
        $tdate = strtotime(date('Y-m-d'));
        $tdate = strtotime("+1 day", $tdate);

        $today = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->count();
        $tomorrow = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d', $tdate))->where('status', 0)->count();
        $overdue = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->count();
        // Week Tasks count
        $date = strtotime(date('Y-m-d'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        $week = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->count();

        $date = strtotime(date('Y-m-d'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");
        $alltasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->count();
        $todo = '';

        return view('tasks.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));

    }

    public function todos($todo)
    {
        // Overdue date
        $tdate = strtotime(date('Y-m-d'));
        $tdate = strtotime("+1 day", $tdate);

        $today = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->count();
        $tomorrow = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d', $tdate))->where('status', 0)->count();
        $overdue = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->count();
        // Week Tasks count
        $date = strtotime(date('Y-m-d'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        $week = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->count();

        $date = strtotime(date('Y-m-d'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");
        $alltasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->count();



        if($todo == 'today')
        {
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        }
        elseif ($todo == 'overdue') {
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'tomorrow') {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+1 day", $date);
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d', $date))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'week') {
            $date = strtotime(date('Y-m-d'));
            $date1 = strtotime("+1 day", $date);
            $date = strtotime("+7 day", $date);
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } else {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+8 day", $date);
            $future_timestamp = strtotime("+1 month");
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        }

        $users = User::where('parent', Auth::user()->id)->get();
        return view('tasks.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'type' => 'required',
            'subtype' => 'required',
            'completion_date' => 'required'
        ]);

        if ($request->input('down_payment') > 0 && $request->input('type') == 'Sales' && $request->input('unit_id') < 1) {
            return back()->withInput()->withErrors(['Please select Unit id against Down Payment ']);
        }

        if ($request->input('token_amount') > 0 && $request->input('type') == 'Sales' && $request->input('unit_id') < 1) {
            return back()->withInput()->withErrors(['Please select Unit id against Token Amount ']);
        }

        if ($request->input('token_amount') > 0 && $request->input('type') == 'Sales' && $request->input('unit_id') > 0) {

            if ($request->input('token_amount') < 10000) {
                return redirect()->back()->withErrors(__('Token amount should be greater than or equal to 10000 atleast.'));
            } else {
                $product = Product::find($request->input('unit_id'));
                $product->status = 'Hold';
                $expiary_date = date('Y-m-d H:i:s');

                if ($request->input('token_amount') >= 10000 && $request->input('token_amount') <= 30000) {
                    $expiary_date = date('Y-m-d H:i:s', strtotime("+5 days"));
                } elseif ($request->input('token_amount') <= 30000 && $request->input('token_amount') >= 100000) {
                    $expiary_date = date('Y-m-d H:i:s', strtotime("+10 days"));
                }
                $product->hold_expiary = $expiary_date;
                $product->save();
            }
        }
        $deadline = date("Y-m-d", strtotime($request->deadline));
        $completion_date = date("Y-m-d", strtotime($request->completion_date));
        Task::where('lead_id', $request->input('lead_id'))->where('client_id', $request->input('client_id'))->where('status', 0)->update(['status' => 1]);
        $task = Task::create($request->merge(['deadline' => $deadline, 'completion_date' => $completion_date])->all());
        $filePath = '';
        $file_location = '';
        if ($request->file) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('task', $fileName, 'public');
            $task->file = $filePath;
        }
        $step_id = 0;
        if ($request->input('type') == 'Meetings') {
            $step_id = 1;
        } elseif ($request->input('type') == 'Sales') {
            $step_id = 2;
        } elseif ($request->input('type') == 'Calls') {
            $step_id = 3;
        } elseif ($request->input('type') == 'Emails') {
            $step_id = 4;
        } elseif ($request->input('type') == 'Acquisition') {
            $step_id = 5;
        }

        DB::table('task_log')->insert([
            'task_id' => $task->id,
            'task' => 'The Agent Performed Action type ' . $request->input('type') . ' ' . $request->input('subtype'),
        ]);

        $taskmeta = new Taskmeta;

        if ($request->input('location') != '') {
            $taskmeta->task_key = 'meeting_location';
            $taskmeta->task_value = $request->input('location');
            $taskmeta->task_id = $task->id;
            $taskmeta->save();
        }
        if ($request->input('token_amount') != '') {
            $taskmeta->task_key = 'token_amount';
            $taskmeta->task_value = $request->input('token_amount');
            $taskmeta->task_id = $task->id;
            $taskmeta->save();
        }
        if ($request->input('down_payment') != '') {
            $taskmeta->task_key = 'down_payment';
            $taskmeta->task_value = $request->input('down_payment');
            $taskmeta->task_id = $task->id;
            $taskmeta->save();
        }

        $lead = Leads::find($request->input('lead_id'));

        if ($request->input('token_amount') != '' && $request->input('cname') != '') {
            $client_data = array();
            $client_data = array('name' => $request->input('cname'), 'email' => $request->input('cemail'), 'phone' => $request->input('cphone'));
            $lead->owner = json_encode($client_data);
        }
        if ($request->input('project_id') != '') {
            $lead->project_id = $request->input('project_id');
        }

        if ($request->input('type_id') != '') {
            $lead->category_id = $request->input('type_id');
        }

        if ($request->input('unit_id') != '') {
            $lead->item_id = $request->input('unit_id');
        }

        if ($request->input('token_amount') != '') {
            $lead->status_id = 6;
            $lead->token_status = 1;
            $lead->token_amount = $request->input('token_amount');
        }

        if ($request->input('down_payment') != '') {
            $lead->status_id = 7;
            $lead->down_pstatus = 1;
            $lead->down_payment = $request->input('down_payment');
        }

        $lead->last_updated_by = Auth::user()->id;

        $task->save();
        $lead->save();

        return redirect()->back()->withStatus(__('Task Created Successfully.'));
    }



    public function get_task(Request $request) {
        $tasks = Task::where('lead_id', $request->input('lead_id'))->where('client_id', $request->input('clientid'))->orderby('id', 'desc')->get();
        $rows = '';
        $item_namee = '';
        if (count($tasks) > 0) {

            foreach ($tasks as $task) {

                if ($request->input('itemid') > 0) {
                    $item_name = DB::table('product')->where('id', $task->leadsTask->item_id)->pluck('name');
                    $item_namee = $item_name[0];
                }

                if ($item_namee == '') {
                    $item_namee = 'Nothing';
                }

                $rows .=''
                        . '<tr>'
                        . '<td>#' . $task->lead_id . '</td>'
                        . '<td>' . $item_namee . '</td>'
                        . '<td>' . $task->type . ' - ' . $task->subtype . '<br>' . date('M d, Y', strtotime($task->created_at)) . '</td>'
                        . '<td>'
                        . '<strong>' . $task->addedBy->name . '</strong> - '
                        . '' . date('M d, Y', strtotime($task->completion_date)) . '</br>';
                $rows .= $task->note;
                if ($task->file != '') {
                    $rows .= '<br><a href="' . url('storage/app/public/' . $task->file) . '" download>Download</a>';
                }
                $rows .='</td>'
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

    public function account_log(Request $request) {

        $tasks = LeadNotes::where('lead_id', $request->input('lead_id'))->where('item_id', $request->input('item_id'))->orderby('id', 'desc')->get();

        $rows = '';
        if (count($tasks) > 0) {

            foreach ($tasks as $task) {
                if ($task->status == 1) {
                    $status = 'Accepted';
                } elseif ($task->status == 2) {
                    $status = 'Pending';
                } elseif ($task->status == 3) {

                } else {
                    $status = 'Rejected';
                }

                $rows .=''
                        . '<tr>'
                        . '<td>#' . $task->lead_id . '</td>'
                        . '<td>' . $status . '</td>'
                        . '<td>' . $task->products->name . '</td>'
                        . '<td>' . $task->notes . '</td>'
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

    public function gettasksubtype(Request $request) {

        $html = '';
        if ($request->task == 'Meetings') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8" required="">
                    <option>Do Nothing</option>
                    <option value="Video Meeting">Video Meeting</option>
                    <option value="Walk-In">Walk-In</option>
                    <option value="Meeting (Done)">Meeting (Done)</option>
                    <option value="Meeting (Arranged)">Meeting (Arranged)</option>
                    <option value="Meeting (Attempt)">Meeting (Attempt)</option>
                    <option value="Meeting (Pushed)">Meeting (Pushed)</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Sales') {
            $tasks = Task::where('lead_id', $request->input('lead_id'))->where('type', 'Sales')->where('subtype', 'Like', '%Payment%')->pluck('subtype')->toArray();
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>';
            if (in_array('Token Payment', $tasks) || in_array('Complete Down Payment', $tasks)) {
                $html .='    <option disabled="disabled" value="Token Payment">Token Payment</option>';
            } else {
                $html .='    <option value="Token Payment">Token Payment</option>';
            }
            if (in_array('Complete Down Payment', $tasks)) {
                $html .='    <option disabled="disabled" value="Complete Down Payment">Complete Down Payment</option>';
            } else {
                $html .='    <option value="Complete Down Payment">Complete Down Payment</option>';
            }

            $html .='<option value="Booking Form">Booking Form</option>
                    <option value="Closed (Won)">Closed (Won)</option>
                    <option value="Closed (Lost)">Closed (Lost)</option>
                    <option value="Closed (Cancelled)">Closed (Cancelled)</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Calls') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>
                    <option value="SMS">SMS</option>
                    <option value="Contacted Client">Contacted Client</option>
                    <option value="Call Attempt">Call Attempt</option>
                    <option value="Followed Up">Followed Up</option>
                    <option value="Whatsapp Call">Whatsapp Call</option>
                    <option value="Whatsapp (Message)">Whatsapp (Message)</option>
                    <option value="Meeting Confirmed">Meeting Confirmed</option>
                    <option value="Meeting Cancelled">Meeting Cancelled</option>
                    <option value="Meeting Postponed">Meeting Postponed</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Emails') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>
                    <option value="Contacted Client">Contacted Client</option>
                    <option value="Followed Up">Followed Up</option>
                    <option value="Porposal">Porposal</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Acquisition') {
            $html .='<label>Sub Type<sup>*</sup></label>
                    <select name="subtype" id="subtype" class="form-control select-picker" data-size="8" required="">
                        <option value=""> Do Nothing </option>
                        <option value="Negotiation">Negotiation</option>
                        <option value="Verification">Verification</option>
                        <option value="Comission">Comission</option>
                        <option value="MOU">MOU</option>
                    </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        }

        return response()->json(['html' => $html]);
    }

    public function getsubtypeoption(Request $request) {
        $html = '';
        if ($request->task == 'Meeting (Done)' || $request->task == 'Meeting (Arranged)' || $request->task == 'Meeting (Attempt)' || $request->task == 'Meeting (Pushed)' && $request->tasktype == 'Meetings') {
            $html .='<div class="form-group"><label>Location</label>
                <select name="location" id="location" class="form-control select-picker" data-size="8">
                    <option>Company Office</option>
                    <option>Out-Door</option>
                    <option>Site Office</option>
                    <option>Sales Office</option>
                </select>
                </div>';
        } elseif ($request->task == 'Token Payment' || $request->task == 'Complete Down Payment' && $request->tasktype == 'Sales') {

            $html = '';
            $projects = Project::orderby('id', 'asc')->get();
            $client = Clients::where('id', $request->client_id)->first();
            $lead = Leads::find($request->lead_id);

            $html .='<div class="form-group">
                <label>Select Project<sup>*</sup></label>
                    <select name="project_id" id="project_id" class="form-control select-picker" data-size="8" required="required">';
            $html .='<option value="">--Select Project </option>';
            foreach ($projects as $project) {
                if ($project->id == $lead->project_id) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $html .='<option ' . $selected . ' value="' . $project->id . '"> ' . $project->name . ' </option>';
            }
            $html .= '</select><span id="name-error" class="error text-danger project-error" for="input-name"></span></div>';
            $categories = Category::orderby('id', 'asc')->get();
            $category_id = $lead->category_id;
            if ($category_id > 0) {
                $type_display = 'block';
            } else {
                $type_display = 'none';
            }
            $html .='<div class="form-group select_type" style="display:' . $type_display . ';">
                <label>Select Type<sup>*</sup></label>
                    <select name="type_id" id="type_id" class="form-control select-picker" data-size="8" required="required">';
            $html .='<option value="">--Select Type--</option>';
            foreach ($categories as $category) {
                if ($category->id == $category_id) {
                    $selected = '';
                } else {
                    $selected = '';
                }
                $html .='<option ' . $selected . ' value="' . $category->id . '"> ' . $category->name . ' </option>';
            }
            $html .= '</select></div>'
                    . '<div class="project_items">';
            if ($lead->item_id > 0) {
                $units = Product::where('project_id', $lead->project_id)->where('category_id', $lead->category_id)->where('status', 'Available')->orWhere('id', $lead->item_id)->orderby('id', 'desc')->get();
                $html .='<div class="form-group"><label>Select Unit<sup>*</sup></label>
            <select name="unit_id" id="unit_id" class="form-control select" data-size="8" required="required">';
                $html .='<option value=""> Select Unit </option>';
                foreach ($units as $unit) {
                    if ($unit->id == $lead->item_id) {
                        $selected = '';
                    } else {
                        $selected = '';
                    }
                    $html .='<option ' . $selected . ' value="' . $unit->id . '"> ' . $unit->name . ' </option>';
                }
                $html .= '</select></div>';
            }
            $html .= '</div>';
            if ($request->task == 'Token Payment' || $request->task == 'Complete Down Payment') {
                $html .= '<div class="unit_payment">';
                $html .= '</div>';
            }

            $html .='<div class="form-group">
                <label>Customer Name</label>
                    <input class="form-control" name="cname" id="input-name" type="text" value="' . $client->name . '"/>
            </div>';

            $html .='<div class="form-group">
                <label>Customer Email</label>
                    <input class="form-control" name="cemail" id="input-name" type="text" value="' . $client->email . '"/>
            </div>';

            $html .='<div class="form-group">
                <label>Customer Phone</label>
                    <input class="form-control" name="cphone" id="input-name" type="text" value="' . $client->phone . '"/>
            </div>';
        } elseif ($request->task == 'Closed (Won)' || $request->task == 'Closed (Lost)' || $request->task == 'Closed (Cancelled)' && $request->tasktype == 'Sales') {

            $html = '';
            $projects = Project::orderby('id', 'asc')->get();
            $client = Clients::where('id', $request->client_id)->first();
            $products = Product::where('status', 'Available')->orderby('id', 'desc')->get();

            $html .='<div class="form-group">
                <label>Select Project</label>
                    <select name="project_id" id="project_id" class="form-control select-picker"  required="required"';
            $html .='<option value="0">--Select Project--</option>';
            foreach ($projects as $project) {
                $html .='<option value="' . $project->id . '"> ' . $project->name . ' </option>';
            }
            $html .= '</select></div>';
            $categories = Category::orderby('id', 'desc')->get();

            $html .='<div class="form-group">
                <label>Select Type</label>
                    <select name="type_id" id="type_id" class="form-control select-picker" required="required">';
            $html .='<option value="0"> Select Type </option>';
            foreach ($categories as $category) {
                $html .='<option value="' . $category->id . '"> ' . $category->name . ' </option>';
            }
            $html .= '</select></div>'
                    . ''
                    . '<div class="project_items"></div>';
        }

        return response()->json(['html' => $html]);
    }

    public function getfloor(Request $request) {
        $html = '';
        $html .='<div class="form-group">
                <label>Select Floor</label>
                    <select name="floor" id="floor_id" class="form-control">';
        $html .='<option value="0"> Select Floor </option>'
                . '<option value="Ground Floor">Ground Floor</option>
                    <option value="' . $request->floor . '" selected>' . $request->floor . '</option>
                    <option value="1st Floor">1st Floor</option>
                    <option value="2nd Floor">2nd Floor</option>
                    <option value="3rd Floor">3rd Floor</option>
                    <option value="4th Floor">4th Floor</option>
                    <option value="5th Floor">5th Floor</option>
                    <option value="6th Floor">6th Floor</option>
                    <option value="7th Floor">7th Floor</option>
                    <option value="8th Floor">8th Floor</option>
                    <option value="9th Floor">9th Floor</option>
                    <option value="10th Floor">10th Floor</option>
                    <option value="11th Floor">11th Floor</option>
                    <option value="12th Floor">12th Floor</option>
                    <option value="13th Floor">13th Floor</option>
                    <option value="14th Floor">14th Floor</option>
                    <option value="15th Floor">15th Floor</option>
                    <option value="16th Floor">16th Floor</option>
                    <option value="17th Floor">17th Floor</option>
                    <option value="18th Floor">18th Floor</option>
                    <option value="19th Floor">19th Floor</option>
                    <option value="20th Floor">20th Floor</option>
                    <option value="21th Floor">21th Floor</option>
                    <option value="22th Floor">22th Floor</option>
                    <option value="23th Floor">23th Floor</option>';
        $html .= '</select></div>';
        $units = Product::where('project_id', $request->project_id)->where('floor', $request->floor)->where('category_id', $request->category_id)->where('status', 'Available')->orderby('id', 'desc')->get();
        if (count($units) > 0) {
            $html .='<div class="form-group"><label>Select Unit<sup>*</sup></label>
                    <select name="unit_id" id="unit_id" class="form-control select" data-size="8" required>';
            $html .='<option value=""> Select Unit </option>';
            foreach ($units as $unit) {
                $html .='<option value="' . $unit->id . '"> ' . $unit->name . ' </option>';
            }
            $html .= '</select></div>';
        } else {
            $html .= '<p>Sorry No record found!</p>';
        }
        return response()->json(['html' => $html]);
    }

    public function getunits(Request $request) {
        $html = '';
        $units = Product::where('project_id', $request->project_id)->where('category_id', $request->category_id)->where('status', 'Available')->orderby('id', 'desc')->get();
        if (count($units) > 0) {
            $html .='<div class="form-group">
                <label>Select Floor</label>
                    <select name="floor" id="floor_id" class="form-control">';
            $html .='<option value="0"> Select Floor </option>'
                    . '<option value="Ground Floor">Ground Floor</option>
                <option value="1st Floor">1st Floor</option>
                <option value="2nd Floor">2nd Floor</option>
                <option value="3rd Floor">3rd Floor</option>
                <option value="4th Floor">4th Floor</option>
                <option value="5th Floor">5th Floor</option>
                <option value="6th Floor">6th Floor</option>
                <option value="7th Floor">7th Floor</option>
                <option value="8th Floor">8th Floor</option>
                <option value="9th Floor">9th Floor</option>
                <option value="10th Floor">10th Floor</option>
                <option value="11th Floor">11th Floor</option>
                <option value="12th Floor">12th Floor</option>
                <option value="13th Floor">13th Floor</option>
                <option value="14th Floor">14th Floor</option>
                <option value="15th Floor">15th Floor</option>
                <option value="16th Floor">16th Floor</option>
                <option value="17th Floor">17th Floor</option>
                <option value="18th Floor">18th Floor</option>
                <option value="19th Floor">19th Floor</option>
                <option value="20th Floor">20th Floor</option>
                <option value="21th Floor">21th Floor</option>
                <option value="22th Floor">22th Floor</option>
                <option value="23th Floor">23th Floor</option>';
            $html .= '</select></div>';

            $html .='<div class="form-group"><label>Select Unit<sup>*</sup></label>
                    <select name="unit_id" id="unit_id" class="form-control select" data-size="8"  required>';
            $html .='<option value=""> Select Unit </option>';
            foreach ($units as $unit) {
                $html .='<option value="' . $unit->id . '"> ' . $unit->name . ' </option>';
            }
            $html .= '</select></div>';
        } else {
            $html .= '<p>Sorry No record found!</p>';
        }
        return response()->json(['html' => $html]);
    }

    public function getunitdetail(Request $request) {
        $html = '';
        $unit = Product::where('id', $request->unit_id)->where('status', 'Available')->first();

        $area = 0;
        if ($unit->carea != '') {
            $area = $unit->carea;
        } else {
            $area = $unit->garea;
        }

        $html .= '<input type="hidden" value="' . $unit->price . '" class="unit_price_value">'
                . '<input type="hidden" value="' . $unit->dpayment . '" class="unit_dpayment_value">'
                . '<input type="hidden" value="' . $unit->dpaymentper . '" class="unit_dpayment_percentage">'
                . '<input type="hidden" value="' . $area . '" class="unit_area">';

        if ($request->type_id == 1) {
            $html .= '<input type="hidden" value="500" class="discount_persqft">';
        } elseif ($request->type_id == 2) {
            $html .= '<input type="hidden" value="1500" class="discount_persqft">';
            $discount_sqft_max_amount = 1500 * $area;
        } else {
            $html .= '<input type="hidden" value="0" class="discount_persqft">';
            $discount_sqft_max_amount = 500 * $area;
        }
        $html .= '<input type="hidden" value="' . $discount_sqft_max_amount . '" class="discount_max_limit">';

        $html .= '<div class="form-group payment_div">
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label ">Unit Price</label>
        <div class="col-md-8 ">
            <span class="text-bold" id="unit_price_task">PKR ' . number_format($unit->price) . '</span>
        </div>
    </div>
    <div class="form-group col-md-12 ng-star-inserted">
        <label class="col-md-3 control-label " for="statuses_list">Unit Status
            <span aria-required="true" class="required"> * </span>
        </label>
        <div class="col-md-8 ">
            <select class="form-control input-sm" disabled="" id="statuses_list" name="statuses_list">
                <option>' . $request->subtype . '</option>
            </select>
        </div>
    </div>
    <div class="form-group col-md-12 discount-sec">
        <label class="col-md-3 control-label ">Discount Psqr/ft</label>
        <div class="col-md-4 ">
            <input class="form-control input-sm input-arrow unit_discount_input" type="number" value="0">
        </div>
        <div class="col-md-4">
            <input class="form-control input-sm input-arrow unit_discount_percentage" type="number" value="0">
			            <span class="perecent-sign">%</span>

        </div>
        <span class="discount_error" style="display:none;">Discount cannot be greater than max limit allowed.</span>
    </div>
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label " for="deal_price">Deal Price
            <span aria-required="true" class="required"> * </span>
        </label>
        <div class="col-md-8 ">
            <input class="form-control" id="deal_price" name="deal_price" type="number" value="0" readonly>
        </div>
        <span class="col-md-12 info"> Enter the price on which the deal was locked with the customer. </span>
    </div>
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label" for="deal_price"> Due Amount: </label>
        <span class="col-md-8" id="due_price_task"> PKR ' . number_format($unit->dpayment) . ' (' . $unit->dpaymentper . '%) </span>
    </div>
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label" for="deal_price">Discount Amount: </label>
        <span class="col-md-8" id="discount_price_unit">  </span>
    </div>
</div>';
        $html .= ''
                . '<div class="nopadding form-group  ng-star-inserted" style="">
    <div class="row add-payment-form">
        <h3 class="modal-title col-md-12 margin-bottom-12">Add Payment</h3>
        <div class="form-group col-md-12">
            <label class="col-md-4 control-label">Amount:
                <span aria-required="true" class="required ng-star-inserted"> * </span>
            </label>
            <div class="col-md-8">
                <input class="form-control input-sm ng-pristine ng-valid ng-touched" type="number">
            </div>
        </div>
        <div class="form-group col-md-12">
            <label class="col-md-4 control-label col-md-offset-3">Payment Mode:
                <span aria-required="true" class="required ng-star-inserted"> * </span>
            </label>
            <div class="col-md-8">
                <select class="form-control input-sm ng-untouched ng-pristine ng-valid">
                    <option value="">Select</option>
                    <option value="1"> Cheque </option>
                    <option value="2"> Cash </option>
                    <option value="3"> Pay Order </option>
                    <option value="8"> Bank Loan </option>
                    <option value="9"> Online Payment </option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-12">
            <label class="col-md-4 control-label">Receipt #: </label>
            <div class="col-md-8">
                <input class="form-control input-sm" min="1" type="number">
            </div>
            <span class="col-md-9 text-muted description text-left"> Receipt number </span>
        </div>
    </div>
</div>';
        return response()->json(['html' => $html]);
    }

    public function dateformat() {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            if ($task->deadline != '') {
                $task_deadline = date("Y-m-d", strtotime($task->deadline));
                $completion_date = date("Y-m-d", strtotime($task->completion_date));
                $task->deadline1 = $task_deadline;
                $task->completion_date1 = $completion_date;
                $task->save();
            }
        }
    }

    public function search(Request $request) {

        $condition = array();
        $userid = 0;
        $condition[] = array('added_by', Auth::user()->id);

        if ($request->tasktype > 0) {
            $condition[] = array('ntype', $request->tasktype);
        }
        $account = 'user';

        $condition = array_filter($condition);
        if (count($condition) > 0) {
            $tasks = Task::where($condition)->get();
        } else {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+8 day", $date);
            $future_timestamp = strtotime("+1 month");
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        }
        $date = strtotime(date('Y-m-d'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");
        $tasks = Task::where($condition)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        // Overdue date
        $tdate = strtotime(date('Y-m-d'));
        $tdate = strtotime("+1 day", $tdate);
        //echo date('Y-m-d'); exit;
        $today = Task::where($condition)->where('deadline', '=', date('Y-m-d'))->where('status', 0)->count();
        $tomorrow = Task::where($condition)->where('deadline', date('Y-m-d', $tdate))->where('status', 0)->count();
        $overdue = Task::where($condition)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->count();
        // Week Tasks count
        $date = strtotime(date('Y-m-d'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        $week = Task::where($condition)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('status', 0)->count();
        $todo = '';
        $alltasks = count($tasks);
        return view('tasks.todos', compact('tasks', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

}
