<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\LeadPriority;
use App\Models\Leads;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {
        $projects = Project::orderBy('id', 'ASC')->get();

        return view('admin.settings.index', compact('projects'));
    }







}
