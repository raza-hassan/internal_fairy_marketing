<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\User;
use App\Models\Project;
use App\Models\Affiliator;
use App\Models\AffiliatorTask;
use App\Models\Offices;
use App\Models\Task;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use Carbon\Carbon;
use Session;

class ReportController extends Controller
{

    public function index()
    {
        $leads = Leads::all()->sortByDesc("id");

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
            $account = $responce['account'];
        // ===== Users With Helper=====

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        return view('completereport', compact('leads', 'users', 'account', 'projects', 'sources'));
    }

    public function assign()
    {
        $leads = Leads::where('user_id', '>', 0)->orderBy('id', 'desc')
            ->get();

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
            $account = $responce['account'];
        // ===== Users With Helper=====

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        return view('completereport', compact('leads', 'users', 'account', 'projects', 'sources'));
    }

    public function noassign()
    {
        $leads = Leads::where('user_id', 0)->orderBy('id', 'desc')
            ->paginate(30);

        $sources = LeadSource::all();
        return view('no-assign-report', compact('leads', 'sources'));
    }

    public function noassignaffiliator()
    {
        $affiliators = Affiliator::where('status', 0)->orderBy('id', 'desc')
            ->paginate(30);

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
        // ===== Users With Helper=====

        return view('affiliators.index', compact('affiliators', 'users'));
    }

    public function overduetask()
    {
        $tasks = Task::where('deadline', '<', date('Y-m-d'))->where('status', 0)->where('lead_id', '>', 0)->paginate(30);

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
            $account = $responce['account'];
        // ===== Users With Helper=====

        return view('todos', compact('tasks', 'users', 'account'));
    }

    public function overdueafftask()
    {
        $tasks = AffiliatorTask::where('deadline', '<', date('Y-m-d'))->where('status', 0)->paginate(30);

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ===== Users With Helper=====

        return view('affiliators.afftodos', compact('tasks', 'users'));
    }

    public function usertask(Request $request)
    {
        $condition = array();
        $userid = array();

        if ($request->input('user_id') > 0) {
            $users[] = $request->input('user_id');
        } else {
            // ===== Users With Helper=====
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users']->where('office_id', 1)->pluck('id');
            // ===== Users With Helper=====
        }

        $condition[] = array('status', 0);

        if ($request->tasktype > 0) {
            $condition[] = array('ntype', $request->tasktype);
        }

        if ($request->has('user_id') && $request->input('user_id') > 0)
        {
            $ids[] = $request->input('user_id');
        }
        elseif ($request->has('user_id') && $request->input('user_id') == 0)
        {
            // ===== Users With Helper=====
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $ids = $responce['users']->where('office_id', 1)->pluck('id');
             // ===== Users With Helper=====
        }
        else {
            $ids[] = Auth::user()->id;
        }

        if($request->has('task'))
        {
            if ($request->task == 'overdue') {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', '<', Carbon::today())->orderBy('id', 'desc')->paginate(50);

                $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                            ->where($condition)
                            ->where('leads.is_delete', 0)
                            ->whereIn('task.added_by', $users)
                            ->whereDate('task.deadline', '<', Carbon::today())
                            ->orderBy('task.id', 'desc')
                            ->select('task.*')
                            ->paginate(50);
            }

            if ($request->task == 'tomorrow') {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', Carbon::tomorrow())->orderBy('id', 'desc')->paginate(50);

                $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                            ->where($condition)
                            ->where('leads.is_delete', 0)
                            ->whereIn('task.added_by', $users)
                            ->whereDate('task.deadline', Carbon::tomorrow())
                            ->orderBy('task.id', 'desc')
                            ->select('task.*')
                            ->paginate(50);
            }

            if ($request->task == 'today') {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', Carbon::today())->orderBy('id', 'desc')->paginate(50);

                $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                            ->where($condition)
                            ->where('leads.is_delete', 0)
                            ->whereIn('task.added_by', $users)
                            ->whereDate('task.deadline',Carbon::today())
                            ->orderBy('task.id', 'desc')
                            ->select('task.*')
                            ->paginate(50);
            }

            if ($request->task == 'week') {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->where('deadline', '>', Carbon::now()->startOfWeek())
                //     ->where('deadline', '<', Carbon::now()->endOfWeek())->orderBy('id', 'desc')->paginate(50);

                $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                            ->where($condition)
                            ->where('leads.is_delete', 0)
                            ->whereIn('task.added_by', $users)
                            ->where('task.deadline', '>', Carbon::now()->startOfWeek())
                            ->where('task.deadline', '<', Carbon::now()->endOfWeek())
                            ->orderBy('task.id', 'desc')
                            ->select('task.*')
                            ->paginate(50);

                            // echo "<pre>"; print_r($tasks) ; exit;

            }

            if ($request->task == 'month') {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->whereMonth('deadline', date('m'))->orderBy('id', 'desc')->paginate(50);

                $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                            ->where($condition)
                            ->where('leads.is_delete', 0)
                            ->whereIn('task.added_by', $users)
                            ->whereMonth('task.deadline', date('m'))
                            ->whereYear('task.deadline', date('Y'))
                            ->orderBy('task.id', 'desc')
                            ->select('task.*')
                            ->paginate(50);

            }
            // echo $request->task; exit;
            if ($request->task == 0) {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->whereMonth('deadline', date('m'))->orderBy('id', 'desc')->paginate(50);

                $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                            ->where($condition)
                            ->where('leads.is_delete', 0)
                            ->whereIn('task.added_by', $users)
                            ->whereMonth('task.deadline', date('m'))
                            ->whereYear('task.deadline', date('Y'))
                            ->orderBy('task.id', 'desc')
                            ->select('task.*')
                            ->paginate(50);
            }
        }
        else
        {
            $tasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
            ->where($condition)
            ->where('leads.is_delete', 0)
            // ->where('task.added_by', Auth::user()->id)
            ->whereIn('task.added_by', $ids)
            ->whereDate('task.deadline', '<',Carbon::today())
            ->orderBy('task.id', 'desc')
            ->select('task.*')
            ->paginate(50);
        }

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ===== Users With Helper=====


        // $ids[] = $request->input('user_id') ? $request->input('user_id') : Auth::user()->id;
        $today=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                ->where('leads.is_delete', 0)
                ->where($condition)
                ->whereIn('task.added_by', $ids)
                ->whereDate('task.deadline',Carbon::today())
                // ->where('task.status', 0)
                ->count();

        $tomorrow=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                ->where('leads.is_delete', 0)
                ->where($condition)
                ->whereIn('task.added_by', $ids)
                ->whereDate('task.deadline',Carbon::tomorrow())
                // ->where('task.status', 0)
                ->count();

        $overdue=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                ->where('leads.is_delete', 0)
                ->where($condition)
                ->whereIn('task.added_by', $ids)
                ->whereDate('task.deadline', '<',Carbon::today())
                // ->where('task.status', 0)
                ->count();

        $week=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                    ->where('leads.is_delete', 0)
                    ->where($condition)
                    ->whereIn('task.added_by', $ids)
                    ->where('task.deadline', '>', Carbon::now()->startOfWeek())
                    ->where('task.deadline', '<', Carbon::now()->endOfWeek())
                    // ->where('task.status', 0)
                    ->count();

        $alltasks=Task::with('addedBy')->join('leads', 'leads.id', '=', 'task.lead_id')
                    ->where('leads.is_delete', 0)
                    ->where($condition)
                    ->whereIn('task.added_by', $ids)
                    ->whereMonth('task.deadline', date('m'))
                    ->whereYear('task.deadline', date('Y'))
                    // ->where('task.status', 0)
                    ->count();
        // echo "<pre>"; print_r($tasks) ; exit;

        $todo = $request->task;
        $offices = Offices::orderBy('id', 'ASC')->get();

	    return view('tasks.todos', compact('offices', 'tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

    public function notassigntask(Request $request)
    {
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

    public function search(Request $request)
    {
        $condition = array();
        $userid = 0;

        //echo Auth::user()->id;
        if ($request->input('account') == 'user') {
            if ($request->input('user_id') == 0) {
                //                echo 'user'; exit;
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

        // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
        // ===== Users With Helper=====

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

    public function reports()
    {
        // ===== Users With Helper=====
            $userdata = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($userdata);
            $users = $responce['users'];
        // ===== Users With Helper=====

        foreach($users as $user)
        {
            $data[$user->id] = array(
                'call_attempt' => Task::where('added_by', $user->id)->where('subtype','Call Attempt')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                'followed_up' => Task::where('added_by', $user->id)->where('subtype','Followed Up')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                'contacted_client' => Task::where('added_by', $user->id)->where('subtype','Contacted Client')->whereDate('completion_date', '<=',  Carbon::now())->count(),

                'meeting_done' => Task::where('added_by', $user->id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                'meeting_arranged' => Task::where('added_by', $user->id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                'meeting_attempt' => Task::where('added_by', $user->id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                'meeting_pushed' => Task::where('added_by', $user->id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                'whatsapp_call' => Task::where('added_by', $user->id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '<=',  Carbon::now())->count(),
            );
        }
        // echo '<pre>'; print_r($data);exit;
        return view('special_users', compact('users' , 'data'));
    }

    public function userreports(Request $request)
    {
        // date_default_timezone_set('Asia/Karachi');
        // echo '<pre>'; print_r($request->all());exit;
        // echo "<pre>";print_r(now()->subYear(1));exit;



        $userid = $request->user_id;

        if($userid > 0){
            $user_ids = User::where('id', $userid)->orderBy('id', 'asc')->pluck('id');
        }
        else{
            $user_ids = User::where('office_id', 1)->orderBy('id', 'asc')->pluck('id');
        }

        $userdata = array(
            'id' => Auth::user()->id,
            'role' => Auth::user()->role,
        );
        $responce = Helper::users($userdata);
        $users = $responce['users'];

        $data = array();

        if ($request->task == 'custom_date' && $request->custom_date_start != ''  && $request->custom_date_end != '')
        {

            $startDate= date("Y-m-d", strtotime($request->custom_date_start)). ' 00:00:00';
            $endDate =  date("Y-m-d", strtotime($request->custom_date_end)) . ' 23:59:59';

            $start = strtotime($startDate);
            $end = strtotime($endDate);

            if($start > $end) // if user enter start_Date into end_Date and end_Date into Start_Date
            {
                return redirect('/')->withErrors(__('Wrong Selection Of Date. Start Date Will Not Be Grater Than End Date.!'));
            }
            else
            {
                // echo "chexk";
                foreach($user_ids as $user_id){
                    $data[$user_id] = [

                        //All (All+Call)
                        'followed_up'       => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'contacted_client'  => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'meeting_done'      => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'meeting_arranged'  => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'meeting_attempt'   => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'meeting_pushed'    => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),

                        // Calls
                        'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),

                        // Meetings
                        'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),

                        // Sales
                        'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),

                        // Affiliators
                        'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),
                        'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', $startDate)->whereDate('completion_date', '<=', $endDate)->count(),

                        // Dealer
                        'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', $startDate)
                                        ->whereDate('affiliatortask.completion_date', '<=', $endDate)
                                        ->where('affiliators.type', '=', 'Dealer')
                                        ->count(),

                        'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', $startDate)
                                        ->whereDate('affiliatortask.completion_date', '<=', $endDate)
                                        ->where('affiliators.type', '=', 'Dealer')
                                        ->count(),

                        'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                    ->where('affiliatortask.user_id', $user_id)
                                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                                    ->whereDate('affiliatortask.completion_date', '>=', $startDate)
                                                    ->whereDate('affiliatortask.completion_date', '<=', $endDate)
                                                    ->where('affiliators.type', '=', 'Freelancer')
                                                    ->count(),

                        'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                    ->where('affiliatortask.user_id', $user_id)
                                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                    ->whereDate('affiliatortask.completion_date', '>=', $startDate)
                                                    ->whereDate('affiliatortask.completion_date', '<=', $endDate)
                                                    ->where('affiliators.type', '=', 'Freelancer')
                                                    ->count(),
                    ];
                }
            }
        }

        if ($request->task == 'today'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    //All (All+Call)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', Carbon::today())->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', Carbon::today())->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', Carbon::today())->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', Carbon::today())->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', Carbon::today())->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', Carbon::today())->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', Carbon::today())->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', Carbon::today())->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', Carbon::today())->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', Carbon::today())->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', Carbon::today())->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', Carbon::today())->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', Carbon::today())->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', Carbon::today())->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', Carbon::today())->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', Carbon::today())->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', Carbon::today())->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', Carbon::today())->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', Carbon::today())->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $user_id)
                                                ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                                ->whereDate('affiliatortask.completion_date', Carbon::today())
                                                ->where('affiliators.type', '=', 'Dealer')
                                                ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                                ->where('affiliatortask.user_id', $user_id)
                                                ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                                ->whereDate('affiliatortask.completion_date', Carbon::today())
                                                ->where('affiliators.type', '=', 'Dealer')
                                                ->count(),

                    // // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', Carbon::today())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', Carbon::today())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                ];
            }
        }
        if ($request->task == 'yesterday') {
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    //All (All+Call)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', Carbon::yesterday())->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', Carbon::yesterday())->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', Carbon::yesterday())->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', Carbon::yesterday())->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', Carbon::yesterday())->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', Carbon::yesterday())->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', Carbon::yesterday())
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', Carbon::yesterday())
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', Carbon::yesterday())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', Carbon::yesterday())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                ];
            }
        }
        if ($request->task == 'week'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(7))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(7))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(7))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(7))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                ];
            }
        }
        if ($request->task == 'half_month'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(15))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(15))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(15))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(15))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                ];
            }
        }
        if ($request->task == 'month'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(30))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(30))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(30))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(30))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                ];
            }
        }
        if ($request->task == 'three_month'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(90))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(90))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(90))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(90))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                ];
            }
        }
        if ($request->task == 'six_month'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(180))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(180))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(180))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', Carbon::now()->subdays(180))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                ];
            }
        }
        if ($request->task == 'year'){
            foreach($user_ids as $user_id){
                $data[$user_id] = [

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '>=', now()->subYear(1))->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '>=', now()->subYear(1))->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '>=', now()->subYear(1))->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '>=', now()->subYear(1))->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '>=', now()->subYear(1))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '>=', now()->subYear(1))
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '>=', now()->subYear(1))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '>=', now()->subYear(1))
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                ];
            }
        }
        if ($request->task == 'all') {
            // echo 'sfsdfas'; exit;
            foreach($user_ids as $user_id){
                $data[$user_id] = array(

                    // All (All+Calls)
                    'followed_up' => Task::where('added_by', $user_id)->where('subtype','Followed Up')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'contacted_client' => Task::where('added_by', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'meeting_done' => Task::where('added_by', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'meeting_arranged' => Task::where('added_by', $user_id)->where('subtype','Meeting (Arranged)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'meeting_attempt' => Task::where('added_by', $user_id)->where('subtype','Meeting (Attempt)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'meeting_pushed' => Task::where('added_by', $user_id)->where('subtype','Meeting (Pushed)')->whereDate('completion_date', '<=',  Carbon::now())->count(),

                    // Calls
                    'call_attempt' => Task::where('added_by', $user_id)->where('subtype','Call Attempt')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'whatsapp_call' => Task::where('added_by', $user_id)->where('subtype','Whatsapp Call')->whereDate('completion_date', '<=',  Carbon::now())->count(),

                    // Meetings
                    'meeting_confirmed' => Task::where('added_by', $user_id)->where('subtype','Meeting Confirmed')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'meeting_cancelled' => Task::where('added_by', $user_id)->where('subtype','Meeting Cancelled')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'meeting_postponed' => Task::where('added_by', $user_id)->where('subtype','Meeting Postponed')->whereDate('completion_date', '<=',  Carbon::now())->count(),

                    // Sales
                    'token_payment' => Task::where('added_by', $user_id)->where('subtype','Token Payment')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'complete_down_payment' => Task::where('added_by', $user_id)->where('subtype','Complete Down Payment')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'booking_form' => Task::where('added_by', $user_id)->where('subtype','Booking Form')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'closed_won' => Task::where('added_by', $user_id)->where('subtype','Closed (Won)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'closed_lost' => Task::where('added_by', $user_id)->where('subtype','Closed (Lost)')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'closed_cancelled' => Task::where('added_by', $user_id)->where('subtype','Closed (Cancelled)')->whereDate('completion_date', '<=',  Carbon::now())->count(),

                    // Affiliators
                    'affiliator_contacted_client' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Contacted Client')->whereDate('completion_date', '<=',  Carbon::now())->count(),
                    'affiliator_meeting_done' => AffiliatorTask::where('user_id', $user_id)->where('subtype','Meeting (Done)')->whereDate('completion_date', '<=',  Carbon::now())->count(),

                    // Dealer
                    'dealer_contacted_client' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                    ->whereDate('affiliatortask.completion_date', '<=',  Carbon::now())
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    'dealer_meeting_done' => AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                    ->where('affiliatortask.user_id', $user_id)
                                    ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                    ->whereDate('affiliatortask.completion_date', '<=',  Carbon::now())
                                    ->where('affiliators.type', '=', 'Dealer')
                                    ->count(),

                    // Freelancer
                    'freelancer_contacted_client'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Contacted Client')
                                        ->whereDate('affiliatortask.completion_date', '<=',  Carbon::now())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),

                    'freelancer_meeting_done'=>AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                        ->where('affiliatortask.user_id', $user_id)
                                        ->where('affiliatortask.subtype', '=', 'Meeting (Done)')
                                        ->whereDate('affiliatortask.completion_date', '<=',  Carbon::now())
                                        ->where('affiliators.type', '=', 'Freelancer')
                                        ->count(),
                );
            }
        }

        if ($request->type == 0)
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                // return view('report_type.invoices.all_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.all_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }
            return view('special_users', compact( 'data', 'users'));
        }
        elseif ($request->type == 'Calls')
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                return view('report_type.invoices.call_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.call_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }
            return view('report_type.calls', compact( 'data', 'users'));
        }
        elseif ($request->type == 'Meetings')
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                // return view('report_type.invoices.meetings_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.meetings_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }
            return view('report_type.meetings', compact( 'data', 'users'));
        }
        elseif ($request->type == 'Sales')
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                // return view('report_type.invoices.sales_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.sales_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }
            return view('report_type.sales', compact( 'data', 'users'));
        }
        elseif ($request->type == 'Affiliators')
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                // return view('report_type.invoices.affiliators_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.affiliators_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }
            return view('report_type.affiliators', compact( 'data', 'users'));
        }
        elseif ($request->type == 'Dealers')
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                // return view('report_type.invoices.dealers_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.dealers_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }
            return view('report_type.dealers', compact( 'data', 'users'));
        }
        elseif ($request->type == 'Freelancers')
        {
            if($_GET['action'] == 'PDF')
            {
                // echo 'download pdf';exit;
                // return view('report_type.invoices.freelancers_type_invoice',compact('data','users'));exit;
                $pdf = Pdf::loadView('report_type.invoices.freelancers_type_invoice', compact('data','users'))
                        ->setPaper('a4')
                        ->setOption([
                            'tempDir' => public_path(),
                            'chroot' => public_path(),
                            'isPhpEnabled' => true,
                        ]);
                return $pdf->download('Invoice.pdf');
            }

            return view('report_type.freelancers', compact( 'data', 'users'));
        }else{
            return back()->with('error' , 'Page Does Not Exist.');
        }
    }

    public function report_info(Request $request,$id,$task,$subtype)
    {
        // print_r($id); echo "<br>";
        // print_r($task); echo "<br>";
        // print_r($subtype);exit;

        $startDate  = date("Y-m-d", strtotime(Session::get('custom_date_start_session'))). ' 00:00:00';
        $endDate    = date("Y-m-d", strtotime(Session::get('custom_date_end_session'))). ' 23:59:59';


        if ($task == 'custom_date')
        {
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereBetween('completion_date', [$startDate , $endDate])->orderBy('id', 'desc')->paginate(30);
        }

        if ($task == 'all')
        {
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '<=',  Carbon::now())->orderBy('id', 'desc')->paginate(30);
        }

        if ($task == 'today'){
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', Carbon::today())->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'yesterday') {
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', Carbon::yesterday())->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'week'){
            // $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->where('completion_date', '>', Carbon::now()->startOfWeek())->where('completion_date', '<', Carbon::now()->endOfWeek())->orderBy('id', 'desc')->paginate(30);
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'half_month'){
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'month'){
            // $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereMonth('completion_date', date('m'))->orderBy('id', 'desc')->paginate(30);
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'three_month'){
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'six_month'){
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'year'){
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '>=', date('Y'))->orderBy('id', 'desc')->paginate(30);
        }
        if ($task == 'all') {
            $all_tasks = Task::where('added_by', $id)->where('subtype', $subtype)->whereDate('completion_date', '<=',  Carbon::now())->orderBy('id', 'desc')->paginate(30);
        }

        return view('special_users_info', compact( 'all_tasks'));
    }

    public function affiliators_report_info(Request $request,$report_of,$id,$task,$subtype)
    {
        $startDate  = date("Y-m-d", strtotime(Session::get('custom_date_start_session'))). ' 00:00:00';
        $endDate    = date("Y-m-d", strtotime(Session::get('custom_date_end_session'))). ' 23:59:59';

        // Affiliators
        if($report_of == 'affiliator')
        {
            if ($task == 'custom_date')
            {
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereBetween('completion_date', [$startDate , $endDate])->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'all')
            {
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '<=',  Carbon::now())->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'today'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', Carbon::today())->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'yesterday') {
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', Carbon::yesterday())->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'week'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(7))->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'half_month'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(15))->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'month'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(30))->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'three_month'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(90))->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'six_month'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '>=', Carbon::now()->subdays(180))->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'year'){
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '>=', date('Y'))->orderBy('id', 'desc')->paginate(30);
            }
            if ($task == 'all') {
                $all_tasks = AffiliatorTask::where('user_id', $id)->where('subtype',$subtype)->whereDate('completion_date', '<=', Carbon::now())->orderBy('id', 'desc')->paginate(30);
            }
        }

        // Dealer
        if($report_of == 'dealer')
        {
            if ($task == 'custom_date')
            {
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where('affiliatortask.user_id', $id)
                            ->where('affiliatortask.subtype', $subtype)
                            ->where('affiliators.type', '=', 'Dealer')
                            ->whereBetween('affiliatortask.completion_date', [$startDate , $endDate])
                            ->orderBy('affiliatortask.id', 'desc')
                            ->paginate(30);            }

            if ($task == 'all')
            {
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where('affiliatortask.user_id', $id)
                            ->where('affiliatortask.subtype', $subtype)
                            ->where('affiliators.type', '=', 'Dealer')
                            ->whereDate('affiliatortask.completion_date', '<=',  Carbon::now())
                            ->orderBy('affiliatortask.id', 'desc')
                            ->paginate(30);
            }
            if ($task == 'today'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where('affiliatortask.user_id', $id)
                            ->where('affiliatortask.subtype', $subtype)
                            ->where('affiliators.type', '=', 'Dealer')
                            ->whereDate('affiliatortask.completion_date',  Carbon::today())
                            ->orderBy('affiliatortask.id', 'desc')
                            ->paginate(30);
            }
            if ($task == 'yesterday') {

                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where('affiliatortask.user_id', $id)
                            ->where('affiliatortask.subtype', $subtype)
                            ->where('affiliators.type', '=', 'Dealer')
                            ->whereDate('affiliatortask.completion_date', Carbon::yesterday())
                            ->orderBy('affiliatortask.id', 'desc')
                            ->paginate(30);
            }
            if ($task == 'week'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(7))
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
            if ($task == 'half_month'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(15))
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
            if ($task == 'month'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(30))
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
            if ($task == 'three_month'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(90))
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
            if ($task == 'six_month'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(180))
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
            if ($task == 'year'){
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '>=', date('Y'))
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
            if ($task == 'all') {
                $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                            ->where('affiliatortask.user_id', $id)
                                            ->where('affiliatortask.subtype', $subtype)
                                            ->where('affiliators.type', '=', 'Dealer')
                                            ->whereDate('affiliatortask.completion_date',  '<=', Carbon::now())
                                            ->orderBy('affiliatortask.id', 'desc')
                                            ->paginate(30);
            }
        }

         // Freelancer
         if($report_of == 'freelancer')
         {
             if ($task == 'custom_date')
             {
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                             ->where('affiliatortask.user_id', $id)
                             ->where('affiliatortask.subtype', $subtype)
                             ->where('affiliators.type', '=', 'Freelancer')
                             ->whereBetween('affiliatortask.completion_date', [$startDate , $endDate])
                             ->orderBy('affiliatortask.id', 'desc')
                             ->paginate(30);            }

             if ($task == 'all')
             {
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                             ->where('affiliatortask.user_id', $id)
                             ->where('affiliatortask.subtype', $subtype)
                             ->where('affiliators.type', '=', 'Freelancer')
                             ->whereDate('affiliatortask.completion_date', '<=',  Carbon::now())
                             ->orderBy('affiliatortask.id', 'desc')
                             ->paginate(30);
             }
             if ($task == 'today'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                             ->where('affiliatortask.user_id', $id)
                             ->where('affiliatortask.subtype', $subtype)
                             ->where('affiliators.type', '=', 'Freelancer')
                             ->whereDate('affiliatortask.completion_date',  Carbon::today())
                             ->orderBy('affiliatortask.id', 'desc')
                             ->paginate(30);
             }
             if ($task == 'yesterday') {

                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                             ->where('affiliatortask.user_id', $id)
                             ->where('affiliatortask.subtype', $subtype)
                             ->where('affiliators.type', '=', 'Freelancer')
                             ->whereDate('affiliatortask.completion_date', Carbon::yesterday())
                             ->orderBy('affiliatortask.id', 'desc')
                             ->paginate(30);
             }
             if ($task == 'week'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(7))
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
             if ($task == 'half_month'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(15))
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
             if ($task == 'month'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(30))
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
             if ($task == 'three_month'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(90))
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
             if ($task == 'six_month'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '>=', Carbon::now()->subdays(180))
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
             if ($task == 'year'){
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '>=', date('Y'))
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
             if ($task == 'all') {
                 $all_tasks  =AffiliatorTask::join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                                             ->where('affiliatortask.user_id', $id)
                                             ->where('affiliatortask.subtype', $subtype)
                                             ->where('affiliators.type', '=', 'Freelancer')
                                             ->whereDate('affiliatortask.completion_date',  '<=', Carbon::now())
                                             ->orderBy('affiliatortask.id', 'desc')
                                             ->paginate(30);
             }
         }
        // echo "<pre>"; print_r($all_tasks); exit;

        return view('affiliator_users_info', compact( 'all_tasks'));
    }

}
