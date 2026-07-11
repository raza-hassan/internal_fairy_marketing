<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Variation;
use App\Models\Attributes;
use Illuminate\Http\Request;

class OptionsController extends Controller {

    public function index($attrid) {
        $records = Variation::where('attributes_id', $attrid)->get();
        return view('admin.options.index', compact('records', 'attrid'));
    }

    public function variations(Request $request) {
        $id = $request->input('attribute_id');

        $records = Variation::where('attributes_id', $id)->get();
        $html = view('admin.options.variations', compact('records'))->render();
        return response()->json(['html' => $html]);
    }

    public function create($attrid) {

        return view('admin.options.create', compact('attrid'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required'
        ]);

        Variation::create($request->all());
        return redirect('admin/options/' . $request->get('attributes_id'))->withStatus(__('Variation successfully added.'));
    }

    public function edit(Variation $options) {
        return view('admin.options.edit', compact('options'));
    }

    public function update(Request $request, Variation $options) {

        $validated = $request->validate([
            'title' => 'required'
        ]);

        $options->update($request->all());
        return redirect('admin/options/' . $request->get('attributes_id'))->withStatus(__('Variation successfully updated.'));
    }

    public function destroy(Variation $options) {
        $options->delete();

        return back()->withStatus(__('Variation successfully deleted.'));
    }

}
