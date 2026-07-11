<?php





namespace App\Http\Controllers\admin;





use App\Http\Controllers\Controller;


use App\Models\Leads;


use App\Models\LeadSource;


use App\Models\LeadStatus;


use App\Models\Category;


use App\Models\User;


use App\Models\Clients;


use App\Models\LeadCategory;


use App\Models\LeadPriority;


use App\Models\Product;


use App\Models\Project;


use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;





class LeadsController extends Controller {





    /**


     * Display a listing of the resource.


     *


     * @return \Illuminate\Http\Response


     */


    public function index() {


        $leads = Leads::paginate(50);


        $sources = LeadSource::where('subtype', 'user')->get();


        $asources = LeadSource::where('subtype', 'affiliator')->get();


        $users = User::where('role', '!=',0)->get();


        $projects = Project::orderBy('id', 'ASC')->get();

        $find_result= 0;

        return view('admin.leads.index', compact('find_result','sources', 'asources', 'leads', 'users', 'projects'));


    }





    /**


     * Show the form for creating a new resource.


     *


     * @return \Illuminate\Http\Response


     */














    public function create()


    {








        if (Auth::user()->role == 0) {


            $sources = LeadSource::orderBy('id', 'desc')->get();


        } else {


            $sources = LeadSource::where('subtype', 'user')->orderBy('id', 'desc')->get();


        }





        $categories = Category::all();


        $priority = LeadPriority::all();


        $projects = Project::all();


        return view('admin.leads.create', compact('sources', 'categories', 'projects', 'priority'));








        // $sources = LeadSource::all();


        // $status = LeadStatus::all();


        // $categories = Category::all();


        // $products = Product::where('status', 'Available')->get();


        // $agents = User::where('role', '!=', 0)->where('role', '!=', 7)->where('role', '!=', 8)->where('status', '=', 1)->get();


        // return view('admin.leads.create', compact('sources', 'agents', 'status', 'categories', 'products'));





    }














//     public function store(Request $request) {


//         $validated = $request->validate([


//             'email' => 'required|unique:users',


//             'mobile' => 'required',


//             'name' => 'required',


//             'user_id' => 'required',


//             'source_id' => 'required'


//         ]);





//         $client = Clients::firstOrCreate(


//                         [


//                     'name' => $request->name,


//                     'email' => $request->email,


//                     'phone' => $request->mobile,


//                     'address' => $request->address,


//                     'uan' => $request->cell,


//                     'user_id' => $request->user_id,


//                         ], [


//                     'email' => $request->email,


//                     'phone' => $request->mobile


//                         ]


//         );





// //        echo '<pre>';


// //        print_r($client);


// //        exit;





//         Leads::create($request->merge(['client_id' => $client->id, 'category_id' => 1])->all());


//         return redirect('admin/leads')->withStatus(__('Leads Created Successfully.'));


//     }











public function store(Request $request) {


    //        print_r($request->input());


    //        exit;


            if ($request->input('account') == 1 && $request->input('client_id') == '') {


    //            echo 'sdfd';


    //            exit;


                $phone = $request->input('countryCode') . $request->input('phone');


                $record = Clients::where('phone', $phone)->first();


    //            print_r($record->phone); exit;


                if (empty($record)) {


                    $validated = $request->validate([


                        'account' => 'required',


                        'name' => 'required',


                        'phone' => 'required',


                        'source_id' => 'required'


                    ]);





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


                                'phone' => $request->countryCode . $request->phone,


                                'telephone' => $telephone,


                                'telephone1' => $telephone1,


                                'address' => $request->address,


                                'user_id' => $request->user_id,


                                'source_id' => $request->source_id,


                                'afflilate_id' => $request->afflilate_id,


                                    ], [


                                'phone' => $request->countryCode . $request->phone


                                    ]


                    );





    //


                    Leads::create($request->merge(['client_id' => $client->id])->all());


                    return redirect('admin/leads')->withStatus(__('Leads Created Successfully.'));


                } else {


                    //echo $record->assigned->name; exit;


                    return back()->withInput()->withErrors(['Client already exist under ' . $record->assigned->name]);


                }


            } else {


                $record = Clients::where('id', $request->input('client_id'))->where('user_id', Auth::user()->id)->first();


                // $users = User::where('role', '!=',0)->get();





    //            echo '<pre>';


    //            print_r($record);


    //            exit;





                if (!empty($record)) {


                    $validated = $request->validate([


                        'account' => 'required',


                        'client_id' => 'required',


                    ]);


    //            echo 'gdfg';


    //            exit;





                    Leads::create($request->merge(['client_id' => $record->id])->all());


                    return redirect('admin/leads')->withStatus(__('Leads Created Successfully.'));


                } else {


                    return back()->withInput()->withErrors(['Please enter correct Client id.']);


                }


            }


            return back();


        }























    /**


     * Display the specified resource.


     *


     * @param  \App\Models\Leads  $leads


     * @return \Illuminate\Http\Response


     */


    public function show(Leads $leads) {


        //


    }





    /**


     * Show the form for editing the specified resource.


     *


     * @param  \App\Models\Leads  $leads


     * @return \Illuminate\Http\Response


     */


    public function edit(Leads $lead) {


        $sources = LeadSource::all();


        $status = LeadStatus::all();


        $categories = LeadCategory::all();


        $products = Product::where('status', 'Available')->get();


//        print_r($products); exit;


        $agents = User::where('role', '!=', 0)->where('status', '=', 1)->get();


        return view('admin.leads.edit', compact('lead', 'sources', 'agents', 'status', 'categories', 'products'));


    }





    /**


     * Update the specified resource in storage.


     *


     * @param  \Illuminate\Http\Request  $request


     * @param  \App\Models\Leads  $leads


     * @return \Illuminate\Http\Response


     */


    public function update(Request $request, Leads $lead) {


//        echo '<pre>';


//        print_r($request->input()); exit;


        $lead->update($request->all());


        return redirect('admin/leads')->withStatus(__('Leads Updated Successfully.'));


    }





    /**


     * Remove the specified resource from storage.


     *


     * @param  \App\Models\Leads  $leads


     * @return \Illuminate\Http\Response


     */


    public function destroy(Leads $lead) {


        $lead->leadtask();


        $lead->delete();


        return redirect('admin/leads')->withStatus(__('Leads deleted Successfully.'));


    }











    public function search(Request $request) {


        // Get the search value from the request




        $find_result= 0;

        $condition = array();





        if ($request->input('lead_id') != '') {


            $condition[] = array('id', '=', $request->input('lead_id'));


        }





        if ($request->input('user_id') != 0) {


            $condition[] = array('user_id', '=', $request->input('user_id'));


        } else {


            $condition[] = array('user_id', '=', Auth::user()->id);


        }





        if ($request->input('status') != '') {


            $condition[] = array('status_id', '=', $request->input('status'));


        }





        if ($request->input('project_id') != 0) {


            $condition[] = array('project_id', '=', $request->input('project_id'));


        }





        if ($request->input('startDate') != '' && $request->input('endDate') != '') {


//            echo $request->input('endDate'); exit;


            $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');


            $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:00');


        }





        if ($request->input('startDate') != '' && $request->input('endDate') == '') {


            $condition[] = array('created_at', '>=', date("Y-m-d", strtotime($request->input('startDate'))) . ' 00:00:00');


        }








        if ($request->input('startDate') == '' && $request->input('endDate') != '') {


//            echo 'sdfd'; exit;


            $condition[] = array('created_at', '<=', date("Y-m-d", strtotime($request->input('endDate'))) . ' 23:59:00');


        }


        //     print_r($condition);


//exit;


        // Search in the title and body columns from the posts table


        $leads = Leads::where($condition)


                ->paginate(50);





            $users = User::where('role', '!=',0)->get();


            $projects = Project::orderBy('id', 'ASC')->get();



        return view('admin.leads.index', compact('find_result','leads', 'users', 'projects'));


    }











}


