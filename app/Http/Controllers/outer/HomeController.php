<?php

namespace App\Http\Controllers\outer;

use App\Http\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\Affiliator;
use App\Models\User;
use App\Models\Clients;
use App\Models\Project;
use App\Models\LeadSource;
use App\Models\Task;
use Auth;
use Mail;
use DB;
use Carbon\Carbon;

class HomeController extends Controller {


    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $leads_count = Leads::where('user_id', Auth::user()->id)->count();
        $clients_count = Clients::where('user_id', Auth::user()->id)->count();

        $todayleads = Leads::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $todayclients = Clients::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $tasks = Task::where('added_by', Auth::user()->id)->where('deadline', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->limit(30)->get();

        $affiliator_count = Affiliator::where('user_id', Auth::user()->id)->count();
        $aaffiliator_count = Affiliator::where('user_id', Auth::user()->id)->where('status', 1)->count();

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
            $account = $responce['account'];
        // ====== Users With Helper======

        return view('home', compact('leads_count', 'clients_count', 'affiliator_count', 'aaffiliator_count', 'users', 'todayleads', 'todayclients', 'projects', 'sources', 'account','tasks'));
    }

    public function search(Request $request) {

        $condition = array();
        $userid = 0;
        if ($request->input('lead_id') != '') {
            $condition[] = array('id', '=', $request->input('lead_id'));
        }
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

        if ($request->input('startDate') != '' && $request->input('endDate') != '') {
            $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
            $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:00');
        }

        if ($request->input('startDate') != '' && $request->input('endDate') == '') {
            $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
        }


        if ($request->input('startDate') == '' && $request->input('endDate') != '') {
            $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:00');
        }

        if ($request->input('status') == 1 && $request->input('status') != '') {
            $condition[] = array('user_id', '>', 0);
        }elseif($request->input('status') == 0 && $request->input('status') != ''){
            $condition[] = array('user_id', 0);
        }else{
            $condition[] = array();
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
        return view('reports', compact('sources', 'leads', 'users', 'projects', 'account', 'assigned_leads','userid'));
    }

}
