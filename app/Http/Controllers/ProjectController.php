<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Models\ProjectEmployeePayment;
use App\Models\ShiftProjectVehicle;
use App\Models\ProjectHoliday;
use League\Csv\Reader;
use League\Csv\Statement;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('project.index', compact('projects'));
    }

    public function create()
    {
        $employees = Employee::all();

        return view('project.create', compact('employees'));
    }

    public function store(Request $request)
    {

        $project = Project::create([
            'clientName' => $request->clientName,
            'clientNameByPDF' => $request->clientNameByPDF,
            'name' => $request->name,
            'payment_type' => $request->type,
            'retail_price' => $request->retail_price,
            'driver_price' => $request->driver_price,
            'estimated_overtime_hours' => $request->overtime,
            'overtime_hourly_wage' => $request->overtime_hourly_wage,
        ]);

        // 日給の場合に実行
        $employeePrices = $request->input('employeePrice');
        if($request->type == 1){
            foreach ($employeePrices as $employeeId => $price) {
                ProjectEmployeePayment::create([
                    'employee_id' => $employeeId,
                    'project_id' => $project->id,
                    'amount' => $price,
                ]);
            }
        }

        // 休日を保存
        $validatedData = $request->validate([
            'sunday' => 'nullable|boolean',
            'monday' => 'nullable|boolean',
            'tuesday' => 'nullable|boolean',
            'wednesday' => 'nullable|boolean',
            'thursday' => 'nullable|boolean',
            'friday' => 'nullable|boolean',
            'saturday' => 'nullable|boolean',
        ]);

        ProjectHoliday::create([
            'project_id' => $project->id,
            'sunday' => $request->has('sunday'),
            'monday' => $request->has('monday'),
            'tuesday' => $request->has('tuesday'),
            'wednesday' => $request->has('wednesday'),
            'thursday' => $request->has('thursday'),
            'friday' => $request->has('friday'),
            'saturday' => $request->has('saturday'),
        ]);



        return redirect()->route('project.');
    }

    public function edit($id)
    {
        $project = Project::find($id);
        $projectHolidays = ProjectHoliday::where('project_id', $id)->first();
        $employees = Employee::all();
        $payments = ProjectEmployeePayment::where('project_id', $id)->get();

        return view('project.edit', compact('project', 'projectHolidays', 'payments', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        $payments = ProjectEmployeePayment::where('project_id', $id)->get();
        $projectHolidays = ProjectHoliday::where('project_id', $id)->first();

        $project->clientName = $request->clientName;
        $project->clientNameByPDF = $request->clientNameByPDF;
        $project->name = $request->name;
        $project->payment_type = $request->type;
        $project->retail_price = $request->retail_price;
        $project->driver_price = $request->driver_price;
        $project->estimated_overtime_hours = $request->overtime;
        $project->overtime_hourly_wage = $request->overtime_hourly_wage;
        $project->save();

        // 従業員別日給の作成||変更
        // 日給が選択されていれば実行
        if($request->type == 1){
            if($payments->isEmpty()){
                // 従業員の支払い情報を新規作成
                $employeePrices = $request->input('employeePrice');
                foreach ($employeePrices as $employeeId => $price) {
                    ProjectEmployeePayment::create([
                        'employee_id' => $employeeId,
                        'project_id' => $project->id,
                        'amount' => $price,
                    ]);
                }
            }else{
                // 従業員の支払い情報の更新
                $employeePrices = $request->input('employeePrice');
                foreach ($employeePrices as $employeeId => $amount) {
                    $payment = ProjectEmployeePayment::where('project_id', $project->id)
                        ->where('employee_id', $employeeId)
                        ->first();
                    if ($payment) {
                        $payment->amount = $amount;
                        $payment->save();
                    }else{
                        ProjectEmployeePayment::create([
                            'employee_id' => $employeeId,
                            'project_id' => $project->id,
                            'amount' => $amount,
                        ]);
                    }
                }
            }
        }else{ //歩合が選択された場合登録済みのデータは削除
            ProjectEmployeePayment::where('project_id', $id)->delete();
        }

        // 休日を保存
        $validatedData = $request->validate([
            'sunday' => 'nullable|boolean',
            'monday' => 'nullable|boolean',
            'tuesday' => 'nullable|boolean',
            'wednesday' => 'nullable|boolean',
            'thursday' => 'nullable|boolean',
            'friday' => 'nullable|boolean',
            'saturday' => 'nullable|boolean',
        ]);
        if($projectHolidays){
            $projectHolidays->sunday = $request->has('sunday');
            $projectHolidays->monday = $request->has('monday');
            $projectHolidays->tuesday = $request->has('tuesday');
            $projectHolidays->wednesday = $request->has('wednesday');
            $projectHolidays->thursday = $request->has('thursday');
            $projectHolidays->friday = $request->has('friday');
            $projectHolidays->saturday = $request->has('saturday');
            $projectHolidays->save();
        }else{
            ProjectHoliday::create([
                'project_id' => $project->id,
                'sunday' => $request->has('sunday'),
                'monday' => $request->has('monday'),
                'tuesday' => $request->has('tuesday'),
                'wednesday' => $request->has('wednesday'),
                'thursday' => $request->has('thursday'),
                'friday' => $request->has('friday'),
                'saturday' => $request->has('saturday'),
            ]);
        }

        return redirect()->route('project.');
    }

    public function delete($id)
    {
        try{
            $project = Project::find($id);

            $project->delete();

            return redirect()->route('project.');
        }catch (\Exception $e){
            return redirect()->route('project.')->with('alert', 'シフトに登録されている案件は、現在削除できない仕様にしてあります。ご了承ください。');
        }
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            Project::create([
                'name' => $row['name'],
                'retail_price' => $row['retail_rpice'],
                'driver_price' => $row['driver_price'],
            ]);
        }

        return redirect()->route('project.');
    }

    public function employeePaymentShow($id)
    {
        $project = Project::find($id);
        $payments = ProjectEmployeePayment::with('employee')
        ->where('project_id', $id)
        ->get();

        return view('project.employeePaymentShow', compact('project','payments'));
    }

}
