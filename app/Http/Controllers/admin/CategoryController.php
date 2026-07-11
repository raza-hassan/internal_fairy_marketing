<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $records = Category::orderBy('id', 'ASC')->paginate(50);
        return view('admin.category.index', compact('records'));
    }

    public function brands($type) {
        $records = Category::where('parent', '!=', 0)->where('type', $type)->orderBy('id', 'ASC')->paginate(250);
        $parentid = Category::where('slug', $type)->first();
        $parent = $parentid->id;
        return view('admin.category.brand', compact('records', 'parent', 'type'));
    }

    public function create()
    {
        $categories = Category::where('parent', 0)->orderby('name', 'asc')->get();
        $type = 'category';
        return view('admin.category.create', compact('categories', 'type'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:category'
        ]);

        $filePath = '';
        $category = Category::where('slug', str_slug($request->input('name')))->first();
        if (empty($category)) {
            if ($request->file) {
                $fileName = time() . '_' . $request->file->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('category', $fileName, 'public');
            }
            $parent = 0;
            if ($request->input('parent')) {
                $parent = $request->input('parent');
            }

            $category = new Category;
            $category->name = $request->input('name');
            $category->parent = $parent;
            $category->description = $request->input('description');
            $category->image = $filePath;
            $category->slug = str_slug($request->input('name'));

            $category->save();
            return redirect('admin/category')->withStatus(__('Category successfully added.'));
        } else {
            return redirect('admin/category')->error('Category Exist. Please try with Different name.');
        }
    }

    public function brand_store(Request $request) {

        $validated = $request->validate([
            'name' => 'required|unique:category'
        ]);

        $category = Category::where('slug', str_slug($request->input('name')))->first();
        if (empty($category)) {
            $filePath = '';
            if ($request->file) {
                $fileName = time() . '_' . $request->file->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('category', $fileName, 'public');
            }
            //request->file
            $flogoPath = '';
            if ($request->logo) {
                $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();
                $flogoPath = $request->file('logo')->storeAs('category', $fileName, 'public');
            }

            $category = new Category;
            $category->name = $request->input('name');
            $category->type = $request->input('type');
            $category->parent = $request->input('parent');
            $category->description = $request->input('description');
            $category->image = $filePath;
            $category->logo = $flogoPath;
            $category->slug = str_slug($request->input('name'));
            $category->display = $request->input('display');
            $category->menuorder = $request->input('menuorder');
            $category->save();
            //Category::create($request->merge(['slug' => str_replace(' ', '-', strtolower($request->input('name'))), 'image' => $filePath, 'logo' => $flogoPath])->all());
            return redirect('admin/type/' . $request->input('type'))->withStatus(__('Category successfully added.'));
        } else {
            return redirect('admin/type/' . $request->input('type'))->error('Category Exist. Please try with Different name.');
        }
    }

    public function show(Category $category) {
        return view('admin.category.type-edit', compact('category'));
    }

    public function edit(Category $category) {
        $categories = Category::orderby('name', 'asc')->get();
        return view('admin.category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category) {

        $filePath = '';
        if ($request->file('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('category', $fileName, 'public');
        } else {
            if ($request->input('oldfile')) {
                $filePath = $request->input('oldfile');
            } else {
                $filePath = '';
            }
        }

        if ($request->file('logo')) {
            $logofileName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $flogoPath = $request->file('logo')->storeAs('category', $logofileName, 'public');
        } else {
            $flogoPath = $request->input('oldlogo');
        }

        $category->name = $request->input('name');
        $category->parent = $request->input('parent');
        $category->description = $request->input('description');
        $category->image = $filePath;
        $category->slug = str_slug($request->input('name'));
        $category->save();
        return redirect('admin/category')->withStatus(__('Category successfully updated.'));
    }

    public function destroy(Category $category) {
        $category->delete();

        return back()->withStatus(__('Category successfully deleted.'));
    }

}
