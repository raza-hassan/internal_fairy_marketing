<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    // Admin page (add form + live tree). Rendering jQuery karti hai.
    public function index()
    {
        if (Auth::user()->can('manage.locations')) {
            return view('locations.index');
        } else {
            return redirect('/')->withErrors(__('Doesn\'t have permission to access this resource'));
        }
    }

    // Saari locations flat JSON mein (id, name, parent_id, full_path).
    // jQuery isi se parent-dropdown, tree aur cascade sab bana leti hai.
    public function data()
    {
        // full_path ke recursion mein N+1 se bachne ke liye parent chain eager-load
        $all = Location::with('parent.parent.parent.parent')
            ->orderBy('name')
            ->get();

        return $all->map(fn($l) => [
            'id'        => $l->id,
            'name'      => $l->name,
            'parent_id' => $l->parent_id,
            'full_path' => $l->full_path,
        ])->values();
    }

    // Ek node ke direct children (agar cascade ko on-demand chalana ho).
    public function children(Location $location)
    {
        return $location->children()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    // Naya location add (city / zone / area — sab isi se).
    public function store(Request $request)
    {
        $parentId = $request->input('parent_id') ?: null;

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:locations,id',
            'name'      => [
                'required',
                'string',
                'max:255',
                // Same parent ke neeche duplicate name rokna (root ke NULL case ko bhi handle karta hai)
                Rule::unique('locations', 'name')->where(function ($query) use ($parentId) {
                    return $parentId
                        ? $query->where('parent_id', $parentId)
                        : $query->whereNull('parent_id');
                }),
            ],
        ], [
            'name.unique' => 'This location already exists under the selected parent.'
        ]);

        $location = Location::create([
            'parent_id' => $parentId,
            'name'      => $validated['name'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location has been created successfully.',
            'data' => [
                'id'        => $location->id,
                'name'      => $location->name,
                'parent_id' => $location->parent_id,
                'full_path' => $location->fresh()->full_path,
            ]
        ], 201);
    }

    // Delete (cascadeOnDelete ki wajah se sub-areas bhi delete ho jayenge).
    public function destroy(Location $location)
    {
        if (!Auth::user()->can('manage.locations')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this location.'
            ], 403);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location has been deleted successfully.'
        ]);
    }
}
