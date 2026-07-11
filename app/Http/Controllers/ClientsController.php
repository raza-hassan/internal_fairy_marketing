<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Clients;
use App\Models\Leads;
use App\Models\User;
use App\Models\LeadSource;
use App\Models\Offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Models\Number;
use phpDocumentor\Reflection\Types\Null_;

class ClientsController extends Controller {

    public function index()
    {
        if(Auth::user()->can('client.view'))
        {
            $search_person = 'user';
            $id = 0;

            // =========== Users With Helper=========
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users']->where('office_id', 1);
            // =========== Users With Helper=========
            $clients = Clients::where('user_id', Auth::user()->id)->where('is_delete', 0)->orderBy('id', 'desc')->paginate(30);
            $offices = Offices::orderBy('id', 'asc')->get();
            return view('clients.index', compact('clients', 'search_person', 'id', 'users','offices'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function All_Clients(Request $request, $id) {
        $search_person = 'manager';
        $clients = Clients::where('user_id', $id)->orderBy('id', 'desc')->paginate(30);

        // ======== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users']->where('office_id', 1);
        // ======== Users With Helper======

        // $users = array();
        $offices = Offices::orderBy('id', 'asc')->get();
        return view('clients.index', compact('clients', 'search_person', 'id', 'users' , 'offices'));
    }

    public function create()
    {
        if(Auth::user()->can('client.create'))
        {
            //$managers = User::where('role', '=', 2)->get();
            if (Auth::user()->role == 1 || Auth::user()->role == 4 || Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14)
            {
                $sources = LeadSource::orderBy('id', 'desc')->get();
            } else {
                $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();
            }
            return view('clients.create', compact('sources'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function store(Request $request)
    {
        // echo "<pre>";print_r($request->all()); exit;

        if(Auth::user()->can('client.create'))
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

            $office_id = Auth::user()->office_id;

            if($request->ptclphone !=''){
                $phone = $request->input('ptclphone');
            }
            if($request->phone !=''){
                $phone = $request->input('full_number');
            }

            $telephone = '';
            $telephone1 = '';


            if ($phone != ''){
                $record=Number::Where('number', 'like', '%'.$phone.'%')->first();
            }

            if (empty($record))
            {
                if($request->ptclphone ==''){
                    $validated = $request->validate([
                        'phone' => 'required|unique:clients',
                        'name' => 'required',
                        'source_id' => 'required'
                    ]); }
                if($request->phone ==''){
                    $validated = $request->validate([
                        'ptclphone' => 'required',
                        'name' => 'required',
                        'source_id' => 'required'
                    ]); }

                $cnicf = '';
                if ($request->file('cnicf')) {
                    $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
                    $cnicf = $request->file('cnicf')->storeAs('clients', $fileName, 'public');
                }

                $cnicb = '';
                if ($request->file('cnicb')) {
                    $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
                    $cnicb = $request->file('cnicb')->storeAs('clients', $fileName, 'public');
                }

                $client = Clients::firstOrCreate(
                                [
                            'name' => $request->name,
                            'email' => $request->email,
                            // 'phone' => $request->countryCode . $request->phone,
                            // 'phone' => $request->full_number,
                            'phone' => $phone,
                            'telephone' => $telephone,
                            'telephone1' => $telephone1,
                            'address' => $request->address,
                            'user_id' => $request->user_id,
                            'cnic' => $request->cnic,
                            'cnicf' => $cnicf,
                            'cnicb' => $cnicb,
                            'source_id' => $request->source_id,
                            'afflilate_id' => $request->afflilate_id,
                            'office_id' => $office_id,
                            'is_delete' => 0,
                                ], [
                            'email' => $request->email,
                            //  'phone' => $request->full_number
                            'phone' => $phone,
                                ]
                );
                $lead = Leads::create($request->merge(['added_by' => Auth::user()->id, 'client_id' => $client->id , 'office_id' => $office_id])->all());


                if ($request->phone !=''){
                    Number::insert([ 'number' => $phone, 'type' => 'clients', 'client_id' => $client->id,]);
                }if ($request->ptclphone !=''){
                    Number::insert([ 'number' => $phone, 'type' => 'clients', 'client_id' => $client->id,]);
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
                                Number::insert([ 'number' => $number, 'type' => 'clients', 'client_id' => $client->id,]);
                            }
                        }
                    }
                }

                // =====================================Email Notify=================================================

                        if ($lead->source_id > 0) {
                            $source = $lead->leadSource->type;
                        } else {
                            $source = '';
                        }


                    $data = array(
                        'name' => $request->name,
                        'email' => $request->email,
                        // 'phone' => $request->full_number,
                        'phone' => $phone,
                        'telephone' => $telephone,
                        'address' => $request->address,
                        'user' => $lead->leadAgent->name,
                        'source_id' => $source,
                    );


                    // $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                    $to_email = ['shoaib.khubaib@fairymarketing.com'];
                    $to_name = Auth::user()->name;

                    Mail::send('clients.customer-mail', $data, function ($message) use ($to_name, $to_email) {
                        $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                                ('Customer Create Notification');
                        $message->from(config('mail.from.address'), Config('mail.from.name'));
                    });

                // =====================================Email Notify=================================================

                return redirect('clients')->withStatus(__('Client Created Successfully.'));
            } else
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
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function show(Clients $clients) {
        //
    }

    public function edit(Clients $client)
    {
        if(Auth::user()->can('client.edit'))
        {
            if ($client->user_id != Auth::user()->id && Auth::user()->role != 1 && Auth::user()->role != 5 && Auth::user()->role != 13 && Auth::user()->role != 14) {
                return redirect('clients')->withErrors(__('Not Allowed!'));
            }

            $managers = User::where('role', '=', 2)->get();
            $numbers=Number::Where('type' , 'clients')->Where('client_id' , $client->id)
                            ->where('number' , '!=' , $client->phone)->get();
            $phone=Number::Where('type' , 'clients')->Where('client_id' , $client->id)
                        ->where('number' , 'Like' , '%'.$client->phone.'%')->first();
            return view('clients.edit', compact('client', 'managers' , 'numbers' , 'phone'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function update(Request $request, Clients $client)
    {
        // echo "<pre>"; print_r($request->all()); exit;

        if(Auth::user()->can('client.edit'))
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
                            // $record=Number::Where('number', 'like', '%'.$phone_number.'%')->first();

                            $record=Number::Where('client_id', '!=' ,$client->id)
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
                        }
                        else
                        {
                            $edit_international_full_number[$key] = $value;
                        }
                    }

                    foreach ($edit_international_full_number as $key => $number)
                    {
                        if($number !='' && is_numeric($number))
                        {
                            // $record=Number::Where('number', 'like', '%'.$phone_number.'%')->first();

                            $record=Number::Where('client_id', '!=' ,$client->id)
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


            if($request->phone !='')
            {
                $validated = $request->validate([
                    'phone' => 'required|unique:clients,phone,'.$client->id,
                    'name' => 'required',
                ]);

                $phone = $request->input('full_number');
                $record=Number::Where('client_id', '!=' ,$client->id)
                    ->where(function ($query) use ($phone)
                    {
                        $query->orWhere('Number', 'like', '%'.$phone.'%');
                    })->first();
            }

            if($request->ptclphone !='')
            {
                $validated = $request->validate([
                    'ptclphone' => 'required|unique:clients,phone,'.$client->id,
                    'name' => 'required',
                ]);

                $phone = $request->input('ptclphone');
                $record=Number::Where('client_id', '!=' ,$client->id)
                    ->where(function ($query) use ($phone)
                    {
                        $query->orWhere('Number', 'like', '%'.$phone.'%');
                    })->first();
            }

            $telephone = '';
            $telephone1 = '';

            // echo "<pre>"; print_r($record); exit;

            if (empty($record))
            {
                $cnicf = '';
                if ($request->file('cnicf')) {
                    $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
                    $cnicf = $request->file('cnicf')->storeAs('clients', $fileName, 'public');
                } else {
                    $cnicf = $request->input('oldcnicf');
                }
                //        echo $cnicf; exit;
                $cnicb = '';
                if ($request->file('cnicb')) {
                    $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
                    $cnicb = $request->file('cnicb')->storeAs('clients', $fileName, 'public');
                } else {
                    $cnicb = $request->input('oldcnicb');
                }

                // $client->update($request->all());
                $client->update($request->merge(['phone' => $phone])->all());

                $client->cnicf = $cnicf;
                $client->cnicb = $cnicb;
                $client->telephone = $telephone;
                $client->telephone1 = $telephone1;
                $client->save();

                if ($request->phone !='' || $request->ptclphone !='')
                {
                    $id = $request->phone_number_id;

                    Number::findOrFail($id)->update([
                        'number' => $phone,
                        'type' => 'clients',
                        'client_id' => $client->id,
                    ]);
                }


                if($request->international_phone_dynamic)
                {
                    foreach ($international_full_number as $key => $number)
                    {
                        if($number !='' && is_numeric($number))
                        {
                            $check=Number::Where('client_id' , $client->id)->Where('Number', 'like', '%'.$number.'%')->first();
                            if (empty($check))
                            {
                                Number::insert([ 'number' => $number, 'type' => 'clients', 'client_id' => $client->id,]);
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
                                        'type' => 'clients',
                                        'client_id' => $client->id,
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

            return back()->withStatus(__('Client Updated Successfully.'));

        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function destroy(Clients $client)
    {
        if(Auth::user()->can('client.trash'))
        {
            Leads::where('client_id' , $client->id )->update(array('is_delete' => 1));

            $client->is_delete = 1 ;
            $client->save();
            return redirect()->back()->withStatus(__('Client deleted Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function search(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;
        if(Auth::user()->can('client.view'))
        {
            $record = $request->input('records') ? $request->input('records') : 30;

            if($record == 'all'){
                $record = count(Clients::where('is_delete', '=='  , 0)->get());
            }

            $condition = array();
            $condition[] = array('is_delete', '=', 0);

            if ($request->input('name') != '') {
                $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
            }

            if ($request->input('office_id') > 0) {
                $condition[] = array('office_id', '=', $request->input('office_id'));
            }

            if ($request->input('user_id') > 0) {
                $condition[] = array('user_id', $request->input('user_id'));
            }

            if(Auth::user()->role==11 || Auth::user()->role==12){
                // echo"check"; exit;
                $condition[] = array('user_id', Auth::user()->id);
            }

            if ($request->input('email') != '') {
                $condition[] = array('email', 'Like', '%' . $request->input('email') . '%');
            }

            if ($request->input('phone') !='')
            {
                $first_nbr=mb_substr($request->phone, 0 , 1);

                if($first_nbr == 0){
                    $phone = substr($request->phone, 1);
                }else{
                    $phone = $request->phone;
                }

                // $condition[] = array('phone', 'Like', '%' . $phone . '%');
                // $condition[] = array('telephone', 'Like', '%' . $request->input('phone') . '%');
                // $condition[] = array('telephone1', 'Like', '%' . $request->input('phone') . '%');
                $client_numbers =Number::where('type', 'clients')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');

            }
            //  echo '<pre>';   print_r($condition);   exit;
            // Search in the title and body columns from the posts table
            $clients = array();
            if (!empty($condition))
            {
                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
                    if ($request->input('phone') !=''){
                        $clients = Clients::where($condition)->whereIn('id', $client_numbers)->orderBy('id', 'desc')->paginate($record);
                    }else{
                        $clients = Clients::where($condition)->orderBy('id', 'desc')->paginate($record);
                    }
                }else{
                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    if ($request->input('phone') !=''){
                        $clients = Clients::where($condition)->whereIn('id', $client_numbers)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate($record);
                    }else{
                        $clients = Clients::where($condition)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate($record);
                    }
                }
            } else
            {
                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14) {
                    $clients = Clients::orderBy('id', 'desc')->paginate($record);
                } else {
                    $users = User::where('parent', Auth::user()->id)->pluck('id');
                    $clients = Clients::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate($record);
                }
                // $clients = Clients::where($condition)->orderBy('id', 'desc')->paginate($record);
            }

            // ===== Users With Helper=====
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);

                if($request->office_id > 0){
                    $users = $responce['users']->where('office_id', $request->office_id);
                }else{
                    $users = $responce['users'];
                }
            // ===== Users With Helper=====

            $search_person = $request->input('search_person');
            $id = $request->input('current_user');
            $offices = Offices::orderBy('id', 'ASC')->get();
            return view('clients.index', compact('clients', 'search_person', 'id', 'users' , 'offices'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function Download(Request $request, $cnicf) {
        $file = Clients::find($cnicf);
        return Storage::download($file->cnicf);
    }

    public function trash_Clients()
    {
        if(Auth::user()->can('trashed.client.view'))
        {
            $search_person = 'user';
            $id = 0;
            if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
                // $users = User::where('role', '!=', 0)->where('office_id', 1)->orderBy('id', 'asc')->get();
                $clients = Clients::where('is_delete', 1)->orderBy('id', 'desc')->paginate(30);
            }else{
                // $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
                $clients = Clients::where('user_id', Auth::user()->id)->where('is_delete', 1)->orderBy('id', 'desc')->paginate(30);
            }

            // ======== Users With Helper========
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users'];
            // ======== Users With Helper========

            $offices = Offices::orderBy('id', 'asc')->get();
            return view('clients.trash', compact('clients', 'search_person', 'id', 'users','offices'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function resotre_Clients(Clients $client)
    {
        if(Auth::user()->can('trashed.client.restore'))
        {
            Leads::where('client_id' , $client->id )->update(array('is_delete' => 0));
            $client->is_delete = 0 ;
            $client->save();
            return redirect()->back()->withStatus(__('Client Active Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function client_delete_permanently(Clients $client)
    {
        if(Auth::user()->can('trashed.client.delete'))
        {
            $leads=Leads::where('client_id' , $client->id )->get();
            foreach($leads as $lead)
            {
                $lead->task()->delete();
                $lead->delete();
            }

            Number::where('type', 'clients')->Where('client_id', $client->id)->delete();
            $client->delete();
            return redirect()->back()->withStatus(__('Client Permanently Deleted Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function multiClientsDeletePermanently(Request $request)
    {
        // echo "<pre>"; print_r($request->all());exit;
        if(Auth::user()->can('trashed.client.delete'))
        {
            $client_ids =$request->multi_del_client_ids;

            if($client_ids == ''){
                return back()->withInput()->withErrors(__('Please Select At-least 1 Client..!'));
            }
            $client_ids_array = explode(',', $client_ids);

            //  echo "<pre>"; print_r($client_ids_array); echo "<br>"; exit;

            foreach ($client_ids_array as $client_id)
            {
                $client=Clients::findOrFail($client_id);
                $leads=Leads::where('client_id' , $client_id)->get();
                foreach($leads as $lead)
                {
                    $lead->task()->delete();
                    $lead->delete();
                }

                Number::where('type', 'clients')->Where('client_id', $client_id)->delete();
                $client->delete();
            }
            return redirect()->back()->withStatus(__('Clients Permanently Deleted Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }


    public function trash_search(Request $request)
    {
        if(Auth::user()->can('trashed.client.view'))
        {
            $record = $request->input('records') ? $request->input('records') : 30;
            if($record == 'all'){
                $record = count(Clients::where('is_delete', 1)->get());
            }

            $condition = array();
            $condition[] = array('is_delete', '=', 1);

            if ($request->input('name') != '') {
                $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
            }

            if ($request->input('office_id') > 0) {
                $condition[] = array('office_id', '=', $request->input('office_id'));
            }

            if ($request->input('user_id') > 0) {
                $condition[] = array('user_id', $request->input('user_id'));
            }

            if(Auth::user()->role==11 || Auth::user()->role==12){
                // echo"check"; exit;
                $condition[] = array('user_id', Auth::user()->id);
            }

            if ($request->input('email') != '') {
                $condition[] = array('email', 'Like', '%' . $request->input('email') . '%');
            }

            if ($request->input('phone') !='')
            {
                $first_nbr=mb_substr($request->phone, 0 , 1);

                if($first_nbr == 0){
                    $phone = substr($request->phone, 1);
                }else{
                    $phone = $request->phone;
                }

                // $condition[] = array('phone', 'Like', '%' . $phone . '%');
                // $condition[] = array('telephone', 'Like', '%' . $request->input('phone') . '%');
                // $condition[] = array('telephone1', 'Like', '%' . $request->input('phone') . '%');
                $client_numbers =Number::where('type', 'clients')->where('number', 'Like', '%' . $phone . '%')->pluck('client_id');
            }

            //    echo '<pre>';   print_r($condition);   exit;
            // Search in the title and body columns from the posts table
            $clients = array();
            if (!empty($condition)) {

                if (Auth::user()->role == 5 || Auth::user()->role == 13 || Auth::user()->role == 14){
                    if ($request->input('phone') !=''){
                        $clients = Clients::where($condition)->whereIn('id', $client_numbers)->orderBy('id', 'desc')->paginate($record);
                    }else{
                        $clients = Clients::where($condition)->orderBy('id', 'desc')->paginate($record);
                    }
                }else{
                    $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->pluck('id');
                    if ($request->input('phone') !=''){
                        $clients = Clients::where($condition)->whereIn('id', $client_numbers)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate($record);
                    }else{
                        $clients = Clients::where($condition)->whereIn('user_id', $users)->orderBy('id', 'desc')->paginate($record);
                    }
                }
            }
            else {
                if (Auth::user()->role == 5)
                {
                    $clients = Clients::where('is_delete', 1)->orderBy('id', 'desc')->paginate($record);
                } else {
                    $users = User::where('parent', Auth::user()->id)->pluck('id');
                    $clients = Clients::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate($record);
                }
            }

            // ===== Users With Helper=====
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);

                if($request->office_id > 0){
                    $users = $responce['users']->where('office_id', $request->office_id);
                }else{
                    $users = $responce['users'];
                }
            // ===== Users With Helper=====

            $search_person = $request->input('search_person');
            $id = $request->input('current_user');
            $offices = Offices::orderBy('id', 'ASC')->get();
            return view('clients.trash', compact('clients', 'search_person', 'id', 'users' , 'offices'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

}
