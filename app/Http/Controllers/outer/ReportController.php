<?php

namespace App\Http\Controllers\outer;

use App\Http\Controllers\Controller;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\ShareLead;
use App\Models\TransferLead;
use App\Models\Category;
use App\Models\User;
use App\Models\Clients;
use App\Models\Product;
use App\Models\Project;
use App\Models\LeadNotes;
use App\Models\LeadPriority;
use App\Models\Affiliator;
use App\Models\AffiliatorTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Auth;
use Mail;
use DB;
use Carbon\Carbon;

class ReportController extends Controller {


    public function index() {
        $leads = Leads::all()->sortByDesc("id");
        if (Auth::user()->role == 5) {
            $account = 'manager';
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $account = 'user';
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        return view('completereport', compact('leads', 'users', 'account', 'projects', 'sources'));
    }

    public function assign() {
        $leads = Leads::where('user_id', '>', 0)->orderBy('id', 'desc')
                ->get();
        if (Auth::user()->role == 5) {
            $account = 'manager';
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $account = 'user';
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        return view('completereport', compact('leads', 'users', 'account', 'projects', 'sources'));
    }

    public function noassign() {
        $leads = Leads::where('user_id', 0)->orderBy('id', 'desc')
                ->paginate(30);

        $sources = LeadSource::all();
        return view('no-assign-report', compact('leads', 'sources'));
    }

    public function noassignaffiliator() {
        $affiliators = Affiliator::where('status', 0)->orderBy('id', 'desc')
                ->paginate(30);

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.index', compact('affiliators', 'users'));
    }

    public function overduetask() {


        $tasks = Task::where('deadline', '<', date('Y-m-d'))->where('status', 0)->where('lead_id', '>', 0)->paginate(30);

        if (Auth::user()->role == 5) {
            $account = 'manager';
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $account = 'user';
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('todos', compact('tasks', 'users', 'account'));
    }

    public function overdueafftask() {


        $tasks = AffiliatorTask::where('deadline', '<', date('Y-m-d'))->where('status', 0)->paginate(30);

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('affiliators.afftodos', compact('tasks', 'users'));
    }

    public function usertask(Request $request)
    {
        $condition = array();
        $userid = 0;

        if ($request->input('user_id') > 0) {
            $condition[] = array('added_by', '=', $request->input('user_id'));
        }

        if ($request->tasktype > 0) {
            $condition[] = array('ntype', $request->tasktype);
        }

        if ($request->task == 'overdue') {
            $condition[] = array('deadline', '<', date('Y-m-d'));
            $condition[] = array('status', 0);
        }

        if ($request->task == 'tomorrow') {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+1 day", $date);
            $condition[] = array('deadline', date('Y-m-d', $date));
            $condition[] = array('status', 0);
        }

        if ($request->task == 'today') {
            $condition[] = array('deadline', date('Y-m-d'));
            $condition[] = array('status', 0);
        }

        if ($request->task == 'week') {
            $date = strtotime(date('Y-m-d'));
            $date1 = strtotime("+1 day", $date);
            $date = strtotime("+7 day", $date);
            $condition[] = array('deadline', '>', date('Y-m-d', $date1));
            $condition[] = array('deadline', '<=', date('Y-m-d', $date));
            $condition[] = array('status', 0);
        }

        if ($request->task == 'month') {
            $date = strtotime(date('Y-m-d'));
            $date1 = strtotime("+1 day", $date);
            $date = strtotime("+30 day", $date);
            $condition[] = array('deadline', '<=', date('Y-m-d', $date));
            $condition[] = array('status', 0);
        }

        if ($request->task == 'all') {
            $condition[] = array();
        }

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }

        $condition = array_filter($condition);

        if (!empty($condition)) {
            $tasks = Task::where($condition)->paginate(30);
        } else {
            if (Auth::user()->role == 5) {
                $tasks = Task::orderBy('id', 'desc')->paginate(30);
            } else {
                $users = User::where('parent', Auth::user()->id)->pluck('id');
                $tasks = Task::whereIn('added_by', $users)->orWhere('added_by', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
            }
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

    public function notassigntask(Request $request) {

        $condition = array();
        $userid = 0;

        if ($request->source_id > 0) {
            $condition[] = array('source_id', $request->source_id);
        }

        $condition[] = array('user_id', 0);

        $condition = array_filter($condition);

        $leads = Leads::where($condition)->paginate(30);

        $account = $request->input('account');
        $sources = LeadSource::all();
        return view('no-assign-report', compact('leads', 'account', 'sources'));
    }

    public function search(Request $request) {


        $condition = array();
        $userid = 0;

        if ($request->input('account') == 'user') {
            if ($request->input('user_id') == 0) {
                $condition[] = array('user_id', Auth::user()->id);
                $userid = Auth::user()->id;
            } else {
                $condition[] = array('user_id', $request->input('user_id'));
                $userid = $request->input('user_id');
            }
        } else {
            if ($request->input('user_id') == 0) {
                $condition[] = array();
                $userid = 0;
            } else {
                $condition[] = array('user_id', '=', $request->input('user_id'));
                $userid = $request->input('user_id');
            }
        }

        if ($request->input('project_id') != 0) {
            $condition[] = array('project_id', '=', $request->input('project_id'));
        }

        if ($request->input('source_id') != 0) {
            $condition[] = array('source_id', $request->input('source_id'));
        }

        if (Auth::user()->role == 5) {
            $account = 'manager';
            $users = User::where('role', '!=', 0)->orderBy('id', 'desc')->get();
        } else {
            $account = 'user';
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        $condition = array_filter($condition);

        if (count($condition) > 0) {

            if ($request->input('records') == 0) {
                $leads = Leads::where($condition)->orderBy('id', 'desc')
                        ->get();
                $assigned_leads = Leads::where($condition)->where('user_id', '>', 0)
                        ->count();
            } else {
                $records = $request->input('records');
                $leads = Leads::where($condition)->limit($records)->orderBy('id', 'desc')
                        ->get();

                $assigned_leads = $records;
            }
        } else {
            $leads = Leads::all();
            $assigned_leads = Leads::where('user_id', '>', 0)
                    ->count();
        }

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();

        $account = $request->input('account');
        return view('completereport', compact('sources', 'leads', 'users', 'projects', 'account', 'assigned_leads', 'userid'));
    }

}
