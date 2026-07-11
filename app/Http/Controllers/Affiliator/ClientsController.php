<?php

namespace App\Http\Controllers\Affiliator;
use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Leads;
use App\Models\User;
use App\Models\LeadSource;
use App\Models\Offices;
use Illuminate\Http\Request;
use Auth;
use Mail;
use phpDocumentor\Reflection\Types\Null_;

class ClientsController extends Controller
{

    public function index()
    {
        $clients = Clients::where('afflilate_id', Auth::guard('affiliator')->user()->id)->orderBy('id', 'desc')->paginate(30);
        return view('freelancer.clients.index', compact('clients'));
    }

    public function create() {
        $sources = LeadSource::where('subtype', 'affiliator')->orderBy('id', 'desc')->get();

        return view('freelancer.clients.create', compact('sources'));
    }


    public function edit(Clients $client)
    {

        if ($client->afflilate_id != Auth::guard('affiliator')->user()->id)
        {
            return redirect()->back()->withErrors(__('Not Allowed!'));
        }

        return view('freelancer.clients.edit', compact('client'));
    }

    public function update(Request $request, Clients $client)
    {
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


    public function search(Request $request) {
        $condition = array();

        if ($request->input('name') != '') {
            $condition[] = array('name', 'Like', '%' . $request->input('name') . '%');
        }

        if ($request->input('office_id') != '') {
            $condition[] = array('office_id', '=', $request->input('office_id'));
        }

        if ($request->input('user_id') > 0) {
            $condition[] = array('user_id', $request->input('user_id'));
        }

        if ($request->input('email') != '') {
            $condition[] = array('email', 'Like', '%' . $request->input('email') . '%');
        }

        if ($request->input('phone') !='')
        {
            $condition[] = array('phone', 'Like', '%' . $request->input('phone') . '%');
        }

        $clients = array();
        if (!empty($condition)) {

            $clients = Clients::where($condition)
                            // ->where('user_id', Auth::guard('affiliator')->user()->user_id)
                            ->where('afflilate_id', Auth::guard('affiliator')->user()->id)
                            ->orderBy('id', 'desc')->paginate(30);
        }
        else {

            $clients = Clients::
                                // where('user_id', Auth::guard('affiliator')->user()->user_id)->
                                where('afflilate_id', Auth::guard('affiliator')->user()->id)
                                ->orderBy('id', 'desc')->paginate(30);
        }

        return view('freelancer.clients.index', compact('clients'));
    }


}
