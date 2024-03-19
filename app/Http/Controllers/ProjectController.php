<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
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
        // client_idが1以外のプロジェクトを取得
        $projects = Project::where('client_id', '!=', 1)->get();

        // idが1以外のクライアントを取得
        $clients = Client::where('id', '!=', 1)->get();

        return view('project.index', compact('clients'));
    }

    public function create()
    {
        $employees = Employee::all();

        return view('project.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // クライアント情報の保存
        $client = Client::create([
            'name' => $request->input('clientName'),
            'pdfName' => $request->input('clientNameByPDF')
        ]);

        // 半角および全角カンマを除去し、intにキャストする関数
        $removeCommasAndCastToInt = function ($value) {
            $valueWithoutCommas = str_replace([',', '，'], '', $value);
            return (int)$valueWithoutCommas; // 文字列を整数型にキャスト
        };

        // 各案件と休日情報の保存
        foreach ($request->input('projects', []) as $projectData) {
            //チャーター情報の初期化
            $is_charter = false;
            if($projectData['is_charter'] ?? null == 1){
                $is_charter = true;
            }

            // 案件情報の保存
            $project = Project::create([
                'client_id' => $client->id,
                'name' => $projectData['name'],
                'is_charter' => $is_charter,
                'payment_type' => $projectData['payment_type'],
                'retail_price' => $removeCommasAndCastToInt($projectData['retail_price']),
                'driver_price' => $removeCommasAndCastToInt($projectData['driver_price']),
                'estimated_overtime_hours' => $projectData['estimated_overtime_hours'],
                'overtime_hourly_wage' => $projectData['overtime_hourly_wage']
            ]);

             // 休日情報の初期化
            $holidaysData = [
                'project_id' => $project->id,
                'sunday' => false,
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => false,
                'public_holiday' => false
            ];

            // 選択された休日を true に設定
            foreach ($projectData['holidays'] ?? [] as $day => $value) {
                if ($value == '1') {
                    $holidaysData[$day] = true;
                }
            }

            // 休日情報の保存
            ProjectHoliday::create($holidaysData);

            // 従業員別日給情報の保存
            if(isset($projectData['employeePayments'])){
                foreach ($projectData['employeePayments'] as $employeeId => $amount) {
                    ProjectEmployeePayment::create([
                        'employee_id' => $employeeId,
                        'project_id' => $project->id,
                        'amount' => $removeCommasAndCastToInt($amount)
                    ]);
                }
            }
        }


        return redirect()->route('project.');
    }

    public function edit($id)
    {
        $client = Client::find($id);
        $projects = Project::where('client_id', $id)->get();
        $projectHolidays = ProjectHoliday::where('project_id', $id)->first();
        $employees = Employee::all();
        $payments = ProjectEmployeePayment::where('project_id', $id)->get();

        return view('project.edit', compact('projects', 'client', 'projectHolidays', 'payments', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        // クライアント更新
        $client->name = $request->clientName;
        $client->pdfName = $request->clientNameByPDF;
        $client->save();

        // 半角および全角カンマを除去し、intにキャストする関数
        $removeCommasAndCastToInt = function ($value) {
            $valueWithoutCommas = str_replace([',', '，'], '', $value);
            return (int)$valueWithoutCommas; // 文字列を整数型にキャスト
        };

        // プロジェクト情報の更新
        foreach ($request->input('editProjects', []) as $projectId => $projectData) {
            $project = Project::findOrFail($projectId); // プロジェクトの存在確認

            //チャーター情報の初期化
            $is_charter = false;
            if($projectData['is_charter'] ?? null == 1){
                $is_charter = true;
            }

            $project->update([
                'is_charter' => $is_charter,
                'name' => $projectData['name'],
                'payment_type' => $projectData['payment_type'],
                'retail_price' => $removeCommasAndCastToInt($projectData['retail_price']),
                'driver_price' => $removeCommasAndCastToInt($projectData['driver_price']),
                'estimated_overtime_hours' => $projectData['estimated_overtime_hours'],
                'overtime_hourly_wage' => $projectData['overtime_hourly_wage'],
                'registration_location' => 1
            ]);

            // 休日情報の更新
            // 休日情報の初期化
            $holidaysData = [
                'sunday' => false,
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => false,
                'public_holiday' => false
            ];
            // 選択された休日を true に設定
            foreach ($projectData['holidays'] ?? [] as $day => $value) {
                if ($value == '1') {
                    $holidaysData[$day] = true;
                }
            }
            $project->holiday->update($holidaysData);

            // 従業員別日給の更新
            if(isset($projectData['employeePayments'])){
                foreach ($projectData['employeePayments'] as $employeeId => $amount) {
                    ProjectEmployeePayment::updateOrCreate(
                        ['project_id' => $projectId, 'employee_id' => $employeeId],
                        ['amount' => $removeCommasAndCastToInt($amount)]
                    );
                }
            }
        }

        // 新規プロジェクトの作成
        foreach ($request->input('projects', []) as $projectData) {
            //チャーター情報の初期化
            $is_charter = false;
            if($projectData['is_charter'] ?? null == 1){
                $is_charter = true;
            }

            // 案件情報の保存
            $project = Project::create([
                'client_id' => $client->id,
                'name' => $projectData['name'],
                'is_charter' => $is_charter,
                'payment_type' => $projectData['payment_type'],
                'retail_price' => $removeCommasAndCastToInt($projectData['retail_price']),
                'driver_price' => $removeCommasAndCastToInt($projectData['driver_price']),
                'estimated_overtime_hours' => $projectData['estimated_overtime_hours'],
                'overtime_hourly_wage' => $projectData['overtime_hourly_wage']
            ]);

             // 休日情報の初期化
            $holidaysData = [
                'project_id' => $project->id,
                'sunday' => false,
                'monday' => false,
                'tuesday' => false,
                'wednesday' => false,
                'thursday' => false,
                'friday' => false,
                'saturday' => false,
                'public_holiday' => false
            ];

            // 選択された休日を true に設定
            foreach ($projectData['holidays'] ?? [] as $day => $value) {
                if ($value == '1') {
                    $holidaysData[$day] = true;
                }
            }

            // 休日情報の保存
            ProjectHoliday::create($holidaysData);

            // 従業員別日給情報の保存
            if(isset($projectData['employeePayments'])){
                foreach ($projectData['employeePayments'] as $employeeId => $amount) {
                    ProjectEmployeePayment::create([
                        'employee_id' => $employeeId,
                        'project_id' => $project->id,
                        'amount' => $removeCommasAndCastToInt($amount)
                    ]);
                }
            }
        }
        return redirect()->route('project.');
    }

    public function delete($id)
    {
        $client = Client::find($id);
        $projects = Project::where('client_id', $id)->get();

        $client->delete();
        foreach($projects as $project){
            $project->delete();
        }

        return redirect()->route('project.');
    }

    public function projectDelete($test)
    {
        $project = Project::find($test);

        $clientId = $project->client->id;

        $project->delete();

        return redirect()->route('project.edit', ['id' => $clientId]);
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        $employees = Employee::all();

        foreach ($csv as $row){
            $client = Client::where('name', $row['clientName'])->first();
            if(!$client){
                $client = Client::create([
                    'name' => $row['clientName'],
                    'pdfName' => $row['pdfName']
                ]);
            }

            $project = Project::create([
                'client_id' => $client->id,
                'is_charter' => $row['is_charter'],
                'name' => $row['name'],
                'payment_type' => $row['payment_type'],
                'retail_price' => $row['retail_rpice'],
                'driver_price' => $row['driver_price'],
                'estimated_overtime_hours' => $row['estimated_overtime_hours'],
                'overtime_hourly_wage' => $row['overtime_hourly_wage']
            ]);

            ProjectHoliday::create([
                'project_id' => $project->id,
                'sunday' => $row['sunday'],
                'monday' => $row['monday'],
                'tuesday' => $row['tuesday'],
                'wednesday' => $row['wednesday'],
                'thursday' => $row['thursday'],
                'friday' => $row['friday'],
                'saturday' => $row['saturday']
            ]);

            foreach($employees as $employee){
                ProjectEmployeePayment::create([
                    'employee_id' => $employee->id,
                    'project_id' => $project->id,
                    'amount' => null
                ]);
            }
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
