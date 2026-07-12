<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Affiliator;
use App\Models\User;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\Project;
use App\Models\AffiliatorTask;
use App\Models\Offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Http\Helpers\Helper;
use App\Models\Number;
use Carbon\Carbon;
use App\Models\Location;

class AffiliatorsController extends Controller {

    public function index()
    {
        if(Auth::user()->can('affiliator.view'))
        {
            $id = 0;
            $search_person = 'user';
            $affiliators = Affiliator::where('user_id',  Auth::user()->id)->where('is_delete',  0)->orderBy('id', 'desc')->paginate(30);
            // ====== Users With Helper======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users']->where('office_id' , 1);
            // ====== Users With Helper======

        $locations = Location::whereHas('affiliators')->get();

            return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users', 'locations'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function All_Affiliator(Request $request, $id) {
        $search_person = 'manager';
        $affiliators = Affiliator::where('user_id', $id)->where('is_delete',  0)->orderBy('id', 'desc')->paginate(30);

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
        // ====== Users With Helper======

        $locations = Location::whereHas('affiliators')->get();

        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users', 'locations'));
    }

    public function dealors() {
        $search_person = 'user';
        $affiliators = Affiliator::where('user_id', Auth::user()->id)->where('is_delete',  0)->orderBy('id', 'desc')->where('status', 1)->where('type', 'Dealer')->paginate(30);
        $id = 0;

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ====== Users With Helper======

        $locations = Location::whereHas('affiliators')->get();

        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users', 'locations'));
    }

    public function freeliencer()
    {
        $search_person = 'user';
        $affiliators = Affiliator::where('user_id', Auth::user()->id)->where('is_delete',  0)->orderBy('id', 'desc')->where('status', 1)->where('type', 'Freelancer')->paginate(30);
        $id = 0;

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ====== Users With Helper======

        $locations = Location::whereHas('affiliators')->get();

        return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users' , 'locations'));
    }

    public function affilitor_leads(Affiliator $affiliator)
    {
        if(Auth::user()->can('lead.view'))
        {
            $search_person = 'user';
            $id = 0;
            $find_result= 0;

            if ($affiliator->status == 1) {
                $sources = LeadSource::where('subtype', 'user')->get();
                $users = User::where('parent', Auth::user()->id)->get();
                $allocation = User::where('parent', Auth::user()->id)->get();
                $projects = Project::orderBy('id', 'ASC')->get();
                $leads = Leads::where('afflilate_id', $affiliator->id)->orderBy('id', 'desc')->paginate(30);
                $offices = Offices::orderBy('id', 'ASC')->get();
                return view('leads.index', compact('find_result','allocation', 'offices' ,'sources', 'leads', 'users', 'projects', 'search_person', 'id'));
            } else {
                return redirect('affiliators')->withErrors(__('Affiliator does not exist or approved'));
            }
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function create()
    {
        if(Auth::user()->can('affiliator.create')){
            $locations = Location::with('parent.parent.parent.parent')->orderBy('name')->get();
            return view('affiliators.create', compact('locations'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function store(Request $request)
    {
        // echo '<pre>';print_r($request->input()); exit;

        if(Auth::user()->can('affiliator.create'))
        {
            if($request->international_phone_dynamic)
            {
                $request->validate([
                    'international_phone_dynamic.*' => 'required'
                ]);

                $numericValues = array();
                $international_full_number = array();

                foreach ($request->international_phone_dynamic as $key => $value) {
                    if (is_numeric($key))
                    {
                        $numericValues[] = $value;
                    }
                    else
                    {
                        $international_full_number[$key] = $value;
                    }
                }

                // echo "<pre>";print_r($international_full_number); exit;

                foreach ($international_full_number as $key => $number)
                {
                    if($number !='' && is_numeric($number))
                    {
                        $record=Number::Where('number', 'like', '%'.$number.'%')->first();

                        if ($record)
                        {
                            if($record->type == 'affiliators')
                            {
                                if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                                    return back()->withInput()->withErrors([
                                        'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                                    ]);
                                }
                                return back()->withInput()->withErrors(['Affiliator already exist under ' .$record->affiliator->assigned->name. ' (' .$record->affiliator->name. ')']);
                            }
                            if($record->type == 'clients')
                            {
                                if ($record->client->user_id === 0 || $record->client->user_id === null) {
                                    return back()->withInput()->withErrors([
                                        'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                                    ]);
                                }
                                return back()->withInput()->withErrors(['Client already exist under ' .$record->client->assigned->name. ' (' .$record->client->name. ')']);
                            }
                        }
                    }
                }
            }

            if($request->ptclphone !=''){
                $phone = $request->input('ptclphone');
            }
            if($request->phone !=''){
                $phone = $request->input('full_number');
            }

            $telephone = '';
            $telephone1 = '';

            if ($phone != '')
            {
                $record=Number::Where('number', 'like', '%'.$phone.'%')->first();
            }

            if (empty($record)) {
                if ($request->type == 'Dealer') {
                    $validated = $request->validate([
                        // 'phone' => 'required|unique:affiliators',
                        'phone'      => 'required_without:ptclphone|unique:affiliators',
                        'ptclphone'  => 'required_without:phone',
                        'name' => 'required',
                        'business' => 'required',
                        'address' => 'required',
                        'cnicf' => 'required',
                        'cnicb' => 'required',
                        'offinspic' => 'required',
                        'offoutspic' => 'required',
                        'offboardpic' => 'required',
                        'location_id' => ['nullable', 'exists:locations,id'],
                    ]);
                } else {
                    $validated = $request->validate([
                        // 'phone' => 'required|unique:affiliators',
                        'phone'      => 'required_without:ptclphone|unique:affiliators',
                        'ptclphone'  => 'required_without:phone',
                        'name' => 'required',
                        'business' => 'required',
                        'address' => 'required',
                        'cnicf' => 'required',
                        'cnicb' => 'required',
                        'location_id' => ['nullable', 'exists:locations,id'],
                    ]);
                }

                $cnicf = '';
                if ($request->file('cnicf')) {
                    $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
                    $cnicf = $request->file('cnicf')->storeAs('affiliators', $fileName, 'public');
                }
                $cnicb = '';
                if ($request->file('cnicb')) {
                    $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
                    $cnicb = $request->file('cnicb')->storeAs('affiliators', $fileName, 'public');
                }
                $offinspic = '';
                if ($request->file('offinspic')) {
                    $fileName = time() . '_' . $request->file('offinspic')->getClientOriginalName();
                    $offinspic = $request->file('offinspic')->storeAs('affiliators', $fileName, 'public');
                }
                $offoutspic = '';
                if ($request->file('offoutspic')) {
                    $fileName = time() . '_' . $request->file('offoutspic')->getClientOriginalName();
                    $offoutspic = $request->file('offoutspic')->storeAs('affiliators', $fileName, 'public');
                }
                $offboardpic = '';
                if ($request->file('offboardpic')) {
                    $fileName = time() . '_' . $request->file('offboardpic')->getClientOriginalName();
                    $offboardpic = $request->file('offboardpic')->storeAs('affiliators', $fileName, 'public');
                }
                $offvisitpic = '';
                if ($request->file('offvisitpic')) {
                    $fileName = time() . '_' . $request->file('offvisitpic')->getClientOriginalName();
                    $offvisitpic = $request->file('offvisitpic')->storeAs('affiliators', $fileName, 'public');
                }



                $affiliator = Affiliator::firstOrCreate(
                                [
                            'name' => $request->name,
                            'email' => $request->email,
                            'business' => $request->business,
                            // 'phone' => $request->countryCode . $request->phone,
                            'phone' => $phone,
                            'telephone' => $telephone,
                            'telephone1' => $telephone1,
                            'address' => $request->address,
                            'user_id' => $request->user_id,
                            'type' => $request->type,
                            'cnic' => $request->cnic,
                            'cnicf' => $cnicf,
                            'cnicb' => $cnicb,
                            'offinspic' => $offinspic,
                            'offoutspic' => $offoutspic,
                            'offboardpic' => $offboardpic,
                            'offvisitpic' => $offvisitpic,
                            'location_id' => $request->location_id,
                            // 'password' => Hash::make($request->password),
                            // 'is_login' => $login,
                                ], [
                            'phone' => $phone,
                                ]
                );


                if ($request->phone !=''){
                    Number::insert([ 'number' => $phone, 'type' => 'affiliators', 'client_id' => $affiliator->id,]);
                }if ($request->ptclphone !=''){
                    Number::insert([ 'number' => $phone, 'type' => 'affiliators', 'client_id' => $affiliator->id,]);
                }


                if($request->international_phone_dynamic)
                {
                    foreach ($international_full_number as $key => $number)
                    {
                        if($number !='' && is_numeric($number))
                        {
                            $check=Number::Where('number', 'like', '%'.$number.'%')->first();

                            if (empty($check))
                            {
                                Number::insert([ 'number' => $number, 'type' => 'affiliators', 'client_id' => $affiliator->id,]);
                            }
                        }
                    }
                }


                // =====Send Notification To Auth User=====
                    $data = array(
                        'type' => 'New Affiliator',
                        'msg_body' => base64_encode('1 New Affiliator Has Created By '.Auth::user()->name.' ID : <a href="'.url("/affiliators/$affiliator->id/edit/").'">'.$affiliator->id.'</a>'),
                        'created_by' => Auth::user()->id,
                        'show_to' => Auth::user()->id,
                        'show_to_role' => 0,
                        'redirect' => 'affiliators',
                    );
                    Helper::notification($data);

                // ======Send Notification To Parent User====

                    $data = array(
                        'type' => 'New Affiliator',
                        'msg_body' => base64_encode('1 New Affiliator Has Created By '.Auth::user()->name.' ID : <a href="'.url("/affiliators/$affiliator->id/edit/").'">'.$affiliator->id.'</a>'),
                        'created_by' => Auth::user()->id,
                        'show_to' => Auth::user()->parent,
                        'show_to_role' => 0,
                        'redirect' => 'affiliators',
                    );
                    Helper::notification($data);

                // ======Send Notification To Role User====

                    $roleUsers=User::where('role' , 5)->get();

                    foreach($roleUsers as $roleUser)
                    {
                        if(Auth::user()->parent != $roleUser->id)
                        {
                            $data = array(
                                'type' => 'New Affiliator',
                                'msg_body' => base64_encode('1 New Affiliator Has Created By '.Auth::user()->name.' ID : <a href="'.url("/affiliators/$affiliator->id/edit/").'">'.$affiliator->id.'</a>'),
                                'created_by' => Auth::user()->id,
                                'show_to' => $roleUser->id, // Show to role Users as a id
                                'show_to_role' => 0,
                                'redirect' => 'affiliators',
                            );
                            Helper::notification($data);
                        }
                    }
                // ==========Notification End==========


                // =====================Email Notify=============
                            $data = array(
                                'id' => $affiliator->id,
                                'name' => $request->name,
                                'email' => $request->email,
                                // 'phone' => $request->countryCode . $request->phone,
                                'phone' => $phone,
                                'status' => 'Under Progress',
                                'note' => 'Under Progress',
                                'user' => $affiliator->assigned->name,
                            );

                            // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                            $to_email = ['shoaib.khubaib@fairymarketing.com'];
                            $to_name = Auth::user()->name;

                            Mail::send('affiliators.affiliator-mail', $data, function ($message) use ($to_name, $to_email) {
                                $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Affiliator Create Notification');
                                $message->from(config('mail.from.address'), Config('mail.from.name'));
                            });
                // ===============Email Notify=====================

                return redirect('affiliators')->withStatus(__('Affiliator Created Successfully.'));
            } else {

                if($record->type == 'affiliators')
                {
                    if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Affiliator already exist under ' .$record->affiliator->assigned->name. ' (' .$record->affiliator->name. ')']);
                }
                if($record->type == 'clients')
                {
                    if ($record->client->user_id === 0 || $record->client->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Client already exist under ' .$record->client->assigned->name. ' (' .$record->client->name. ')']);
                }
            }

        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function show(Affiliator $affiliator) {
    }

    public function edit(Affiliator $affiliator)
    {
        // echo '<pre>';print_r($affiliator);exit;

        if(Auth::user()->can('affiliator.edit'))
        {
            if ($affiliator->user_id != Auth::user()->id  && Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) {
                return back()->withErrors(__('Not Allowed!'));
            }
            $managers = User::where('role', '=', 2)->get();

            $numbers=Number::Where('type' , 'affiliators')->Where('client_id' , $affiliator->id)
                            ->where('number' , '!=' , $affiliator->phone)->get();


            $phone=Number::Where('type' , 'affiliators')->Where('client_id' , $affiliator->id)
                        ->where('number' , 'Like' , '%'.$affiliator->phone.'%')->first();

            $locations = Location::with('parent.parent.parent.parent')->orderBy('name')->get();
            return view('affiliators.edit', compact('affiliator', 'managers' , 'numbers' , 'phone' ,'locations'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function update(Request $request, Affiliator $affiliator)
    {
        // echo "<pre>"; print_r($request->all());  exit;

        if(Auth::user()->can('affiliator.edit'))
        {
            //======= add new international number check start
                if($request->international_phone_dynamic)
                {
                    $request->validate([
                        'international_phone_dynamic.*' => 'required'
                    ]);

                    $numericValues = array();
                    $international_full_number = array();

                    foreach ($request->international_phone_dynamic as $key => $value)
                    {
                        if (is_numeric($key))
                        {
                            $numericValues[] = $value;
                        }else
                        {
                            $international_full_number[$key] = $value;
                        }
                    }

                    // echo "<pre>";print_r($international_full_number); exit;

                    foreach ($international_full_number as $key => $number)
                    {
                        if($number !='' && is_numeric($number))
                        {
                            // $record=Number::Where('number', 'like', '%'.$phone_number.'%')->first();

                            $record=Number::Where('client_id', '!=' ,$affiliator->id)
                                ->where(function ($query) use ($number)
                                {
                                    $query->orWhere('Number', 'like', '%'.$number.'%');
                                })->first();

                            if ($record)
                            {
                                if($record->type == 'affiliators')
                                {
                                    if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                                        return back()->withInput()->withErrors([
                                            'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                                        ]);
                                    }
                                    return back()->withInput()->withErrors(['Affiliator already exist under ' .$record->affiliator->assigned->name. ' (' .$record->affiliator->name. ')']);
                                }
                                if($record->type == 'clients')
                                {
                                    if ($record->client->user_id === 0 || $record->client->user_id === null) {
                                        return back()->withInput()->withErrors([
                                            'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                                        ]);
                                    }
                                    return back()->withInput()->withErrors(['Client already exist under ' .$record->client->assigned->name. ' (' .$record->client->name. ')']);
                                }
                            }
                        }
                    }
                }
            //======= add new international number check end

            //======= edit international number check start
                if($request->edit_international_phone_dynamic)
                {
                    $request->validate(
                    [
                        'edit_international_phone_dynamic.*' => 'required'
                    ]);

                    $editnumericValues = array();
                    $edit_international_full_number = array();

                    foreach ($request->edit_international_phone_dynamic as $key => $value) {
                        if (is_numeric($key))
                        {
                            $editnumericValues[] = $value;
                        }else
                        {
                            $edit_international_full_number[$key] = $value;
                        }
                    }

                    foreach ($edit_international_full_number as $key => $number)
                    {
                        if($number !='' && is_numeric($number))
                        {
                            // $record=Number::Where('number', 'like', '%'.$phone_number.'%')->first();

                            $record=Number::Where('client_id', '!=' ,$affiliator->id)
                                ->where(function ($query) use ($number)
                                {
                                    $query->orWhere('Number', 'like', '%'.$number.'%');
                                })->first();

                            if ($record)
                            {
                                if($record->type == 'affiliators')
                                {
                                    if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                                        return back()->withInput()->withErrors([
                                            'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                                        ]);
                                    }
                                    return back()->withInput()->withErrors(['Affiliator already exist under ' .$record->affiliator->assigned->name. ' (' .$record->affiliator->name. ')']);
                                }
                                if($record->type == 'clients')
                                {
                                    if ($record->client->user_id === 0 || $record->client->user_id === null) {
                                        return back()->withInput()->withErrors([
                                            'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                                        ]);
                                    }
                                    return back()->withInput()->withErrors(['Client already exist under ' .$record->client->assigned->name. ' (' .$record->client->name. ')']);
                                }
                            }
                        }
                    }
                }
            //======= edit international number check end

            $validated = $request->validate([
                'phone'      => 'required_without:ptclphone|unique:affiliators,phone,' . $affiliator->id,
                'ptclphone'  => 'required_without:phone|unique:affiliators,phone,' . $affiliator->id,
                'name'       => 'required',
                'business'   => 'required',
                'address'    => 'required',
                'location_id' => ['nullable', 'exists:locations,id'],
            ]);

            // Determine which phone to use
            $phone = $request->filled('phone') ? $request->input('full_number') : $request->input('ptclphone');

            $record=Number::Where('client_id', '!=' ,$affiliator->id)
                    ->where(function ($query) use ($phone)
                    {
                        $query->orWhere('Number', 'like', '%'.$phone.'%');
                    })->first();

            $telephone = '';
            $telephone1 = '';

            if (empty($record))
            {

                $cnicf = '';
                if ($request->file('cnicf')) {
                    $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
                    $cnicf = $request->file('cnicf')->storeAs('affiliators', $fileName, 'public');
                } else {
                    $cnicf = $request->input('oldcnicf');
                }

                $cnicb = '';
                if ($request->file('cnicb')) {
                    $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
                    $cnicb = $request->file('cnicb')->storeAs('affiliators', $fileName, 'public');
                } else {
                    $cnicb = $request->input('oldcnicb');
                }

                $offinspic = '';
                if ($request->file('offinspic')) {
                    $fileName = time() . '_' . $request->file('offinspic')->getClientOriginalName();
                    $offinspic = $request->file('offinspic')->storeAs('affiliators', $fileName, 'public');
                } else {
                    $offinspic = $request->input('oldoffinspic');
                }
                $offoutspic = '';
                if ($request->file('offoutspic')) {
                    $fileName = time() . '_' . $request->file('offoutspic')->getClientOriginalName();
                    $offoutspic = $request->file('offoutspic')->storeAs('affiliators', $fileName, 'public');
                } else {
                    $offoutspic = $request->input('oldoffoutspic');
                }
                $offboardpic = '';
                if ($request->file('offboardpic')) {
                    $fileName = time() . '_' . $request->file('offboardpic')->getClientOriginalName();
                    $offboardpic = $request->file('offboardpic')->storeAs('affiliators', $fileName, 'public');
                } else {
                    $offboardpic = $request->input('oldoffboardpic');
                }
                $offvisitpic = '';
                if ($request->file('offvisitpic')) {
                    $fileName = time() . '_' . $request->file('offvisitpic')->getClientOriginalName();
                    $offvisitpic = $request->file('offvisitpic')->storeAs('affiliators', $fileName, 'public');
                } else {
                    $offvisitpic = $request->input('oldoffvisitpic');
                }

                $note='';
                if ($request->note != '') {
                    $note = $request->note;
                }else{
                    $note = $affiliator->note;
                }

                // Validation
                if ($affiliator->type == 'Dealer')
                {
                    if ($cnicf == '' ||  $cnicb == '' ||  $offinspic == '' ||  $offoutspic == '' ||  $offboardpic == '')
                    {
                        return back()->withInput()->withErrors(['Please Select All The Required Images First...!']);
                    }
                } else {
                    if ($cnicf == '' ||  $cnicb == '')
                    {
                        return back()->withInput()->withErrors(['Please Select All The Required Images First...!']);
                    }
                }



                // ===============Notification===============


                    if($request->status==0)
                    {
                        $data = array(
                            'type' => 'Affiliator Disable',
                            'msg_body' =>base64_encode('Affiliator Has Disabled By ' .Auth::user()->name. ' ID : <a href="'.url("/affiliators/$request->affiliator_id/edit/").'">'.$request->affiliator_id.'</a>'),
                            'created_by' => Auth::user()->id,
                            'show_to' => $request->added_by_user_id,
                            'show_to_role' => 0,
                            'redirect' => 'affiliators',
                        );
                        Helper::notification($data);
                    }
                    if($request->status==1)
                    {
                        $data = array(
                            'type' => 'Affiliator Enable',
                            'msg_body' =>base64_encode('Affiliator Has Enabled By ' .Auth::user()->name. ' ID : <a href="'.url("/affiliators/$request->affiliator_id/edit/").'">'.$request->affiliator_id.'</a>'),
                            'created_by' => Auth::user()->id,
                            'show_to' => $request->added_by_user_id,
                            'show_to_role' => 0,
                            'redirect' => 'affiliators',
                        );
                        Helper::notification($data);
                    }
                    if($request->status==2)
                    {
                        // echo 'Affiliator Has Rejected By'.Auth::user()->name.'<br>'. '<a href="'.url("/affiliators/".$request->affiliator_id."/edit/").'"> Affiliator Has Rejected By' .Auth::user()->name.'</a>'; exit;

                        $data = array(
                            'type' => 'Affiliator Reject',
                            // 'msg_body' => 'Affiliator Has Rejected By'.Auth::user()->name.'<br>'. '<a href="'.url("/affiliators/".$request->affiliator_id."/edit/").'">View More</a>',
                            'msg_body' =>base64_encode('Affiliator Has Rejected By ' .Auth::user()->name. ' ID : <a href="'.url("/affiliators/$request->affiliator_id/edit/").'">'.$request->affiliator_id.'</a>'),
                            'created_by' => Auth::user()->id,
                            'show_to' => $request->added_by_user_id,
                            'show_to_role' => 0,
                            'redirect' => 'affiliators',
                        );
                        Helper::notification($data);
                    }
                    // if($request->added_by_user_id==Auth::user()->id)
                    if($request->status==3)
                    {
                        // $user = User::where('id' , $request->added_by_user_id)->first();

                        $data = array(
                            'type' => 'Again Approval',
                            'msg_body' =>base64_encode('Affiliator Needs Again Approval By ' .Auth::user()->name. ' ID : <a href="'.url("/affiliators/$request->affiliator_id/edit/").'">'.$request->affiliator_id.'</a>'),
                            'created_by' => Auth::user()->id,
                            // 'show_to' => $request->added_by_user_id,
                            // 'show_to' => Auth::user()->parent, // $user->parent
                            'show_to' => $affiliator->assigned->parent,
                            'show_to_role' => 0,
                            'redirect' => 'affiliators',
                        );
                        Helper::notification($data);
                    }
                // ===========================================


                // $affiliator->update($request->all());
                // $affiliator->update($request->merge(['phone' => $phone])->all());

                // Get all request data, excluding original phone to prevent conflict
                $data = $request->except(['phone']);
                // Add custom formatted phone
                $data['phone'] = $phone;
                // Update all relevant fields from the request
                $affiliator->update($data);

                $affiliator->cnicf = $cnicf;
                $affiliator->cnicb = $cnicb;
                $affiliator->offinspic = $offinspic;
                $affiliator->offoutspic = $offoutspic;
                $affiliator->offboardpic = $offboardpic;
                $affiliator->offvisitpic = $offvisitpic;
                $affiliator->telephone = $telephone;
                $affiliator->telephone1 = $telephone1;
                $affiliator->note = $note;
                $affiliator->location_id = $request->location_id;
                // $affiliator->phone = $phone;
                $affiliator->save();


                if ($request->phone !='' || $request->ptclphone !='')
                {
                    $id = $request->phone_number_id;

                    Number::updateOrCreate(['id' => $id], [
                        'number' => $phone,
                        'type' => 'affiliators',
                        'client_id' => $affiliator->id,
                    ]);

                }


                if($request->international_phone_dynamic)
                {
                    foreach ($international_full_number as $key => $number)
                    {
                        if($number !='' && is_numeric($number))
                        {
                            $check=Number::Where('client_id' , $affiliator->id)->Where('Number', 'like', '%'.$number.'%')->first();
                            if (empty($check))
                            {
                                Number::insert([ 'number' => $number, 'type' => 'affiliators', 'client_id' => $affiliator->id,]);
                            }
                        }
                    }
                }

                if($request->edit_international_phone_dynamic)
                {
                    $counter=0;
                    foreach ($edit_international_full_number as $key => $number)
                    {
                        $counter=$counter+1;

                        if($number !='' && is_numeric($number))
                        {
                            $exist=Number::Where('Number', 'like', '%'.$number.'%')->first();

                            if (empty($exist))
                            {
                                if('edit_international_full_number'.$counter == $key)
                                {
                                    $id = $request->edit_phone_number_ids[$counter];

                                    Number::findOrFail($id)->update([
                                        'number' => $number,
                                        'type' => 'affiliators',
                                        'client_id' => $affiliator->id,
                                    ]);
                                }
                            }
                        }
                    }
                }

            }
            else
            {
                if($record->type == 'affiliators')
                {
                    if ($record->affiliator->user_id === 0 || $record->affiliator->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Affiliator already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this affiliator (' . $record->affiliator->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Affiliator already exist under ' .$record->affiliator->assigned->name. ' (' .$record->affiliator->name. ')']);
                }
                if($record->type == 'clients')
                {
                    if ($record->client->user_id === 0 || $record->client->user_id === null) {
                        return back()->withInput()->withErrors([
                            'Client already exists but user ID is 0. Which means it has not been assigned to anyone yet. Please assign a user to this client (' . $record->client->name . ')'
                        ]);
                    }
                    return back()->withInput()->withErrors(['Client already exist under ' .$record->client->assigned->name. ' (' .$record->client->name. ')']);
                }
            }

            // =====================================Email Notify=================================================
                $data = array(
                    'id' => $affiliator->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    // 'phone' => $request->countryCode . $request->phone,
                    'phone' => $phone,
                    'status' => $request->status,
                    'note' => $request->note,
                );

                // $to_email = [Auth::user()->email, 'shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                $to_email = [Auth::user()->email,'shoaib.khubaib@fairymarketing.com'];

                $to_name = Auth::user()->name;

                Mail::send('affiliators.affiliator-mail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject('Affiliator Status Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });
            // =====================================Email Notify=================================================

            return back()->withStatus(__('Affiliator Updated Successfully.'));

        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function todolist()
    {
        $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', Auth::user()->id)
                    ->where('affiliatortask.deadline', '<', date('Y-m-d'))
                    ->where('affiliatortask.status', 0)
                    ->orderBy('affiliatortask.id', 'desc')
                    ->select('affiliatortask.*')
                    ->paginate(30);


        // ============
            $today=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', Auth::user()->id)
                    ->whereDate('affiliatortask.deadline',Carbon::today())
                    ->where('affiliatortask.status', 0)
                    ->count();


            $tomorrow=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', Auth::user()->id)
                    ->whereDate('affiliatortask.deadline',Carbon::tomorrow())
                    ->where('affiliatortask.status', 0)
                    ->count();

            $overdue=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', Auth::user()->id)
                    ->whereDate('affiliatortask.deadline', '<',Carbon::today())
                    ->where('affiliatortask.status', 0)
                    ->count();

            $week=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', Auth::user()->id)
                    ->where('affiliatortask.deadline', '>', Carbon::now()->startOfWeek())
                    ->where('affiliatortask.deadline', '<', Carbon::now()->endOfWeek())
                    ->where('affiliatortask.status', 0)
                    ->count();

        // ============

        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ====== Users With Helper======

        $todo = '';
        return view('affiliators.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo'));
    }

    public function todos($todo)
    {
        // echo '<pre>';print_r($todo); exit;

        // Overdue date
        $tdate = strtotime(date('Y-m-d'));
        $tdate = strtotime("+1 day", $tdate);

        $id=Auth::user()->id;

        $today=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', $id)
                    ->where('affiliatortask.deadline', date('Y-m-d'))
                    ->where('affiliatortask.status', 0)
                    ->count();

        $tomorrow=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', $id)
                    ->where('affiliatortask.deadline', date('Y-m-d', $tdate))
                    ->where('affiliatortask.status', 0)
                    ->count();

        $overdue=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', $id)
                    ->where('affiliatortask.deadline', '<', date('Y-m-d'))
                    ->where('affiliatortask.status', 0)
                    ->count();


        // Week Tasks count
        $date = strtotime(date('Y-m-d'));
        $date1 = strtotime("+1 day", $date);
        $date = strtotime("+7 day", $date);
        // $week = AffiliatorTask::where('user_id', $id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->count();
        $week=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', $id)
                    ->where('affiliatortask.deadline', '>', date('Y-m-d', $date1))
                    ->where('affiliatortask.deadline', '<=', date('Y-m-d', $date))
                    ->where('affiliatortask.deadline', '>=', date('Y-m-d'))
                    ->where('affiliatortask.status', 0)
                    ->count();


        $date = strtotime(date('Y-m-d'));
        $date = strtotime("+8 day", $date);
        $future_timestamp = strtotime("+1 month");

        // $alltasks = AffiliatorTask::where('user_id', $id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->count();

        $alltasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where('affiliatortask.user_id', $id)
                    ->where('affiliatortask.deadline', '>', date('Y-m-d', $date))
                    ->where('affiliatortask.deadline', '<=', date('Y-m-d', $future_timestamp))
                    ->where('affiliatortask.status', 0)
                    ->count();


        if ($todo == 'today') {

            // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
            $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where('affiliatortask.user_id', $id)
                        ->where('affiliatortask.deadline', date('Y-m-d'))
                        ->where('affiliatortask.status', 0)
                        ->orderBy('affiliatortask.id', 'desc')
                        ->select('affiliatortask.*')
                        ->paginate(50);

        } elseif ($todo == 'overdue') {
            // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', '<', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);

            $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where('affiliatortask.user_id', $id)
                        ->where('affiliatortask.deadline', '<', date('Y-m-d'))
                        ->where('affiliatortask.status', 0)
                        ->orderBy('affiliatortask.id', 'desc')
                        ->select('affiliatortask.*')
                        ->paginate(50);

        } elseif ($todo == 'tomorrow') {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+1 day", $date);
            // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', date('Y-m-d', $date))->where('status', 0)->orderBy('id', 'desc')->paginate(50);

            $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where('affiliatortask.user_id', $id)
                        ->where('affiliatortask.deadline', date('Y-m-d', $date))
                        ->where('affiliatortask.status', 0)
                        ->orderBy('affiliatortask.id', 'desc')
                        ->select('affiliatortask.*')
                        ->paginate(50);

        } elseif ($todo == 'week') {
            $date = strtotime(date('Y-m-d'));
            $date1 = strtotime("+1 day", $date);
            $date = strtotime("+7 day", $date);
            // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);

            $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where('affiliatortask.user_id', $id)
                        ->where('affiliatortask.deadline', '>', date('Y-m-d', $date1))
                        ->where('affiliatortask.deadline', '<=', date('Y-m-d', $date))
                        ->where('affiliatortask.deadline', '>=', date('Y-m-d'))
                        ->where('affiliatortask.status', 0)
                        ->orderBy('affiliatortask.id', 'desc')
                        ->select('affiliatortask.*')
                        ->paginate(50);

            // echo '<pre>';print_r($tasks); exit;

        } else {
            $date = strtotime(date('Y-m-d'));
            $date = strtotime("+8 day", $date);
            $future_timestamp = strtotime("+1 month");
            // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);

            $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where('affiliatortask.user_id', $id)
                        ->where('affiliatortask.deadline', '>', date('Y-m-d', $date))
                        ->where('affiliatortask.deadline', '<=', date('Y-m-d', $future_timestamp))
                        ->where('affiliatortask.status', 0)
                        ->orderBy('affiliatortask.id', 'desc')
                        ->select('affiliatortask.*')
                        ->paginate(50);
        }

        $users = User::where('parent', $id)->get();
        return view('affiliators.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo', 'alltasks'));
    }

    public function todossearch(Request $request)
    {
        // echo '<pre>';print_r($request->input());exit;

        $condition = array();

        if ($request->input('tasktype') != 0) {
            $condition[] = array('ntype', $request->input('tasktype'));
        }

        if ($request->input('user_id') > 0) {
            $condition[] = array('affiliatortask.user_id', $request->input('user_id'));
        }

        // echo '<pre>';print_r($condition);exit;

        if($request->has('task'))
        {
            if ($request->task == 'overdue') {

                $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where($condition)
                            ->where('affiliators.is_delete', 0)
                            ->where('affiliatortask.deadline', '<', date('Y-m-d'))
                            // ->whereDate('affiliatortask.deadline', '<',Carbon::today())
                            ->where('affiliatortask.status', 0)
                            ->orderBy('affiliatortask.id', 'desc')
                            ->select('affiliatortask.*')
                            ->paginate(50);
            }
            if ($request->task == 'today') {
                // $tasks = Task::where($condition)->whereIn('added_by', $users)->whereDate('deadline', Carbon::today())->orderBy('id', 'desc')->paginate(50);

                $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where($condition)
                            ->where('affiliators.is_delete', 0)
                            ->where('affiliatortask.deadline', date('Y-m-d'))
                            ->where('affiliatortask.status', 0)
                            ->orderBy('affiliatortask.id', 'desc')
                            ->select('affiliatortask.*')
                            ->paginate(50);
            }
            if ($request->task == 'tomorrow') {

                $date = strtotime(date('Y-m-d'));
                $date = strtotime("+1 day", $date);
                // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', date('Y-m-d', $date))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
                $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where($condition)
                            ->where('affiliators.is_delete', 0)
                            ->where('affiliatortask.deadline', date('Y-m-d', $date))
                            ->where('affiliatortask.status', 0)
                            ->orderBy('affiliatortask.id', 'desc')
                            ->select('affiliatortask.*')
                            ->paginate(50);
            }
            if ($request->task == 'week') {

                $date = strtotime(date('Y-m-d'));
                $date1 = strtotime("+1 day", $date);
                $date = strtotime("+7 day", $date);
                // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', '>', date('Y-m-d', $date1))->where('deadline', '<=', date('Y-m-d', $date))->where('deadline', '>=', date('Y-m-d'))->where('status', 0)->orderBy('id', 'desc')->paginate(50);
                $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where($condition)
                            ->where('affiliators.is_delete', 0)
                            ->where('affiliatortask.deadline', '>', date('Y-m-d', $date1))
                            ->where('affiliatortask.deadline', '<=', date('Y-m-d', $date))
                            ->where('affiliatortask.deadline', '>=', date('Y-m-d'))
                            ->where('affiliatortask.status', 0)
                            ->orderBy('affiliatortask.id', 'desc')
                            ->select('affiliatortask.*')
                            ->paginate(50);
            }
            if ($request->task == 'month' || $request->task == 'all')
            {
                $date = strtotime(date('Y-m-d'));
                $date = strtotime("+8 day", $date);
                $future_timestamp = strtotime("+1 month");
                // $tasks = AffiliatorTask::where('user_id', $id)->where('deadline', '>', date('Y-m-d', $date))->where('deadline', '<=', date('Y-m-d', $future_timestamp))->where('status', 0)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(50);
                $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                            ->where($condition)
                            ->where('affiliators.is_delete', 0)
                            ->where('affiliatortask.deadline', '>', date('Y-m-d', $date))
                            ->where('affiliatortask.deadline', '<=', date('Y-m-d', $future_timestamp))
                            ->where('affiliatortask.status', 0)
                            ->orderBy('affiliatortask.id', 'desc')
                            ->select('affiliatortask.*')
                            ->paginate(50);

            }
            // if ($request->task == 'all') {
            //         $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
            //                 ->where($condition)
            //                 ->where('affiliators.is_delete', 0)
            //                 ->where('affiliatortask.deadline', '<', Carbon::now())
            //                 ->where('affiliatortask.status', 0)
            //                 ->orderBy('affiliatortask.id', 'desc')
            //                 ->select('affiliatortask.*')
            //                 ->paginate(50);
            // }
        }
        else
        {
            $tasks=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
            ->where($condition)
            ->where('affiliators.is_delete', 0)
            ->where('affiliatortask.deadline', '<', date('Y-m-d'))
            // ->whereDate('affiliatortask.deadline', '<',Carbon::today())
            ->where('affiliatortask.status', 0)
            ->orderBy('affiliatortask.id', 'desc')
            ->select('affiliatortask.*')
            ->paginate(50);
        }
        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ====== Users With Helper======

        if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14 )
        {
            if($request->input('user_id') > 0 )
            {
                $id = $request->input('user_id');

                $today=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->where('affiliatortask.user_id', $id)
                        ->whereDate('affiliatortask.deadline',Carbon::today())
                        ->where('affiliatortask.status', 0)
                        ->count();

                $tomorrow=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->where('affiliatortask.user_id', $id)
                        ->whereDate('affiliatortask.deadline',Carbon::tomorrow())
                        ->where('affiliatortask.status', 0)
                        ->count();

                $overdue=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->where('affiliatortask.user_id', $id)
                        ->whereDate('affiliatortask.deadline', '<',Carbon::today())
                        ->where('affiliatortask.status', 0)
                        ->count();

                $week=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->where('affiliatortask.user_id', $id)
                        ->where('affiliatortask.deadline', '>', Carbon::now()->startOfWeek())
                        ->where('affiliatortask.deadline', '<', Carbon::now()->endOfWeek())
                        ->where('affiliatortask.status', 0)
                        ->count();

            }
            else
            {
                $today=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->whereDate('affiliatortask.deadline',Carbon::today())
                        ->where('affiliatortask.status', 0)
                        ->count();

                $tomorrow=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->whereDate('affiliatortask.deadline',Carbon::tomorrow())
                        ->where('affiliatortask.status', 0)
                        ->count();

                $overdue=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->whereDate('affiliatortask.deadline', '<',Carbon::today())
                        ->where('affiliatortask.status', 0)
                        ->count();

                $week=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                        ->where('affiliators.is_delete', 0)
                        ->where($condition)
                        ->where('affiliatortask.deadline', '>', Carbon::now()->startOfWeek())
                        ->where('affiliatortask.deadline', '<', Carbon::now()->endOfWeek())
                        ->where('affiliatortask.status', 0)
                        ->count();
            }
        }
        else
        {
            if($request->input('user_id') > 0 ){
                $id = $request->input('user_id');
            }else{
                $id = Auth::user()->id;
            }

            $ids=$users->pluck('id');

            if (!($ids->contains($id))) {
                return back()->withErrors(__('Not Allowed!'));
            }

            $today=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where($condition)
                    ->where('affiliatortask.user_id', $id)
                    ->whereDate('affiliatortask.deadline',Carbon::today())
                    ->where('affiliatortask.status', 0)
                    ->count();

            $tomorrow=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where($condition)
                    ->where('affiliatortask.user_id', $id)
                    ->whereDate('affiliatortask.deadline',Carbon::tomorrow())
                    ->where('affiliatortask.status', 0)
                    ->count();

            $overdue=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where($condition)
                    ->where('affiliatortask.user_id', $id)
                    ->whereDate('affiliatortask.deadline', '<',Carbon::today())
                    ->where('affiliatortask.status', 0)
                    ->count();



            $week=AffiliatorTask::with('addedBy')->join('affiliators', 'affiliators.id', '=', 'affiliatortask.affliator_id')
                    ->where('affiliators.is_delete', 0)
                    ->where($condition)
                    ->where('affiliatortask.user_id', $id)
                    ->where('affiliatortask.deadline', '>', Carbon::now()->startOfWeek())
                    ->where('affiliatortask.deadline', '<', Carbon::now()->endOfWeek())
                    ->where('affiliatortask.status', 0)
                    ->count();
        }

        $todo = $request->task;
        return view('affiliators.todos', compact('tasks', 'users', 'today', 'tomorrow', 'overdue', 'week', 'todo'));
    }

    public function taskstore(Request $request) {

        // echo "<pre>"; print_r($request->all());exit;
        $validated = $request->validate([
            'type' => 'required',
            'subtype' => 'required',
            'completion_date' => 'required',
            'deadline' => 'required',
        ]);

        // $deadline = date("Y-m-d H:i:s", strtotime($request->deadline . $request->time));
        $deadline = date("Y-m-d H:i:s", strtotime($request->deadline.' 23:59:59'));

        // $deadline = date("Y-m-d", strtotime($request->deadline));
        $completion_date = date("Y-m-d", strtotime($request->completion_date));

        AffiliatorTask::where('affliator_id', $request->input('affliator_id'))->where('status', 0)->update(['status' => 1]);
        $task = AffiliatorTask::create($request->merge(['deadline' => $deadline, 'completion_date' => $completion_date])->all());
        $filePath = '';
        $file_location = '';
        if ($request->file) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('task', $fileName, 'public');
            $task->file = $filePath;
        }

        $task->save();
        return redirect()->back()->withStatus(__('Task Created Successfully.'));
    }

    public function getafitasksubtype(Request $request) {
        //echo $request->task; exit;
        $html = '';
        if ($request->task == 'Meetings') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                    <option>Do Nothing</option>
                    <option value="Video Meeting">Video Meeting</option>
                    <option value="Walk-In">Walk-In</option>
                    <option value="Meeting (Done)">Meeting (Done)</option>
                    <option value="Meeting (Arranged)">Meeting (Arranged)</option>
                    <option value="Meeting (Attempt)">Meeting (Attempt)</option>
                    <option value="Meeting (Pushed)">Meeting (Pushed)</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Calls') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>
                    <option value="Contact Client">Contact Client (Call)</option>
                    <option value="Call Attempt">Call Attempt</option>
                    <option value="Followed Up">Followed Up</option>
                    <option value="Whatsapp Call">Whatsapp Call</option>
                    <option value="Whatsapp (Message)">Whatsapp (Message)</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Emails') {
            $html .='<label>Sub Type<sup>*</sup></label>
                <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                    <option value="">Do Nothing</option>
                    <option value="Contacted Client">Contacted Client</option>
                    <option value="Followed Up">Followed Up</option>
                </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        } elseif ($request->task == 'Complaint') {
            $html .='<label>Sub Type<sup>*</sup></label>
                    <select name="subtype" id="afsubtype" class="form-control select-picker" data-size="8" required="">
                        <option value=""> Do Nothing </option>
                        <option value="New Project Lead">New Project Lead</option>
                        <option value="Lack of visit from the BDA side">Lack of visit from the BDA side</option>
                        <option value="Behavioral Issue">Behavioral Issue</option>
                        <option value="Comission Delay">Comission Delay</option>
                        <option value="Do not work with us">Do not work with us</option>
                        <option value="Others">Others</option>
                        <option value="Resolved">Resolved</option>
                    </select><span id="name-error" class="error text-danger subtype-error" for="input-name"></span>';
        }

        return response()->json(['html' => $html]);
    }

    public function get_affiliator_task_history(Request $request)
    {
        $tasks = AffiliatorTask::where('affliator_id', $request->affiliator_id)->orderby('id', 'desc')->get();
        $rows = '';
        if (count($tasks) > 0) {

            foreach ($tasks as $task) {

                $rows .=''
                        . '<tr>'
                        . '<td>#' . $task->id . '</td>'
                        . '<td>' . $task->type . ' - ' . $task->subtype . '<br>' . date('M d, Y', strtotime($task->created_at)) . '</td>'
                        . '<td>'
                        . '<strong>' . $task->addedBy->name . '</strong> - '
                        . '' . date('M d, Y', strtotime($task->completion_date)) . '</br>';
                $rows .= $task->note;
                if ($task->file != '') {
                    $rows .= '<br><a href="' . url('storage/app/public/' . $task->file) . '" download>Download</a>';
                }
                $rows .='</td>'
                        . '<td>#' . date('M d, Y', strtotime($task->created_at)) . '</td>'
                        . '</tr>';
            }
        } else {
            $rows .=''
                    . '<tr>'
                    . '<td>No Task Found!</td>'
                    . '</tr>';
        }
        // $html = view('admin.task.log', compact('tasks'))->render();
        return response()->json(['html' => $rows]);
    }

    public function destroy(Affiliator $affiliator)
    {
        //$affiliator->delete();
        if(Auth::user()->can('affiliator.trash'))
        {
            Leads::where('afflilate_id', $affiliator->id)->update(array('is_delete' => 1));
            $affiliator->is_delete = 1;
            $affiliator->save();

            // return redirect('affiliators')->withStatus(__('Affiliator Deleted Successfully.'));
            return back()->withInput()->withStatus(__('Affiliator Deleted Successfully.'));

        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function trash_affiliators()
    {
        if(Auth::user()->can('trashed.affiliator.view'))
        {
            $id = 0;
            $search_person = 'user';
            if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
                // $users = User::where('role', '!=', 0)->where('office_id', 1)->orderBy('id', 'asc')->get();
                $affiliators = Affiliator::where('is_delete', 1)->orderBy('id', 'desc')->paginate(30);
            }else{
                // $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
                $affiliators = Affiliator::where('user_id', Auth::user()->id)->where('is_delete', 1)->orderBy('id', 'desc')->paginate(30);
            }

            // ====== Users With Helper======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users'];
            // ====== Users With Helper======

            return view('affiliators.trash', compact('affiliators', 'search_person', 'id', 'users'));

        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function resotre_affiliator(Affiliator $affiliator)
    {
        if(Auth::user()->can('trashed.affiliator.restore'))
        {
            Leads::where('afflilate_id' , $affiliator->id )->update(array('is_delete' => 0));
            $affiliator->is_delete = 0 ;
            $affiliator->save();
            return redirect()->back()->withStatus(__('Affiliator Active Successfully.'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function affiliator_delete_permanently(Affiliator $affiliator)
    {
        if(Auth::user()->can('trashed.affiliator.delete'))
        {
            $leads=Leads::where('afflilate_id' , $affiliator->id )->get();
            foreach($leads as $lead){
                $lead->task()->delete();
                $lead->delete();
            }
            Number::where('type', 'affiliators')->Where('client_id', $affiliator->id)->delete();
            AffiliatorTask::where('affliator_id' , $affiliator->id)->delete();
            $affiliator->delete();
            return redirect()->back()->withStatus(__('Affiliator Permanently Deleted Successfully.'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function multiAffiliatorsDeletePermanently(Request $request)
    {
        // echo "<pre>"; print_r($request->all());exit;
        if(Auth::user()->can('trashed.affiliator.delete'))
        {
            $affiliator_ids =$request->multi_del_affiliator_ids;

            if($affiliator_ids == ''){
                return back()->withInput()->withErrors(__('Please Select At-least 1 Record..!'));
            }
            $affiliator_ids_array = explode(',', $affiliator_ids);

            //  echo "<pre>"; print_r($affiliator_ids_array); echo "<br>"; exit;

            foreach ($affiliator_ids_array as $affiliator_id)
            {
                $leads=Leads::where('afflilate_id' , $affiliator_id)->get();
                foreach($leads as $lead){
                    $lead->task()->delete();
                    $lead->delete();
                }
                Number::where('type', 'affiliators')->Where('client_id', $affiliator_id)->delete();
                AffiliatorTask::where('affliator_id' , $affiliator_id)->delete();
                Affiliator::findOrFail($affiliator_id)->delete();
            }

            return redirect()->back()->withStatus(__('Affiliators Permanently Deleted Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }

    }

    public function trash_search(Request $request)
    {
        // echo '<pre>';print_r($request->input());exit;
        if(Auth::user()->can('trashed.affiliator.view'))
        {
            $condition = array();
            $condition[] = array('is_delete', '=', 1);

            if ($request->input('name') != '') {
                $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
            }

            if ($request->input('type') > 0) {
                $condition[] = array('type', $request->input('type'));
            }
            if ($request->input('status') != '') {
                $condition[] = array('status', $request->input('status'));
            }

            if ($request->input('email') != '') {
                $condition[] = array('email', '=', '%' . $request->input('email') . '%');
            }

            if ($request->input('phone') != '')
            {
                $first_nbr=mb_substr($request->phone, 0 , 1);

                if($first_nbr == 0){
                    $phone = substr($request->phone, 1);
                }else{
                    $phone = $request->phone;
                }

                // $condition[] = array('phone', 'Like', '%' . $phone . '%');
                $affiliator_numbers =Number::where('type', 'affiliators')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');
            }

            if ($request->input('user_id') > 0) {
                $condition[] = array('user_id', $request->input('user_id'));
            }

            $affiliators = array();
            if (!empty($condition)) {

                // if ($request->input('phone') !=''){
                //     $affiliators = Affiliator::where($condition)->whereIn('id', $affiliator_numbers)->orderBy('id', 'desc')->paginate(30);
                // }else{
                //     $affiliators = Affiliator::where($condition)->orderBy('id', 'desc')->paginate(30);
                // }

                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
                    if ($request->input('phone') !=''){
                        $affiliators = Affiliator::where($condition)->whereIn('id', $affiliator_numbers)->orderBy('id', 'desc')->paginate(30);
                    }else{
                        $affiliators = Affiliator::where($condition)->orderBy('id', 'desc')->paginate(30);
                    }
                }else{
                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    if ($request->input('phone') !=''){
                        $affiliators = Affiliator::where($condition)->whereIn('id', $affiliator_numbers)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate(30);
                    }else{
                        $affiliators = Affiliator::where($condition)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate(30);
                    }
                }

            } else {
                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                    $affiliators = Affiliator::orderBy('id', 'desc')->paginate(30);
                } else {
                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    $affiliators = Affiliator::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
                }
            }

            $search_person = $request->input('search_person');
            $id = $request->input('current_user');

            // ====== Users With Helper======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users'];
            // ====== Users With Helper======

            return view('affiliators.trash', compact('affiliators', 'search_person', 'id', 'users'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    // public function search(Request $request)
    // {
    //     // echo '<pre>';print_r($request->input());exit;
    //     if(Auth::user()->can('affiliator.view'))
    //     {
    //         $condition = array();
    //         $condition[] = array('is_delete', '=', 0);

    //         if ($request->input('name') != '') {
    //             $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
    //         }

    //         if ($request->input('type') > 0) {
    //             $condition[] = array('type', $request->input('type'));
    //         }
    //         if ($request->input('status') != '') {
    //             $condition[] = array('status', $request->input('status'));
    //         }

    //         if ($request->input('email') != '') {
    //             $condition[] = array('email', '=', '%' . $request->input('email') . '%');
    //         }

    //         if ($request->input('phone') != '')
    //         {
    //             $first_nbr=mb_substr($request->phone, 0 , 1);

    //             if($first_nbr == 0){
    //                 $phone = substr($request->phone, 1);
    //             }else{
    //                 $phone = $request->phone;
    //             }

    //             // $condition[] = array('phone', 'Like', '%' . $phone . '%');
    //             $affiliator_numbers =Number::where('type', 'affiliators')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');
    //         }

    //         if ($request->input('user_id') > 0) {
    //             $condition[] = array('user_id', $request->input('user_id'));
    //         }

    //         // echo '<pre>';print_r($condition);exit;

    //         // Search in the title and body columns from the posts table
    //         $affiliators = array();
    //         if (!empty($condition)) {

    //             // if ($request->input('phone') !=''){
    //             //     $affiliators = Affiliator::where($condition)->whereIn('id', $affiliator_numbers)->orderBy('id', 'desc')->paginate(30);
    //             // }else{
    //             //     $affiliators = Affiliator::where($condition)->orderBy('id', 'desc')->paginate(30);
    //             // }

    //             if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
    //                 if ($request->input('phone') !=''){
    //                     $affiliators = Affiliator::where($condition)->whereIn('id', $affiliator_numbers)->orderBy('id', 'desc')->paginate(30);
    //                 }else{
    //                     $affiliators = Affiliator::where($condition)->orderBy('id', 'desc')->paginate(30);
    //                 }
    //             }else{
    //                 $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
    //                 if ($request->input('phone') !=''){
    //                     $affiliators = Affiliator::where($condition)->whereIn('id', $affiliator_numbers)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate(30);
    //                 }else{
    //                     $affiliators = Affiliator::where($condition)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate(30);
    //                 }
    //             }

    //         } else {
    //             if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
    //                 $affiliators = Affiliator::orderBy('id', 'desc')->paginate(30);
    //             } else {
    //                 $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
    //                 $affiliators = Affiliator::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
    //             }
    //         }

    //         // echo '<pre>';print_r($records);exit;

    //         $search_person = $request->input('search_person');
    //         $id = $request->input('current_user');

    //         // ====== Users With Helper======
    //             $data = array(
    //                 'id' => Auth::user()->id,
    //                 'role' => Auth::user()->role,
    //             );
    //             $responce = Helper::users($data);
    //             $users = $responce['users']->where('office_id', 1);
    //         // ====== Users With Helper======

    //         return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users'));
    //     }else{
    //         return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
    //     }
    // }


    public function search(Request $request)
    {
        if(Auth::user()->can('affiliator.view'))
        {
            $condition = array();
            $condition[] = array('is_delete', '=', 0);

            if ($request->input('name') != '') {
                $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
            }

            if ($request->input('type') > 0) {
                $condition[] = array('type', $request->input('type'));
            }
            if ($request->input('status') != '') {
                $condition[] = array('status', $request->input('status'));
            }

            if ($request->input('email') != '') {
                $condition[] = array('email', '=', '%' . $request->input('email') . '%');
            }

            if ($request->input('phone') != '')
            {
                $first_nbr=mb_substr($request->phone, 0 , 1);

                if($first_nbr == 0){
                    $phone = substr($request->phone, 1);
                }else{
                    $phone = $request->phone;
                }

                $affiliator_numbers =Number::where('type', 'affiliators')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');
            }

            if ($request->input('user_id') > 0) {
                $condition[] = array('user_id', $request->input('user_id'));
            }

            $affiliators = array();

            $locationIds = array_filter($request->input('location_id', []));

            if (!empty($condition)) {

                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {

                    $query = Affiliator::where($condition);

                    if ($request->input('phone') != '') {
                        $query->whereIn('id', $affiliator_numbers);
                    }

                    if (!empty($locationIds)) {
                        $allLocationIds = [];
                        $locations = Location::with('children')->whereIn('id', $locationIds)->get();
                        foreach ($locations as $location) {
                            $allLocationIds = array_merge($allLocationIds, $location->descendantIds());
                        }
                        $query->whereIn('location_id', array_unique($allLocationIds));
                    }

                    $affiliators = $query->orderBy('id', 'desc')->paginate(30);

                } else {

                    $users = User::where('id', Auth::user()->id)
                        ->orWhere('parent', Auth::user()->id)
                        ->pluck('id');

                    $query = Affiliator::where($condition)
                        ->whereIn('user_id', $users);

                    if ($request->input('phone') != '') {
                        $query->whereIn('id', $affiliator_numbers);
                    }

                    if (!empty($locationIds)) {
                        $allLocationIds = [];
                        $locations = Location::with('children')->whereIn('id', $locationIds)->get();
                        foreach ($locations as $location) {
                            $allLocationIds = array_merge($allLocationIds, $location->descendantIds());
                        }
                        $query->whereIn('location_id', array_unique($allLocationIds));
                    }

                    $affiliators = $query->orderBy('id', 'desc')->paginate(30);

                }

            } else {
                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                    $affiliators = Affiliator::orderBy('id', 'desc')->paginate(30);
                } else {
                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    $affiliators = Affiliator::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
                }
            }

            $search_person = $request->input('search_person');
            $id = $request->input('current_user');

            // ====== Users With Helper======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users']->where('office_id', 1);
            // ====== Users With Helper======

            $locations = Location::whereHas('affiliators')->get();

            return view('affiliators.index', compact('affiliators', 'search_person', 'id', 'users','locations'));
        }else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

}
