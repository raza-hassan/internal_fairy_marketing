<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributesController extends Controller {


    public function index() {
        $records = Attributes::all();
        return view('admin.attributes.index', compact('records'));
    }

    public function create() {
        return view('admin.attributes.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'slug' => 'unique:attributes',
            'name' => 'required',
            'type' => 'required',
        ]);

        if($request->input('required')){
            $status = $request->input('required');
        }else{
            $status = 0;
        }
        $request->required = $status;

        Attributes::create($request->merge(['slug' => str_slug(($request->input('name')))])->all());
        return redirect('admin/attributes')->withStatus(__('Attributes successfully added.'));
    }

    public function show(Attributes $attributes) {
        //
    }

    public function edit(Attributes $attributes) {
        return view('admin.attributes.edit', compact('attributes'));
    }

    public function update(Request $request, Attributes $attributes) {
        $validated = $request->validate([
            'slug' => 'unique:attributes',
            'name' => 'required',
            'type' => 'required',
        ]);

        if($request->input('required')){
            $status = $request->input('required');
        }else{
            $status = 0;
        }
        $request->required = $status;

        $attributes->update($request->merge(['slug' => str_slug(($request->input('name')))])->all());
        return redirect('admin/attributes')->withStatus(__('Attributes successfully updated.'));
    }
    public function destroy(Attributes $attributes) {
       $attributes->delete();

        return back()->withStatus(__('Attributes successfully deleted.'));
    }

}
