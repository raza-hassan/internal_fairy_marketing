<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Affiliator;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\Clients;
use App\Models\Offices;
use App\Models\Project;
use App\Models\Compain;
use App\Models\LeadSource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Helpers\Helper;
use App\Models\Number;


class FacebookController extends Controller
{

    public function clientsImport()
    {
        if(Auth::user()->can('client.import.option'))
        {
            $offices = Offices::orderBy('id', 'ASC')->get();

            // ====== Users With Helper======
                $data = array(
                    'id' => Auth::user()->id,
                    'role' => Auth::user()->role,
                );
                $responce = Helper::users($data);
                $users = $responce['users'];
            // ====== Users With Helper=====

            $sources = LeadSource::orderBy('type', 'ASC')->get();
            return view('client-import', compact('offices' , 'users' , 'sources'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    public function clientfileImportInstantForm(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;

        if(Auth::user()->can('client.import.option'))
        {
            $request->validate([
                'file' => 'required',
                'office_id' => 'required',
                'user_id' => 'required',
                'source_id' => 'required',
            ]);

            if (($request->source_id == 12 || $request->source_id == 13) && $request->afflilate_id == '') {
                return back()->withInput()->withErrors(['Your Source is Affiliator. You must need to select Affiliator for this lead.']);
            }

            $path = $request->file('file')->getRealPath();
            $records = array_map('str_getcsv', file($path));

            if (!count($records) > 0) {
                return 'Error...';
            }

            // Get field names from header column
            $fields = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
            // echo '<pre>';    print_r($fields);   exit;
            array_shift($records);

            // echo '<pre>'; print_r($records);  exit;

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

            $counter = 0;
            $record_exist = [];
            $clients_added = [];

            foreach ($this->rows as $data)
            {
                if(!empty($data['name']) && (!empty($data['phone1']) || !empty($data['phone2'])))
                {
                    $source_id = $request->source_id;
                    $note = $request->note;
                    $office_id = $request->office_id;
                    $user_id = $request->user_id;
                    $status = 1;

                    if($request->afflilate_id != ''){
                        $afflilate_id =  $request->afflilate_id;
                    }else{
                        $afflilate_id = null;
                    }
                    // $afflilate_id = !empty($request->afflilate_id) ? $request->afflilate_id : null;


                    $phones = array_filter([$data['phone1'], $data['phone2']]);
                    $extractNumbers = preg_replace('/[^0-9]/', '', $phones);

                    // echo"<pre>"; print_r($phones);
                    // echo"<pre>"; print_r($extractNumbers); exit;

                    $processedNumbers = [];

                    foreach ($phones as $phone) {
                        $processedNumbers[] = $this->processPhoneNumber($phone);
                    }

                    // echo"<pre>"; print_r($processedNumbers); exit;

                    $query = Number::query();
                    foreach ($processedNumbers as $checkPhoneNumber) {
                        $query->orWhere('number', 'like', '%' . $checkPhoneNumber . '%');
                    }

                    $records = $query->get();
                    // echo"<pre>"; echo print_r($records); exit;

                    if ($records->isEmpty())
                    {
                        $clientQuery = Clients::query();

                        foreach ($processedNumbers as $number)
                        {
                            $clientQuery->orWhere('phone', 'like', '%' . $number . '%');
                        }

                        if (!empty($data['email'])) {
                            $clientQuery->orWhere('email', $data['email']);
                        }

                        $client = $clientQuery->first();
                        // echo '<pre>';print_r($client);exit;

                        $phoneNumber1 = '+' . $extractNumbers[0]; // Save the first phone number

                        // echo '<pre>';print_r($phoneNumber1);exit;

                        if (empty($client))
                        {
                            $client = Clients::firstOrCreate(
                                [
                                    'name' => $data['name'],
                                    'email' => $data['email'],
                                    'phone' => $phoneNumber1,
                                    'address' => $data['address'],
                                    'status' => $status,
                                    'user_id' => $user_id,
                                    'source_id' => $source_id,
                                    'afflilate_id' => $afflilate_id,
                                    'note' => $note,
                                    'office_id' => $office_id,
                                    'is_delete' => 0,
                                ],[
                                    'phone' => $phoneNumber1,
                                ]
                            );

                            Leads::create([
                                'client_id' => $client->id,
                                'source_id' => $source_id,
                                'afflilate_id' => $afflilate_id,
                                'office_id' => $office_id,
                                'user_id' => $user_id,
                                'added_by' => Auth::user()->id,
                            ]);

                            foreach ($extractNumbers as $phoneNumber)
                            {
                                // echo '+'.$phoneNumber; echo "<br>";exit;
                                // $checkNumber = Number::where('number' , 'like', '%' . $phoneNumber . '%')->first();

                                // if(empty($checkNumber))
                                // {
                                    Number::insert([ 'number' => '+'.$phoneNumber, 'type' => 'clients', 'client_id' => $client->id,]);
                                // }
                            }

                            $counter++;
                            $clients_added[] = $client;

                        } else {
                            if (!empty($client))
                            {
                                $record_exist[] = $client->id;

                                $leads = Leads::where('client_id', $client['id'])->get();

                                if (count($leads) < 1) {

                                    Leads::create([
                                        'client_id' => $client->id,
                                        'source_id' => $source_id,
                                        'afflilate_id' => $client['afflilate_id'],
                                        'user_id' => $client['user_id'],
                                        'office_id' => $client['office_id'],
                                        'added_by' => Auth::user()->id,
                                    ]);

                                    foreach ($extractNumbers as $phoneNumber)
                                    {
                                        // $checkNumber = Number::where('number' , 'like', '%' . $phoneNumber . '%')->first();

                                        // if(empty($checkNumber))
                                        // {
                                            Number::insert([ 'number' => '+'.$phoneNumber, 'type' => 'clients', 'client_id' => $client->id,]);
                                        // }
                                    }

                                    $counter++;
                                }
                            }
                        }
                    }
                    else
                    {
                        foreach ($records as $record)
                        {
                            $record_exist[] = $record->client_id;
                        }
                        // echo"<pre>"; print_r($record_exist); exit;
                    }
                }
            }
            // exit;

            // =======Notification for client============
                if($counter > 0)
                {
                    // ======Send Notification To Auth User====
                        $data = array(
                            'type' => 'Added New Lead',
                            'msg_body' => Auth::user()->name . ' Has Added ' . $counter . ' New ' . (($counter < 2) ? ' Lead' : ' Leads') . ' From '.$client->leadSource->type.' File.',
                            'created_by' => Auth::user()->id,
                            'show_to' => Auth::user()->id,
                            'show_to_role' => 0,
                            'redirect' => 'leads',
                        );
                        Helper::notification($data);

                    // ======Send Notification To Parent User====

                        $data = array(
                            'type' => 'Added New Lead',
                            'msg_body' => Auth::user()->name . ' Has Added ' . $counter . ' New ' . (($counter < 2) ? ' Lead' : ' Leads') . ' From '.$client->leadSource->type.' File.',
                            'created_by' => Auth::user()->id,
                            'show_to' => Auth::user()->parent,
                            'show_to_role' => 0,
                            'redirect' => 'leads',
                        );
                        Helper::notification($data);

                    // ======Send Notification To Role User====

                        $roleUsers=User::where('role' , 5)->get();

                        foreach($roleUsers as $roleUser)
                        {
                            if(Auth::user()->id != $roleUser->id && Auth::user()->parent != $roleUser->id)
                            {
                                $data = array(
                                    'type' => 'Added New Lead',
                                    'msg_body' => Auth::user()->name . ' Has Added ' . $counter . ' New ' . (($counter < 2) ? ' Lead' : ' Leads') . ' From '.$client->leadSource->type.' File.',
                                    'created_by' => Auth::user()->id,
                                    'show_to' => $roleUser->id, // Show to role Users as a id
                                    'show_to_role' => 0,
                                    'redirect' => 'leads',
                                );
                                Helper::notification($data);
                            }
                        }
                    // ==========Notification End==========
                }
            // ==========================================

            // return redirect('newleads')->withStatus(__('Records Created Successfully.'));
            $clients_exist = Clients::whereIn('id',$record_exist)->get();
            return view('leads.new_leads_added' , compact('clients_exist' , 'clients_added'));

        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    function processPhoneNumber($phone)
    {
        $extractNumber = preg_replace('/[^0-9]/', '', $phone); // Removing non-numeric characters

        $first_2_digit = mb_substr($extractNumber, 0, 2); // checking the start of number is with 92
        $first_digit = mb_substr($extractNumber, 0, 1);   // checking the start of number is with 0

        if ($first_2_digit == '92') {
            return substr($extractNumber, 2);    // Removing the start 92
        } elseif ($first_digit == '0') {
            return substr($extractNumber, 1);    // Removing the start 0
        } else {
            return $extractNumber;               // if not 0 not 92 move as-it-is
        }
    }


    // ============================================================================


    public function facebookImport()
    {
        if(Auth::user()->can('facebook.leads.import'))
        {
            $offices = Offices::orderBy('id', 'ASC')->get();
            return view('facebook-import', compact('offices'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }
    public function facebookfileImportInstantForm(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;
        // print_r($request->office_id); exit;

        if(Auth::user()->can('facebook.leads.import'))
        {
            $request->validate([
                'file' => 'required',
            ]);

            $path = $request->file('file')->getRealPath();
            $records = array_map('str_getcsv', file($path));

            if (!count($records) > 0) {
                return 'Error...';
            }

            // Get field names from header column
            $fields = array_map('strtolower', preg_replace('/[^a-zA-Z0-9\']/', '', $records[0]));
            // echo '<pre>';    print_r($fields);   exit;
            array_shift($records);

            // echo '<pre>'; print_r($records);  exit;

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
                // echo '<pre>';
                // print_r($data);
                // exit;
                $campaign = Compain::where('form_id', $data['formid'])->first();

                // $client = Clients::where('phone', $data['phonenumber'])->orWhere('email', $data['email'])->first();
                $client=Number::Where('number', 'like', '%'.$data['phonenumber'].'%')->first();


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
                    Number::insert([ 'number' => $data['phonenumber'], 'type' => 'clients', 'client_id' => $client->id]);
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

            // ======Send Notification To Auth User====
                $data = array(
                    'type' => 'Added New Lead',
                    'msg_body' => Auth::user()->name . ' Has Added ' . $counter . ' Leads From FaceBook.',
                    'created_by' => Auth::user()->id,
                    'show_to' => Auth::user()->id,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);

            // ======Send Notification To Parent User====

                $data = array(
                    'type' => 'Added New Lead',
                    'msg_body' => Auth::user()->name . ' Has Added ' . $counter . ' Leads From FaceBook.',
                    'created_by' => Auth::user()->id,
                    'show_to' => Auth::user()->parent,
                    'show_to_role' => 0,
                    'redirect' => 'leads',
                );
                Helper::notification($data);

            // ======Send Notification To Role User====

                $roleUsers=User::where('role' , 5)->get();

                foreach($roleUsers as $roleUser)
                {
                    if(Auth::user()->id != $roleUser->id && Auth::user()->parent != $roleUser->id)
                    {
                        $data = array(
                            'type' => 'Added New Lead',
                            'msg_body' => Auth::user()->name . ' Has Added ' . $counter . ' Leads From FaceBook.',
                            'created_by' => Auth::user()->id,
                            'show_to' => $roleUser->id, // Show to role Users as a id
                            'show_to_role' => 0,
                            'redirect' => 'leads',
                        );
                        Helper::notification($data);
                    }
                }
            // ==========Notification End==========

            return redirect('newleads')->withStatus(__('Client Created Successfully.'));
        }
        else{
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    private function clear_encoding_str($value)
    {
    }
}
