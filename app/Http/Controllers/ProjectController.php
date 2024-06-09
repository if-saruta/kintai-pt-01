<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Employee;
use App\Models\ProjectEmployeePayment;
use App\Models\ShiftProjectVehicle;
use App\Models\ProjectHoliday;
use App\Models\ProjectAllowance;
use League\Csv\Reader;
use League\Csv\Statement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use($request) {
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
                    'display_name' => $projectData['display_name'],
                    'is_charter' => $is_charter,
                    'payment_type' => $projectData['payment_type'],
                    'retail_price' => $removeCommasAndCastToInt($projectData['retail_price']),
                    'driver_price' => $is_charter ? '0' : $removeCommasAndCastToInt($projectData['driver_price']),
                    // 'estimated_overtime_hours' => $projectData['estimated_overtime_hours'],
                    'overtime_hourly_wage' => $removeCommasAndCastToInt($projectData['overtime_hourly_wage']),
                    'is_suspended' => $projectData['is_suspended'] ?? 0
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


                // 手当
                foreach($projectData['allowance'] as $allowanceData){
                    if($allowanceData['allowance_name'] != null && $allowanceData['allowance_retail_amount'] != null && $allowanceData['allowance_driver_amount'] != null){
                        // 必須項目か確認
                        if(isset($allowanceData['is_required'])){
                            $is_required = 1;
                        }else{
                            $is_required = 0;
                        }
                        ProjectAllowance::create([
                            'project_id' => $project->id,
                            'name' => $allowanceData['allowance_name'],
                            'retail_amount' => $removeCommasAndCastToInt($allowanceData['allowance_retail_amount']),
                            'driver_amount' => $removeCommasAndCastToInt($allowanceData['allowance_driver_amount']),
                            'is_required' => $is_required,
                        ]);
                    }
                }

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
        });


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
        DB::transaction(function () use($request, $id) {
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
                    'display_name' => $projectData['display_name'],
                    'payment_type' => $projectData['payment_type'],
                    'retail_price' => $removeCommasAndCastToInt($projectData['retail_price']),
                    'driver_price' => $is_charter ? '0' :  $removeCommasAndCastToInt($projectData['driver_price']),
                    // 'estimated_overtime_hours' => $projectData['estimated_overtime_hours'],
                    'overtime_hourly_wage' => $removeCommasAndCastToInt($projectData['overtime_hourly_wage']),
                    'registration_location' => 1,
                    'is_suspended' => $projectData['is_suspended'] ?? 0
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

                // 手当
                // 手当編集
                if(isset($projectData['allowances'])){
                    foreach($projectData['allowances'] as $allowanceId => $allowanceValues){
                        if($allowanceValues['allowance_name'] != null && $allowanceValues['allowance_retail_amount'] != null && $allowanceValues['allowance_driver_amount'] != null){
                            $projectAllowance = ProjectAllowance::find($allowanceId);
                            $projectAllowance->name = $allowanceValues['allowance_name'];
                            $projectAllowance->retail_amount = $removeCommasAndCastToInt($allowanceValues['allowance_retail_amount']);
                            $projectAllowance->driver_amount = $removeCommasAndCastToInt($allowanceValues['allowance_driver_amount']);
                            if(isset($allowanceValues['is_required'])){
                                $projectAllowance->is_required = $allowanceValues['is_required'];
                            }else{
                                $projectAllowance->is_required = 0;
                            }
                            $projectAllowance->save();
                        }
                    }
                }

                if(isset($projectData['allowance'])){
                // 新規手当
                    foreach($projectData['allowance'] as $allowanceData){
                        if($allowanceData['allowance_name'] != null && $allowanceData['allowance_retail_amount'] != null && $allowanceData['allowance_driver_amount'] != null){
                            // 必須項目か確認
                            if(isset($allowanceData['is_required'])){
                                $is_required = 1;
                            }else{
                                $is_required = 0;
                            }
                            ProjectAllowance::create([
                                'project_id' => $project->id,
                                'name' => $allowanceData['allowance_name'],
                                'retail_amount' => $removeCommasAndCastToInt($allowanceData['allowance_retail_amount']),
                                'driver_amount' => $removeCommasAndCastToInt($allowanceData['allowance_driver_amount']),
                                'is_required' => $is_required,
                            ]);
                        }
                    }
                }

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
                    'display_name' => $projectData['display_name'],
                    'is_charter' => $is_charter,
                    'payment_type' => $projectData['payment_type'],
                    'retail_price' => $removeCommasAndCastToInt($projectData['retail_price']),
                    'driver_price' => $is_charter ? '0' : $removeCommasAndCastToInt($projectData['driver_price']),
                    // 'estimated_overtime_hours' => $projectData['estimated_overtime_hours'],
                    'overtime_hourly_wage' => $projectData['overtime_hourly_wage'],
                    'is_suspended' => $projectData['is_suspended'] ?? 0
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

                // 手当
                foreach($projectData['allowance'] as $allowanceData){
                    if($allowanceData['allowance_name'] != null && $allowanceData['allowance_retail_amount'] != null && $allowanceData['allowance_driver_amount'] != null){
                        // 必須項目か確認
                        if(isset($allowanceData['is_required'])){
                            $is_required = 1;
                        }else{
                            $is_required = 0;
                        }
                        ProjectAllowance::create([
                            'project_id' => $project->id,
                            'name' => $allowanceData['allowance_name'],
                            'retail_amount' => $removeCommasAndCastToInt($allowanceData['allowance_retail_amount']),
                            'driver_amount' => $removeCommasAndCastToInt($allowanceData['allowance_driver_amount']),
                            'is_required' => $is_required,
                        ]);
                    }
                }

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
        });

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

    public function allowanceDelete($allowanceId, $clientId)
    {
        ProjectAllowance::destroy($allowanceId);

        return redirect()->route('project.edit', ['id' => $clientId]);
    }

    public function info($id)
    {
        $project = Project::find($id);

        $financialMetrics = $this->calculateFinancialMetrics($project);

        return view('project.info', compact('project', 'financialMetrics'));
    }

    public function infoEdit($id)
    {
        $project = Project::find($id);

        $financialMetrics = $this->calculateFinancialMetrics($project);

        return view('project.infoEdit', compact('project', 'financialMetrics'));
    }

    public function infoUpdate(Request $request)
    {
        // リクエストデータを取得
        $data = $request->all();

        // retail_price_for_hgl が存在する場合、カンマを除去
        if (isset($data['retail_price_for_hgl'])) {
            $data['retail_price_for_hgl'] = str_replace(',', '', $data['retail_price_for_hgl']);
        }
        projectDetail::updateOrCreate(
            ['project_id' => $request->project_id],
            $data
        );

        $projectId = $request->project_id;

        return redirect()->route('project.info', ['id' => $projectId]);
    }

    public function infoPdf(Request $request)
    {
        $id = $request->project_id;
        $project = Project::find($id);

        $financialMetrics = $this->calculateFinancialMetrics($project);

        $pdf =  PDF::loadView('project.infoPdf',compact('project','financialMetrics'));
        $fileName = "{$project->name}_案件表.pdf";

        return $pdf->download($fileName); //生成されるファイル名

        return view('project.infoPdf');
    }

    public function calculateFinancialMetrics($project)
    {
        $retail_price = $project->retail_price ?? 0;
        $driver_price = $project->driver_price ?? 0;
        // 数値にキャストしてから演算を行う
        $tng_head = (float)($project->retail_price - $project->driver_price);
        $retail_price = (float)($project->retail_price);
        $profit_rate_tng = $retail_price != 0 ? $tng_head / $retail_price : 0;

        $retail_price_for_hgl = (float)($project->projectDetail->retail_price_for_hgl ?? 0);
        if($retail_price_for_hgl != 0){
            $hgl_head = (float)($retail_price - $retail_price_for_hgl);
            $profit_rate_hgl = $retail_price != 0 ? $hgl_head / $retail_price : 0;
        }else{
            $hgl_head = 0;
            $profit_rate_hgl = 0;
        }

        return [
            'retail_price' => $retail_price,
            'driver_price' => $driver_price,
            'tng_head' => $tng_head,
            'profit_rate_tng' => $profit_rate_tng,
            'retail_price_for_hgl' => $retail_price_for_hgl,
            'hgl_head' => $hgl_head,
            'profit_rate_hgl' => $profit_rate_hgl,
        ];
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
                // 'estimated_overtime_hours' => $row['estimated_overtime_hours'],
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
