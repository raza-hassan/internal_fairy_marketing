<?php





namespace App\Http\Controllers\admin;





use App\Http\Controllers\Controller;


use App\Models\Clients;


use App\Models\LeadSource;


use App\Models\User;


use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;





class ClientsController extends Controller {




    public function index()
    {
        $clients = Clients::where('is_delete', 0)->paginate(50);
        return view('admin.clients.index', compact('clients'));
    }


    public function create()


    {


        $sources = LeadSource::orderBy('id', 'desc')->get();


        return view('admin.clients.create', compact('sources'));


    }











    public function store(Request $request) {


        $phone = $request->input('countryCode') . $request->input('phone');


        $record = Clients::where('phone', $phone)->pluck('phone')->first();


        if ($record == '') {


            $validated = $request->validate([


                'countryCode' => 'required',


                'phone' => 'required',


                'name' => 'required'


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





            $clients = Clients::create($request->merge(['phone' => $request->input('countryCode') . '-' . $request->input('phone')])->all());


            $clients->cnicf = $cnicf;


            $clients->cnicb = $cnicb;


            $clients->save();


            return redirect('admin/clients')->withStatus(__('Client Created Successfully.'));


        } else {


            return back()->withInput()->withErrors(['Client already exist']);


        }


    }






    public function edit(Clients $client) {


        $managers = User::where('role', '!=', 0)->where('role', '!=', 6)->where('role', '!=', 7)->where('role', '!=', 8)->get();


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


            $cnicf = $request->input('oldcnicb');


        }





        $client->update($request->all());


        return redirect('admin/clients')->withStatus(__('Client Updated Successfully.'));


    }



    public function destroy(Clients $client) {

        $client->is_delete = 1 ;
        $client->save();


        return redirect('admin/clients')->withStatus(__('Client deleted Successfully.'));


    }








    public function search(Request $request) {


        $condition = array();




        $condition[] = array('is_delete', '=', 0);

        if ($request->input('name') != '') {


            $condition[] = array('name', '=', $request->input('name'));


        }





        $condition[] = array('user_id', '=', Auth::user()->id);





        if ($request->input('email') != '') {


            $condition[] = array('email', '=', $request->input('email'));


        }





        if ($request->input('phone') != '') {


            $condition[] = array('phone', '=', $request->input('phone'));


        }





        // Search in the title and body columns from the posts table


        $clients = array();


        if ($request->input('phone') != '' || $request->input('email') != '' || $request->input('name') != '') {


            $clients = Clients::where($condition)->orderBy('id', 'desc')->paginate(50);


        } else {


            $clients = Clients::orderBy('id', 'desc')->paginate(50);


        }





        return view('admin.clients.index', compact('clients'));


    }














}


