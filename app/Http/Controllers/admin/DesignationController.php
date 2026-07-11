<?php





namespace App\Http\Controllers\admin;





use App\Http\Controllers\Controller;


use App\Models\Designations;


use Illuminate\Http\Request;





class DesignationController extends Controller


{











    public function index()


    {


        $designations = Designations::orderBy('id', 'DESC')->get();





        return view('admin.designations.index', compact('designations'));


    }











    public function create()


    {


        return view('admin.designations.create');


    }








    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $designations=Designations::create($request->all());

        $designations->update($request->all());

        $designations->sequence_menu = $designations->id;
        $designations->save();

        return redirect('admin/designations')->withStatus(__('Designations Created Successfully.'));


    }








    public function edit(Designations $designations)


    {


        return view('admin.designations.edit', compact('designations'));


    }








    public function update(Request $request, Designations $designations)


    {


        $designations->update($request->all());


        return redirect('admin/designations')->withStatus(__('Designations Updated Successfully.'));


    }








    public function destroy(Designations $designations)


    {


        $designations->delete();


        return back()->withStatus(__('Designations Successfully Deleted.'));


    }

















}


