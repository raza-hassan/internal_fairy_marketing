<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Offices;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(){
        $Offices = Offices::orderBy('id', 'DESC')->get();
        return view('admin.offices.index', compact('Offices'));
    }
    public function create(){
        return view('admin.offices.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required',
        ]);
        Offices::create($request->all());
        return redirect('admin/offices')->withStatus(__('Offices Created Successfully.'));
    }

    public function edit(Offices $offices){
        return view('admin.offices.edit', compact('offices'));
    }

    public function update(Request $request, Offices $offices){
        $offices->update($request->all());
        return redirect('admin/offices')->withStatus(__('Office Updated Successfully.'));
    }

    public function destroy(Offices $offices){
        $offices->delete();
        return back()->withStatus(__('Office Successfully Deleted.'));
    }

}
