<?php

namespace App\Http\Controllers\outer;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\Clients;
use App\Models\Leads;
use App\Models\User;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Auth;
use Mail;

class ClientsController extends Controller {

    public function index() {
        $search_person = 'user';
        $id = 0;
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        $clients = Clients::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
        return view('clients.index', compact('clients', 'search_person', 'id', 'users'));
    }

    public function All_Clients(Request $request, $id) {
        $search_person = 'manager';
        $clients = Clients::where('user_id', $id)->orderBy('id', 'desc')->paginate(30);
        if (Auth::user()->role == 5) {
            $users = User::where('role', '!=', 0)->orderBy('id', 'asc')->get();
        } else {
            $users = User::where('id', Auth::user()->id)->Orwhere('parent', Auth::user()->id)->orderBy('id', 'asc')->get();
        }
        return view('clients.index', compact('clients', 'search_person', 'id', 'users'));
    }

    public function create() {
        if (Auth::user()->role == 4 || Auth::user()->role == 1) {
            $sources = LeadSource::orderBy('id', 'desc')->get();
        } else {
            $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();
        }
        return view('clients.create', compact('sources'));
    }


    public function store(Request $request)
    {
        $phone = $request->input('countryCode') . $request->input('phone');
        $record = Clients::where('phone', $phone)->pluck('phone')->first();
        if ($record == '') {
            $validated = $request->validate([
                // 'countryCode' => 'required',
                'phone' => 'required|unique:clients',
                'name' => 'required',
                'source_id' => 'required'
            ]);
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

                        'phone' => $request->full_number,


                        'telephone' => $telephone,
                        'telephone1' => $telephone1,
                        'address' => $request->address,
                        'user_id' => $request->user_id,
                        'cnic' => $request->cnic,
                        'cnicf' => $cnicf,
                        'cnicb' => $cnicb,
                        'source_id' => $request->source_id,
                        'afflilate_id' => $request->afflilate_id,
                            ], [
                        'email' => $request->email,
                        // 'phone' => $request->countryCode . $request->phone
                            ]
            );
            $lead = Leads::create($request->merge(['client_id' => $client->id])->all());

            // =========Email Notify=========

                    if ($lead->source_id > 0) {
                        $source = $lead->leadSource->type;
                    } else {
                        $source = '';
                    }


                $data = array(
                    'name' => $request->name,
                    'email' => $request->email,
                    // 'phone' => $request->countryCode . $request->phone,
                    'phone' => $request->full_number,
                    'telephone' => $telephone,
                    'address' => $request->address,
                    'user' => $lead->leadAgent->name,
                    'source_id' => $request->source_id,
                );


                $to_email = ['shoaib.khubaib@fairymarketing.com', 'shahid.shahid34@gmail.com'];
                $to_name = Auth::user()->name;

                Mail::send('clients.customer-mail', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->cc('shoaib.khubaib@fairymarketing.com')->subject
                            ('Customer Create Notification');
                    $message->from(config('mail.from.address'), Config('mail.from.name'));
                });

            // =========Email Notify=========


            return redirect('clients')->withStatus(__('Client Created Successfully.'));
        } else {
            return back()->withInput()->withErrors(['Client already exist']);
        }
    }


    public function edit(Clients $client)
    {

        if ($client->user_id != Auth::user()->id && Auth::user()->role != 1 && Auth::user()->role != 5)
        {
            return redirect('clients')->withErrors(__('Not Allowed!'));
        }

        $managers = User::where('role', '=', 2)->get();
        return view('clients.edit', compact('client', 'managers'));
    }

    public function update(Request $request, Clients $client) {
        $cnicf = '';
        if ($request->file('cnicf')) {
            $fileName = time() . '_' . $request->file('cnicf')->getClientOriginalName();
            $cnicf = $request->file('cnicf')->storeAs('clients', $fileName, 'public');
        } else {
            $cnicf = $request->input('oldcnicf');
        }
        $cnicb = '';
        if ($request->file('cnicb')) {
            $fileName = time() . '_' . $request->file('cnicb')->getClientOriginalName();
            $cnicb = $request->file('cnicb')->storeAs('clients', $fileName, 'public');
        } else {
            $cnicb = $request->input('oldcnicb');
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

        $client->update($request->all());

        $client->cnicf = $cnicf;
        $client->cnicb = $cnicb;
        $client->telephone = $telephone;
        $client->telephone1 = $telephone1;

        $client->phone = $request->full_number;
        $client->save();
        return back()->withStatus(__('Client Updated Successfully.'));
    }

    public function destroy(Clients $client) {
        $client->delete();
        return redirect('clients')->withStatus(__('Client deleted Successfully.'));
    }

    public function search(Request $request) {
        $condition = array();

        if ($request->input('name') != '') {
            $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
        }

        if ($request->input('user_id') > 0) {
            $condition[] = array('user_id', $request->input('user_id'));
        }

        if ($request->input('email') != '') {
            $condition[] = array('email', 'Like', '%' . $request->input('email') . '%');
        }

        if ($request->input('phone') != '') {
            $condition[] = array('phone', 'Like', '%' . $request->input('phone') . '%');
        }

        $clients = array();
        if (!empty($condition)) {
            $clients = Clients::where($condition)->orderBy('id', 'desc')->paginate(30);
        } else {
            if (Auth::user()->role == 5) {
                $clients = Clients::orderBy('id', 'desc')->paginate(30);
            } else {
                $users = User::where('parent', Auth::user()->id)->pluck('id');
                $clients = Clients::whereIn('user_id', $users)->orWhere('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(30);
            }
        }


        // ====== Users With Helper======
            $data = array(
                'id' => Auth::user()->id,
                'role' => Auth::user()->role,
            );
            $responce = Helper::users($data);
            $users = $responce['users'];
        // ====== Users With Helper======

        $search_person = $request->input('search_person');
        $id = $request->input('current_user');
        return view('clients.index', compact('clients', 'search_person', 'id', 'users'));
    }

    public function Download(Request $request, $cnicf) {
        $file = Clients::find($cnicf);
        return Storage::download($file->cnicf);
    }

}
