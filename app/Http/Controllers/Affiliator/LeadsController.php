<?php

namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use Helper;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\ShareLead;
use App\Models\TransferLead;
use App\Models\Category;
use App\Models\User;
use App\Models\Clients;
use App\Models\Task;
use App\Models\Product;
use App\Models\Project;
use App\Models\LeadNotes;
use App\Models\LeadPriority;
use App\Models\Affiliator;
use App\Models\Compain;
use App\Models\Notification;
use App\Models\Offices;
use Illuminate\Http\Request;
use Auth;
use Mail;
use DB;
use Carbon\Carbon;

class LeadsController extends Controller {

    public function index()
    {
        $leads = Leads::where('afflilate_id', Auth::guard('affiliator')->user()->id)
                        ->orderBy('updated_at', 'desc')
                        ->paginate(30);

        $sources = LeadSource::all();
        $projects = Project::orderBy('id', 'ASC')->get();
        $offices = Offices::orderBy('id', 'ASC')->get();
        $todo='';
        $find_result = 0;

        return view('freelancer.leads.index', compact('find_result','sources', 'leads', 'projects', 'offices' , 'todo'));
    }

    public function search(Request $request) {

        $condition = array();

        if ($request->input('lead_id') != '') {
            $condition[] = array('id', '=', $request->input('lead_id'));
        }

        if ($request->input('office_id') > 0) {
            $condition[] = array('office_id', '=', $request->input('office_id'));
        }

        if ($request->input('project_id') > 0) {
            $condition[] = array('project_id', $request->input('project_id'));
        }
        if ($request->input('source_id') > 0) {
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
        if ($request->input('phone') != '') {
            $phone = ltrim($request->input('phone'), "0");
            $clients = Clients::where('phone', 'Like', '%' . $phone . '%')->orWhere('telephone', 'Like', '%' . $phone . '%')->orWhere('telephone1', 'Like', '%' . $phone . '%')->pluck('id');
        }

        // Search phone number
        if ($request->input('phone') && $request->input('phone') != '')
        {

            $users = User::where('parent', Auth::guard('affiliator')->user()->user_id)->pluck('id');

            $leads = Leads::where($condition)->whereIn('user_id', $users)
                            ->orWhere('afflilate_id', Auth::guard('affiliator')->user()->id)
                            ->whereIn('client_id', $clients)->orderBy('id', 'DESC')
                            ->paginate(30);

        }
        else
        {
            if (!empty($condition))
            {
                $leads = Leads::where('afflilate_id',Auth::guard('affiliator')->user()->id)
                                ->where($condition)->orderBy('id', 'DESC')
                                ->paginate(30);
            }
            else
            {
                $users = User::where('id', Auth::guard('affiliator')->user()->user_id)
                             ->where('parent', Auth::guard('affiliator')->user()->user_id)->pluck('id');
                $leads = Leads::whereIn('user_id', $users)->orWhere('user_id', Auth::guard('affiliator')->user()->user_id)->orderBy('id', 'DESC')
                        ->paginate(30);
            }
        }

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $offices = Offices::orderBy('id', 'ASC')->get();
        $todo='';
        $find_result = 0;

        return view('freelancer.leads.index', compact('find_result','sources', 'leads', 'projects' , 'offices', 'todo'));
    }

    public function sorting(Request $request)
    {
        $status = $request->input('status') ? $request->input('status') : 'id';
        $order = $request->input('sort') ? $request->input('sort') : 'ASC';
        $record = $request->input('records') ? $request->input('records') : 30;
        $todo='';
        $find_result = 0;

        if ($record == 'all') {
            $all_leads = leads::all();
            $leads = Leads::where('afflilate_id', Auth::guard('affiliator')->user()->id)->orderBy($status, $order)->paginate(count($all_leads));
        } else {
            $leads = Leads::where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy($status, $order)->paginate($record);
        }

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $offices = Offices::orderBy('id', 'ASC')->get();

        return view('freelancer.leads.index', compact('find_result','sources', 'leads', 'projects', 'offices', 'todo'));
    }


    public function leads_sorting($todo)
    {
        if ($todo == 'today') {
            // today
            $leads = Leads::whereDate('created_at', Carbon::today())->where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'yesterday') {
            // yesterday
            $leads = Leads::whereDate('created_at', '=', Carbon::yesterday())->where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'thisweek') {
            // this week
            $leads = Leads::whereBetween('created_at', [Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()])->where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'lastweek') {
            // last week
            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight", $previous_week);
            $end_week = strtotime("next saturday", $start_week);
            $start_week = date("Y-m-d", $start_week);
            $end_week = date("Y-m-d", $end_week);
            $leads = Leads::whereBetween('created_at', [$start_week, $end_week])->where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'thismonth') {
            // this month
            $leads = Leads::whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))->where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->paginate(30);
        } else {
             // Last month
            $leads = Leads::
                        whereMonth('created_at', '<=', Carbon::now()->subMonth(1)->month)->
                        whereMonth('created_at', '>=', Carbon::now()->subMonth(2)->month)->
                        where('afflilate_id',Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->
                        paginate(30);
        }

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $offices = Offices::orderBy('id', 'ASC')->get();
        $find_result = 0;

        return view('freelancer.leads.index', compact('find_result', 'todo' , 'sources', 'leads', 'projects', 'offices'));
    }

    public function leadsImport()
    {
        $offices = Offices::orderBy('id', 'ASC')->get();
        return view('freelancer.leads.leads-import', compact('offices'));
    }

    public function leadsfileImport(Request $request)
    {

        $request->validate([
            'file' => 'required',
            'office_id' => 'required',
        ]);

        $path = $request->file('file')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (!count($records) > 0) {
            return 'Error...';
        }

        // Get field names from header column
        $fields = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
        array_shift($records);

        foreach ($records as $record) {
            if (count($fields) == count($record)) {

                // Decode unwanted html entities
                $record = array_map("html_entity_decode", $record);

                // Set the field name as key
                $record = array_combine($fields, $record);

                // Get the clean data
                $this->rows[] = $record; //$this->clear_encoding_str($record);
            }
        }

        $counter = 1;
        foreach ($this->rows as $data) {

            $campaign = Compain::where('form_id', $data['formid'])->first();

            $client = Clients::where('phone', $data['phonenumber'])->orWhere('email', $data['email'])->first();

            if (empty($client) && !empty($campaign)) {
                $client = Clients::firstOrCreate(
                    [
                        'name' => $data['fullname'],
                        'email' => $data['email'],
                        'phone' => $data['phonenumber'],
                        'source_id' => 3,
                        'office_id' => $campaign['office_id'],
                    ],
                    [
                        'email' => $data['email'],
                        'phone' => $data['phonenumber']
                    ]
                );
                Leads::create(['client_id' => $client->id, 'project_id' => $campaign['project_id'], 'source_id' => 3, 'office_id' => $campaign['office_id']]);
                $counter++;
            } else {
                if (!empty($client) && !empty($campaign)) {
                    $leads = Leads::where('client_id', $client['id'])->where('project_id', $campaign['project_id'])->get();

                    if (count($leads) < 1) {

                        Leads::create(['client_id' => $client['id'], 'project_id' => $campaign['project_id'], 'source_id' => 3, 'user_id' => $client['user_id'], 'office_id' => $campaign['office_id']]);
                        $counter++;
                    }
                }
            }
        }


        return redirect('affiliator/leads')->withStatus(__('Leads Added Successfully.'));
    }


}
