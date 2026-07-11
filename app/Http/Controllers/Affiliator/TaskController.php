<?php

namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use App\Models\Affiliator;
use App\Models\Task;
use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use App\Models\Clients;
use App\Models\Leads;
use App\Models\User;
use App\Models\LeadNotes;
use App\Models\Offices;
use App\Models\Order;
use DB;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{


    public function todolist()
    {

        $today = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->whereDate('deadline',  Carbon::today())->where('status', 0)->count();
        $tomorrow = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->whereDate('deadline', Carbon::tomorrow())->where('status', 0)->count();
        $overdue = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->where('deadline', '<', Carbon::today())->where('status', 0)->count();

        $week = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->
                      where('deadline', '>', Carbon::now()->startOfWeek())->
                      where('deadline', '<', Carbon::now()->endOfWeek())->where('status', 0)->count();

        $tasks   = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->
                         where('deadline', '<', Carbon::today())->
                         where('status', 0)->orderBy('id', 'desc')->paginate(30); //Overdue Task

        $users = User::where('id', Auth::guard('affiliator')->user()->user_id)->Orwhere('parent', Auth::guard('affiliator')->user()->user_id)->orderBy('id', 'asc')->get();

        $alltasks = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->whereMonth('deadline',  date('m'))->where('status', 0)->count();

        $todo = '';
        $offices = Offices::orderBy('id', 'ASC')->get();
        return view('freelancer.tasks.todos', compact('offices', 'tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));

    }

    public function todos($todo)
    {

        $today = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->whereDate('deadline',  Carbon::today())->where('status', 0)->count();
        $tomorrow = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->whereDate('deadline', Carbon::tomorrow())->where('status', 0)->count();
        $overdue = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->where('deadline', '<', Carbon::today())->where('status', 0)->count();
        $week = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->where('deadline', '>', Carbon::now()->startOfWeek())->where('deadline', '<', Carbon::now()->endOfWeek())->where('status', 0)->count();


        $alltasks = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->whereMonth('deadline',  date('m'))->where('status', 0)->count();

        if ($todo == 'today') {
            $tasks = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->whereDate('deadline', Carbon::today())->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'overdue') {
            $tasks = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->where('deadline', '<', Carbon::today())->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'tomorrow') {
            $tasks = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->whereDate('deadline',  Carbon::tomorrow())->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } elseif ($todo == 'week') {
            $tasks = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->where('deadline', '>', Carbon::now()->startOfWeek())
                ->where('deadline', '<', Carbon::now()->endOfWeek())->where('status', 0)->orderBy('id', 'desc')->paginate(50);
        } else {
            $tasks = Task::where('added_by',  Auth::guard('affiliator')->user()->user_id)->whereMonth('deadline', date('m'))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
        }

        $users = User::where('id',  Auth::guard('affiliator')->user()->user_id)->Orwhere('parent', Auth::guard('affiliator')->user()->user_id)->orderBy('id', 'asc')->get();

        $offices = Offices::orderBy('id', 'ASC')->get();

        return view('freelancer.tasks.todos', compact('offices', 'tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }






}
