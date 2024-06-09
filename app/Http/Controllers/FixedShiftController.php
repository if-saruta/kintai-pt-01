<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\FixedShift;
use App\Models\FixedShiftDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('fixed-shift.create', compact('employees', 'project', 'id', 'daysOfWeek'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,id',
            'holiday_working' => 'sometimes|boolean',
            'shift_details' => 'required|array',
        ],
        [
            'shift_details.required' => '表のチェックボックスにはいずれか一つチェックを入れてください。',
        ]);

        $isFixedShift = FixedShift::where('project_id', $request->project_id)
                                ->where('employee_id', $request->employee_id)
                                ->get();

        if($isFixedShift->isNotEmpty()){
            return redirect()->back()
            ->withErrors(['employee_id' => '選択した従業員はすでに登録されています。']);
        }

        $id = null;
        DB::transaction(function () use(&$id, $request) {
            $fixedShift = FixedShift::create([
                'project_id' => $request->project_id,
                'employee_id' => $request->employee_id,
                'holiday_working' => $request->holiday_working ?? 0,
            ]);

            foreach ($request->shift_details as $week => $days) {
                foreach ($days as $day => $periods) {
                    foreach ($periods as $period => $value) {
                        if ($value) {
                            FixedShiftDetail::create([
                                'fixed_shift_id' => $fixedShift->id,
                                'week_number' => $week,
                                'day_of_week' => $day,
                                'time_of_day' => $period,
                            ]);
                        }
                    }
                }
            }

            $id = $fixedShift->id;
        });


        return redirect()->route('project.fixedShiftShow', ['id' => $id]);
    }

    public function show($id)
    {
        $fixedShift = FixedShift::find($id);

        return view('fixed-shift.show', compact('fixedShift'));
    }

    public function edit($id)
    {
        $fixedShift = FixedShift::find($id);

        $employees = Employee::all();

        return view('fixed-shift.edit', compact('fixedShift', 'employees'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,id',
            'holiday_working' => 'sometimes|boolean',
            'shift_details' => 'required|array',
        ],
        [
            'employee_id.unique' => '選択したドライバーはすでに登録されています。',
            'shift_details.required' => '表のチェックボックスにはいずれか一つチェックを入れてください。',
        ]);


        $id = null;
        DB::transaction(function () use(&$id, $request) {
            // 既存のシフトを取得して更新
            $fixedShift = FixedShift::updateOrCreate(
                ['project_id' => $request->project_id, 'employee_id' => $request->employee_id],
                ['holiday_working' => $request->holiday_working ?? 0]
            );

            // 既存のシフト詳細を削除して再作成
            $fixedShift->fixedShiftDetails()->delete();

            foreach ($request->shift_details as $week => $days) {
                foreach ($days as $day => $periods) {
                    foreach ($periods as $period => $value) {
                        if ($value) {
                            FixedShiftDetail::create([
                                'fixed_shift_id' => $fixedShift->id,
                                'week_number' => $week,
                                'day_of_week' => $day,
                                'time_of_day' => $period,
                            ]);
                        }
                    }
                }
            }

            $id = $fixedShift->id;
        });


        return redirect()->route('project.fixedShiftShow', ['id' => $id]);

    }

    public function delete($id)
    {
        $fixedShift = FixedShift::find($id);

        $project_id = $fixedShift->project_id;

        $fixedShift->delete();

        return redirect()->route('project.fixedShift', ['id' => $project_id]);
    }
}
