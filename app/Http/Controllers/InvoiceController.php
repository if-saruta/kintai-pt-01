<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Models\AllowanceByProject;
use App\Models\Client;
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
        $getYear = null;
        $getMonth = null;
        $employeeId = null;
        $employees = Employee::all();

        $shifts = null;
        $warning = null;

        return view('invoice.driverShift', compact('employees', 'shifts', 'warning', 'getYear', 'getMonth', 'employeeId'));
    }

    public function driverShiftCreate(Request $request)
    {
        $getYear = $request->year;
        $getMonth = $request->month;

        $getDate = $request->createDate;
        $employeeId = $request->employeeId;
        $shiftPvId = $request->shiftPvId;
        $dayOfPart = $request->dayOfPart;
        $projectId = $request->createProject;
        $vehicleId = $request->createVehicle;
        $retailPrice = $request->createRetail;
        $salaryPrice = $request->createSalary;


        // 日付がシフトに登録されていなければその日付週すべのドライバーを登録
        $carbonDate = new Carbon($getDate);
        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $carbonDate->copy()->startOfWeek();
        $endOfWeek = $carbonDate->copy()->endOfWeek();

        $weekDates = [];
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $weekDates[] = $date->toDateString();
        }

        // $weekDatesには、週の始まりから終わりまでの日付が入っています
        $employees = Employee::all();
        foreach ($weekDates as $day) {
            foreach($employees as $employee){
                Shift::updateOrCreate(
                    ['date' => $day, 'employee_id' => $employee->id],
                    ['date' => $day, 'employee_id' => $employee->id],
                );
            }
        }

        $findEmployee = Employee::find($employeeId);

        $attributes = ['date' => $getDate, 'employee_id' => $employeeId];
        $values = ['date' => $getDate, 'employee_id' => $employeeId];
        $shift = Shift::updateOrCreate($attributes, $values);

        if($shiftPvId != null){
             $shiftPv = ShiftProjectVehicle::find($shiftPvId);
             if ($request->input('action') == 'update') {
                // 更新処理
                if($shiftPv){
                    $valuesByPvShift = [
                        'project_id' => $projectId,
                        'unregistered_project' => null,
                        'vehicle_id' => $vehicleId,
                        'unregistered_vehicle' => null,
                        'retail_price' => $retailPrice,
                        'driver_price' => $salaryPrice,
                        'vehicle_rental_type' => $findEmployee->vehicle_rental_type,
                        'rental_vehicle_id' => $findEmployee->vehicle_id,
                     ];
                    $shiftPv->update($valuesByPvShift);
                 }
            } elseif ($request->input('action') == 'delete') {
                // 削除処理
                $shiftPv->delete();
            }
        }else{
            $valuesByPvShift = [
                'shift_id' => $shift->id,
                'project_id' => $projectId,
                'vehicle_id' => $vehicleId,
                'retail_price' => $retailPrice,
                'driver_price' => $salaryPrice,
                'time_of_day' => $dayOfPart,
                'vehicle_rental_type' => $findEmployee->vehicle_rental_type,
                'rental_vehicle_id' => $findEmployee->vehicle_id,
             ];
            ShiftProjectVehicle::create($valuesByPvShift);
        }

        return redirect()->route('invoice.searchShift')->with([
            'employeeId' => $employeeId,
            'year' => $getYear,
            'month' => $getMonth
        ]);
    }

    public function driverShiftUpdate(Request $request)
    {

        $expressway = $request->expressway_fee ?? [];
        $parking = $request->parking_fee ?? [];
        $overtimeFee = $request->overtime_fee ?? [];
        $driverPrice = $request->driver_price ?? [];
        $allowance = $request->allowance ?? [];
        $getVehicle = $request->vehicle ?? [];

        $vehicles = Vehicle::all();

        foreach ($expressway as $id => $value) {

            $getShift = ShiftProjectVehicle::find($id);
            // 全角・半角のカンマを除去
            $getShift->expressway_fee = str_replace([',', '，'], '', $value);
            $getShift->driver_price = str_replace([',', '，'], '', $driverPrice[$id]);
            $getShift->parking_fee = str_replace([',', '，'], '', $parking[$id]);
            $getShift->overtime_fee = str_replace([',', '，'], '', $overtimeFee[$id]);
            $getShift->total_allowance = str_replace([',', '，'], '', $allowance[$id]);

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
        // 検索情報
        $employeeId = $request->employeeId ?? session('employeeId');
        $getYear = $request->year ?? session('year');
        $getMonth = $request->month ?? session('month');

        // 列の絞り込み
        $selectedNarrowCheck = $request->input('narrowCheck', []);
        // 行数の絞り込み
        $needRowCount = $request->input('rowNeedCount');

        $employees = Employee::all();
        $findEmployee = Employee::find($employeeId);

        $projects = Project::where('client_id', '!=', '1')->get();
        $allowanceProject = AllowanceByProject::where('employee_id', $findEmployee->id)->get();

        $vehicles = Vehicle::all();

        $shifts = Shift::with(['employee', 'projectsVehicles.project', 'projectsVehicles.vehicle', 'projectsVehicles.rentalVehicle'])
            ->where('employee_id', $employeeId)
            ->whereYear('date', $getYear)
            ->whereMonth('date', $getMonth)
            ->get();

        // 取得したシフトに含まれるproject・clientのIDを取得
        $projectArray = [];
        $clientArray = [];
        foreach($shifts as $shift){
            foreach($shift->projectsVehicles as $spv){
                if($spv->project){
                    if(!in_array($spv->project_id, $projectArray)){
                        $projectArray[] = $spv->project_id;
                    }
                    if(!in_array($spv->project->client_id, $clientArray)){
                        $clientArray[] = $spv->project->client_id;
                    }
                }
            }
        }
        // Projectモデルを使用して、$projectArrayに含まれるIDのプロジェクトを取得
        $findProjects = Project::whereIn('id', $projectArray)->get();
        $findClients = Client::whereIn('id', $clientArray)->where('id', '!=', 1)->get();

        // // 絞り込み情報の取得
        $clientsId = $request->input('clientsId', []);
        $projectsId = $request->input('projectsId', []);

        // ShiftProjectVehicle モデルを使用して、特定の条件に一致するデータを取得
        $query = ShiftProjectVehicle::query()
        ->with(['shift','shift.employee', 'project', 'vehicle', 'rentalVehicle'])
        ->whereNotNull('project_id')
        ->whereHas('shift', function ($query) use ($employeeId, $getYear, $getMonth) {
            // Employee ID、年、月でフィルタリング
            $query->where('employee_id', $employeeId)
                  ->whereYear('date', $getYear)
                  ->whereMonth('date', $getMonth);
        });


        $shiftProjectVehiclesByEmployee = $query->get();

        // コレクションのfilterメソッドを使用してフィルタリング
        $shiftProjectVehicles = $shiftProjectVehiclesByEmployee->filter(function ($spv) use ($projectsId, $clientsId) {
            if(!empty($clientsId) || !empty($projectsId)){
                if($spv->project){
                    // client_idは排除
                    if($spv->project->client_id != 1){
                        return in_array($spv->project->id, $projectsId) || in_array($spv->project->client_id, $clientsId);
                    }
                }
            }else{
                if($spv->project){
                    // client_idは排除
                    if($spv->project->client_id != 1){
                        return $spv;
                    }
                }else{
                    return $spv;
                }
            }
        });


        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);
        // 祝日を取得
        $holidays = $this->getHoliday($getYear);
        // 二代目以降の情報を取得
        [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount] = $this->machineInfoExtract($shiftProjectVehicles, $dates);
        // 案件情報を取得
        $projectInfoArray = $this->projectInfoExtract($shiftProjectVehicles);
        // 集計表情報を取得
        [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime] = $this->totallingInfoExtract($shiftProjectVehicles);

        $warning = null;
        if ($shifts == null || $shifts->isEmpty()) {
            $warning = "選択したシフトは登録されいません";
        }

        return view('invoice.driverShift',
            compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'shiftProjectVehicles', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'warning', 'secondMachineArray', 'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking', 'totalExpressWay', 'totalOverTime', 'findProjects', 'findClients',
                    'selectedNarrowCheck', 'needRowCount', 'clientsId', 'projectsId', 'employeeId'));
    }

    public function driverCalendarPDF(Request $request)
    {
        $employeeId = $request->employeeId;
        $getYear = $request->year;
        $getMonth = $request->month;

        $textarea = $request->textarea;
        // チェックボックス
        $amountCheck = $request->invoiceAmountCheck;
        $allowanceCheck = $request->invoiceAllowanceCheck;
        $expresswayCheck = $request->invoiceExpresswayCheck;
        $parkingCheck = $request->invoiceParkingCheck;
        $vehicleCheck = $request->invoiceVehicleCheck;
        $overtimeCheck = $request->invoiceOvertimeCheck;
        $setRowCount = $request->setRowCount;

        // 合計テーブルのデータを受け取り
        $totalSalaryName = $request->input('totalSalary.name'); //給与合計
        $totalSalaryAmount = str_replace([',', '，'], '', $request->input('totalSalary.amount')); //給与合計
        $allowanceName = $request->input('allowance.name'); //手当
        $allowanceAmount = str_replace([',', '，'], '', $request->input('allowance.amount')); //手当
        $taxName = $request->input('tax.name'); //消費税
        $taxAmount = str_replace([',', '，'], '', $request->input('tax.amount')); //消費税
        $expressWayName = $request->input('expressWayName'); //高速代
        $expressWayAmount = str_replace([',', '，'], '', $request->input('expressWayAmount')); //高速代
        $parkingName = $request->input('parkingName'); //パーキング代
        $parkingAmount = str_replace([',', '，'], '', $request->input('parkingAmount')); //パーキング代
        $overtimeName = $request->input('overtimeName'); //残業代
        $overtimeAmount = str_replace([',', '，'], '', $request->input('overtimeAmount')); //残業代
        $otherNames = $request->input('otherName', []); //追加項目名
        $otherAmounts = $request->input('otherAmount', []); //追加項目金額
        // 追加項目の空でない名前と金額のペアを新しい配列に追加
        $others = [];
        foreach ($otherNames as $index => $name) {
            if (!empty($name) && !empty($otherAmounts[$index])) {
                $others[] = [
                    'name' => $name,
                    'amount' => str_replace([',', '，'], '', $otherAmounts[$index])
                ];
            }
        }

        // 費用テーブルのデータを受け取り
        $administrativeOutsourcingName = $request->input('administrativeOutsourcing.name'); //業務委託手数料
        $administrativeOutsourcingAmount = str_replace([',', '，'], '', $request->input('administrativeOutsourcing.amount')); //業務委託手数料
        $administrativeName = $request->input('administrative.name'); //事務手数料
        $administrativeAmount = str_replace([',', '，'], '', $request->input('administrative.amount')); //事務手数料
        $transferName = $request->input('transfer.name'); //振り込み手数料
        $transferAmount = str_replace([',', '，'], '', $request->input('transfer.amount')); //振り込み手数料
        // 条件に応じてリクエストから値を受け取る
        $monthLeaseName = $request->input('monthLease.name'); //月リース
        $monthLeaseAmount = str_replace([',', '，'], '', $request->input('monthLease.amount')); //月リース
        $secondLeaseName = $request->input('secondLease.name'); //二代目りーす
        $secondLeaseAmount = str_replace([',', '，'], '', $request->input('secondLease.amount')); //二代目りーす
        $thirdLeaseName = $request->input('thirdLease.name'); //三台目リース
        $thirdLeaseAmount = str_replace([',', '，'], '', $request->input('thirdLease.amount')); //三台目リース
        $monthInsuranceName = $request->input('monthInsurance.name'); //月保険料
        $monthInsuranceAmount = str_replace([',', '，'], '', $request->input('monthInsurance.amount')); //月保険料
        $secondInsuranceName = $request->input('secondInsurance.name'); //二代目以降保険料
        $secondInsuranceAmount = str_replace([',', '，'], '', $request->input('secondInsurance.amount')); //二代目以降保険料
        $otherCostNames = $request->input('otherCostName', []); //費用追加項目名
        $otherCostAmonts = $request->input('otherCostAmont', []); //費用追加項目金額
        // 追加項目の空でない名前と金額のペアを新しい配列に追加
        $CostOthers = [];
        foreach ($otherCostNames as $index => $name) {
            if (!empty($name) && !empty($otherCostAmonts[$index])) {
                $CostOthers[] = [
                    'name' => $name,
                    'amount' => str_replace([',', '，'], '', $otherCostAmonts[$index])
                ];
            }
        }

        // // 絞り込み情報の取得
        $clientsId = $request->input('calendarClientsId', []);
        $projectsId = $request->input('calendarProjectsId', []);
        // dd($clientsId);
        // ShiftProjectVehicle モデルを使用して、特定の条件に一致するデータを取得
        $query = ShiftProjectVehicle::query()
        ->with(['shift','shift.employee', 'project', 'vehicle', 'rentalVehicle'])
        ->whereNotNull('project_id')
        ->whereHas('shift', function ($query) use ($employeeId, $getYear, $getMonth) {
            // Employee ID、年、月でフィルタリング
            $query->where('employee_id', $employeeId)
                    ->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
        });


        $shiftProjectVehiclesByEmployee = $query->get();

        // コレクションのfilterメソッドを使用してフィルタリング
        $shiftProjectVehicles = $shiftProjectVehiclesByEmployee->filter(function ($spv) use ($projectsId, $clientsId) {
            if(!empty($clientsId) || !empty($projectsId)){
                if($spv->project){
                    // client_idは排除
                    if($spv->project->client_id != 1){
                        return in_array($spv->project->id, $projectsId) || in_array($spv->project->client_id, $clientsId);
                    }
                }
            }else{
                if($spv->project){
                    // client_idは排除
                    if($spv->project->client_id != 1){
                        return $spv;
                    }
                }else{
                    return $spv;
                }
            }
        });

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
        [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount] = $this->machineInfoExtract($shiftProjectVehicles, $dates);
        // 案件情報を取得
        $projectInfoArray = $this->projectInfoExtract($shiftProjectVehicles);
        // 集計表情報を取得
        [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime] = $this->totallingInfoExtract($shiftProjectVehicles);

        $pdf =  PDF::loadView('issue-calendar-pdf.driver-calendar',
                    compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'secondMachineArray',
                            'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking',
                            'totalExpressWay', 'totalOverTime', 'textarea', 'amountCheck', 'allowanceCheck', 'expresswayCheck', 'parkingCheck', 'vehicleCheck', 'overtimeCheck',
                            'setRowCount', 'shiftProjectVehicles',
                            // ドライバー価格
                            'totalSalaryName','totalSalaryAmount','allowanceName','allowanceAmount','taxName','taxAmount','expressWayAmount','parkingAmount','overtimeAmount','others',
                            'parkingName', 'expressWayName', 'overtimeName',
                            // 費用関連
                            'administrativeOutsourcingName','administrativeOutsourcingAmount','administrativeName','administrativeAmount','transferName','transferAmount','monthLeaseName',
                            'monthLeaseAmount','secondLeaseName','secondLeaseAmount','thirdLeaseName','thirdLeaseAmount','monthInsuranceName','monthInsuranceAmount','secondInsuranceName','secondInsuranceAmount','CostOthers'
                        ));
        $fileName = "{$getMonth}月_{$employeeName}.pdf";
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download($fileName); //生成されるファイル名

        return view('issue-calendar-pdf.driver-calendar', compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'secondMachineArray',
                            'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking',
                            'totalExpressWay', 'totalOverTime', 'textarea', 'amountCheck', 'allowanceCheck', 'expresswayCheck', 'parkingCheck', 'vehicleCheck', 'overtimeCheck',
                            'setRowCount', 'shiftProjectVehicles',
                            // ドライバー価格
                            'totalSalaryName','totalSalaryAmount','allowanceName','allowanceAmount','taxName','taxAmount','expressWayAmount','parkingAmount','overtimeAmount','others',
                            'parkingName', 'expressWayName', 'overtimeName',
                            // 費用関連
                            'administrativeOutsourcingName','administrativeOutsourcingAmount','administrativeName','administrativeAmount','transferName','transferAmount','monthLeaseName',
                            'monthLeaseAmount','secondLeaseName','secondLeaseAmount','thirdLeaseName','thirdLeaseAmount','monthInsuranceName','monthInsuranceAmount','secondInsuranceName','secondInsuranceAmount','CostOthers'));
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
                if(!in_array($vehicleNumber, $secondMachineArray)){
                    if (!in_array($vehicleNumber, $thirdMachineArray)) {
                        $thirdMachineArray[] = $vehicleNumber;
                    }
                    return 1; // 3台目カウント増加不要
                }
            }
        }
    }

    function machineInfoExtract($shiftProjectVehicles, $dates)
    {
        $secondMachineArray = [];
        $thirdMachineArray = [];
        $secondMachineCount = 0;
        $thirdMachineCount = 0;

        foreach($dates as $date){
            $secondMachineCheck = true;
            foreach($shiftProjectVehicles as $spv){
                if($spv->shift->date == $date->format('Y-m-d')){
                    if(in_array($spv->vehicle_rental_type, [0, 1, 3])){
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
        }


        return [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount];
    }

    function projectInfoExtract($shiftProjectVehicles)
    {
        $projectInfoArray = [];
        foreach($shiftProjectVehicles as $spv){
            $projectName = $spv->project ? $spv->project->name : $spv->unregistered_project;
            if($projectName != null){
                if($projectName != '休み'){
                    if(isset($projectInfoArray[$projectName][$spv->driver_price])){
                        $projectInfoArray[$projectName][$spv->driver_price]++;
                    }else{
                        $projectInfoArray[$projectName][$spv->driver_price] = 1;
                    }
                }
            }
        }

        return $projectInfoArray;
    }

    function totallingInfoExtract($shiftProjectVehicles)
    {
        $totalSalary = 0;
        $totalAllowance = 0;
        $totalParking = 0;
        $totalExpressWay = 0;
        $totalOverTime = 0;

        foreach($shiftProjectVehicles as $spv){
            $totalSalary += $spv->driver_price;
            $totalAllowance += $spv->total_allowance;
            $totalParking += $spv->parking_fee;
            $totalExpressWay += $spv->expressway_fee;
            $totalOverTime += $spv->overtime_fee;
        }

        return [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime];
    }


    //*********************************************************************
    //******************************************************************* */



    public function projectShift()
    {
        $clients = Client::where('id', '!=', 1)->get();
        $ShiftProjectVehicles = null;
        $warning = null;
        $clientId = null;
        $getYear = null;
        $getMonth = null;

        return view('invoice.projectShift', compact('clients', 'ShiftProjectVehicles', 'warning', 'clientId', 'getYear', 'getMonth'));
    }

    public function projectShiftUpdate(Request $request)
    {
        $expressway = $request->expressway_fee;
        $parking = $request->parking_fee;
        $driverPrice = $request->driver_price;
        $retailPrice = $request->retail_price;

        foreach ($expressway as $id => $value) {
            $getShift = ShiftProjectVehicle::find($id);
            $getShift->expressway_fee = str_replace([',', '，'], '', $value);
            $getShift->parking_fee = str_replace([',', '，'], '', $parking[$id]);
            $getShift->driver_price = str_replace([',', '，'], '', $driverPrice[$id]);
            $getShift->retail_price = str_replace([',', '，'], '', $retailPrice[$id]);
            $getShift->save();
        }

        return $this->searchProjectShift($request);
    }

    public function projectShiftDelete(Request $request)
    {
        $id = $request->shiftPvId;

        $shiftPv = ShiftProjectVehicle::find($id);
        $shiftPv->delete();

        return $this->searchProjectShift($request);
    }

    public function searchProjectShift(Request $request)
    {
        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        $projects = Project::where('client_id', $clientId)->get();

        // 検索用
        $clients = Client::where('id', '!=', 1)->get();
        $getClient = Client::find($clientId);

        // 列の表示・非表示
        $selectedDisplayCheck = $request->input('displayCheck', []);
        $selectedDisplayCoCheck = $request->input('displayCoCheck', []);
        // 案件の絞り込み
        $narrowProjectIds = $request->input('narrowProjects', []);

        // シフトを検索・取得
        $ShiftProjectVehiclesByClient = ShiftProjectVehicle::with('shift', 'shift.employee.company', 'project')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereHas('project', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->get();

        // シフトを案件で絞り込み
        $ShiftProjectVehicles = $ShiftProjectVehiclesByClient->filter(function ($spv) use ($narrowProjectIds){
            if(!empty($narrowProjectIds)){
                return in_array($spv->project_id, $narrowProjectIds);
            }else{
                return $spv;
            }
        });
        // 絞り込み対象案件
        $narrowProjects = Project::where('client_id', $clientId)
                        ->when(!empty($narrowProjectIds), function($query) use ($narrowProjectIds){
                            return $query->whereIn('id', $narrowProjectIds);
                        })->get();

        // 所属先ID取得
        $companyIds = [];
        foreach ($ShiftProjectVehiclesByClient as $spv) {
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
        if ($ShiftProjectVehiclesByClient == null || $ShiftProjectVehiclesByClient->isEmpty()) {
            $warning = "選択されたシフトは登録されていません";
        }

        return view('invoice.projectShift',
            compact('projects', 'clients', 'clientId', 'getClient', 'ShiftProjectVehicles', 'getCompanies',
                    'getYear', 'getMonth', 'dates', 'warning',
                    'selectedDisplayCheck', 'selectedDisplayCoCheck', 'narrowProjects', 'narrowProjectIds'));
    }

    public function projectCalendarPDF(Request $request)
    {
        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        $retailCheck = $request->input('retailCheck');
        $salaryCheck = $request->input('salaryCheck');
        $expresswayCheck = $request->input('expresswayCheck');
        $parkingCheck = $request->input('parkingCheck');
        $selectedCompanies = $request->input('company', []);
        $selectedProjectIds = $request->input('narrowProjectIds', []);

        $projects = Project::where('client_id', $clientId)
                    ->when(!empty($selectedProjectIds), function($query) use ($selectedProjectIds){
                        return $query->whereIn('id', $selectedProjectIds);
                    })->get();

        // 検索用
        $clients = Client::where('id', '!=', 1)->get();
        $client = Client::find($clientId);

        // シフトを検索・取得
        $ShiftProjectVehiclesByClient = ShiftProjectVehicle::with('shift', 'shift.employee.company', 'project')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth)
                    ->whereNotNull('employee_id');
            })
            ->whereHas('project', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->get();

        $ShiftProjectVehicles = $ShiftProjectVehiclesByClient->filter(function ($spv) use ($selectedProjectIds){
            if(!empty($selectedProjectIds)){
                if($spv->project){
                    return in_array($spv->project->id, $selectedProjectIds);
                }
            }else{
                return $spv;
            }
        });

        // 所属先ID格納配列
        $companyIds = [];
        // 取得したinputの配列のKeyにcompanyのidが格納済み
        foreach($selectedCompanies as $index => $value){
            if($value == null) continue;
            $companyIds[] = $value;
        }
        $getCompanies = Company::whereIn('id', $companyIds)->get();

        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        $clientName = $client->name;
        // pdfの向きの設定
        $direction = '';
        if($request->action == "beside" ){
            $direction = 'landscape';
        }
        $pdf =  PDF::loadView('issue-calendar-pdf.project-calendar', compact('projects', 'clients', 'client', 'ShiftProjectVehicles', 'getCompanies', 'getYear', 'getMonth', 'dates', 'retailCheck', 'salaryCheck', 'expresswayCheck', 'parkingCheck'))->setPaper('a4', $direction);
        $fileName = "{$getMonth}月_{$clientName}.pdf";

        return $pdf->download($fileName); //生成されるファイル名

        return view('issue-calendar-pdf.project-calendar', compact('projects', 'clients', 'client', 'ShiftProjectVehicles', 'getCompanies', 'getYear', 'getMonth', 'dates', 'retailCheck', 'salaryCheck', 'expresswayCheck', 'parkingCheck'));
    }



    //*********************************************************************
    //******************************************************************* */



    public function charterShift()
    {
        $shiftArray = null;
        $unregisterProjectShift = collect();
        $warning = null;
        $getYear = null;
        $getMonth = null;

        return view('invoice.charterShift', compact('shiftArray', 'unregisterProjectShift', 'warning', 'getYear', 'getMonth'));
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
                'registration_location' => 2,
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
                'registration_location' => 2,
            ]);

            $findShiftPV = ShiftProjectVehicle::where('unregistered_project', $project_name)->get();
            foreach ($findShiftPV as $shift) {
                $shift->project_id = $project->id;
                $shift->unregistered_project = null;
                $shift->save();
            }
        }

        $employees = Employee::all();
        foreach($employees as $employee){
            ProjectEmployeePayment::create([
                'employee_id' => $employee->id,
                'project_id' => $project->id,
                'amount' => null
            ]);
        }

        ProjectHoliday::create([
            'project_id' => $project->id
        ]);

        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.findCharterShift')->with([
            'year' => $getYear,
            'month' => $getMonth
        ]);
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
                $getShift->retail_price = isset($retail_price[$id]) ? str_replace([',', '，'], '', $retail_price[$id]) : $getShift->retail_price;
                $getShift->expressway_fee = isset($expressway_fee[$id]) ? str_replace([',', '，'], '', $expressway_fee[$id]) : $getShift->expressway_fee;
                $getShift->parking_fee = isset($parking_fee[$id]) ? str_replace([',', '，'], '', $parking_fee[$id]) : $getShift->parking_fee;
                $getShift->driver_price = isset($driver_price[$id]) ? str_replace([',', '，'], '', $driver_price[$id]) : $getShift->driver_price;
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

        return redirect()->route('invoice.findCharterShift')->with([
            'year' => $getYear,
            'month' => $getMonth
        ]);
    }

    public function charterProjectUpdate(Request $request)
    {
        $id = $request->shiftPvId;
        $shiftPv = ShiftProjectVehicle::find($id);

        if($shiftPv){
            if ($request->input('action') == 'update') {
                // 更新処理
                if($request->projectRadio == 0){
                    // 既存案件
                    $shiftPv->project_id = $request->projectId;
                    $shiftPv->unregistered_project = null;
                    $shiftPv->save();
                }else{
                    // 未登録案件
                    $shiftPv->unregistered_project = $request->unProject;
                    $shiftPv->save();
                }
            } elseif ($request->input('action') == 'delete') {
                // 削除処理
                $shiftPv->delete();
            }
        }

        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.findCharterShift')->with([
            'year' => $getYear,
            'month' => $getMonth
        ]);
    }

    public function charterProjectChangeUnregister(Request $request)
    {
        $projectId = $request->projectId;

        // 案件をもとにシフトを取得
        $shiftPvs = ShiftProjectVehicle::where('project_id', $projectId)->get();
        // 案件を取得
        $project = Project::find($projectId);

        // 案件の登録場所がチャーター画面なのかチェック
        if($project->registration_location == 2){
            // シフトデータを更新
            if($shiftPvs){
                foreach($shiftPvs as $shiftPv){
                    $shiftPv->project_id = null;
                    $shiftPv->unregistered_project = $project->name;
                    $shiftPv->initial_project_name = null;
                    $shiftPv->save();
                }
            }
            // 休日の削除
            $ProjectHoliday = ProjectHoliday::where('project_id', $projectId)->first();
            $ProjectHoliday->delete();
            // 従業員別給与の削除
            $ProjectEmployeePayments = ProjectEmployeePayment::where('project_id', $projectId)->get();
            foreach($ProjectEmployeePayments as $ProjectEmployeePayment){
                $ProjectEmployeePayment->delete();
            }
            // 案件の削除
            $project->forceDelete();
        }

        // リダイレクト
        $getYear = $request->year;
        $getMonth = $request->month;
        return redirect()->route('invoice.findCharterShift')->with([
            'year' => $getyear,
            'month' => $getMonth
        ]);
    }

    public function charterDriverUpdate(Request $request)
    {
        $id = $request->shiftPvId;
        $shiftPv = ShiftProjectVehicle::find($id);

        $date = $shiftPv->shift->date;
        $employeeId = $request->employeeId;
        $shift = Shift::updateOrCreate(
            ['date' => $date, 'employee_id' => $employeeId],
            ['date' => $date, 'employee_id' => $employeeId]
        );

        ShiftProjectVehicle::create([
            'shift_id' => $shift->id,
            'project_id' => $shiftPv->project_id,
            'unregistered_project' => $shiftPv->unregistered_project,
            'vehicle_id' => $shiftPv->vehicle_id,
            'unregistered_vehicle'=> $shiftPv->unregistered_vehicle,
            'retail_price' => $shiftPv->retail_price,
            'driver_price' => $shiftPv->driver_price,
            'total_allowance' => $shiftPv->total_allowance,
            'parking_fee' => $shiftPv->parking_fee,
            'expressway_fee' => $shiftPv->expressway_fee,
            'overtime_fee' => $shiftPv->overtime_fee,
            'vehicle_rental_type' => $shiftPv->vehicle_rental_type,
            'rental_vehicle_id' => $shiftPv->rental_vehicle_id,
            'time_of_day' => $shiftPv->time_of_day
        ]);

        $shiftPv->delete();

        $getYear = $request->year;
        $getMonth = $request->month;


        return redirect()->route('invoice.findCharterShift')->with([
            'year' => $getYear,
            'month' => $getMonth
        ]);
    }

    public function searchCharterShift(Request $request)
    {
        $getYear = $request->year;
        $getMonth = $request->month;

        return redirect()->route('invoice.findCharterShift')->with([
            'year' => $getYear,
            'month' => $getMonth
        ]);
    }

    public function findCharterDate(Request $request)
    {
        $getYear = $request->year ?? session('year');
        $getMonth = $request->month ?? session('month');
        $narrowClientId = $request->input('narrowClientId', []);

        // チャーター案件が含まれるシフト
        $basicShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
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

        // シフトに含まれるクライアントを格納
        $includedClientId = []; // シフトに含まれているクライアントを格納変数
        foreach($basicShiftProjectVehicles as $spv){
            if(!in_array($spv->project->client->id, $includedClientId)){
                $includedClientId[] = $spv->project->client->id;
            }
        }
        // idをもとにクライアントを取得
        $includedClients = Client::whereIn('id', $includedClientId)->get();

        // 絞り込みのデータをもとにデータをフィルタリング
        $ShiftProjectVehicles = $basicShiftProjectVehicles->filter(function ($shiftPv) use ($narrowClientId){
            if(!empty($narrowClientId)){
                if(in_array($shiftPv->project->client->id, $narrowClientId)){ //$narrowClientIdに含まれるidがあるか
                    return $shiftPv;
                }
            }else{
                return $shiftPv; //$narrowClientIdが空なら全てを返す
            }
        });

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
        $clients = Client::where('id', '!=', 1)->get();

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
            $firstCheckName = $data['initial_project_name'];

            // 引取という文字列が入っているか
            if (str_contains($firstCheckName, '引取') && !str_contains($firstCheckName, '納品')) {
                // プロジェクトのclient_idを取得
                $tmpClientId = $data['project']['id'];
                // 引取に紐づく納品を探す
                foreach ($shiftArray as $innerIndex => $innerData) {
                    if (in_array($innerIndex, $skipIndex)) {
                        continue;
                    }
                    if ($index > $innerIndex) {
                        continue;
                    }
                    $secondCheckName = $innerData['initial_project_name'];

                    if ($innerData['project']['id'] == $tmpClientId) {
                        if (!str_contains($secondCheckName, '引取') && str_contains($secondCheckName, '納品')) {
                            //【】を除去
                            $cleanStr01 = preg_replace('/【.*?】/u', "", $firstCheckName);
                            $cleanStr02 = preg_replace('/【.*?】/u', "", $secondCheckName);
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

        $warning = null;
        if (empty($shiftArray) && $unregisterProjectShift->isEmpty()) {
            $warning = "選択されたシフトは登録されていません";
        }

        $projectsByCharter = project::where('is_charter', 1)->get();
        $employees = Employee::all();

        return view('invoice.charterShift', compact('ShiftProjectVehicles', 'shiftArray', 'unregisterProjectShift', 'clients', 'getYear', 'getMonth', 'warning', 'dates', 'projectsByCharter', 'employees', 'includedClients', 'narrowClientId'));
    }

    public function charterCalendarPDF(Request $request)
    {
        $getYear = $request->year;
        $getMonth = $request->month;
        $narrowClientId = $request->input('narrowClientId', []);

        // チャーター案件が含まれるシフト
        $basicShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
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

        // シフトに含まれるクライアントを格納
        $includedClientId = []; // シフトに含まれているクライアントを格納変数
        foreach($basicShiftProjectVehicles as $spv){
            if(!in_array($spv->project->client->id, $includedClientId)){
                $includedClientId[] = $spv->project->client->id;
            }
        }
        // idをもとにクライアントを取得
        $includedClients = Client::whereIn('id', $includedClientId)->get();

        // 絞り込みのデータをもとにデータをフィルタリング
        $ShiftProjectVehicles = $basicShiftProjectVehicles->filter(function ($shiftPv) use ($narrowClientId){
            if(!empty($narrowClientId)){
                if(in_array($shiftPv->project->client->id, $narrowClientId)){ //$narrowClientIdに含まれるidがあるか
                    return $shiftPv;
                }
            }else{
                return $shiftPv; //$narrowClientIdが空なら全てを返す
            }
        });

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
        $clients = Client::where('id', '!=', 1)->get();

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
            $firstCheckName = $data['initial_project_name'];

            // 引取という文字列が入っているか
            if (str_contains($firstCheckName, '引取') && !str_contains($firstCheckName, '納品')) {
                // プロジェクトのclient_idを取得
                $tmpClientId = $data['project']['id'];
                // 引取に紐づく納品を探す
                foreach ($shiftArray as $innerIndex => $innerData) {
                    if (in_array($innerIndex, $skipIndex)) {
                        continue;
                    }
                    if ($index > $innerIndex) {
                        continue;
                    }
                    $secondCheckName = $innerData['initial_project_name'];

                    if ($innerData['project']['id'] == $tmpClientId) {
                        if (!str_contains($secondCheckName, '引取') && str_contains($secondCheckName, '納品')) {
                            //【】を除去
                            $cleanStr01 = preg_replace('/【.*?】/u', "", $firstCheckName);
                            $cleanStr02 = preg_replace('/【.*?】/u', "", $secondCheckName);
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

    public function charterCalendarCsv(Request $request)
    {
        // $getYear = $request->year ?? session('year');
        // $getMonth = $request->month ?? session('month');
        // $narrowClientId = $request->input('narrowClientId', []);

        $getYear = $request->year;
        $getMonth = $request->month;
        $narrowClientId = $request->input('narrowClientId', []);

        // チャーター案件が含まれるシフト
        $basicShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client')
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

        // シフトに含まれるクライアントを格納
        $includedClientId = []; // シフトに含まれているクライアントを格納変数
        foreach($basicShiftProjectVehicles as $spv){
            if(!in_array($spv->project->client->id, $includedClientId)){
                $includedClientId[] = $spv->project->client->id;
            }
        }
        // idをもとにクライアントを取得
        $includedClients = Client::whereIn('id', $includedClientId)->get();

        // 絞り込みのデータをもとにデータをフィルタリング
        $ShiftProjectVehicles = $basicShiftProjectVehicles->filter(function ($shiftPv) use ($narrowClientId){
            if(!empty($narrowClientId)){
                if(in_array($shiftPv->project->client->id, $narrowClientId)){ //$narrowClientIdに含まれるidがあるか
                    return $shiftPv;
                }
            }else{
                return $shiftPv; //$narrowClientIdが空なら全てを返す
            }
        });

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
        $clients = Client::where('id', '!=', 1)->get();

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
            $firstCheckName = $data['initial_project_name'];

            // 引取という文字列が入っているか
            if (str_contains($firstCheckName, '引取') && !str_contains($firstCheckName, '納品')) {
                // プロジェクトのclient_idを取得
                $tmpClientId = $data['project']['id'];
                // 引取に紐づく納品を探す
                foreach ($shiftArray as $innerIndex => $innerData) {
                    if (in_array($innerIndex, $skipIndex)) {
                        continue;
                    }
                    if ($index > $innerIndex) {
                        continue;
                    }
                    $secondCheckName = $innerData['initial_project_name'];

                    if ($innerData['project']['id'] == $tmpClientId) {
                        if (!str_contains($secondCheckName, '引取') && str_contains($secondCheckName, '納品')) {
                            //【】を除去
                            $cleanStr01 = preg_replace('/【.*?】/u', "", $firstCheckName);
                            $cleanStr02 = preg_replace('/【.*?】/u', "", $secondCheckName);
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

        // 項目を設定
        $csvHeader = [
            '日付','案件名','配送料金','高速料金','駐車料金','ドライバー','ドライバー価格','クライアント名'
        ];

        $temps = []; //一時的に配列に格納
        array_push($temps, $csvHeader); //ヘッダーを設定

        foreach($shiftArray as $data){
            // 案件名
            if($data['initial_project_name'] != null){
                $projectName = $data['initial_project_name'];
            }else{
                $projectName = $data['project']['name'];
            }
            // 従業員名
            if(isset($data['shift']['employee']['name'])){
                $employeeName = $data['shift']['employee']['name'];
            }else{
                $employeeName = $data['shift']['unregistered_employee'];
            }
            // 格納するデータを整形
            $temp = [
                $data['shift']['date'],
                $projectName,
                $data['retail_price'],
                $data['expressway_fee'],
                $data['parking_fee'],
                $employeeName,
                $data['driver_price'],
                $data['project']['client']['name'],
            ];
            // １行づつ追加
            array_push($temps, $temp);
        }

        //  ファイルを作成
        $stream = fopen('php://temp', 'r+b');
        // １行づつ作成したファイルに書き込み
        foreach ($temps as $temp) {
            fputcsv($stream, $temp);
        }
        // ファイルポインタを先頭に戻す
        rewind($stream);
        // 改行コードを置き換え・文字列に変換・エンコード
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        // 文字列をエンコードする
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

        $fileName = 'チャーターリスト.csv';
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$fileName,
        );
        return Response::make($csv, 200, $headers);
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
