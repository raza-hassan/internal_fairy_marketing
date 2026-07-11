<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller {

    public function index()
    {
        $records = Project::orderBy('id', 'ASC')->get();
        return view('admin.projects.index', compact('records'));
    }

    public function create() {
        return view('admin.projects.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:projects'
        ]);
        Project::create($request->merge(['slug' => str_slug($request->input('name'))])->all());

        return redirect('admin/projects');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->slug = str_slug($request->input('name'));
        $project->save();
        return redirect('admin/projects')->withStatus(__('Project successfully updated.'));
    }

    public function destroy(Project $project) {
        $project->delete();

        return back()->withStatus(__('Project successfully deleted.'));
    }

}
