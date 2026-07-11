<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller {

    public function index()
    {
         $module=Module::all();
        return view('admin.module.index',compact('module'));
    }

    public function create()
    {
    $category=Category::all();
       return view('admin.module.create',compact('category'));
    }


    public function store(Request $request)
    {

       $validated = request()->validate([
            "tittle" => "required|min:3|max:255",
            "price" => "required|min:3|integer",
            "sdate" => "required|date|after:today",
            "edate" => "required|date|after:start_date",
            "rule" => "required",

        ]);
        if($request->input('status')){

            $status = $request->input('status');
        }else{
            $status = 0;
        }
        $request->status = $status;

        $module=new Module;
        $module->tittle = $request->input('tittle');
        $module->price = $request->input('price');
        $module->rule =  $request->input('rule');
        $module->status = $request->status ;
        $module->sdate = $request->input('sdate');
        $module->edate = $request->input('edate');
        $module->save();
        foreach ($request->input('categories') as $categories){
         $category = Category::find($categories);
         echo '<pre>';
         print_r($category->childs);

        } exit;
        $module->categories()->attach($category);

        return redirect("admin/module");
    }


    public function edit(Module $module)
    {     $categories=Category::all();
        return view('admin.module.edit', compact('module','categories'));
    }

    public function update(Request $request, Module $module)
    {
     if($request->input('status')){

            $status = $request->input('status');
        }else{
            $status = 0;
        }
        $request->status = $status;
       $module->update([
            "tittle" => $request->tittle,
            "price" => $request->price,
            "sdate" => $request->sdate,
            "edate" => $request->edate,
            "status" => $request->status,
            "rule" => $request->rule,
        ]);
             $category = Category::find($request->input('categories'));
        $module->categories()->sync($category);
        return redirect("admin/module")->withStatus(__('Module successfully Updated.'));;
    }

    public function destroy(Module $module)
    {
            $module->delete();

        return back()->withStatus(__('Module successfully deleted.'));
    }
}
