<?php

namespace App\Http\Controllers;

use App\Models\AllowanceByProject;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectHoliday;
use App\Models\ProjectEmployeePayment;
use App\Models\Shift;
use App\Models\ShiftProjectVehicle;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Yasumi\Yasumi;

class InvoiceController extends Controller
{

    public function index()
    {
        return view('invoice.index');
    }

    public function driverShift()
    {
        $employees = Employee::all();

        $shifts = null;
        $warning = null;

        return view('invoice.driverShift', compact('employees', 'shifts', 'warning'));
    }

    public function driverShiftUpdate(Request $request)
    {

        $expressway = $request->expressway_fee;
        $parking = $request->parking_fee;
        $overtimeFee = $request->overtime_fee;
        $driverPrice = $request->driver_price;
        $allowance = $request->allowance;
        $getVehicle = $request->vehicle;

        $vehicles = Vehicle::all();

        foreach ($expressway as $id => $value) {

            $getShift = ShiftProjectVehicle::find($id);
            $getShift->expressway_fee = $value;
            $getShift->driver_price = $driverPrice[$id];
            $getShift->parking_fee = $parking[$id];
            $getShift->overtime_fee = $overtimeFee[$id];
            $getShift->total_allowance = $allowance[$id];

            $getShift->save();
        }

        foreach ($getVehicle as $id => $value) {
            $isCheck = false;
            $getShift = ShiftProjectVehicle::find($id);
            foreach ($vehicles as $vehicle) {
                if ($vehicle->number == $getVehicle[$id]) {
                    $getShift->vehicle_id = $vehicle->id;
                    $getShift->unregistered_vehicle = null;
                    $isCheck = true;
                }
            }
            if (!$isCheck) {
                $getShift->vehicle_id = null;
                $getShift->unregistered_vehicle = $getVehicle[$id];
            }

            $getShift->save();
        }

        // return $this->searchShift($request);
        $employeeId = $request->employeeId;
        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.searchShift')->with([
            'employeeId' => $employeeId,
            'year' => $getYear,
            'month' => $getMonth
        ]);
    }

    public function searchShift(Request $request)
    {

        $employeeId = $request->employeeId ?? session('employeeId');
        $getYear = $request->year ?? session('year');
        $getMonth = $request->month ?? session('month');

        $employees = Employee::all();
        $findEmployee = Employee::find($employeeId);

        $projects = Project::all();
        $allowanceProject = AllowanceByProject::where('employee_id', $findEmployee->id)->get();

        $vehicles = Vehicle::all();

        // シフトを検索・取得
        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle', 'projectsVehicles.rentalVehicle')
            ->where('employee_id', $employeeId)
            ->whereYear('date', $getYear)
            ->whereMonth('date', $getMonth)
            ->get();


        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);
        // 祝日を取得
        $holidays = $this->getHoliday($getYear);
        // 二代目以降の情報を取得
        [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount] = $this->machineInfoExtract($shifts);
        // 案件情報を取得
        $projectInfoArray = $this->projectInfoExtract($shifts);
        // 集計表情報を取得
        [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime] = $this->totallingInfoExtract($shifts);

        $warning = null;
        if ($shifts == null || $shifts->isEmpty()) {
            $warning = "選択したシフトは登録されいません";
        }

        return view('invoice.driverShift', compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'warning', 'secondMachineArray', 'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking', 'totalExpressWay', 'totalOverTime'));
    }

    public function driverCalendarPDF(Request $request)
    {
        $employeeId = $request->employeeId;
        $getYear = $request->year;
        $getMonth = $request->month;
        $textarea = $request->textarea;
        $amountCheck = $request->amountCheck;
        $allowanceCheck = $request->allowanceCheck;
        $expresswayCheck = $request->expresswayCheck;
        $parkingCheck = $request->parkingCheck;
        $vehicleCheck = $request->vehicleCheck;
        $overtimeCheck = $request->overtimeCheck;

        $employees = Employee::all();
        $findEmployee = Employee::find($employeeId);
        $employeeName = $findEmployee->name;

        $projects = Project::all();
        $allowanceProject = AllowanceByProject::where('employee_id', $findEmployee->id)->get();

        $vehicles = Vehicle::all();

        // シフトを検索・取得
        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle', 'projectsVehicles.rentalVehicle')
            ->where('employee_id', $employeeId)
            ->whereYear('date', $getYear)
            ->whereMonth('date', $getMonth)
            ->get();


        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);
        // 祝日を取得
        $holidays = $this->getHoliday($getYear);
        // 二代目以降の情報を取得
        [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount] = $this->machineInfoExtract($shifts);
        // 案件情報を取得
        $projectInfoArray = $this->projectInfoExtract($shifts);
        // 集計表情報を取得
        [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime] = $this->totallingInfoExtract($shifts);

        $warning = null;
        if ($shifts == null || $shifts->isEmpty()) {
            $warning = "選択したシフトは登録されいません";
        }

        $pdf =  PDF::loadView('issue-calendar-pdf.driver-calendar', compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'warning', 'secondMachineArray', 'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking', 'totalExpressWay', 'totalOverTime', 'textarea', 'amountCheck', 'allowanceCheck', 'expresswayCheck', 'parkingCheck', 'vehicleCheck', 'overtimeCheck'));
        $fileName = "{$getMonth}月_{$employeeName}.pdf";

        return $pdf->download($fileName); //生成されるファイル名
        return view('issue-calendar-pdf.driver-calendar', compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'warning', 'secondMachineArray', 'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking', 'totalExpressWay', 'totalOverTime', 'textarea', 'amountCheck', 'allowanceCheck', 'expresswayCheck', 'parkingCheck', 'vehicleCheck', 'overtimeCheck'));
    }

    function addVehicle($vehicleNumber, &$secondMachineArray, &$thirdMachineArray, &$secondMachineCheck) {
        if($vehicleNumber != '自車'){
            if ($secondMachineCheck) {
                if (!in_array($vehicleNumber, $secondMachineArray)) {
                    $secondMachineArray[] = $vehicleNumber;
                }
                $secondMachineCheck = false;
                return 1; // 2台目カウント増加
            } else {
                if (!in_array($vehicleNumber, $thirdMachineArray)) {
                    $thirdMachineArray[] = $vehicleNumber;
                }
                return 1; // 3台目カウント増加不要
            }
        }
    }

    function machineInfoExtract($shifts)
    {
        $secondMachineArray = [];
        $thirdMachineArray = [];
        $secondMachineCount = 0;
        $thirdMachineCount = 0;

        foreach($shifts as $shift){
            $secondMachineCheck = true;
            foreach($shift->projectsVehicles as $spv){
                if(in_array($spv->vehicle_rental_type, [0, 1])){
                    $vehicleNumber = $spv->vehicle ? $spv->vehicle->number : $spv->unregistered_vehicle;
                    if($vehicleNumber != null){
                        if(!$spv->rental_vehicle_id || $spv->vehicle_id != $spv->rental_vehicle_id){
                            if ($secondMachineCheck) {
                                $secondMachineCount += $this->addVehicle($vehicleNumber, $secondMachineArray, $thirdMachineArray, $secondMachineCheck);
                            } else {
                                $thirdMachineCount += $this->addVehicle($vehicleNumber, $secondMachineArray, $thirdMachineArray, $secondMachineCheck);
                            }
                        }
                    }
                }
            }
        }


        return [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount];
    }

    function projectInfoExtract($shifts)
    {
        $projectInfoArray = [];
        foreach($shifts as $shift){
            foreach($shift->projectsVehicles as $spv){
                $projectName = $spv->project ? $spv->project->name : $spv->unregistered_project;
                if($projectName != null){
                    if($projectName != '休み'){
                        if(isset($projectInfoArray[$projectName][$spv->retail_price])){
                            $projectInfoArray[$projectName][$spv->retail_price]++;
                        }else{
                            $projectInfoArray[$projectName][$spv->retail_price] = 1;
                        }
                    }
                }
            }
        }

        return $projectInfoArray;
    }

    function totallingInfoExtract($shifts)
    {
        $totalSalary = 0;
        $totalAllowance = 0;
        $totalParking = 0;
        $totalExpressWay = 0;
        $totalOverTime = 0;

        foreach($shifts as $shift){
            foreach($shift->projectsVehicles as $spv){
                $totalSalary += $spv->driver_price;
                $totalAllowance += $spv->total_allowance;
                $totalParking += $spv->parking_fee;
                $totalExpressWay += $spv->expressway_fee;
                $totalOverTime += $spv->overtime_fee;
            }
        }

        return [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime];
    }


    //*********************************************************************
    //******************************************************************* */



    public function projectShift()
    {
        $clients = Client::all();
        $ShiftProjectVehicles = null;
        $warning = null;

        return view('invoice.projectShift', compact('clients', 'ShiftProjectVehicles', 'warning'));
    }

    public function projectShiftUpdate(Request $request)
    {
        $expressway = $request->expressway_fee;
        $parking = $request->parking_fee;
        $driverPrice = $request->driver_price;
        $retailPrice = $request->retail_price;

        foreach ($expressway as $id => $value) {
            $getShift = ShiftProjectVehicle::find($id);
            $getShift->expressway_fee = $value;
            $getShift->parking_fee = $parking[$id];
            $getShift->driver_price = $driverPrice[$id];
            $getShift->retail_price = $retailPrice[$id];
            $getShift->save();
        }

        // $clientId = $request->client;
        // $getYear = $request->year;
        // $getMonth = $request->month;

        // $projects = Project::where('client_id', $clientId)->get();

        // // 検索用
        // $clients = Client::all();
        // $client = Client::find($clientId);

        // // シフトを検索・取得
        // $ShiftProjectVehicles = ShiftProjectVehicle::with('shift','shift.employee.company','project')
        // ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
        //     $query->whereYear('date', $getYear)
        //         ->whereMonth('date', $getMonth);
        // })
        // ->whereHas('project', function ($query) use ($clientId) {
        //     $query->where('client_id', $clientId);
        // })
        // ->get();

        // // 所属先取得
        // $companyIds = [];
        // foreach ($ShiftProjectVehicles as $spv) {
        //     if ($spv->shift && $spv->shift->employee && $spv->shift->employee->company) {
        //         // Company にアクセス
        //         $company = $spv->shift->employee->company;
        //         if(!in_array($company->id,$companyIds)){
        //             $companyIds[] = $company->id;;
        //         }
        //     }
        // }
        // $getCompanies = Company::whereIn('id', $companyIds)->get();

        // // 全日にちを取得
        // $dates = $this->createDate($getYear, $getMonth);

        // return view('invoice.projectShift', compact('projects', 'clients' ,'client', 'ShiftProjectVehicles', 'getCompanies', 'getYear', 'getMonth', 'dates'));

        return $this->searchProjectShift($request);
    }

    public function searchProjectShift(Request $request)
    {
        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        $projects = Project::where('client_id', $clientId)->get();

        // 検索用
        $clients = Client::all();
        $getClient = Client::find($clientId);

        // シフトを検索・取得
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee.company', 'project')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereHas('project', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->get();

        // 所属先ID取得
        $companyIds = [];
        foreach ($ShiftProjectVehicles as $spv) {
            if ($spv->shift && $spv->shift->employee && $spv->shift->employee->company) {
                // Company にアクセス
                $company = $spv->shift->employee->company;
                if (!in_array($company->id, $companyIds)) {
                    $companyIds[] = $company->id;;
                }
            }
        }
        // 所属先データ取得
        $getCompanies = Company::whereIn('id', $companyIds)->get();

        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        $warning = null;
        if ($ShiftProjectVehicles == null || $ShiftProjectVehicles->isEmpty()) {
            $warning = "選択されたシフトは登録されていません";
        }

        return view('invoice.projectShift', compact('projects', 'clients', 'getClient', 'ShiftProjectVehicles', 'getCompanies', 'getYear', 'getMonth', 'dates', 'warning'));
    }

    public function projectCalendarPDF(Request $request)
    {
        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        $retailCheck = $request->has('retailCheck') ? 1 : 0;
        $salaryCheck = $request->has('salaryCheck') ? 1 : 0;
        $expresswayCheck = $request->has('expresswayCheck') ? 1 : 0;
        $parkingCheck = $request->has('parkingCheck') ? 1 : 0;
        $selectedCompanies = $request->input('company', []);

        $projects = Project::where('client_id', $clientId)->get();

        // 検索用
        $clients = Client::all();
        $client = Client::find($clientId);

        // シフトを検索・取得
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee.company', 'project')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereHas('project', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->get();

        // 所属先ID格納配列
        $companyIds = [];
        // 取得したinputの配列のKeyにcompanyのidが格納済み
        foreach($selectedCompanies as $index => $value){
            $companyIds[] = $index;
        }
        $getCompanies = Company::whereIn('id', $companyIds)->get();


        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        $clientName = $client->name;
        $pdf =  PDF::loadView('issue-calendar-pdf.project-calendar', compact('projects', 'clients', 'client', 'ShiftProjectVehicles', 'getCompanies', 'getYear', 'getMonth', 'dates', 'retailCheck', 'salaryCheck', 'expresswayCheck', 'parkingCheck'));
        $fileName = "{$getMonth}月_{$clientName}.pdf";

        return $pdf->download($fileName); //生成されるファイル名

        return view('issue-calendar-pdf.project-calendar', compact('projects', 'clients', 'client', 'ShiftProjectVehicles', 'getCompanies', 'getYear', 'getMonth', 'dates', 'retailCheck', 'salaryCheck', 'expresswayCheck', 'parkingCheck'));
    }



    //*********************************************************************
    //******************************************************************* */



    public function charterShift()
    {
        $shiftArray = null;
        $warning = null;

        return view('invoice.charterShift', compact('shiftArray', 'warning'));
    }

    public function charterClientUpdate(Request $request)
    {
        $radio = $request->client_switch;
        // 既存案件に使用変数
        $client_id = $request->clientId;
        // 新規案件に使用変数
        $client_name = $request->clientName;
        $client_pdf_name = $request->clientPdfName;

        $shift_id = $request->shift_id;

        $shiftPV = ShiftProjectVehicle::find($shift_id);
        $project_name = $shiftPV->unregistered_project;

        $project = null;

        if ($radio == 0) { // 既存の案件
            $project = Project::create([
                'client_id' => $client_id,
                'name' => $project_name,
                'is_charter' => 1,
                'payment_type' => 1,
            ]);

            $findShiftPV = ShiftProjectVehicle::where('unregistered_project', $project_name)->get();
            foreach ($findShiftPV as $shift) {
                $shift->project_id = $project->id;
                $shift->unregistered_project = null;
                $shift->save();
            }
        } else { // 新規の案件
            $client = Client::create([
                'name' => $client_name,
                'pdfName' => $client_pdf_name,
            ]);
            $project = Project::create([
                'client_id' => $client->id,
                'name' => $project_name,
                'is_charter' => 1,
                'payment_type' => 1,
            ]);
            $shiftPV->project_id = $project->id;
            $shiftPV->unregistered_project = null;
            $shiftPV->save();
        }


        ProjectHoliday::create([
            'project_id' => $project->id
        ]);

        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.findCharterShift', ['year' => $getYear, 'month' => $getMonth]);
    }

    public function charterShiftUpdate(Request $request)
    {
        $retail_price = $request->input('retail_price');
        $expressway_fee = $request->input('expressway_fee');
        $parking_fee = $request->input('parking_fee');
        $driver_price = $request->input('driver_price');
        $unregistered_project = $request->input('unregistered_project');


        foreach ($retail_price as $id => $value) {
            $getShift = ShiftProjectVehicle::find($id);
            if ($getShift !== null) { // $getShiftがnullでないことを確認
                $getShift->retail_price = $retail_price[$id];
                $getShift->expressway_fee = $expressway_fee[$id];
                $getShift->parking_fee = $parking_fee[$id];
                $getShift->driver_price = $driver_price[$id];
                $getShift->save();
            }
        }

        foreach ($unregistered_project as $id => $value) {
            $getShift = ShiftProjectVehicle::find($id);
            if ($getShift !== null) { // 同様に、$getShiftがnullでないことを確認
                $getShift->unregistered_project = $value;
                $getShift->save();
            }
        }

        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.findCharterShift', ['year' => $getYear, 'month' => $getMonth]);
    }

    public function searchCharterShift(Request $request)
    {
        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.findCharterShift', ['year' => $getYear, 'month' => $getMonth]);
    }

    public function findCharterDate($getYear, $getMonth)
    {
        // チャーター案件が含まれるシフト
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
            ->join('shifts', 'shift_project_vehicle.shift_id', '=', 'shifts.id')
            ->select('shift_project_vehicle.*', 'shifts.date as shift_date')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereHas('project', function ($query) {
                $query->where('is_charter', 1);
            })
            ->orderBy('shifts.date', 'asc')
            ->get();

        // 未登録の案件があるシフト
        $unregisterProjectShift = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
            ->join('shifts', 'shift_project_vehicle.shift_id', '=', 'shifts.id')
            ->select('shift_project_vehicle.*', 'shifts.date as shift_date')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereNotNull('shift_project_vehicle.unregistered_project') // この行を追加
            ->orderBy('shifts.date', 'asc')
            ->get();

            // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);



        // チャーター案件が含まれるシフトを配列に変換
        $shiftArray = $ShiftProjectVehicles->toArray();

        // チャーター案件があるクライアントを取得
        $clients = Client::all();

        // 新しい配列を作成
        $arrangeShiftArray = [];
        $tmpItem = null; // 外側のスコープで変数を宣言
        $skipIndex = [];

        foreach ($shiftArray as $index => $data) {

            if (in_array($index, $skipIndex)) {
                continue;
            }
            // 新しい配列に要素を追加
            $arrangeShiftArray[] = $data;
            // プロジェクト名を格納
            $firstCheckName = $data['project']['name'];

            if (str_contains($firstCheckName, '引取') && !str_contains($firstCheckName, '納品')) {
                // プロジェクトのclient_idを取得
                $tmpClientId = $data['project']['client']['id'];

                foreach ($shiftArray as $innerIndex => $innerData) {
                    if (in_array($innerIndex, $skipIndex)) {
                        continue;
                    }
                    if ($index > $innerIndex) {
                        continue;
                    }
                    $secondCheckName = $innerData['project']['name'];

                    if ($innerData['project']['client_id'] == $tmpClientId) {
                        if (!str_contains($secondCheckName, '引取') && str_contains($secondCheckName, '納品')) {
                            // 全角・半角の括弧の正規表現
                            $pattern = "/[\(（].+?[\)）]/u";
                            // 全角・半角の括弧と括弧の中身を削除
                            $cleanStr01 = preg_replace($pattern, "", $firstCheckName);
                            $cleanStr02 = preg_replace($pattern, "", $secondCheckName);
                            if ($cleanStr01 === $cleanStr02) {
                                // データを一時格納
                                $tmpItem = $innerData;
                                $skipIndex[] = $innerIndex;
                                break; // 内側のループを終了
                            }
                        }
                    }
                }

                if ($tmpItem !== null) {
                    // 条件を満たしたアイテムを $arrangeShiftArray に追加
                    $arrangeShiftArray[] = $tmpItem;
                    $tmpItem = null; // $tmpItem をリセット
                }
            }
        }
        // 元の配列に代入
        $shiftArray = array_values($arrangeShiftArray);
        // dd($shiftArray);

        $warning = null;
        if (!$shiftArray) {
            $warning = "選択されたシフトは登録されていません";
        }

        return view('invoice.charterShift', compact('ShiftProjectVehicles', 'shiftArray', 'unregisterProjectShift', 'clients', 'getYear', 'getMonth', 'warning', 'dates'));
    }

    public function charterCalendarPDF(Request $request)
    {
        $getYear = $request->year;
        $getMonth = $request->month;


        // チャーター案件が含まれるシフト
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
            ->join('shifts', 'shift_project_vehicle.shift_id', '=', 'shifts.id')
            ->select('shift_project_vehicle.*', 'shifts.date as shift_date')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereHas('project', function ($query) {
                $query->where('is_charter', 1);
            })
            ->orderBy('shifts.date', 'asc')
            ->get();

        // 未登録の案件があるシフト
        $unregisterProjectShift = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
            ->join('shifts', 'shift_project_vehicle.shift_id', '=', 'shifts.id')
            ->select('shift_project_vehicle.*', 'shifts.date as shift_date')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereNotNull('shift_project_vehicle.unregistered_project') // この行を追加
            ->orderBy('shifts.date', 'asc')
            ->get();


        // チャーター案件が含まれるシフトを配列に変換
        $shiftArray = $ShiftProjectVehicles->toArray();

        // チャーター案件があるクライアントを取得
        $clients = Client::all();

        // 新しい配列を作成
        $arrangeShiftArray = [];
        $tmpItem = null; // 外側のスコープで変数を宣言
        $skipIndex = [];

        foreach ($shiftArray as $index => $data) {

            if (in_array($index, $skipIndex)) {
                continue;
            }
            // 新しい配列に要素を追加
            $arrangeShiftArray[] = $data;
            // プロジェクト名を格納
            $firstCheckName = $data['project']['name'];

            if (str_contains($firstCheckName, '引取') && !str_contains($firstCheckName, '納品')) {
                // プロジェクトのclient_idを取得
                $tmpClientId = $data['project']['client']['id'];

                foreach ($shiftArray as $innerIndex => $innerData) {
                    if (in_array($innerIndex, $skipIndex)) {
                        continue;
                    }
                    if ($index > $innerIndex) {
                        continue;
                    }
                    $secondCheckName = $innerData['project']['name'];

                    if ($innerData['project']['client_id'] == $tmpClientId) {
                        if (!str_contains($secondCheckName, '引取') && str_contains($secondCheckName, '納品')) {
                            // 全角・半角の括弧の正規表現
                            $pattern = "/[\(（].+?[\)）]/u";
                            // 全角・半角の括弧と括弧の中身を削除
                            $cleanStr01 = preg_replace($pattern, "", $firstCheckName);
                            $cleanStr02 = preg_replace($pattern, "", $secondCheckName);
                            if ($cleanStr01 === $cleanStr02) {
                                // データを一時格納
                                $tmpItem = $innerData;
                                $skipIndex[] = $innerIndex;
                                break; // 内側のループを終了
                            }
                        }
                    }
                }

                if ($tmpItem !== null) {
                    // 条件を満たしたアイテムを $arrangeShiftArray に追加
                    $arrangeShiftArray[] = $tmpItem;
                    $tmpItem = null; // $tmpItem をリセット
                }
            }
        }
        // 元の配列に代入
        $shiftArray = array_values($arrangeShiftArray);

        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        $pdf =  PDF::loadView('issue-calendar-pdf.charter-calendar', compact('ShiftProjectVehicles', 'shiftArray', 'unregisterProjectShift', 'clients', 'getYear', 'getMonth', 'dates'));
        $fileName = "{$getYear}_{$getMonth}_チャーター.pdf";

        return $pdf->download($fileName); //生成されるファイル名

        // return view('issue-calendar-pdf.charter-calendar', compact('ShiftProjectVehicles', 'shiftArray', 'unregisterProjectShift', 'clients', 'getYear', 'getMonth', 'dates'));
    }



    public function createDate($year, $month)
    {
        $start = Carbon::createFromDate($year, $month, 1);
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $dates = [];
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dates[] = $date->copy();
        }

        return $dates;
    }

    public function getHoliday($year)
    {
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');

        return $holidays;
    }
}
