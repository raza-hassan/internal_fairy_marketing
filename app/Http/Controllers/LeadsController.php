<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
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
use App\Models\Notification;
use App\Models\Offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Number;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpParser\Node\Stmt\Else_;
use App\Models\LeadFeedBack;
use App\Enums\LeadFeedBackStatus;
use Illuminate\Validation\Rules\Enum;
use App\Jobs\SendFacebookConversionJob;
use Illuminate\Validation\Rule;

class LeadsController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('lead.view')) {
            $leads = Leads::where('is_delete', 0)
                ->where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                        ->Orwhere('share_id', Auth::user()->id);
                })->orderBy('updated_at', 'desc')
                ->paginate(30);

            // echo"<pre>"; print_r( $leads); exit;

            $sources = LeadSource::all();

            // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
            $allocation = $users;
            // ====== Users With Helper======

            $projects = Project::orderBy('id', 'ASC')->get();
            $offices = Offices::orderBy('id', 'ASC')->get();
            $search_person = 'user';
            $id = 0;
            $find_result = 0;

            return view('leads.index', compact('find_result', 'sources', 'leads', 'allocation', 'users', 'projects', 'search_person', 'id', 'offices'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    // public function websiteleads(Request $request) {
    //     $record = Clients::where('phone', '+92' . $request->phone)->first();
    //     //print_r($record); exit;
    //     if ($record == '') {
    //         $validated = $request->validate([
    //             'phone' => 'required|unique:clients',
    //             'name' => 'required',
    //         ]);
    //         $client = Clients::firstOrCreate(
    //                         [
    //                     'name' => $request->name,
    //                     'email' => $request->email,
    //                     'phone' => '+92' . $request->phone,
    //                     'user_id' => 0,
    //                     'source_id' => 14,
    //                         ], [
    //                     'email' => $request->email,
    //                     'phone' => '+92' . $request->phone
    //                         ]
    //         );
    //         $lead = Leads::create($request->merge(['client_id' => $client->id])->all());

    //         // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
    //         $to_email = ['shoaib.khubaib@fairymarketing.com'];

    //         $to_name = 'Website';

    //         $data = array('name' => $request->name,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'user' => 0,
    //             'source' => 'Website',
    //             'notes' => $request->message
    //         );

    //         Mail::send('leads.weblead-mail', $data, function ($message) use ($to_name, $to_email) {
    //             $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
    //                     ('Lead Create Notification');
    //             $message->from(config('mail.from.address'), Config('mail.from.name'));
    //         });
    //         return response()->json([
    //                     "message" => $lead->id . " Lead created successfully"
    //                         ], 200);
    //     } else {
    //         $lead = Leads::create($request->merge(['client_id' => $record->id])->all());

    //         // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
    //         $to_email = ['shoaib.khubaib@fairymarketing.com'];

    //         $to_name = 'Website';

    //         $data = array('name' => $record->name,
    //             'email' => $record->email,
    //             'phone' => $record->phone,
    //             'user' => 0,
    //             'source' => 'Website',
    //             'notes' => $request->message
    //         );

    //         Mail::send('leads.weblead-mail', $data, function ($message) use ($to_name, $to_email) {
    //             $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
    //                     ('Duplicate Lead Create Notification');
    //             $message->from(config('mail.from.address'), Config('mail.from.name'));
    //         });
    //         return response()->json([
    //                     "message" => $lead->id . " Lead Not created"
    //                         ], 200);
    //     }
    // }

    public function websiteleads(Request $request)
    {
        // Check if the string starts with '+', preserve it and remove all other '+' signs
        // if nbr is like +923 57!@33&*()84+-(64%*66) this code will remove only + signs except 1st + sign
        $cleanedString = preg_replace('/(?!^)\+/', '', $request->phone); // Remove all '+' except the first one
        // Remove all unwanted characters (spaces, parentheses, %, *, etc.)
        $phone = preg_replace('/[^0-9+]/', '', $cleanedString);
        $email = !empty($request->email) ? $request->email : null;

        // Check if email is not empty before adding it to the query
        $client = Clients::where('phone', 'like', '%' . $phone . '%')
            ->when(!empty($email), function ($query) use ($email) {
                return $query->orWhere('email', $email);
            })
            ->first();

        $client_record = Number::Where('number', 'like', '%' . $phone . '%')->Where('type', 'clients')->first();

        if (empty($client) && empty($client_record)) {
            $validated = $request->validate([
                'phone' => 'required|unique:clients',
                'name' => 'required',
            ]);

            $attributes = [
                'name' => $request->name,
                'phone' => $phone,
                'user_id' => 0,
                'source_id' => 14,
            ];
            // Add email only if it's not empty
            if (!empty($email)) {
                $attributes['email'] = $email;
            }

            $client = Clients::firstOrCreate(
                $attributes,
                [
                    'email' => $email, // This will still be included in the "create" step
                    'phone' => $phone,
                ]
            );
            $lead = Leads::create($request->merge(['client_id' => $client->id])->all());
            Number::insert(['number' => $phone, 'type' => 'clients', 'client_id' => $client->id,]);

            // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
            $to_email = ['shoaib.khubaib@fairymarketing.com'];
            $to_name = 'Website';
            $data = array(
                'name' => $request->name,
                'email' => $email,
                'phone' => $phone,
                'user' => 0,
                'source' => 'Website',
                'notes' => $request->message
            );

            Mail::send('leads.weblead-mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Lead Create Notification');
                $message->from(config('mail.from.address'), Config('mail.from.name'));
            });
            return response()->json([
                "message" => "Lead ID " . $lead->id . " Created Successfully"
            ], 200);
        } else {
            if (!empty($client) && !empty($client_record)) {
                $lead = Leads::create($request->merge([
                    'client_id' => $client->id,
                    'user_id' => !empty($client['user_id']) ? $client['user_id'] : 21,
                ])->all());

                // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                $to_email = ['shoaib.khubaib@fairymarketing.com'];
                $to_name = 'Website';

                $data = array(
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'user' => 0,
                    'source' => 'Website',
                    'notes' => $request->message
                );

                Mail::send('leads.weblead-mail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Duplicate Lead Create Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });
                return response()->json([
                    "message" => "Lead ID " . $lead->id . " Created"
                ], 200);
            }

            return response()->json([
                "message" => "Lead Not Created"
            ], 200);
        }
    }

    public function All_Leads(Request $request, $id)
    {
        $search_person = 'manager';
        $leads = Leads::where('user_id', $id)->orWhere('share_id', $id)->orderBy('id', 'desc')->paginate(30);
        $sources = LeadSource::all();
        $users = User::where('role', '!=', 0)->get();
        $allocation = User::where('role', '!=', 0)->get();
        $projects = Project::orderBy('id', 'ASC')->get();
        $offices = Offices::orderBy('id', 'ASC')->get();
        //        echo $id; exit;
        $find_result = 0;

        return view('leads.index', compact('find_result', 'allocation', 'sources', 'leads', 'users', 'projects', 'search_person', 'id', 'offices'));
    }

    public function shareleads()
    {
        //        echo 'sdfsadf';
        $search_person = 'user';
        $leads = Leads::where('share_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        $sources = LeadSource::all();

        // $users = User::where('role', '!=', 0)->where('role', '!=', 13)->where('role', '!=', 14)->get();
        // $allocation = User::where('role', '!=', 0)->where('role', '!=', 13)->where('role', '!=', 14)->get();

        // ====== Users With Helper======
        $data = array(
            'id' => Auth::user()->id,
            'role' => Auth::user()->role,
        );
        $responce = Helper::users($data);
        $users = $responce['users']->where('office_id', 1);
        $allocation = $users;
        // ====== Users With Helper======

        $projects = Project::orderBy('id', 'ASC')->get();
        $offices = Offices::orderBy('id', 'ASC')->get();
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result', 'allocation', 'sources', 'leads', 'users', 'projects', 'search_person', 'id', 'offices'));
    }

    public function clientLeads($id)
    {
        $leads = Leads::where('client_id', $id)->orderBy('id', 'desc')->paginate(30);
        $sources = LeadSource::all();
        $users = User::where('role', '!=', 0)->get();
        $allocation = User::where('role', '!=', 0)->get();
        $projects = Project::orderBy('id', 'ASC')->get();
        $offices = Offices::orderBy('id', 'ASC')->get();
        $search_person = 'user';
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result', 'allocation', 'offices', 'sources', 'leads', 'users', 'projects', 'search_person', 'id'));
    }

    public function share_leads(Request $request)
    {
        ShareLead::create($request->all());
        return back()->withStatus(__('Leads Shared Successfully.'));
    }

    public function assigned(Request $request)
    {
        // echo '<pre>';    print_r($request->input());  exit;

        if (Auth::user()->can('assign.leads.option')) {
            if (!empty($request->input('leadids')) && $request->input('user_id') != '') {
                $array_of_ids = $request->input('leadids');

                $client_array = DB::table('leads')->whereIn('id', $array_of_ids)->groupBy('client_id')->pluck('client_id');
                //            print_r($client_array);
                //            exit;

                Leads::whereIn('id', $array_of_ids)->update(array('user_id' => $request->input('user_id')));
                Clients::whereIn('id', $client_array)->update(array('user_id' => $request->input('user_id')));


                // ===============Notification===============
                $user = User::where('id', $request->input('user_id'))->first();
                $data = array(
                    'type' => 'Assign New Lead ',
                    'msg_body' => count($array_of_ids) . ' New Lead Has Assigned From ' . Auth::user()->name . ' To ' . $user->name,
                    'created_by' => Auth::user()->id,
                    'show_to' => $user->id,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);
                //  exit;
                // ==========================================

                return redirect('newleads')->withStatus(__('Leads Assigned Successfully.'));
            } else {
                return redirect('newleads')->withErrors(__('Please select leads and user!'));
            }
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function facebookleads()
    {
        if (Auth::user()->can('new-leads.view')) {
            // $leads = Leads::where('user_id', '')->where('office_id', Auth::user()->office_id)->orderBy('id', 'desc')->get();
            $leads = Leads::where('office_id', Auth::user()->office_id) // First where clause
                ->where(function ($query) {
                    $query->where('user_id', '')
                        ->orWhere('user_id', 0);
                })->where('is_delete', 0)
                ->orderBy('id', 'desc')
                ->get();
            // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', Auth::user()->office_id);
            // ====== Users With Helper======
            return view('leads.facebookleads', compact('leads', 'users'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('lead.create')) {
            if (Auth::user()->role == 1 || Auth::user()->role == 4 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                $sources = LeadSource::orderBy('id', 'desc')->get();
            } else {
                $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();
            }

            $categories = Category::all();
            $priority = LeadPriority::all();
            $projects = Project::all();
            return view('leads.create', compact('sources', 'categories', 'projects', 'priority'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function getaffiliators(Request $request)
    {
        //        echo $request->input('source'); exit;
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

        //        echo '<pre>';print_r($affiliators);exit;

        if (count($affiliators)) {
            $html .= '
                    <label> Select ' . $source . '<sup>*</sup></label>

                    <select name="afflilate_id" id="unit_id" class="form-control select-picker" required data-size="8">';
            $html .= '<option value="">  Please Select ' . $source . ' </option>';

            foreach ($affiliators as $affiliator) {
                $html .= '<option value="' . $affiliator->id . '"> ' . ucfirst($affiliator->name) . ' ( ' . $affiliator->id . ' )' . ' </option>';
            }

            $html .= '</select>';
        } else {
            $html .= 'Sorry No Record Found';
        }

        return response()->json(['html' => $html]);
    }

    public function getclients(Request $request)
    {
        $clients = Clients::where('user_id', Auth::user()->id)->where('afflilate_id', $request->input('afflilate_id'))
            ->where('name', 'like', '%' . $request->input('client') . '%')
            ->orWhere('phone', 'like', '%' . $request->input('client') . '%')
            ->orWhere('email', 'like', '%' . $request->input('client') . '%')
            ->orderBy('id', 'desc')
            ->get();
        //        echo '<pre>';
        //        print_r($clients);
        //        exit;
        $html = '';
        if (count($clients) > 0) {
            $html .= '<label>Select Client</label>';
            foreach ($clients as $client) {
                $html .= '<input class="form-control client_id" type="radio" name="client_id" value="' . $client->id . '"><span>' . $client->name . ' (' . $client->id . ')</span>';
            }
        } else {
            $html .= 'Sorry No Record Found!';
        }

        return response()->json(['html' => $html]);
    }

    public function clientLead(Clients $client)
    {
        if (Auth::user()->can('lead.create')) {
            $categories = Category::all();
            $projects = Project::all();
            $priority = LeadPriority::all();
            return view('leads.client', compact('projects', 'categories', 'client', 'priority'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function affiliatorLead(Affiliator $affiliator)
    {
        if ($affiliator->status == 1) {
            $categories = Category::all();
            $projects = Project::all();
            $priority = LeadPriority::all();
            return view('leads.affiliator', compact('categories', 'projects', 'affiliator', 'priority'));
        } else {
            return redirect('affiliators')->withErrors(__('Affiliator does not exist or approved'));
        }
    }

    public function leadstore(Request $request)
    {

        $validated = $request->validate([
            'project_id' => 'required',
            'category_id' => 'required',
        ]);
        //  echo 'gdfg';
        //  exit;

        Leads::create($request->all());
        return redirect('leads')->withStatus(__('Leads Created Successfully.'));
    }

    // ===lead share transfer===

    public function sharedleadstore(Request $request)
    {
        if (Auth::user()->can('lead.share') || Auth::user()->can('trashed.lead.share')) {
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
                    ]
                );

                Leads::where('id', $request->lead_id)->update(array('share_id' => $request->to_user, 'is_delete' => 0));

                // ===============Notification===============
                $user =   User::where('id', $request->to_user)->first();
                $from =   User::where('id', $request->from_user)->first();
                $shared_by =  User::where('id', $request->shared_by)->first();

                $data = array(
                    'type' => 'Lead Share',
                    'msg_body' => 'Lead Has Shared From ' . $from->name . ' To ' . $user->name . ' By ' . $shared_by->name . '. Lead ID: ' . $request->lead_id,
                    'created_by' => $shared_by->id,
                    'show_to' => $user->id,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);
                $data = array(
                    'type' => 'Lead Share',
                    'msg_body' => 'Lead Has Shared From ' . $from->name . ' To ' . $user->name . ' By ' . $shared_by->name . '. Lead ID: ' . $request->lead_id,
                    'created_by' => $shared_by->id,
                    'show_to' => $from->id,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);
                // ==========================================
                // return redirect('leads')->withStatus(__('Leads Shared Successfully.'));
                return back()->withInput()->withStatus(__('Leads Shared Successfully.'));
            } else {
                // return redirect('leads')->withErrors(__('Leads already Shared.'));
                return back()->withInput()->withErrors(__('Leads already Shared.'));
            }
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function transferleadstore(Request $request)
    {
        if (Auth::user()->can('lead.transfer') || Auth::user()->can('trashed.lead.transfer')) {
            // echo "<pre>"; print_r($request->all());  exit;

            TransferLead::firstOrCreate(
                [
                    'lead_id' => $request->lead_id,
                    'from_user' => $request->from_user,
                    'to_user' => $request->to_user,
                    'transfer_by' => $request->transfer_by,
                    'status' => 1,
                    'note' => $request->note,
                ]
            );

            // Replace used id in Leads table
            Leads::where('id', $request->lead_id)->update(array('user_id' => $request->to_user, 'is_delete' => 0));
            // Replace used id in clients table
            Clients::where('id', $request->client_id)->where('user_id', $request->from_user)->update(array('user_id' => $request->to_user));
            // Replace used id in Task table
            // Task::where('client_id', $request->client_id)->where('added_by', $request->from_user)->update(array('added_by' => $request->to_user));

            // ===============Notification===============
            $user = User::where('id', $request->to_user)->first();
            $from = User::where('id', $request->from_user)->first();
            $transfer_by = User::where('id', $request->transfer_by)->first();

            $data = array(
                'type' => 'Lead Transfer',
                'msg_body' => 'Lead Has Transfered From ' . $from->name . ' To ' . $user->name . ' By ' . $transfer_by->name . '. Lead ID: ' . $request->lead_id,
                'created_by' => $transfer_by->id,
                'show_to' => $user->id,
                'show_to_role' => 0,
                'redirect' => 'leads',
            );
            Helper::notification($data);
            $data = array(
                'type' => 'Lead Transfer',
                'msg_body' => 'Lead Has Transfered From ' . $from->name . ' To ' . $user->name . ' By ' . $transfer_by->name . '. Lead ID: ' . $request->lead_id,
                'created_by' => $transfer_by->id,
                'show_to' => $from->id,
                'show_to_role' => 0,
                'redirect' => 'leads',
            );
            Helper::notification($data);
            // ==========================================

            // return redirect('leads')->withStatus(__('Leads Tranfered Successfully.'));
            return back()->withInput()->withStatus(__('Leads Tranfered Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    // ===lead share transfer End===


    public function store(Request $request)
    {
        // echo "<pre>";print_r($request->all()); exit;

        if ($request->international_phone_dynamic) {
            $request->validate([
                'international_phone_dynamic.*' => 'required'
            ]);

            $numericValues = array();
            $international_full_number = array();

            foreach ($request->international_phone_dynamic as $key => $value) {
                if (is_numeric($key)) {
                    $numericValues[] = $value;
                } else {
                    $international_full_number[$key] = $value;
                }
            }

            // echo "<pre>";print_r($international_full_number); exit;

            foreach ($international_full_number as $key => $number) {
                if ($number != '' && is_numeric($number)) {
                    $record = Number::Where('number', 'like', '%' . $number . '%')->first();

                    if ($record) {
                        if ($record->type == 'affiliators') {
                            if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                                return back()->withInput()->withErrors([
                                    'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                                ]);
                            }
                            return back()->withInput()->withErrors(['Affiliator already exist under ' . $record->affiliator->assigned->name . ' (' . $record->affiliator->name . ')']);
                        }
                        if ($record->type == 'clients') {
                            if ($record->client->user_id === 0 || $record->client->user_id === null) {
                                return back()->withInput()->withErrors([
                                    'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                                ]);
                            }
                            return back()->withInput()->withErrors(['Client already exist under ' . $record->client->assigned->name . ' (' . $record->client->name . ')']);
                        }
                    }
                }
            }
        }
        $office_id = Auth::user()->office_id;

        // echo "<pre>"; print_r($office_id); exit;

        if ($request->input('account') == 1 && $request->input('client_id') == '') {
            if ($request->ptclphone != '') {
                $phone = $request->input('ptclphone');
            }
            if ($request->phone != '') {
                $phone = $request->input('full_number');
            }

            // if ($request->telephone != '') {
            //     $telephone = $request->countryCode1 . $request->telephone;
            // } else {
            $telephone = '';
            // }

            // if ($request->telephone1 != '') {
            //     $telephone1 = $request->countryCode2 . $request->telephone1;
            // } else {
            $telephone1 = '';
            // }

            // $phone = $request->input('full_number');
            //$record = Clients::where('phone', $phone)->first();
            // $record=Number::Where('number', 'like', '%'.$phone.'%')->first();
            // print_r($record->phone); exit;


            if ($phone != '') {
                $record = Number::Where('number', 'like', '%' . $phone . '%')->first();
            }
            // if ($phone != '' && $telephone != '') {
            //     $record=Number::Where('number', 'like', '%'.$phone.'%')->orWhere('Number', 'like', '%'.$telephone.'%')->first();
            // }if ($phone != '' && $telephone == '' && $telephone1 != ''){
            //     $record=Number::Where('number', 'like', '%'.$phone.'%')->orWhere('Number', 'like', '%'.$telephone1.'%')->first();
            // }if ($phone != '' && $telephone != '' && $telephone1 != ''){
            //     $record=Number::Where('number', 'like', '%'.$phone.'%')->orWhere('Number', 'like', '%'.$telephone.'%')->orWhere('Number', 'like', '%'.$telephone1.'%')->first();
            // }

            // echo "<pre>"; print_r($record); exit;

            if (empty($record)) {

                if ($request->ptclphone == '') {
                    $validated = $request->validate([
                        'account' => 'required',
                        'name' => 'required',
                        'full_number' => 'required',
                        'source_id' => 'required'
                    ]);
                }

                if ($request->phone == '') {
                    $validated = $request->validate([
                        'account' => 'required',
                        'name' => 'required',
                        'ptclphone' => 'required',
                        'source_id' => 'required'
                    ]);
                }

                if (($request->source_id == 12 || $request->source_id == 13) && $request->afflilate_id == '') {
                    return back()->withErrors(['Your Source is Affiliator. You must need to select Affiliator for this lead.']);
                }


                // print_r($phone);
                // exit;
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
                        'office_id' => $office_id,
                        'is_delete' => 0,
                    ],
                    [
                        'email' => $request->email,
                        // 'phone' => $request->full_number
                        'phone' => $phone,
                    ]
                );

                $lead = Leads::create($request->merge(['client_id' => $client->id, 'office_id' => $office_id])->all());


                if ($request->phone != '' || $request->ptclphone != '') {
                    $number_record = Number::Where('number', 'like', '%' . $phone . '%')->first();

                    if (empty($number_record)) {
                        Number::insert(['number' => $phone, 'type' => 'clients', 'client_id' => $client->id,]);
                    }
                }
                // if ($request->telephone != ''){
                //     $number_record=Number::Where('number', 'like', '%'.$telephone.'%')->first();
                //     if (empty($number_record)){
                //         Number::insert( [ 'number' => $telephone, 'type' => 'clients', 'client_id' => $client->id, ]);
                //     }
                // }if ($request->telephone1 != ''){
                //     $number_record=Number::Where('number', 'like', '%'.$telephone1.'%')->first();
                //     if (empty($number_record)){
                //         Number::insert( [ 'number' => $telephone1, 'type' => 'clients', 'client_id' => $client->id, ]);
                //     }
                // }

                if ($request->international_phone_dynamic) {
                    foreach ($international_full_number as $key => $number) {
                        if ($number != '' && is_numeric($number)) {
                            $check = Number::Where('number', 'like', '%' . $number . '%')->first();

                            if (empty($check)) {
                                Number::insert(['number' => $number, 'type' => 'clients', 'client_id' => $client->id,]);
                            }
                        }
                    }
                }


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

                $data = array(
                    'name' => $request->name,
                    'email' => $request->email,
                    // 'phone' => $request->full_number,
                    'phone' => $phone,
                    'address' => $request->address,
                    'user' => $lead->leadAgent->name,
                    'source' => $source,
                    'project' => $project,
                    'interested' => $category,
                    'notes' => $request->note,
                );

                //  print_r($data); exit;
                //$to_email = Auth::user()->email;
                // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];


                // // ==========Comment=============
                // $to_email = ['shoaib.khubaib@fairymarketing.com'];
                // $to_name = Auth::user()->name;
                // Mail::send('leads.lead-mail', $data, function ($message) use ($to_name, $to_email) {
                //     $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                //             ('Lead Create Notification');
                //     $message->from(config('mail.from.address'), Config('mail.from.name'));
                // });
                // // ==========Comment=============

                return redirect('leads')->withStatus(__('Leads Created Successfully.'));
            } else {

                if ($record->type == 'affiliators') {
                    if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                        ]);
                    }

                    return back()->withInput()->withErrors(['Affiliator already exist under ' . $record->affiliator->assigned->name . ' (' . $record->affiliator->name . ')']);
                }
                if ($record->type == 'clients') {
                    if ($record->client->user_id === 0 || $record->client->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Client already exist under ' . $record->client->assigned->name . ' (' . $record->client->name . ')']);
                }
            }
        } else {
            $record = Clients::where('id', $request->input('client_id'))->where('user_id', Auth::user()->id)->first();

            // echo "check"; exit;

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

                if ($request->ptclphone != '') {
                    $phone = $request->input('ptclphone');
                }
                if ($request->phone != '') {
                    $phone = $request->input('full_number');
                }


                //    echo $phone; exit;

                $data = array(
                    'name' => $record->name,
                    'email' => $record->email,
                    // 'phone' => $request->phone,
                    // 'phone' => $request->full_number,
                    'phone' => $phone,
                    'address' => $record->address,
                    'user' => $lead->leadAgent->name,
                    'source' => $source,
                    'project' => $project,
                    'interested' => $category,
                    'notes' => $request->note,
                );

                // $to_email = [Auth::user()->email, 'shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                $to_email = [Auth::user()->email, 'shoaib.khubaib@fairymarketing.com'];

                $to_name = Auth::user()->name;

                Mail::send('leads.lead-mail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Lead Create Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });

                $phone_number = Number::Where('number', 'like', '%' . $phone . '%')->first();

                if (empty($phone_number)) {
                    if ($request->phone != '') {
                        Number::insert(['number' => $phone_number, 'type' => 'clients', 'client_id' => $record->id,]);
                    }
                    if ($request->ptclphone != '') {
                        Number::insert(['number' => $phone_number, 'type' => 'clients', 'client_id' => $record->id,]);
                    }
                }

                return redirect('leads')->withStatus(__('Leads Created Successfully.'));
            } else {
                return back()->withInput()->withErrors(['Please enter correct Client id.']);
            }
        }
        return back();
    }

    public function show(Leads $leads)
    {
        //
    }

    public function edit(Leads $lead)
    {
        if (Auth::user()->can('lead.edit')) {

            if (Auth::user()->role == 1 || Auth::user()->role == 4) {
                $sources = LeadSource::orderBy('id', 'desc')->get();
            } else {
                $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();
            }
            if ($lead->user_id != Auth::user()->id && Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) {
                return back()->withErrors(__('Not Allowed!'));
            }
            $agents = User::where('role', '!=', 0)->where('status', '=', 1)->get();
            $categories = Category::all();
            $priority = LeadPriority::all();
            $projects = Project::all();
            $lead_status = LeadStatus::all();

            // $numbers=Number::Where('type' , 'clients')->Where('client_id' , $lead->client_id)->get();
            $numbers = Number::Where('type', 'clients')->Where('client_id', $lead->client_id)
                ->where('number', '!=', $lead->client->phone)->get();


            $phone = Number::Where('type', 'clients')->Where('client_id', $lead->client_id)
                ->where('number', 'Like', '%' . $lead->client->phone . '%')->first();

            // $output = substr($phone->number, 0, 3);

            $task = Order::where('lead_id', $lead->id)
                ->where('client_id', $lead->client_id)
                ->where('approved_by', 0)
                ->where('status', '!=', 'Sold')
                ->latest()->first();


            // echo '<pre>';print_r($task); exit;

            return view('leads.edit', compact('task', 'lead', 'sources', 'categories', 'projects', 'priority', 'agents', 'lead_status', 'numbers', 'phone'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function update(Request $request, Leads $lead)
    {
        if (Auth::user()->can('lead.edit')) {

            if ($request->change_inventory == 1) {
                $request->validate([
                    'type_id' => 'required',
                    'floor' => 'required',
                    'old_order_unit_id' => 'required',
                    'change_unit_id' => 'required',
                ]);
            }

            //======= add new international number check start
            if ($request->international_phone_dynamic) {
                $request->validate([
                    'international_phone_dynamic.*' => 'required'
                ]);

                $numericValues = array();
                $international_full_number = array();

                foreach ($request->international_phone_dynamic as $key => $value) {
                    if (is_numeric($key)) {
                        $numericValues[] = $value;
                    } else {
                        $international_full_number[$key] = $value;
                    }
                }
                // echo "<pre>";print_r($international_full_number); exit;

                foreach ($international_full_number as $key => $number) {
                    if ($number != '' && is_numeric($number)) {
                        // $record=Number::Where('number', 'like', '%'.$phone_number.'%')->first();

                        $record = Number::Where('client_id', '!=', $lead->client_id)
                            ->where(function ($query) use ($number) {
                                $query->orWhere('Number', 'like', '%' . $number . '%');
                            })->first();

                        if ($record) {
                            if ($record->type == 'affiliators') {
                                if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                                    return back()->withInput()->withErrors([
                                        'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                                    ]);
                                }
                                return back()->withInput()->withErrors(['Affiliator already exist under ' . $record->affiliator->assigned->name . ' (' . $record->affiliator->name . ')']);
                            }
                            if ($record->type == 'clients') {
                                if ($record->client->user_id === 0 || $record->client->user_id === null) {
                                    return back()->withInput()->withErrors([
                                        'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                                    ]);
                                }
                                return back()->withInput()->withErrors(['Client already exist under ' . $record->client->assigned->name . ' (' . $record->client->name . ')']);
                            }
                        }
                    }
                }
            }
            //======= add new international number check end


            //======= edit international number check start
            if ($request->edit_international_phone_dynamic) {
                $request->validate(
                    [
                        'edit_international_phone_dynamic.*' => 'required'
                    ]
                );

                $editnumericValues = array();
                $edit_international_full_number = array();

                foreach ($request->edit_international_phone_dynamic as $key => $value) {
                    if (is_numeric($key)) {
                        $editnumericValues[] = $value;
                    } else {
                        $edit_international_full_number[$key] = $value;
                    }
                }

                foreach ($edit_international_full_number as $key => $number) {
                    if ($number != '' && is_numeric($number)) {
                        // $record=Number::Where('number', 'like', '%'.$phone_number.'%')->first();

                        $record = Number::Where('client_id', '!=', $lead->client_id)
                            ->where(function ($query) use ($number) {
                                $query->orWhere('Number', 'like', '%' . $number . '%');
                            })->first();

                        if ($record) {
                            if ($record->type == 'affiliators') {
                                if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                                    return back()->withInput()->withErrors([
                                        'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                                    ]);
                                }
                                return back()->withInput()->withErrors(['Affiliator already exist under ' . $record->affiliator->assigned->name . ' (' . $record->affiliator->name . ')']);
                            }
                            if ($record->type == 'clients') {
                                if ($record->client->user_id === 0 || $record->client->user_id === null) {
                                    return back()->withInput()->withErrors([
                                        'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                                    ]);
                                }
                                return back()->withInput()->withErrors(['Client already exist under ' . $record->client->assigned->name . ' (' . $record->client->name . ')']);
                            }
                        }
                    }
                }
            }
            //======= edit international number check end


            if ($request->phone != '') {
                $validated = $request->validate([
                    'name' => 'required',
                    'full_number' => 'required',
                ]);

                $phone = $request->input('full_number');

                $record = Number::Where('client_id', '!=', $lead->client_id)
                    ->where(function ($query) use ($phone) {
                        $query->orWhere('Number', 'like', '%' . $phone . '%');
                    })->first();
            }

            if ($request->ptclphone != '') {
                $validated = $request->validate([
                    'name' => 'required',
                    'ptclphone' => 'required',
                ]);

                $phone = $request->input('ptclphone');

                $record = Number::Where('client_id', '!=', $lead->client_id)
                    ->where(function ($query) use ($phone) {
                        $query->orWhere('Number', 'like', '%' . $phone . '%');
                    })->first();
            }

            if (empty($record)) {
                $lead->update($request->all());

                $lead->client->name = $request->name;
                $lead->client->phone = $request->full_number;
                $lead->client->save();

                if ($request->phone != '' || $request->ptclphone != '') {
                    $id = $request->orignal_phone_number_id;

                    Number::findOrFail($id)->update([
                        'number' => $phone,
                        'type' => 'clients',
                        'client_id' => $lead->client_id,
                    ]);
                }

                // if($request->multi_phone)
                // {
                //     foreach ($request->multi_phone as $key => $number)
                //     {
                //         if ($request->countryCode[$key] != ''){
                //             $phone_number = $request->countryCode[$key] . $number['number-'.$key];
                //         }else{
                //             $phone_number = $number['number-'.$key];
                //         }
                //         if($phone_number !='' && is_numeric($phone_number))
                //         {
                //             $check=Number::Where('client_id' , $lead->client_id)->Where('Number', 'like', '%'.$phone_number.'%')->first();
                //             if (empty($check))
                //             {
                //                 Number::insert([ 'number' => $phone_number, 'type' => 'clients', 'client_id' => $lead->client_id,]);
                //             }
                //         }
                //     }
                // }

                // if($request->edit_multi_phone)
                // {
                //     foreach ($request->edit_multi_phone as $key => $number)
                //     {
                //         if($request->edit_countryCode[$key] != ''){
                //             $phone_number = $request->edit_countryCode[$key] . $number['number-'.$key];
                //         }else{
                //             $phone_number = $number['number-'.$key];
                //         }

                //         if($phone_number !='' && is_numeric($phone_number))
                //         {
                //             $exist=Number::Where('Number', 'like', '%'.$phone_number.'%')->first();

                //             if (empty($exist))
                //             {
                //                 $id = $request->number_id[$key];

                //                 Number::findOrFail($id)->update([
                //                     'number' => $phone_number,
                //                     'type' => 'clients',
                //                     'client_id' => $lead->client_id,
                //                 ]);
                //             }
                //         }
                //     }
                //     // exit;
                // }


                if ($request->international_phone_dynamic) {
                    foreach ($international_full_number as $key => $number) {
                        if ($number != '' && is_numeric($number)) {
                            $check = Number::Where('client_id', $lead->client_id)->Where('Number', 'like', '%' . $number . '%')->first();
                            if (empty($check)) {
                                Number::insert(['number' => $number, 'type' => 'clients', 'client_id' => $lead->client_id,]);
                            }
                        }
                    }
                }

                if ($request->edit_international_phone_dynamic) {
                    $counter = 0;
                    foreach ($edit_international_full_number as $key => $number) {
                        $counter = $counter + 1;

                        if ($number != '' && is_numeric($number)) {
                            $exist = Number::Where('Number', 'like', '%' . $number . '%')->first();

                            if (empty($exist)) {
                                if ('edit_international_full_number' . $counter == $key) {
                                    $id = $request->edit_phone_number_ids[$counter];

                                    Number::findOrFail($id)->update([
                                        'number' => $number,
                                        'type' => 'clients',
                                        'client_id' => $lead->client_id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            } else {
                if ($record->type == 'affiliators') {
                    if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Affiliator already exist under ' . $record->affiliator->assigned->name . ' (' . $record->affiliator->name . ')']);
                }
                if ($record->type == 'clients') {
                    if ($record->client->user_id === 0 || $record->client->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Client already exist under ' . $record->client->assigned->name . ' (' . $record->client->name . ')']);
                }
            }

            if ($request->change_inventory == 1) {
                $task = Order::where('lead_id', $lead->id)->where('client_id', $lead->client_id)
                    ->where('unit_id', $request->old_order_unit_id)->where('approved_by', 0)
                    ->where('status', '!=', 'Sold')->latest()->first()
                    ->update([
                        'unit_id' => $request->change_unit_id
                    ]);
            }
            return back()->withStatus(__('Leads Updated Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function destroy(Leads $lead)
    {
        if (Auth::user()->can('lead.trash')) {
            $lead->is_delete = 1;
            $lead->save();
            return redirect('admin/leads')->withStatus(__('Lead deleted Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function leadDelete($id)
    {
        if (Auth::user()->can('lead.trash')) {
            $lead = Leads::findOrFail($id);

            // $lead->task()->delete();
            // $lead->delete();

            $lead->is_delete = 1;
            $lead->save();

            return redirect('leads')->withStatus(__('Lead Deleted Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function trash_leads()
    {
        if (Auth::user()->can('trashed.lead.view')) {
            if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                $leads = Leads::where('is_delete', 1)->orderBy('id', 'desc')->paginate(30);
            } else {
                // $leads = Leads::where('is_delete' , 1)->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);

                // // Check Where Condition with Or-Condition
                $leads = Leads::where('is_delete', 1) // First where clause
                    ->where(function ($query) {
                        $query->where('user_id', Auth::user()->id)
                            ->orWhere('share_id', Auth::user()->id);
                    })
                    ->orderBy('id', 'desc')->paginate(30);
            }

            $sources = LeadSource::all();


            // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
            $allocation = $users;
            // ====== Users With Helper======

            $projects = Project::orderBy('id', 'ASC')->get();
            $offices = Offices::orderBy('id', 'ASC')->get();
            $search_person = 'user';
            $id = 0;

            return view('leads.trash', compact('sources', 'leads', 'allocation', 'users', 'projects', 'search_person', 'id', 'offices'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function resotre_leads(Leads $leads)
    {
        if (Auth::user()->can('trashed.lead.restore')) {
            $leads->is_delete = 0;
            $leads->save();
            return redirect()->back()->withStatus(__('Lead Active Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function lead_delete_permanently($id)
    {
        if (Auth::user()->can('trashed.lead.delete')) {
            $lead = Leads::findOrFail($id);
            $lead->task()->delete();
            $lead->delete();
            return redirect()->back()->withStatus(__('Lead Permanently Deleted Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function trash_search(Request $request)
    {
        if (Auth::user()->can('trashed.lead.view')) {
            $status = $request->input('status') ? $request->input('status') : 'id';
            $order = $request->input('sort') ? $request->input('sort') : 'ASC';
            $record = $request->input('records') ? $request->input('records') : 30;

            if ($record == 'all') {
                $record = count(leads::where('is_delete', 1)->get());
            }

            $condition = array();

            $condition[] = array('is_delete', '=', 1);

            if ($request->input('lead_id') != '') {
                $condition[] = array('id', '=', $request->input('lead_id'));
            }

            if ($request->input('office_id') > 0) {
                $condition[] = array('office_id', '=', $request->input('office_id'));
            }

            $user_id = 0;
            if ($request->input('user_id') > 0) {
                // $condition[] = array('user_id', '=', $request->input('user_id'));
                $user_id = $request->input('user_id');
            }

            if ($request->input('project_id') > 0) {
                $condition[] = array('project_id', $request->input('project_id'));
            }
            if ($request->input('source_id') > 0) {
                $condition[] = array('source_id', $request->input('source_id'));
            }

            if ($request->input('startDate') != '' && $request->input('endDate') != '') {
                //            echo $request->input('endDate'); exit;
                $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
                $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:59');
            }

            if ($request->input('startDate') != '' && $request->input('endDate') == '') {
                $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
            }

            if ($request->input('startDate') == '' && $request->input('endDate') != '') {
                //            echo 'sdfd'; exit;
                $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:59');
            }
            if ($request->input('phone') != '') {
                $first_nbr = mb_substr($request->phone, 0, 1);

                if ($first_nbr == 0) {
                    $phone = substr($request->phone, 1);
                } else {
                    $phone = $request->phone;
                }

                // $phone = ltrim($request->input('phone'), "0");
                // $clients = Clients::where('phone', 'Like', '%' . $phone . '%')->pluck('id');
                // $clients = Clients::where('phone', 'Like', '%' . $phone . '%')->orWhere('telephone', 'Like', '%' . $phone . '%')->orWhere('telephone1', 'Like', '%' . $phone . '%')->pluck('id');
                $clients = Number::where('type', 'clients')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');
            }

            //     echo '<pre>';  print_r($condition); exit;

            // Search phone number
            if ($request->input('phone') && $request->input('phone') != '') {
                if ($request->input('user_id') > 0) {
                    $condition[] = array('user_id', '=', $request->input('user_id'));
                }

                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                    $leads = Leads::where($condition)->whereIn('client_id', $clients)->orderBy($status, $order)->paginate($record);
                } else {
                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    // $leads = Leads::where($condition)->whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->whereIn('client_id', $clients)->orderBy($status, $order)->paginate($record);
                    $leads = Leads::where($condition)->whereIn('user_id', $users)->whereIn('client_id', $clients)->orderBy($status, $order)->paginate($record);
                }
            } else {
                if (!empty($condition)) {
                    if ($user_id > 0) {
                        // $leads = Leads::where($condition)->orWhere('share_id', $user_id)->orderBy($status, $order)->paginate($record);
                        $leads = Leads::where($condition)->where(function ($query) use ($user_id) {
                            $query->where('user_id', $user_id)->orWhere('share_id', $user_id);
                        })->orderBy($status, $order)->paginate($record);
                    } else {
                        if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                            $leads = Leads::where('user_id', '>', 0)->where($condition)->orderBy($status, $order)->paginate($record);
                        } else {
                            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                            $leads = Leads::whereIn('user_id', $users)->where($condition)->orderBy($status, $order)->paginate($record);
                        }
                    }
                } else {
                    if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                        // $leads = Leads::where('user_id', '!=', 0)->orderBy('id', 'DESC')->paginate(30);
                        $leads = Leads::where('user_id', '!=', 0)->orderBy($status, $order)->paginate($record);
                    } else {
                        $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                        $leads = Leads::whereIn('user_id', $users)->orderBy($status, $order)->paginate($record);
                    }
                }
            }

            // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);

            if ($request->office_id > 0) {
                $users = $responce['users']->where('office_id', $request->office_id);
                $allocation = $users;
            } else {
                $users = $responce['users'];
                $allocation = $users;
            }
            // ===== Users With Helper=====

            $projects = Project::orderBy('id', 'ASC')->get();
            $sources = LeadSource::all();
            $offices = Offices::orderBy('id', 'ASC')->get();

            return view('leads.trash', compact('sources', 'leads', 'allocation', 'users', 'projects', 'offices'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function search(Request $request)
    {
        //    dd($request->all());
        if (Auth::user()->can('lead.view')) {
            $status = $request->input('status') ? $request->input('status') : 'id';
            $order = $request->input('sort') ? $request->input('sort') : 'ASC';
            $record = $request->input('records') ? $request->input('records') : 30;

            // dd($record);

            if ($record == 'all') {
                $record = count(leads::where('is_delete', '==', 0)->get());
            }

            $condition = array();
            $condition[] = array('is_delete', '=', 0);

            if ($request->input('lead_id') != '') {
                $condition[] = array('id', '=', $request->input('lead_id'));
            }

            if ($request->input('office_id') > 0) {
                $condition[] = array('office_id', '=', $request->input('office_id'));
            }

            $user_id = 0;
            if ($request->input('user_id') > 0) {
                // $condition[] = array('user_id', '=', $request->input('user_id'));
                $user_id = $request->input('user_id');
            }

            if ($request->input('project_id') > 0) {
                $condition[] = array('project_id', $request->input('project_id'));
            }
            if ($request->input('source_id') > 0) {
                $condition[] = array('source_id', $request->input('source_id'));
            }

            if ($request->input('startDate') != '' && $request->input('endDate') != '') {
                // echo $request->input('endDate'); exit;
                $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
                $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:59');
            }

            if ($request->input('startDate') != '' && $request->input('endDate') == '') {
                $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');
            }

            if ($request->input('startDate') == '' && $request->input('endDate') != '') {
                // echo 'sdfd'; exit;
                $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:59');
            }

            $find_result = 0;

            if ($request->input('phone') != '') {
                $first_nbr = mb_substr($request->phone, 0, 1);

                if ($first_nbr == 0) {
                    $phone = substr($request->phone, 1);
                } else {
                    $phone = $request->phone;
                }

                $clients = Number::where('type', 'clients')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');

                $find_number = Number::Where('number', 'like', '%' . $phone . '%')->first();

                if ($find_number != '') {
                    if ($find_number->type == 'affiliators') {
                        $chcek_affiliator_trash = $find_number->affiliator->is_delete;

                        // If leadtrash is a collection
                        $leadtrash_records = $find_number->affiliator->leadtrash;
                        $chcek_lead_trash = $leadtrash_records->filter(function ($item) {
                            return $item->is_delete == 1;
                        })->isNotEmpty();

                        $find_result = "<span style='color:red;' class='client_exist'>
                                            Affiliator Already Exists Under " . $find_number->affiliator->assigned->name .
                            " (" . $find_number->affiliator->name . ")" .
                            ($chcek_affiliator_trash == 1 || $chcek_lead_trash == 1 ? " (Please Check The Trash-Box)" : "") .
                            "</span>";
                    }

                    if ($find_number->type == 'clients') {
                        $chcek_client_trash = $find_number->client->is_delete;
                        // If leadtrash is a collection
                        $leadtrash_records = $find_number->client->leadtrash;
                        $chcek_lead_trash = $leadtrash_records->filter(function ($item) {
                            return $item->is_delete == 1;
                        })->isNotEmpty();

                        $assignedName = $find_number->client->assigned->name ?? 'No User Assigned';

                        $find_result = "<span style='color:red;' class='client_exist'>
                                            Client Already Exists Under " . $assignedName .
                            " (" . $find_number->client->name . ")" .
                            ($chcek_client_trash == 1 || $chcek_lead_trash == 1
                                ? " (Please Check The Trash-Box)"
                                : "") .
                            "</span>";
                    }
                }
            }

            // Search phone number
            if ($request->input('phone') && $request->input('phone') != '') {

                // if ($request->input('user_id') > 0) {
                //     $condition[] = array('user_id', '=', $request->input('user_id'));
                // }

                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                    // $leads = Leads::where($condition)->whereIn('client_id', $clients)->orderBy($status, $order);

                    $leads = Leads::where($condition)
                        ->when($user_id > 0, function ($query) use ($user_id) {
                            $query->where(function ($q) use ($user_id) {
                                $q->where('user_id', $user_id)
                                    ->orWhere('share_id', $user_id);
                            });
                        })
                        ->whereIn('client_id', $clients)
                        ->orderBy($status, $order);
                } else {

                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    // $leads = Leads::where($condition)->whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->whereIn('client_id', $clients)->orderBy($status, $order)->paginate($record);
                    // $leads = Leads::where($condition)->whereIn('user_id', $users)->whereIn('client_id', $clients)->orderBy($status, $order);

                    $allowedUsers = collect($users);
                    if ($user_id > 0) {
                        $allowedUsers->push($user_id);
                    }
                    $allowedUsers = $allowedUsers->unique()->values();

                    $leads = Leads::where($condition)
                        ->where(function ($query) use ($allowedUsers) {
                            $query->whereIn('user_id', $allowedUsers)
                                ->orWhereIn('share_id', $allowedUsers);
                        })
                        ->whereIn('client_id', $clients)
                        ->orderBy($status, $order);
                }
            } else {
                if (!empty($condition)) {
                    if ($user_id > 0) {
                        $leads = Leads::where($condition)
                            ->where(function ($query) use ($user_id) {
                                $query->where('user_id', $user_id)
                                    ->orWhere('share_id', $user_id);
                            })
                            ->orderBy($status, $order);
                    } else {
                        if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                            $leads = Leads::where('user_id', '>', 0)->where($condition)->orderBy($status, $order);
                        } else {
                            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                            $leads = Leads::whereIn('user_id', $users)->where($condition)->orderBy($status, $order);
                        }
                    }
                } else {
                    if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                        // $leads = Leads::where('user_id', '!=', 0)->orderBy('id', 'DESC')->paginate(30);
                        $leads = Leads::where('user_id', '!=', 0)->orderBy($status, $order);
                    } else {
                        $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                        // $leads = Leads::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy($status, $order)->paginate($record);
                        $leads = Leads::whereIn('user_id', $users)->orderBy($status, $order);
                    }
                }
            }

            // ===== Users With Helper=====
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);

            if ($request->office_id > 0) {
                $users = $responce['users']->where('office_id', $request->office_id);
                $allocation = $users;
            } else {
                $users = $responce['users'];
                $allocation = $users;
            }
            // ===== Users With Helper=====

            if ($request->input('status_type') == 1) {
                // $leadsWithTasks = Task::whereIn('lead_id' , $leads->pluck('id'))->pluck('lead_id');
                // $leads = $leads->whereIn('id', $leadsWithTasks)->paginate($record);

                $leadIds = $leads->pluck('id')->toArray();

                $leadsWithTasks = Task::select('lead_id')
                    ->whereIn('lead_id', $leadIds)
                    ->distinct()
                    ->pluck('lead_id');
                $leads = $leads->whereIn('id', $leadsWithTasks)->paginate($record);
            } elseif ($request->input('status_type') == 2) {
                // $leadsWithoutTasks = Task::whereIn('lead_id' , $leads->pluck('id'))->pluck('lead_id');
                // $leads = $leads->whereNotIn('id', $leadsWithoutTasks)->paginate($record);
                $leadIds = $leads->pluck('id')->toArray();
                $leadsWithTasks = Task::select('lead_id')
                    ->whereIn('lead_id', $leadIds)
                    ->distinct()
                    ->pluck('lead_id');
                $leads = $leads->whereNotIn('id', $leadsWithTasks)->paginate($record);
            } else {
                $leads = $leads->paginate($record);
            }

            // echo '<pre>';  print_r($leads); exit;
            $projects = Project::orderBy('id', 'ASC')->get();
            $sources = LeadSource::all();
            $offices = Offices::orderBy('id', 'ASC')->get();
            $id = 0;
            return view('leads.index', compact('find_result', 'sources', 'leads', 'id', 'allocation', 'users', 'projects', 'offices'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function leadstatus(Request $request)
    {
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

    public function sorting(Request $request)
    {

        $status = $request->input('status') ? $request->input('status') : 'id';
        $order = $request->input('sort') ? $request->input('sort') : 'ASC';
        $record = $request->input('records') ? $request->input('records') : 30;

        if ($record == 'all') {
            $all_leads = leads::all();
            $leads = Leads::where('user_id', Auth::user()->id)->orderBy($status, $order)->paginate(count($all_leads));
        } else {
            $leads = Leads::where('user_id', Auth::user()->id)->orderBy($status, $order)->paginate($record);
        }

        // ====== Users With Helper======
        $data = array(
            'id' => Auth::user()->id,
            'role' => Auth::user()->role,
        );
        $responce = Helper::users($data);
        $users = $responce['users'];
        // ====== Users With Helper======


        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $offices = Offices::orderBy('id', 'ASC')->get();
        $search_person = 'user';
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result', 'sources', 'leads', 'users', 'projects', 'search_person', 'id', 'offices'));
    }

    public function leads_sorting($todo)
    {

        if ($todo == 'today') {
            // today
            $leads = Leads::whereDate('created_at', Carbon::today())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'yesterday') {
            // yesterday
            $leads = Leads::whereDate('created_at', '=', Carbon::yesterday())->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        } elseif ($todo == 'thisweek') {
            // this week
            $leads = Leads::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
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

        // ====== Users With Helper======
        $data = array(
            'id' => Auth::user()->id,
            'role' => Auth::user()->role,
        );
        $responce = Helper::users($data);
        $users = $responce['users'];
        // ====== Users With Helper======

        $projects = Project::orderBy('id', 'ASC')->get();
        $sources = LeadSource::all();
        $offices = Offices::orderBy('id', 'ASC')->get();

        $search_person = 'user';
        $id = 0;
        $find_result = 0;

        return view('leads.index', compact('find_result', 'sources', 'leads', 'users', 'projects', 'search_person', 'id', 'offices'));
    }

    public function office_users_get($office_id)
    {
        $html = '';
        if ($office_id > 0) {
            $users = User::where('role', '!=', 0)->where('status', 1)->where('is_delete', 0)->where('office_id', $office_id)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('role', '!=', 0)->where('status', 1)->where('is_delete', 0)->orderBy('id', 'asc')->get();
        }
        if (count($users) > 0) {
            // $html.='<select class="form-control user_id" name="user_id" id="user_id">';
            $html .= '<option value="0"> All </option>';

            foreach ($users as $user) {
                $html .= '<option value="' . $user->id . '"> ' . $user->name . '</option>';
            }
            // $html.= '</select>';
        } else {
            $html .= '<p>Sorry No Record Found...!</p>';
        }
        return response()->json(['html' => $html]);
    }

    public function markAsRead(Request $request, $id)
    {
        if ($id) {
            if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                $data = Notification::where('id', $id)->update([
                    'role_read_at'  => Carbon::now(),
                    'read_by_role'  => Auth::user()->role,
                ]);
            } else {
                $data = Notification::where('id', $id)->update([
                    'user_read_at'  => Carbon::now(),
                    'read_by_user'  => Auth::user()->id,
                ]);
            }

            $html = $this->loadAgainAllNotification();

            return response()->json(array(
                'html' => $html,
                'data' => $data
            ));
        }
    }

    public function loadAgainAllNotification()
    {
        $returnHTML = view('load_notification')->render();
        return $returnHTML;
    }

    public function markAllAsRead(Request $request)
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
            $data = Notification::where('role_read_at', Null)->where('read_by_role', Null)
                ->where(function ($query) {
                    $query->where('show_to_role', Auth::user()->role)
                        ->orWhere('show_to', Auth::user()->id);
                })->update([
                    'role_read_at'  => Carbon::now(),
                    'read_by_role'  => Auth::user()->role,
                ]);
        } else {
            $data = Notification::where('show_to', Auth::user()->id)->where('user_read_at', Null)
                ->where('read_by_user', Null)->update(
                    [
                        'user_read_at'  => Carbon::now(),
                        'read_by_user'  =>  Auth::user()->id,
                    ]
                );
        }

        $html = $this->loadAgainAllNotification();

        return response()->json(array(
            'html' => $html,
            'data' => $data
        ));
    }

    public function getAllNotification()
    {
        $returnHTML = view('load_notification')->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function viewAllNotification()
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 5) {
            $notifications = Notification::where(function ($query) {
                $query->where('show_to_role', Auth::user()->role)
                    ->orWhere('show_to', Auth::user()->id);
            })
                ->orderBy('id', 'desc')
                ->paginate(100);
        } elseif (Auth::user()->role == 13 || Auth::user()->role == 14) {
            // Array
            $show_to_role  = [Auth::user()->role, 5];
            $show_to  = [Auth::user()->id, 5];

            $notifications = Notification::where(function ($query) use ($show_to_role, $show_to) {
                $query->whereIn('show_to_role', $show_to_role)
                    ->orWhereIn('show_to', $show_to);
            })
                ->orderBy('id', 'desc')
                ->paginate(100);
        } else {
            $notifications = Notification::where('show_to', Auth::user()->id)->orderBy('id', 'desc')->paginate(100);
        }

        return view('all_notifications', compact('notifications'));
    }

    public function move_numbers()
    {
        $affiliator_counter = 0;
        $client_counter = 0;

        $affiliators = Affiliator::all();
        foreach ($affiliators as $record) {
            if ($record->phone != '') {
                $phone = $record->phone;
                $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
                if (empty($number)) {
                    DB::table('numbers')->insert(array('number' => $phone, 'type' => 'affiliators', 'client_id' => $record->id));
                    $affiliator_counter++;
                }
            }
            if ($record->telephone != '') {
                $phone = $record->telephone;
                $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
                if (empty($number)) {
                    DB::table('numbers')->insert(array('number' => $phone, 'type' => 'affiliators', 'client_id' => $record->id));
                    $affiliator_counter++;
                }
            }
            if ($record->telephone1 != '') {
                $phone = $record->telephone1;
                $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
                if (empty($number)) {
                    DB::table('numbers')->insert(array('number' => $phone, 'type' => 'affiliators', 'client_id' => $record->id));
                    $affiliator_counter++;
                }
            }
        }

        $clients = Clients::all();
        foreach ($clients as $record) {
            if ($record->phone != '') {
                $phone = $record->phone;
                $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
                if (empty($number)) {
                    DB::table('numbers')->insert(array('number' => $phone, 'type' => 'clients', 'client_id' => $record->id));
                    $client_counter++;
                }
            }
            if ($record->telephone != '') {
                $phone = $record->telephone;
                $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
                if (empty($number)) {
                    DB::table('numbers')->insert(array('number' => $phone, 'type' => 'clients', 'client_id' => $record->id));
                    $client_counter++;
                }
            }
            if ($record->telephone1 != '') {
                $phone = $record->telephone1;
                $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
                if (empty($number)) {
                    DB::table('numbers')->insert(array('number' => $phone, 'type' => 'clients', 'client_id' => $record->id));
                    $client_counter++;
                }
            }
        }

        echo "All numbers has been add into Table";
        echo '<br>';
        echo $client_counter . " Clients add into Table";
        echo '<br>';
        echo $affiliator_counter . " Affiliators add into Table";
        echo '<br>';
    }

    public function confirmUserAjax(Request $request)
    {
        // $first_nbr=mb_substr($request->phone, 0 , 1);

        // if($first_nbr == 0){
        //     $phone = substr($request->phone, 1);
        // }else{
        $phone = $request->phone;
        // }

        $number = Number::Where('number', 'like', '%' . $phone . '%')->first();

        // print($number);

        if ($number != '') {
            if ($number->type == 'affiliators') {
                if ($number->affiliator->user_id === 0 || $number->affiliator->user_id === null) {
                    return
                        "<span style='color:red;' class='client_exist'>
                            Affiliator already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this affiliator (" . $number->affiliator->name . ").
                        </span>";
                }

                return
                    "<span style='color:red;' class='client_exist'>
                    Affiliator Already Exist Under " . $number->affiliator->assigned->name . " (" . $number->affiliator->name . ")
                </span>";
            }
            if ($number->type == 'clients') {
                if ($number->client->user_id === 0 || $number->client->user_id === null) {
                    return
                        "<span style='color:red;' class='client_exist'>
                            Client already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this client (" . $number->client->name . ").
                        </span>";
                }

                return
                    "<span style='color:red;' class='client_exist'>
                        Client Already Exist Under " . $number->client->assigned->name . " (" . $number->client->name . ")
                </span>";
            }
        }
    }

    public function confirmEditUserAjax(Request $request)
    {
        // $first_nbr=mb_substr($request->phone, 0 , 1);

        // if($first_nbr == 0){
        //     $phone = substr($request->phone, 1);
        // }else{
        $phone = $request->phone;
        // }

        $number = Number::Where('number', 'like', '%' . $phone . '%')->Where('client_id', '!=', $request->client_id)->first();

        // print($number);

        if ($number != '') {
            if ($number->type == 'affiliators') {
                if ($number->affiliator->user_id === 0 || $number->affiliator->user_id === null) {
                    return
                        "<span style='color:red;' class='client_exist'>
                            Affiliator already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this affiliator (" . $number->affiliator->name . ").
                        </span>";
                }

                return
                    "<span style='color:red;' class='client_exist'>
                    Affiliator Already Exist Under " . $number->affiliator->assigned->name . " (" . $number->affiliator->name . ")
                </span>";
            }
            if ($number->type == 'clients') {
                if ($number->client->user_id === 0 || $number->client->user_id === null) {
                    return
                        "<span style='color:red;' class='client_exist'>
                            Client already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this client (" . $number->client->name . ").
                        </span>";
                }

                return
                    "<span style='color:red;' class='client_exist'>
                        Client Already Exist Under " . $number->client->assigned->name . " (" . $number->client->name . ")
                </span>";
            }
        }
    }

    public function confirmMultiPhoneAjax(Request $request)
    {
        // $first_nbr=mb_substr($request->phone, 0 , 1);

        // if($first_nbr == 0){
        //     $phone = substr($request->phone, 1);
        // }else{
        $phone = $request->phone;
        // }

        $number = Number::Where('number', 'like', '%' . $phone . '%')->first();
        // print($number);

        $html = '';

        if ($number != '') {
            if ($number->type == 'affiliators') {
                if ($number->affiliator->user_id === 0 || $number->affiliator->user_id === null) {
                    $html .=
                        "<span style='color:red;' class='client_exist'>
                            Affiliator already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this affiliator (" . $number->affiliator->name . ").
                        </span>";
                } else {
                    $html .= "<span style='color:red;' class='client_exist'>
                                Affiliator Already Exist Under " . $number->affiliator->assigned->name . " (" . $number->affiliator->name . ")
                            </span>";
                }
            }
            if ($number->type == 'clients') {
                if ($number->client->user_id === 0 || $number->client->user_id === null) {
                    $html .= "<span style='color:red;' class='client_exist'>
                            Client already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this client (" . $number->client->name . ").
                        </span>";
                } else {
                    $html .= "<span style='color:red;' class='client_exist'>
                                Client Already Exist Under " . $number->client->assigned->name . " (" . $number->client->name . ")
                            </span>";
                }
            }
        }


        return response()->json(array(
            'html' => $html,
            'id_counter' => $request->id_counter
        ));
    }

    public function confirmEditMultiPhoneAjax(Request $request)
    {
        // $first_nbr=mb_substr($request->phone, 0 , 1);

        // if($first_nbr == 0){
        //     $phone = substr($request->phone, 1);
        // }else{
        $phone = $request->phone;
        // }

        $number = Number::Where('number', 'like', '%' . $phone . '%')->Where('client_id', '!=', $request->client_id)->first();

        $html = '';

        if ($number != '') {
            if ($number->type == 'affiliators') {
                if ($number->affiliator->user_id === 0 || $number->affiliator->user_id === null) {
                    $html .=
                        "<span style='color:red;' class='client_exist'>
                           Affiliator already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this affiliator (" . $number->affiliator->name . ").
                       </span>";
                } else {
                    $html .= "<span style='color:red;' class='client_exist'>
                                Affiliator Already Exist Under " . $number->affiliator->assigned->name . " (" . $number->affiliator->name . ")
                            </span>";
                }
            }
            if ($number->type == 'clients') {
                if ($number->client->user_id === 0 || $number->client->user_id === null) {
                    $html .=
                        "<span style='color:red;' class='client_exist'>
                            Client already exists, but the user ID is 0. <br> Which means it has not been assigned to anyone yet. <br> Please assign a user to this client (" . $number->client->name . ").
                        </span>";
                } else {
                    $html .= "<span style='color:red;' class='client_exist'>
                                Client Already Exist Under " . $number->client->assigned->name . " (" . $number->client->name . ")
                            </span>";
                }
            }
        }

        return response()->json(array(
            'html' => $html,
            'id_counter' => $request->id_counter
        ));
    }

    public function deleteMultiPhoneAjax(Request $request)
    {
        Number::where('id', $request->number_id)->Where('client_id', $request->client_id)->delete();
        return response()->json();
    }

    // ================= multi shared & transfer lead store===================

    public function multisharedleadstore(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); // exit;

        if (Auth::user()->can('lead.share') || Auth::user()->can('trashed.lead.share')) {
            $lead_ids = $request->lead_ids;
            $from_user_ids = $request->from_user;
            $lead_shared_by = $request->shared_by;
            $to_user = $request->to_user;
            $note = $request->note;

            if ($lead_ids == '') {
                return back()->withInput()->withErrors(__('Please Select At-least 1 Lead..!'));
            }
            if ($from_user_ids == '') {
                return back()->withInput()->withErrors(__('From (User) ID is missing..!'));
            }
            if ($to_user == '') {
                return back()->withInput()->withErrors(__('To (User) ID is missing..!'));
            }
            if ($note == '') {
                return back()->withInput()->withErrors(__('Note Field is Rrquired..!'));
            }

            $lead_ids_array = explode(',', $lead_ids);
            $from_ids_array = explode(',', $from_user_ids);

            // print_r($lead_ids_array); exit;

            foreach ($lead_ids_array as $key => $lead_id) {
                $from_user = $from_ids_array[$key];

                // echo "Lead _id = ".$lead_id . " From User Id = ".$from_user  ; echo "<br>"; exit();

                $record = Leads::where('id', $lead_id)->first();
                if ($record->share_id == 0) {
                    ShareLead::firstOrCreate(
                        [
                            'lead_id' => $lead_id,
                            'from_user' => $from_user,
                            'to_user' => $to_user,
                            'shared_by' => $lead_shared_by,
                            'status' => 1,
                            'note' => $note,
                        ]
                    );

                    Leads::where('id', $lead_id)->update(array('share_id' => $to_user, 'is_delete' => 0));

                    // ===============Notification===============
                    $user =   User::where('id', $to_user)->first();
                    $from =   User::where('id', $from_user)->first();
                    $shared_by =  User::where('id', $lead_shared_by)->first();

                    $data = array(
                        'type' => 'Lead Share',
                        'msg_body' => 'Lead Has Shared From ' . $from->name . ' To ' . $user->name . ' By ' . $shared_by->name . '. Lead ID: ' . $lead_id,
                        'created_by' => $shared_by->id,
                        'show_to' => $user->id,
                        'show_to_role' => 0,
                        'redirect' => 'leads',
                    );
                    Helper::notification($data);
                    $data = array(
                        'type' => 'Lead Share',
                        'msg_body' => 'Lead Has Shared From ' . $from->name . ' To ' . $user->name . ' By ' . $shared_by->name . '. Lead ID: ' . $lead_id,
                        'created_by' => $shared_by->id,
                        'show_to' => $from->id,
                        'show_to_role' => 0,
                        'redirect' => 'leads',
                    );
                    Helper::notification($data);
                    // ==========================================
                }
            }
            // echo "check"; exit;

            // return redirect()->back()->withStatus(__('Leads Shared Successfully.'));
            return back()->withInput()->withStatus(__('Leads Shared Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function multitransferleadstore(Request $request)
    {
        // echo "<pre>"; print_r($request->all());// exit;
        if (Auth::user()->can('lead.transfer') || Auth::user()->can('trashed.lead.transfer')) {
            $lead_ids = $request->lead_ids;
            $from_user_ids = $request->from_users;
            $lead_transfer_by = $request->transfer_by;
            $client_ids = $request->client_ids;
            $to_user = $request->to_user;
            $note = $request->note;

            if ($lead_ids == '') {
                return back()->withInput()->withErrors(__('Please Select At-least 1 Lead..!'));
            }
            if ($from_user_ids == '') {
                return back()->withInput()->withErrors(__('From (User) ID is missing..!'));
            }
            if ($client_ids == '') {
                return back()->withInput()->withErrors(__('Client ID is missing..!'));
            }
            if ($to_user == '') {
                return back()->withInput()->withErrors(__('To (User) ID is missing..!'));
            }
            if ($note == '') {
                return back()->withInput()->withErrors(__('Note Field is Rrquired..!'));
            }

            $lead_ids_array = explode(',', $lead_ids);
            $from_ids_array = explode(',', $from_user_ids);
            $client_ids_array = explode(',', $client_ids);

            // echo "<pre>"; print_r($lead_ids_array); echo "<br>";
            // echo "<pre>"; print_r($client_ids_array); exit;

            foreach ($lead_ids_array as $key => $lead_id) {
                $from_user = $from_ids_array[$key];
                $client_id = $client_ids_array[$key];

                // echo "Lead _id = ".$lead_id . " From User Id = ".$from_user . " Client Id = ".$client_id  ; echo "<br>" ; exit();

                TransferLead::firstOrCreate(
                    [
                        'lead_id' => $lead_id,
                        'from_user' => $from_user,
                        'to_user' => $to_user,
                        'transfer_by' => $lead_transfer_by,
                        'status' => 1,
                        'note' => $note,
                    ]
                );

                // Replace used id in Leads table
                Leads::where('id', $lead_id)->update(array('user_id' => $to_user, 'is_delete' => 0));
                // Replace used id in clients table
                Clients::where('id', $client_id)->where('user_id', $from_user)->update(array('user_id' => $to_user));
                // Replace used id in Task table
                // Task::where('client_id', $client_id)->where('added_by', $from_user)->update(array('added_by' => $to_user));

                // ===============Notification===============
                $user = User::where('id', $to_user)->first();
                $from = User::where('id', $from_user)->first();
                $transfer_by = User::where('id', $lead_transfer_by)->first();

                $data = array(
                    'type' => 'Lead Transfer',
                    'msg_body' => 'Lead Has Transfered From ' . $from->name . ' To ' . $user->name . ' By ' . $transfer_by->name . '. Lead ID: ' . $lead_id,
                    'created_by' => $transfer_by->id,
                    'show_to' => $user->id,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);
                $data = array(
                    'type' => 'Lead Transfer',
                    'msg_body' => 'Lead Has Transfered From ' . $from->name . ' To ' . $user->name . ' By ' . $transfer_by->name . '. Lead ID: ' . $lead_id,
                    'created_by' => $transfer_by->id,
                    'show_to' => $from->id,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);
                // =============================================

            }

            // return redirect()->back()->withStatus(__('Leads Tranfered Successfully.'));
            return back()->withInput()->withStatus(__('Leads Tranfered Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    // ================= multi shared & transfer lead store===================

    public function move_notifications()
    {
        $yes_notifications = Notification::where('show_to_role', 'Yes')->orderBy('id', 'ASC')->get();
        $no_notifications = Notification::where('show_to_role', 'No')->orderBy('id', 'ASC')->get();

        foreach ($yes_notifications as $notification) {
            if ($notification->showTo->role == 1 || $notification->showTo->role == 5) {
                if ($notification->showTo->role == 1) {
                    $role = 5;
                } elseif ($notification->showTo->role == 5) {
                    $role = 1;
                } else {
                    $role = 0;
                }

                Notification::insert(
                    [
                        'type'          => $notification->type,
                        'msg_body'      => $notification->msg_body,
                        'created_by'    => $notification->created_by,
                        'show_to'       => $notification->show_to,
                        'show_to_role'  => $role,
                        'redirect'      => $notification->redirect,
                        'today'         => $notification->today,

                        'created_at'    => $notification->created_at,
                        'updated_at'    => $notification->updated_at,
                        'user_read_at'  => $notification->user_read_at,
                        'role_read_at'  => $notification->role_read_at,
                        'read_by_user'  => $notification->read_by_user,
                        'read_by_role'  => $notification->read_by_role,
                    ]
                );
            } else {
                $roles = array(1, 5);
                foreach ($roles as $role) {
                    Notification::insert(
                        [
                            'type'          => $notification->type,
                            'msg_body'      => $notification->msg_body,
                            'created_by'    => $notification->created_by,
                            'show_to'       => $notification->show_to,
                            'show_to_role'  => $role,
                            'redirect'      => $notification->redirect,
                            'today'         => $notification->today,

                            'created_at'    => $notification->created_at,
                            'updated_at'    => $notification->updated_at,
                            'user_read_at'  => $notification->user_read_at,
                            'role_read_at'  => $notification->role_read_at,
                            'read_by_user'  => $notification->read_by_user,
                            'read_by_role'  => $notification->read_by_role,
                        ]
                    );
                }
            }
        }

        foreach ($no_notifications as $notification) {
            Notification::insert(
                [
                    'type'          => $notification->type,
                    'msg_body'      => $notification->msg_body,
                    'created_by'    => $notification->created_by,
                    'show_to'       => $notification->show_to,
                    'show_to_role'  => 0,
                    'redirect'      => $notification->redirect,
                    'today'         => $notification->today,

                    'created_at'    => $notification->created_at,
                    'updated_at'    => $notification->updated_at,
                    'user_read_at'  => $notification->user_read_at,
                    'role_read_at'  => $notification->role_read_at,
                    'read_by_user'  => $notification->read_by_user,
                    'read_by_role'  => $notification->read_by_role,
                ]
            );
        }

        Notification::where('show_to_role', 'Yes')->delete();
        Notification::where('show_to_role', 'No')->delete();


        echo "All Notifications Has Been Done";
    }


    public function multiLeadsDeletePermanently(Request $request)
    {
        // echo "<pre>"; print_r($request->all());exit;
        if (Auth::user()->can('trashed.lead.delete')) {
            $lead_ids = $request->multi_del_lead_ids;

            if ($lead_ids == '') {
                return back()->withInput()->withErrors(__('Please Select At-least 1 Lead..!'));
            }
            $lead_ids_array = explode(',', $lead_ids);

            //  echo "<pre>"; print_r($lead_ids_array); echo "<br>"; exit;

            foreach ($lead_ids_array as $key => $lead_id) {
                $lead = Leads::findOrFail($lead_id);
                $lead->task()->delete();
                //  echo "<pre>"; print_r($lead->task()->get()); echo "<br>"; exit;
                $lead->delete();
            }
            return redirect()->back()->withStatus(__('Leads Permanently Deleted Successfully.'));
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function multiTrashLeads(Request $request)
    {
        $leadIds = $request->leadids;

        if (!$leadIds || count($leadIds) == 0) {
            return redirect()->back()->with('error', 'No leads selected.');
        }

        Leads::whereIn('id', $leadIds)->update([
            'is_delete' => 1,
            'last_updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('status', count($leadIds) . ' lead(s) trashed successfully.');
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'status' => ['required', new Enum(LeadFeedBackStatus::class)],
            'remarks' => 'required',
            'amount' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::requiredIf($request->status == LeadFeedBackStatus::SALE_CLOSED->value),
            ],
        ]);

        // $alreadySent = LeadFeedBack::where('lead_id', $request->lead_id)
        //     ->where('status', $request->status)
        //     ->where('facebook_synced', true)
        //     ->exists();

        // if ($alreadySent) {
        //     return back()->with('error', 'Facebook event already sent.');
        // }

        $feedback = LeadFeedBack::create([
            'lead_id' => $request->lead_id,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'amount' => $request->amount,
            'created_by' => Auth::id()
        ]);

        SendFacebookConversionJob::dispatch($feedback);

        return redirect()->back()->with('status', ' Feedback submitted successfully.');
    }
}
