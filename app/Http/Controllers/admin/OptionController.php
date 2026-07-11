<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $options = Option::all();
        return view('admin/option/options', compact('options'));
    }

    public function create()
    {
        return view('admin/option/create-options');
    }

    public function store(Request $request)
    {
        Option::create($request->merge(['key'=>  str_replace(' ', '-', strtolower($request->input('key')))])->all());
        return redirect('admin/option')->withStatus(__('Option successfully added.'));
    }

    public function edit(Option $option)
    {
        return view('admin.option.edit-options', compact('option'));
    }


    public function update(Request $request, Option $option)
    {
        $option->update($request->all());
        return redirect('admin/option')->withStatus(__('Option successfully updated.'));
    }

    public function destroy(Option $option)
    {
        $option->delete();

        return back()->withStatus(__('Record successfully deleted.'));
    }
}
