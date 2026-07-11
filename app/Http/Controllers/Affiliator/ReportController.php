<?php

namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use App\Models\Affiliator;
use App\Models\User;
use App\Models\Offices;
use App\Models\Task;
use Illuminate\Http\Request;
use Auth;

use Carbon\Carbon;


class ReportController extends Controller
{


    public function usertask(Request $request)
    {

        $condition = array();
        $userid = array();

        $users = User::where('id', Auth::guard('affiliator')->user()->user_id)->pluck('id');


        $condition[] = array('status', 0);

        if ($request->tasktype > 0) {
            $condition[] = array('ntype', $request->tasktype);
        }

        if ($request->task == 'overdue') {
            $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', '<', Carbon::today())->orderBy('id', 'desc')->paginate(50);
        }

        if ($request->task == 'tomorrow') {
            $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', Carbon::tomorrow())->orderBy('id', 'desc')->paginate(50);
        }

        if ($request->task == 'today') {
            $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', Carbon::today())->orderBy('id', 'desc')->paginate(50);
        }

        if ($request->task == 'week') {
            $tasks = Task::where($condition)->whereIn('added_by', $users)->where('deadline', '>', Carbon::now()->startOfWeek())
                         ->where('deadline', '<', Carbon::now()->endOfWeek())->orderBy('id', 'desc')->paginate(50);
        }

        if ($request->task == 'month') {
            $tasks = Task::where($condition)->whereIn('added_by', $users)->whereMonth('deadline', date('m'))->orderBy('id', 'desc')->paginate(50);
        }

        if ($request->task == 0) {
            $tasks = Task::where($condition)->whereIn('added_by', $users)->whereMonth('deadline', date('m'))->orderBy('id', 'desc')->paginate(50);
        }

        $users = User::where('id', Auth::guard('affiliator')->user()->user_id)->Orwhere('parent', Auth::guard('affiliator')->user()->user_id)->orderBy('id', 'asc')->get();


        // ========================================================================================
        $today = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->whereDate('deadline',  Carbon::today())->where('status', 0)->count();
        $tomorrow = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->whereDate('deadline', Carbon::tomorrow())->where('status', 0)->count();
        $overdue = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->where('deadline', '<', Carbon::today())->where('status', 0)->count();
        $week = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->where('deadline', '>', Carbon::now()->startOfWeek())->where('deadline', '<', Carbon::now()->endOfWeek())->where('status', 0)->count();

        $alltasks = Task::where('added_by', Auth::guard('affiliator')->user()->user_id)->whereMonth('deadline',  date('m'))->where('status', 0)->count();
        $todo = '';

        $offices = Offices::orderBy('id', 'ASC')->get();
        return view('freelancer.tasks.todos', compact('offices', 'tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));

    }










}
