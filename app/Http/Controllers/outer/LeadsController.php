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
use App\Models\Task;
use App\Models\Product;
use App\Models\Project;
use App\Models\LeadNotes;
use App\Models\LeadPriority;
use App\Models\Affiliator;
use App\Models\Number;
use Illuminate\Http\Request;
use Auth;
use Mail;
use DB;
use Carbon\Carbon;

class LeadsController extends Controller {

    public function index() {
        $leads = Leads::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);

        $sources = LeadSource::all();

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        $projects = Project::orderBy('id', 'ASC')->get();
        $search_person = 'user';
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

    public function websiteleads(Request $request) {

        $record = Clients::where('phone', '+92' . $request->phone)->first();
        if ($record == '') {
            $validated = $request->validate([
                'phone' => 'required|unique:clients',
                'name' => 'required',
            ]);
            $client = Clients::firstOrCreate(
                            [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => '+92' . $request->phone,
                        'user_id' => 0,
                        'source_id' => 14,
                            ], [
                        'email' => $request->email,
                        'phone' => '+92' . $request->phone
                            ]
            );
            $lead = Leads::create($request->merge(['client_id' => $client->id])->all());

            $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
            $to_name = 'Website';

            $data = array('name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user' => 0,
                'source' => 'Website',
                'notes' => $request->message
            );

            Mail::send('leads.weblead-mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                        ('Lead Create Notification');
                $message->from(config('mail.from.address'), Config('mail.from.name'));
            });
            return response()->json([
                        "message" => $lead->id . " Lead created successfully"
                            ], 200);
        } else {
            $lead = Leads::create($request->merge(['client_id' => $record->id])->all());

            $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
            $to_name = 'Website';

            $data = array('name' => $record->name,
                'email' => $record->email,
                'phone' => $record->phone,
                'user' => 0,
                'source' => 'Website',
                'notes' => $request->message
            );

            Mail::send('leads.weblead-mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                        ('Duplicate Lead Create Notification');
                $message->from(config('mail.from.address'), Config('mail.from.name'));
            });
            return response()->json([
                        "message" => $lead->id . " Lead Not created"
                            ], 200);
        }
    }

    public function All_Leads(Request $request, $id) {
        $search_person = 'manager';
        $leads = Leads::where('user_id', $id)->orWhere('share_id', $id)->orderBy('id', 'desc')->paginate(30);
        $sources = LeadSource::all();
        $users = User::where('role', '!=', 0)->get();
        $projects = Project::orderBy('id', 'ASC')->get();
        $find_result = 0;
        return view('leads.index', compact('find_result','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

    public function shareleads() {
        $search_person = 'user';
        $leads = Leads::where('share_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        $sources = LeadSource::all();
        $users = User::where('role', '!=', 0)->get();
        $projects = Project::orderBy('id', 'ASC')->get();
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

    public function clientLeads($id) {
        $leads = Leads::where('client_id', $id)->orderBy('id', 'desc')->paginate(30);
        $sources = LeadSource::all();
        $users = User::where('role', '!=', 0)->get();
        $projects = Project::orderBy('id', 'ASC')->get();
        $search_person = 'user';
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

    public function share_leads(Request $request) {
        ShareLead::create($request->all());
        return back()->withStatus(__('Leads Shared Successfully.'));
        ;
    }

    public function assigned(Request $request) {
        if (!empty($request->input('leadids')) && $request->input('user_id') != '') {
            $array_of_ids = $request->input('leadids');
            $client_array = DB::table('leads')->whereIn('id', $array_of_ids)->groupBy('client_id')->pluck('client_id');
            DB::table('leads')->whereIn('id', $array_of_ids)->update(array('user_id' => $request->input('user_id')));
            DB::table('clients')->whereIn('id', $client_array)->update(array('user_id' => $request->input('user_id')));

            return redirect('newleads')->withStatus(__('Leads Assigned Successfully.'));
        } else {
            return redirect('newleads')->withErrors(__('Please select leads and user!'));
        }
    }

    public function facebookleads() {
        $leads = Leads::where('user_id', '')->orderBy('id', 'desc')->get();
        $users = User::where('role', '!=', 0)->get();
        return view('leads.facebookleads', compact('leads', 'users'));
    }

    public function create()
    {
        if (Auth::user()->role == 4 || Auth::user()->role == 1)
        {
            $sources = LeadSource::orderBy('id', 'desc')->get();
        }
        else
        {
            $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();
        }

        $categories = Category::all();
        $priority = LeadPriority::all();
        $projects = Project::all();
        return view('leads.create', compact('sources', 'categories', 'projects', 'priority'));
    }

    public function getaffiliators(Request $request) {
        $html = '';
        $source = '';
        $affiliators = array();
        if ($request->input('source') == 12) {
            $source = 'Dealer';
            $affiliators = Affiliator::where('user_id', Auth::user()->id)->where('status', 1)->where('type', 'Dealer')->get();
        } else {
            $source = 'Freelancer';
            $affiliators = Affiliator::where('user_id', Auth::user()->id)->where('status', 1)->where('type', 'Freelancer')->get();
        }
        if (count($affiliators)) {
            $html .='<label>Select ' . $source . '</label>
                    <select name="afflilate_id" id="unit_id" class="form-control select-picker" data-size="8">';
            foreach ($affiliators as $affiliator) {
                $html .='<option value="' . $affiliator->id . '"> ' . ucfirst($affiliator->name) . ' ( ' . $affiliator->id . ' )' . ' </option>';
            }
            $html .= '</select>';
        } else {
            $html .= 'Sorry No Record Found';
        }

        return response()->json(['html' => $html]);
    }

    public function getclients(Request $request) {
        $clients = Clients::where('user_id', Auth::user()->id)->where('afflilate_id', $request->input('afflilate_id'))
                ->where('name', 'like', '%' . $request->input('client') . '%')
                ->orWhere('phone', 'like', '%' . $request->input('client') . '%')
                ->orWhere('email', 'like', '%' . $request->input('client') . '%')
                ->orderBy('id', 'desc')
                ->get();
        $html = '';
        if (count($clients) > 0) {
            $html .='<label>Select Client</label>';
            foreach ($clients as $client) {
                $html .='<input class="form-control client_id" type="radio" name="client_id" value="' . $client->id . '"><span>' . $client->name . ' (' . $client->id . ')</span>';
            }
        } else {
            $html .= 'Sorry No Record Found!';
        }

        return response()->json(['html' => $html]);
    }

    public function clientLead(Clients $client) {
        $categories = Category::all();
        $projects = Project::all();
        $priority = LeadPriority::all();
        return view('leads.client', compact('projects', 'categories', 'client', 'priority'));
    }

    public function affiliatorLead(Affiliator $affiliator) {
        if ($affiliator->status == 1) {
            $categories = Category::all();
            $projects = Project::all();
            $priority = LeadPriority::all();
            return view('leads.affiliator', compact('categories', 'projects', 'affiliator', 'priority'));
        } else {
            return redirect('affiliators')->withErrors(__('Affiliator does not exist or approved'));
        }
    }

    public function leadstore(Request $request) {



        $validated = $request->validate([
            'project_id' => 'required',
            'category_id' => 'required',
        ]);
        Leads::create($request->all());
        return redirect('leads')->withStatus(__('Leads Created Successfully.'));
    }


    public function sharedleadstore(Request $request) {

        $record = Leads::where('id', $request->lead_id)->first();
        if ($record->share_id == 0) {
            ShareLead::firstOrCreate(
                    [
                        'lead_id' => $request->lead_id,
                        'from_user' => $request->from_user,
                        'to_user' => $request->to_user,
                        'shared_by' => $request->shared_by,
                        'status' => 1,
                        'note' => $request->note,
            ]);

            Leads::where('id', $request->lead_id)->update(array('share_id' => $request->to_user));
            return redirect('leads')->withStatus(__('Leads Shared Successfully.'));
        } else {
            return redirect('leads')->withErrors(__('Leads already Shared.'));
        }
    }

    public function transferleadstore(Request $request) {

        TransferLead::firstOrCreate(
                [
                    'lead_id' => $request->lead_id,
                    'from_user' => $request->from_user,
                    'to_user' => $request->to_user,
                    'transfer_by' => $request->transfer_by,
                    'status' => 1,
                    'note' => $request->note,
        ]);

        // Replace used id in Leads table
        Leads::where('id', $request->lead_id)->update(array('user_id' => $request->to_user));
        // Replace used id in clients table
        Clients::where('id', $request->client_id)->where('user_id', $request->from_user)->update(array('user_id' => $request->to_user));
        // Replace used id in Task table
        Task::where('client_id', $request->client_id)->where('added_by', $request->from_user)->update(array('added_by' => $request->to_user));
        return redirect('leads')->withStatus(__('Leads Tranfered Successfully.'));
    }

    public function store(Request $request)
    {


        if ($request->input('account') == 1 && $request->input('client_id') == '') {
            $phone = $request->input('countryCode') . $request->input('phone');
            $record = Clients::where('phone', $phone)->first();
            if (empty($record))
            {
                $validated = $request->validate([
                    'account' => 'required',
                    'phonenumber' => 'required',
                    'name' => 'required',
                    // 'phone' => 'required',
                    'source_id' => 'required'
                ]);


                if($request->ptclphone !='')
                {
                    $phone = $request->ptclphone;
                }
                if($request->phone !='')
                {
                    $phone = $request->full_number;
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


                $client = Clients::firstOrCreate(
                                [
                            'name' => $request->name,
                            'email' => $request->email,
                            // 'phone' => $request->countryCode . $request->phone,
                            // 'phone' => $request->phone,
                            // 'phone' => $request->full_number,
                            'phone' => $phone,

                            'telephone' => $telephone,
                            'telephone1' => $telephone1,
                            'address' => $request->address,
                            'user_id' => $request->user_id,
                            'source_id' => $request->source_id,
                            'afflilate_id' => $request->afflilate_id,
                                ]
                            //     , [
                            // 'phone' => $request->countryCode . $request->phone
                            //     ]
                );

                $lead = Leads::create($request->merge(['client_id' => $client->id])->all());

                if ($lead->project_id > 0) {
                    $project = $lead->project->name;
                } else {
                    $project = '';
                }

                if ($lead->source_id > 0) {
                    $source = $lead->leadSource->type;
                } else {
                    $source = '';
                }

                if ($lead->category_id > 0) {
                    $category = $lead->category->name;
                } else {
                    $category = '';
                }

                $data = array('name' => $request->name,

                    'email' => $request->email,

                    // 'phone' => $request->countryCode . $request->phone,
                    // 'phone' => $request->phone,
                    // 'phone' => $request->full_number,
                    'phone' => $phone,

                    'address' => $request->address,
                    'user' => $lead->leadAgent->name,
                    'source' => $source,
                    'project' => $project,
                    'interested' => $category,
                    'notes' => $request->note,
                );

                $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                $to_name = Auth::user()->name;

                Mail::send('leads.lead-mail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                            ('Lead Create Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });

                return redirect('leads')->withStatus(__('Leads Created Successfully.'));
            } else {
                return back()->withInput()->withErrors(['Client already exist under ' . $record->assigned->name . ' (' . $record->name . ')']);
            }
        } else {
            $record = Clients::where('id', $request->input('client_id'))->where('user_id', Auth::user()->id)->first();

            if (!empty($record)) {
                $validated = $request->validate([
                    'account' => 'required',
                    'client_id' => 'required',
                ]);

                $lead = Leads::create($request->merge(['client_id' => $record->id])->all());

                if ($lead->project_id != '') {
                    $project = $lead->project->name;
                } else {
                    $project = '';
                }

                if ($lead->source_id != '') {
                    $source = $lead->leadSource->type;
                } else {
                    $source = '';
                }

                if ($lead->category_id != '') {
                    $category = $lead->category->name;
                } else {
                    $category = '';
                }

                $data = array('name' => $record->name,
                    'email' => $record->email,
                    // 'phone' => $request->phone,
                    'phone' => $request->full_number,

                    'address' => $record->address,
                    'user' => $lead->leadAgent->name,
                    'source' => $source,
                    'project' => $project,
                    'interested' => $category,
                    'notes' => $request->note,
                );


                $to_email = [Auth::user()->email, 'shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                $to_name = Auth::user()->name;

                Mail::send('leads.lead-mail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                            ('Lead Create Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });

                return redirect('leads')->withStatus(__('Leads Created Successfully.'));
            } else {
                return back()->withInput()->withErrors(['Please enter correct Client id.']);
            }
        }
        return back();
    }

    public function edit(Leads $lead) {
        if (Auth::user()->role == 4 || Auth::user()->role == 1) {
            $sources = LeadSource::orderBy('id', 'desc')->get();
        } else {
            $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();
        }
        if ($lead->user_id != Auth::user()->id && Auth::user()->role != 1 && Auth::user()->role != 5) {
            return back()->withErrors(__('Not Allowed!'));
        }
        $agents = User::where('role', '!=', 0)->where('status', '=', 1)->get();
        $categories = Category::all();
        $priority = LeadPriority::all();
        $projects = Project::all();
        $lead_status = LeadStatus::all();

        return view('leads.edit', compact('lead', 'sources', 'categories', 'projects', 'priority', 'agents', 'lead_status'));
    }


    public function update(Request $request, Leads $lead) {

        $lead->update($request->all());

        $lead->client->name = $request->name;
        $lead->client->phone = $request->full_number;
        $lead->client->save();
        return back()->withStatus(__('Leads Updated Successfully.'));
    }

    public function destroy(Leads $lead) {
        $lead->leadtask();
        $lead->delete();
        return redirect('admin/leads')->withStatus(__('Leads deleted Successfully.'));
    }

    public function search(Request $request) {

        $condition = array();
        $find_number=0;

        if ($request->input('lead_id') != '') {
            $condition[] = array('id', '=', $request->input('lead_id'));
        }
        $user_id = 0;
        if ($request->input('user_id') > 0) {
            $condition[] = array('user_id', '=', $request->input('user_id'));
            $user_id = $request->input('user_id');
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
        if ($request->input('phone') != '')
        {
            $first_nbr=mb_substr($request->phone, 0 , 1);

                if($first_nbr == 0){
                    $phone = substr($request->phone, 1);
                }else{
                    $phone = $request->phone;
                }

                $clients =Number::where('type', 'clients')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');

                $find_number=Number::Where('number', 'like', '%'.$phone.'%')->first();

                if($find_number != '')
                {
                    if($find_number->type == 'affiliators'){
                        $find_result=
                        "<span style='color:red;' class='client_exist'>
                            Affiliator Already Exist Under ".$find_number->affiliator->assigned->name." (".$find_number->affiliator->name.")
                        </span>";
                    }
                    if($find_number->type == 'clients'){
                        $find_result=
                        "<span style='color:red;' class='client_exist'>
                                Client Already Exist Under ".$find_number->client->assigned->name." (".$find_number->client->name.")
                        </span>";
                    }
                }

        }

        if ($request->input('phone') && $request->input('phone') != '') {
            $leads = Leads::where($condition)->whereIn('client_id', $clients)->orWhere('share_id', $user_id)->orderBy('id', 'DESC')
                    ->paginate(30);
        } else {
            if ( !empty($condition) ) {
                if($user_id > 0){
                $leads = Leads::where($condition)->orWhere('share_id', $user_id)->orderBy('id', 'DESC')
                        ->paginate(30);
                }else{
                    $leads = Leads::where($condition)->orderBy('id', 'DESC')
                        ->paginate(30);
                }
            }else{
                if(Auth::user()->role == 5){
                $leads = Leads::where('user_id','!=', 0)->orderBy('id', 'DESC')
                        ->paginate(30);
                }else{
                    $users = User::where('parent', Auth::user()->id)->pluck('id');
                    $leads = Leads::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'DESC')
                        ->paginate(30);
                }
            }
        }

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();

        return view('leads.index', compact('find_number','sources', 'leads', 'users', 'projects'));
    }

    public function leadstatus(Request $request) {
        LeadNotes::create($request->all());
        $lead = Leads::find($request->input('lead_id'));

        if ($request->input('type') == 6 && $request->input('status') == 1) {
            $lead->token_status = 2;
        }

        if ($request->input('type') == 7 && $request->input('status') == 1) {
            $item = Product::find($request->input('item_id'));
            $lead->down_pstatus = 2;
            $lead->status_id = 3;
            $item->status = 'Booked';
            $item->save();
        }

        $lead->save();



        return redirect('leads')->withStatus(__('Leads Updated Successfully.'));
    }

    public function sorting(Request $request) {
        $status = $request->input('status') ? $request->input('status') : 'id';
        $order = $request->input('sort') ? $request->input('sort') : 'ASC';
        $record = $request->input('records') ? $request->input('records') : 30;


        if ($record == 'all') {
            $all_leads = leads::all();
            $leads = Leads::where('user_id', Auth::user()->id)->orderBy($status, $order)->paginate(count($all_leads));
        } else {
            $leads = Leads::where('user_id', Auth::user()->id)->orderBy($status, $order)->paginate($record);
        }


        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $search_person = 'user';
        $id = 0;
        $find_number=0;

        return view('leads.index', compact('find_number','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

    public function leads_sorting($todo) {

        if ($todo == 'today') {
            // today
            $leads = Leads::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'yesterday') {
            // yesterday
            $leads = Leads::whereDate('created_at', '=', Carbon::yesterday())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'thisweek') {
            // this week
            $leads = Leads::whereBetween('created_at', [Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()])->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'lastweek') {
            // last week
            $previous_week = strtotime("-1 week +1 day");
            $start_week = strtotime("last sunday midnight", $previous_week);
            $end_week = strtotime("next saturday", $start_week);
            $start_week = date("Y-m-d", $start_week);
            $end_week = date("Y-m-d", $end_week);
            $leads = Leads::whereBetween('created_at', [$start_week, $end_week])->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'thismonth') {
            // this month
            $leads = Leads::whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } else {
            $leads = Leads::whereMonth('created_at', '<=', Carbon::now()->subMonth(1)->month)->where('user_id', Auth::user()->id)->orderBy('id', 'desc')
                    ->paginate(30);
        }

        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $search_person = 'user';
        $id = 0;
        $find_number=0;

        return view('leads.index', compact('find_number','sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

}
