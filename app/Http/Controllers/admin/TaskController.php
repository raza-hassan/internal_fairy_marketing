<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Taskmeta;
use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use App\Models\Clients;
use App\Models\Leads;
use App\Models\User;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller {

    public function index() {
        $tasks = Task::all();
        return view('admin.tasks.index', compact('tasks'));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'type' => 'required',
            'subtype' => 'required',
            'duedate' => 'required'
        ]);

        $filePath = '';
        $file_location = '';
        if ($request->file) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('task', $fileName, 'public');
        }

        $task = Task::create($request->merge(['file' => $filePath])->all());

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
            'step_id' => $step_id,
            'task' => 'The Agent Performed Action type ' . $request->input('type') . ' ' . $request->input('subtype'),
        ]);

        $taskmeta = new Taskmeta;

        if ($request->input('location') != '') {
            $taskmeta->task_key = 'meeting_location';
            $taskmeta->task_value = $request->input('location');
            $taskmeta->task_id = $task->id;
            $taskmeta->save();
        }
        if ($request->input('token') != '') {
            $taskmeta->task_key = 'token_amount';
            $taskmeta->task_value = $request->input('token');
            $taskmeta->task_id = $task->id;
            $taskmeta->save();
        }

        $lead = Leads::find($request->input('lead_id'));

        if ($request->input('token') != '' && $request->input('cname') != '') {
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

        if ($request->input('item_id') != '') {
            $lead->item_id = $request->input('item_id');
        }

        $lead->last_updated_by = Auth::user()->id;
        $lead->save();

        if ($request->input('token') > 0 && $request->input('type') == 'Sales') {
            $product = Product::find($request->input('item_id'));
            $product->status = 'Hold';
            $product->hold_status = 1;
            $product->hodl_expiary = date('Y-m-d', strtotime($Date . ' + 1 days'));
            $product->save();
        }

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
                        . '(' . $task->addedBy->designation . ') ' . $task->addedBy->name . ' - '
                        . '' . date('M d, Y', strtotime($task->duedate)) . ''
                        . '' . $task->note . ''
                        . '</td>'
                        . '</tr>';
            }
        }
//        $html = view('admin.task.log', compact('tasks'))->render();
        return response()->json(['html' => $rows]);
    }

    public function gettasksubtype(Request $request) {

        $html = '';
        if ($request->task == 'Meetings') {
            $html .='<label>Sub Type</label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8">
                    <option>Do Nothing</option>
                    <option value="Video Meeting">Video Meeting</option>
                    <option value="Walk-In">Walk-In</option>
                    <option value="Meeting (Done)">Meeting (Done)</option>
                    <option value="Meeting (Arranged)">Meeting (Arranged)</option>
                    <option value="Meeting (Attempt)">Meeting (Attempt)</option>
                    <option value="Meeting (Pushed)">Meeting (Pushed)</option>
                </select>';
        } elseif ($request->task == 'Sales') {
            $tasks = Task::where('lead_id', $request->input('lead_id'))->where('type', 'Sales')->where('subtype', 'Like', '%Payment%')->pluck('subtype')->toArray();

            $html .='<label>Sub Type</label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8">
                    <option value="">Do Nothing</option>';
            if (in_array('Token Payment', $tasks)) {
                $html .='    <option disabled="disabled" value="Token Payment">Token Payment</option>';
            } else {
                $html .='    <option value="Token Payment">Token Payment</option>';
            }
            if (in_array('Complete Down Payment', $tasks)) {
                $html .='    <option disabled="disabled" value="Complete Down Payment">Complete Down Payment</option>';
            } else {
                $html .='    <option value="Token Payment">Token Payment</option>';
            }

            $html .='<option value="Booking Form">Booking Form</option>
                    <option value="Closed (Won)">Closed (Won)</option>
                    <option value="Closed (Lost)">Closed (Lost)</option>
                    <option value="Closed (Cancelled)">Closed (Cancelled)</option>
                </select>';
        } elseif ($request->task == 'Calls') {
            $html .='<label>Sub Type</label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8">
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
                </select>';
        } elseif ($request->task == 'Emails') {
            $html .='<label>Sub Type</label>
                <select name="subtype" id="subtype" class="form-control select-picker" data-size="8">
                    <option value="">Do Nothing</option>
                    <option value="Contacted Client">Contacted Client</option>
                    <option value="Followed Up">Followed Up</option>
                    <option value="Porposal">Porposal</option>
                </select>';
        } elseif ($request->task == 'Acquisition') {
            $html .='<label>Sub Type</label>
                    <select name="subtype" id="subtype" class="form-control select-picker" data-size="8">
                        <option value=""> Do Nothing </option>
                        <option value="Negotiation">Negotiation</option>
                        <option value="Verification">Verification</option>
                        <option value="Comission">Comission</option>
                        <option value="MOU">MOU</option>
                    </select>';
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
        } elseif ($request->task == 'Complete Down Payment' || $request->task == 'Token Payment' || $request->task == 'Partial Down Payment' && $request->tasktype == 'Sales') {

            $html = '';
            $projects = Project::orderby('id', 'asc')->get();
            $client = Clients::where('id', $request->client_id)->first();
            $html .='<div class="form-group">
                <label>Token Amount</label>
                    <input class="form-control" name="token" id="input-name" type="text" placeholder="Enter Amount"/>
            </div>';

            $html .='<div class="form-group">
                <label>Select Project</label>
                    <select name="project_id" id="projectid" class="form-control select-picker" data-size="8">';
            $html .='<option> Select Project </option>';
            foreach ($projects as $project) {
                $html .='<option value="' . $project->id . '"> ' . $project->name . ' </option>';
            }
            $html .= '</select></div>';
            $categories = Category::orderby('id', 'desc')->get();

            $html .='<div class="form-group">
                <label>Select Type</label>
                    <select name="type_id" id="typeid" class="form-control select-picker" data-size="8">';
            $html .='<option> Select Type </option>';
            foreach ($categories as $category) {
                $html .='<option value="' . $category->id . '"> ' . $category->name . ' </option>';
            }
            $html .= '</select>'
                    . '</div>'
                    . '<div class="project_items"></div>';

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

            $html .='<div class="form-group">
                <label>Select Project</label>
                    <select name="project_id" id="projectid" class="form-control select-picker" data-size="8">';
            $html .='<option> Select Project </option>';
            foreach ($projects as $project) {
                $html .='<option value="' . $project->id . '"> ' . $project->name . ' </option>';
            }
            $html .= '</select></div>';
            $categories = Category::orderby('id', 'desc')->get();

            $html .='<div class="form-group">
                <label>Select Type</label>
                    <select name="type_id" id="typeid" class="form-control select-picker" data-size="8">';
            $html .='<option> Select Type </option>';
            foreach ($categories as $category) {
                $html .='<option value="' . $category->id . '"> ' . $category->name . ' </option>';
            }
            $html .= '</select></div><div class="project_items"></div>';
        } elseif ($request->task == 'Complete Down Payment' || $request->task == 'Closed (Won)' || $request->task == 'Closed (Lost)' || $request->task == 'Closed (Cancelled)' && $request->tasktype == 'Sales') {

            $html = '';
            $projects = Project::orderby('id', 'asc')->get();
            $client = Clients::where('id', $request->client_id)->first();

            $html .='<div class="form-group">
                <label>Select Project</label>
                    <select name="project_id" id="projectid" class="form-control select-picker" data-size="8">';
            $html .='<option> Select Project </option>';
            foreach ($projects as $project) {
                $html .='<option value="' . $project->id . '"> ' . $project->name . ' </option>';
            }
            $html .= '</select></div>';
            $categories = Category::orderby('id', 'desc')->get();

            $html .='<div class="form-group">
                <label>Select Type</label>
                    <select name="type_id" id="typeid" class="form-control select-picker" data-size="8">';
            $html .='<option> Select Type </option>';
            foreach ($categories as $category) {
                $html .='<option value="' . $category->id . '"> ' . $category->name . ' </option>';
            }
            $html .= '</select></div><div class="project_items"></div>';
        }

        return response()->json(['html' => $html]);
    }

    public function getunits(Request $request) {
        $html = '';
        $units = Product::where('project_id', $request->project_id)->where('category_id', $request->category_id)->orderby('id', 'desc')->get();
        $html .='<div class="form-group"><label>Select Unit</label>
                    <select name="unit_id" id="unit_id" class="form-control select-picker" data-size="8">';
        $html .='<option> Select Unit </option>';
        foreach ($units as $unit) {
            $html .='<option value="' . $unit->id . '"> ' . $unit->name . ' </option>';
        }
        $html .= '</select></div>';
        return response()->json(['html' => $html]);
    }

    public function todolist() {
        $date = strtotime(date('m/d/Y'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");
        $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('m/d/Y', $date))->where('deadline', '<=', date('m/d/Y', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        // Overdue date
        $tdate = strtotime(date('m/d/Y'));
        $tdate = strtotime("+1 day", $tdate);
        $today = Task::where('added_by', Auth::user()->id)->where('deadline', '=', date('m/d/Y'))->where('status', 0)->count();
        $tomorrow = Task::where('added_by', Auth::user()->id)->where('deadline', date('m/d/Y', $tdate))->where('status', 0)->count();
        $overdue = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('m/d/Y'))->where('status', 0)->count();
        // Week Tasks count
        $date = strtotime(date('m/d/Y'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        $week = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('m/d/Y', $date1))->where('deadline', '<=', date('m/d/Y', $date))->where('status', 0)->count();
        $todo = '';
        $users = User::where('role', '!=',0)->get();
        $alltasks = count($tasks);
        return view('admin.tasks.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

    public function todos($todo) {
        // Overdue date
        $tdate = strtotime(date('m/d/Y'));
        $tdate = strtotime("+1 day", $tdate);

        $today = Task::where('added_by', Auth::user()->id)->where('deadline', date('m/d/Y'))->where('status', 0)->count();
        $tomorrow = Task::where('added_by', Auth::user()->id)->where('deadline', date('m/d/Y', $tdate))->where('status', 0)->count();
        $overdue = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('m/d/Y'))->where('status', 0)->count();
        // Week Tasks count
        $date = strtotime(date('m/d/Y'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        $week = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('m/d/Y', $date1))->where('deadline', '<=', date('m/d/Y', $date))->where('deadline', '>=', date('m/d/Y'))->where('status', 0)->count();

        $date = strtotime(date('m/d/Y'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");
        $alltasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('m/d/Y', $date))->where('deadline', '<=', date('m/d/Y', $future_timestamp))->where('status', 0)->count();
        if ($todo == 'today') {
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('m/d/Y'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'overdue') {
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '<', date('m/d/Y'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'tomorrow') {
            $date = strtotime(date('m/d/Y'));
            $date = strtotime("+1 day", $date);
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('m/d/Y', $date))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'week') {
            $date = strtotime(date('m/d/Y'));
            $date1 = strtotime("+1 day", $date);
            $date = strtotime("+7 day", $date);
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('m/d/Y', $date1))->where('deadline', '<=', date('m/d/Y', $date))->where('deadline', '>=', date('m/d/Y'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } else {
            $date = strtotime(date('m/d/Y'));
            $date = strtotime("+8 day", $date);
            $future_timestamp = strtotime("+1 month");
            $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', '>', date('m/d/Y', $date))->where('deadline', '<=', date('m/d/Y', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        }

        $users = User::where('role', '!=',0)->get();
        return view('admin.tasks.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

}
