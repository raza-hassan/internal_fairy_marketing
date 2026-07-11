<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use App\Models\Affiliator;

use App\Models\User;

use App\Models\Leads;

use App\Models\LeadSource;

use App\Models\LeadStatus;

use App\Models\Project;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;



class AffiliatorsController extends Controller
{
    public function index()
    {
        $affiliators = Affiliator::orderBy('id', 'desc')->paginate(50);
        return view('admin.affiliators.index', compact('affiliators'));
    }
   public function dealors() {
       $affiliators = Affiliator::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->where('type', 'Dealer')->paginate(50);
       return view('admin.affiliators.index', compact('affiliators'));
   }
   public function freeliencer() {
       $affiliators = Affiliator::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->where('type', 'Freelancer')->paginate(50);
       return view('admin.affiliators.index', compact('affiliators'));
   }
   public function affilitor_leads(Affiliator $affiliator)
   {
        $find_result= 0;

       if ($affiliator->status == 1) {
           $sources = LeadSource::where('subtype', 'user')->get();
           $users = User::where('parent', Auth::user()->id)->get();
           $projects = Project::orderBy('id', 'ASC')->get();
           $leads = Leads::where('afflilate_id', $affiliator->id)->orderBy('id', 'desc')->paginate(50);
           return view('admin.leads.index', compact('find_result','sources', 'leads', 'users', 'projects'));
       } else {
           return redirect('affiliators')->withErrors(__('Affiliator does not exist or approved'));
       }
   }
   public function create()
   {
       return view('admin.affiliators.create');
   }
   public function store(Request $request) {

       $phone = $request->input('countryCode') . $request->input('phone');
       $record = Affiliator::where('phone', $phone)->pluck('phone')->first();
       //print_r($record); exit;
       if (empty($record)) {
           if ($request->type == 'Dealer') {
               $validated = $request->validate([
                   'countryCode' => 'required',
                   'phone' => 'required|unique:affiliators',
                   'cnic' => 'required|unique:affiliators',
                   'name' => 'required',
                   'address' => 'required',
                   'offinspic' => 'required',
                   'offoutspic' => 'required',
                   'offboardpic' => 'required',
               ]);
           } else {
               $validated = $request->validate([
                   'countryCode' => 'required',
                   'phone' => 'required|unique:affiliators',
                   'cnic' => 'required|unique:affiliators',
                   'name' => 'required'
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
           $affiliator = Affiliator::firstOrCreate(
                           [
                       'name' => $request->name,
                       'email' => $request->email,
                       'phone' => $request->countryCode . $request->phone,
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
                           ], [
                       'phone' => $request->countryCode . $request->phone
                           ]
           );
           return redirect('admin/affiliators')->withStatus(__('Affiliator Created Successfully.'));
       } else {
           return back()->withInput()->withErrors(['Affiliator already exist']);
       }
   }
   public function show(Affiliator $affiliator) {
       //
   }
   public function edit(Affiliator $affiliator) {
       $managers = User::where('role', '=', 2)->get();
       return view('admin.affiliators.edit', compact('affiliator', 'managers'));
   }
   public function update(Request $request, Affiliator $affiliator) {
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
           $offvisitpic = $request->file('offboardpic')->storeAs('affiliators', $fileName, 'public');
       } else {
           $offvisitpic = $request->input('oldoffboardpic');
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

       $affiliator->update($request->all());

       $affiliator->cnicf = $cnicf;
       $affiliator->cnicb = $cnicb;
       $affiliator->offinspic = $offinspic;
       $affiliator->offoutspic = $offoutspic;
       $affiliator->offboardpic = $offboardpic;
       $affiliator->offvisitpic = $offvisitpic;
       $affiliator->telephone = $telephone;
       $affiliator->telephone1 = $telephone1;
       $affiliator->save();
       return redirect('affiliators')->withStatus(__('Affiliator Updated Successfully.'));
   }
   public function destroy(Affiliator $affiliator) {
       $affiliator->delete();
       return redirect('affiliators')->withStatus(__('Affiliator Updated Successfully.'));
   }
   public function search(Request $request) {
       $condition = array();
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
       $affiliators = array();
       if ($request->input('phone') != '' || $request->input('email') != '' || $request->input('name') != '') {
           $affiliators = Affiliator::where($condition)->orderBy('id', 'desc')->paginate(50);
       } else {
           $affiliators = Affiliator::orderBy('id', 'desc')->paginate(50);
       }
       return view('admin.affiliators.index', compact('affiliators'));
   }













}

