<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\FixedShift;

use Illuminate\Http\Request;

class FixedShiftController extends Controller
{
    public function index($id)
    {
        $project = Project::find($id);

        $fixedShifts = FixedShift::where('project_id', $id)->get();

        return view('fixed-shift.index', compact('project', 'fixedShifts', 'id'));
    }

    public function create($id)
    {
        $project = Project::find($id);

        $employees = Employee::all();

        return view('fixed-shift.create', compact('employees', 'project', 'id'));
    }
}
