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
use App\Models\ProjectAllowance;
use App\Models\Shift;
use App\Models\ShiftProjectVehicle;
use App\Models\Vehicle;
use App\Models\InfoManagement;
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
                if($shiftPv->project->is_charter == 0){
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
                }else{ //チャーターの場合
                    $shiftPv->delete();
                    $valuesByPvShift = [
                        'shift_id' => $shift->id,
                        'project_id' => $projectId,
                        'unregistered_project' => null,
                        'vehicle_id' => $vehicleId,
                        'unregistered_vehicle' => null,
                        'retail_price' => $retailPrice,
                        'driver_price' => $salaryPrice,
                        'vehicle_rental_type' => $findEmployee->vehicle_rental_type,
                        'rental_vehicle_id' => $findEmployee->vehicle_id,
                    ];
                    ShiftProjectVehicle::create($valuesByPvShift);
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

        $sagawa = Client::where('name', '佐川急便株式会社')->first();
        $sagawaId = $sagawa ? $sagawa->id : 1;

        $projects = Project::where('client_id', '!=', '1')
                            ->where('client_id', '!=', $sagawaId)
                            ->where('is_suspended', '!=', '1')
                            ->get();
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
        $shiftProjectVehicles = $shiftProjectVehiclesByEmployee->filter(function ($spv) use ($projectsId, $clientsId, $sagawaId) {
            if(!empty($clientsId) || !empty($projectsId)){
                if($spv->project){
                    // client_idは排除
                    if($spv->project->client_id != 1 && $spv->project->client_id != $sagawaId){
                        return in_array($spv->project->id, $projectsId) || in_array($spv->project->client_id, $clientsId);
                    }
                }
            }else{
                if($spv->project){
                    // client_idは排除
                    if($spv->project->client_id != 1 && $spv->project->client_id != $sagawaId){
                        return $spv;
                    }
                }else{
                    return $spv;
                }
            }
        });


        // 全日にちを取得
        $dates = $this->createMontDate($getYear, $getMonth);
        // 祝日を取得
        $holidays = $this->getHoliday($getYear);
        // 二代目以降の情報を取得
        [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount] = $this->rentalPlan($shiftProjectVehicles, $dates);
        // その月の案件情報
        $projectInfoArray = $this->projectInfoExtract($shiftProjectVehicles);
        // 集計表情報を取得
        [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime, $allowanceArray] = $this->totallingInfoExtract($shiftProjectVehicles);
        // 情報管理を取得
        $InfoManagement = InfoManagement::first();

        $warning = null;
        if ($shifts == null || $shifts->isEmpty()) {
            $warning = "選択したシフトは登録されいません";
        }

        // 一時的にメモリ制限を増やす
        ini_set('memory_limit', '256M');

        return view('invoice.driverShift',
            compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'shiftProjectVehicles', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays', 'warning', 'secondMachineArray', 'thirdMachineArray', 'secondMachineCount', 'thirdMachineCount', 'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking', 'totalExpressWay', 'totalOverTime', 'findProjects', 'findClients',
                    'selectedNarrowCheck', 'needRowCount', 'clientsId', 'projectsId', 'employeeId', 'InfoManagement', 'allowanceArray'));
    }

    public function overtimeUpdate(Request $request)
    {
        $shiftPv = ShiftProjectVehicle::find($request->id);
        if($shiftPv != null){
            $shiftPv->overtime_type = $request->overtime_type;
            $shiftPv->overtime_fee = str_replace([',', '，'], '', $request->over_time_value);
            $shiftPv->save();
        }

        return redirect()->route('invoice.searchShift')->with([
            'employeeId' => $request->employeeId,
            'year' => $request->year,
            'month' => $request->month
        ]);
    }

    public function allowanceCreate(Request $request)
    {
        // カンマを排除する処理
        $request->merge([
            'retail_amount' => str_replace(',', '', $request->input('retail_amount')),
            'driver_amount' => str_replace(',', '', $request->input('driver_amount')),
        ]);

        // バリデーション
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'is_required' => 'boolean',
            'name' => 'required|string|max:255',
            'retail_amount' => 'required|numeric|min:0',
            'driver_amount' => 'required|numeric|min:0',
        ]);
        // 手当の作成
        $allowance = projectAllowance::create($request->except('id'));

        // シフトを取得
        $shiftPvId = $request->id;
        $shiftMiddle = ShiftProjectVehicle::find($shiftPvId);
        // 取得したシフトに作成したい手当を登録
        $shiftMiddle->shiftAllowance()->attach($allowance->id);

        // 手当の合計を再計算
        $totalAllowanceAmount = $shiftMiddle->shiftAllowance()->sum('driver_amount');
        $shiftMiddle->total_allowance = $totalAllowanceAmount;

        $shiftMiddle->save();

        // JSON形式で登録結果を返す
        return response()->json($totalAllowanceAmount, 201);

    }

    public function allowanceUpdate(Request $request)
    {
        $shiftPvId = $request->id;
        $shiftMiddle = ShiftProjectVehicle::find($shiftPvId);

        $allowanceId = $request->input('allowanceId');
        if (!$shiftMiddle->shiftAllowance()->where('project_allowance_id', $allowanceId)->exists()) {
            // 存在しない場合にのみ追加
            $shiftMiddle->shiftAllowance()->attach($allowanceId);

            if(!empty($allowanceId)){
                $totalAllowanceAmount = $shiftMiddle->shiftAllowance()->sum('driver_amount');

                $shiftMiddle->total_allowance = $totalAllowanceAmount;

                $shiftMiddle->save();
            }
        }
        // JSON形式で登録結果を返す
        return response()->json('ok', 201);
    }

    public function allowanceDelete($allowanceId, $shiftPvId)
    {
        $shiftMiddle = ShiftProjectVehicle::find($shiftPvId);

        $shiftMiddle->shiftAllowance()->detach($allowanceId);

        $totalAllowanceAmount = $shiftMiddle->shiftAllowance()->sum('driver_amount');

        $shiftMiddle->total_allowance = $totalAllowanceAmount;

        $shiftMiddle->save();

        return response()->json($totalAllowanceAmount, 201);
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
        // $allowanceName = $request->input('allowance.name'); //手当
        // $allowanceAmount = str_replace([',', '，'], '', $request->input('allowance.amount')); //手当
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

        $allowanceName = $request->input('allowanceName', []);
        $allowanceAmount = $request->input('allowanceAmount', []);

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
        $dates = $this->createMontDate($getYear, $getMonth);
        // 祝日を取得
        $holidays = $this->getHoliday($getYear);
        // 案件情報を取得
        $projectInfoArray = $this->projectInfoExtract($shiftProjectVehicles);
        // 集計表情報を取得
        [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime, $allowanceArray] = $this->totallingInfoExtract($shiftProjectVehicles);

        // 一時的にメモリ制限を増やす
        ini_set('memory_limit', '256M');

        $pdf =  PDF::loadView('issue-calendar-pdf.driver-calendar',
                    compact('employees', 'findEmployee', 'projects', 'vehicles', 'shifts', 'allowanceProject', 'getYear', 'getMonth', 'dates','holidays',
                             'projectInfoArray', 'projectInfoArray','totalSalary', 'totalAllowance', 'totalParking',
                            'totalExpressWay', 'totalOverTime', 'textarea', 'amountCheck', 'allowanceCheck', 'expresswayCheck', 'parkingCheck', 'vehicleCheck', 'overtimeCheck',
                            'setRowCount', 'shiftProjectVehicles',
                            // ドライバー価格
                            'totalSalaryName','totalSalaryAmount','allowanceName','allowanceAmount','taxName','taxAmount','expressWayAmount','parkingAmount','overtimeAmount','others',
                            'parkingName', 'expressWayName', 'overtimeName','allowanceArray',
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

    // 0 : 自車, 1 : 月リース, 2 : なんでも月リース, 3 : 日割り
    function rentalPlan($shiftProjectVehicles, $dates)
    {
        $secondMachineArray = [];
        $thirdMachineArray = [];
        $secondMachineCount = 0;
        $thirdMachineCount = 0;

        // シフトがなければ処理に進む前に返す
        if($shiftProjectVehicles->isEmpty()){
            return [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount];
        }

        $rental_type = $shiftProjectVehicles->first()->vehicle_rental_type;

        if($rental_type == 0){ //自車
            $this->myCarPlan($shiftProjectVehicles, $dates, $secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount);
        }
        if($rental_type == 1){ //月リース
            $this->monthLeasePlan($shiftProjectVehicles, $dates, $secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount);
        }
        if($rental_type == 2){ //なんでも
            $this->freeLeasePlan($shiftProjectVehicles, $dates, $secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount);
        }
        if($rental_type == 3){ //日割り
            $this->dailyRatePlan($shiftProjectVehicles, $dates, $secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount);
        }

        return [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount];

    }

    // 自車
    function myCarPlan($shiftProjectVehicles, $dates, &$secondMachineArray, &$thirdMachineArray, &$secondMachineCount, &$thirdMachineCount)
    {
        foreach($dates as $date){
            $secondMachineCheck = true; //1日ごとに二代目の判定
            $secondMachineArrayForDay = []; //1日ごとの二代目を格納
            $thirdMachineArrayForDay = []; //1日ごとの3代目を格納
            foreach($shiftProjectVehicles as $spv){
                if($spv->shift->date == $date->format('Y-m-d')){
                    // その日に使用されている車両を格納
                    $vehicleNumber = $spv->vehicle ? $spv->vehicle->number : $spv->unregistered_vehicle;
                    if($vehicleNumber == '自車' || $vehicleNumber == null) continue; //自車の場合スキップ
                    if($secondMachineCheck){
                        if(!in_array($vehicleNumber, $secondMachineArray)){
                            $secondMachineArray[] = $vehicleNumber;
                        }
                        $secondMachineCheck = false;
                        $secondMachineArrayForDay[] = $vehicleNumber;
                        $secondMachineCount++; //2代目の件数を増やす
                    }else{
                        if(!in_array($vehicleNumber, $secondMachineArrayForDay)){
                            if(!in_array($vehicleNumber, $thirdMachineArrayForDay)){
                                if (!in_array($vehicleNumber, $thirdMachineArray)) {
                                    $thirdMachineArray[] = $vehicleNumber;
                                }
                                $thirdMachineArrayForDay[] = $vehicleNumber;
                                $thirdMachineCount++;
                            }
                        }
                    }
                }
            }
        }
    }

    // 月リース
    function monthLeasePlan($shiftProjectVehicles, $dates, &$secondMachineArray, &$thirdMachineArray, &$secondMachineCount, &$thirdMachineCount)
    {
        // 契約している車両のナンバーを格納
        $rental_vehicle_number = null;

        foreach($shiftProjectVehicles as $spv){
            if($spv->rentalVehicle){
                $rental_vehicle_number = $spv->rentalVehicle->number;
                break;
            }
        }

        foreach($dates as $date){
            $secondMachineCheck = true; //1日ごとに二代目の判定
            $secondMachineArrayForDay = []; //1日ごとの2代目を格納
            $thirdMachineArrayForDay = []; //1日ごとの3代目を格納
            foreach($shiftProjectVehicles as $spv){
                if($spv->shift->date == $date->format('Y-m-d')){
                    // その日に使用されている車両を格納
                    $vehicleNumber = $spv->vehicle ? $spv->vehicle->number : $spv->unregistered_vehicle;
                    if($vehicleNumber == '自車' || $vehicleNumber == null) continue; //自車の場合スキップ
                    if($vehicleNumber != $rental_vehicle_number){ //契約車両と使用車両が一致なのか判定
                        if($secondMachineCheck){
                            if(!in_array($vehicleNumber, $secondMachineArray)){
                                $secondMachineArray[] = $vehicleNumber;
                            }
                            $secondMachineCheck = false;
                            $secondMachineArrayForDay[] = $vehicleNumber;
                            $secondMachineCount++; //2代目の件数を増やす
                        }else{
                            if(!in_array($vehicleNumber, $secondMachineArrayForDay)){
                                if(!in_array($vehicleNumber, $thirdMachineArrayForDay)){
                                    if (!in_array($vehicleNumber, $thirdMachineArray)) {
                                        $thirdMachineArray[] = $vehicleNumber;
                                    }
                                    $thirdMachineArrayForDay[] = $vehicleNumber;
                                    $thirdMachineCount++;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // なんでも月リース
    function freeLeasePlan($shiftProjectVehicles, $dates, &$secondMachineArray, &$thirdMachineArray, &$secondMachineCount, &$thirdMachineCount)
    {
        foreach($dates as $date){
            $secondMachineCheck = true; //1日ごとに二代目の判定
            $secondMachineArrayForDay = []; //1日ごとの二代目を格納
            $thirdMachineArrayForDay = []; //1日ごとの3代目を格納
            foreach($shiftProjectVehicles as $spv){
                if($spv->shift->date == $date->format('Y-m-d')){
                    // その日に使用されている車両を格納
                    $vehicleNumber = $spv->vehicle ? $spv->vehicle->number : $spv->unregistered_vehicle;
                    if($vehicleNumber == '自車' || $vehicleNumber == null) continue; //自車の場合スキップ
                    if($secondMachineCheck){
                        if(!in_array($vehicleNumber, $secondMachineArray)){
                            $secondMachineArray[] = $vehicleNumber;
                        }
                        $secondMachineCheck = false;
                        $secondMachineArrayForDay[] = $vehicleNumber;
                        $secondMachineCount++; //2代目の件数を増やす
                    }else{
                        if(!in_array($vehicleNumber, $secondMachineArrayForDay)){
                            if(!in_array($vehicleNumber, $thirdMachineArrayForDay)){
                                if (!in_array($vehicleNumber, $thirdMachineArray)) {
                                    $thirdMachineArray[] = $vehicleNumber;
                                }
                                $thirdMachineArrayForDay[] = $vehicleNumber;
                                $thirdMachineCount++;
                            }
                        }
                    }
                }
            }
        }
    }

    // 日割り
    function dailyRatePlan($shiftProjectVehicles, $dates, &$secondMachineArray, &$thirdMachineArray, &$secondMachineCount, &$thirdMachineCount)
    {
        foreach($dates as $date){
            $secondMachineCheck = true; //1日ごとに二代目の判定
            $secondMachineArrayForDay = []; //1日ごとの二代目を格納
            $thirdMachineArrayForDay = []; //1日ごとの3代目を格納
            foreach($shiftProjectVehicles as $spv){
                if($spv->shift->date == $date->format('Y-m-d')){
                    // その日に使用されている車両を格納
                    $vehicleNumber = $spv->vehicle ? $spv->vehicle->number : $spv->unregistered_vehicle;
                    if($vehicleNumber == '自車' || $vehicleNumber == null) continue; //自車の場合スキップ
                    if($secondMachineCheck){
                        if(!in_array($vehicleNumber, $secondMachineArray)){
                            $secondMachineArray[] = $vehicleNumber;
                        }
                        $secondMachineCheck = false;
                        $secondMachineArrayForDay[] = $vehicleNumber;
                        $secondMachineCount++; //2代目の件数を増やす
                    }else{
                        if(!in_array($vehicleNumber, $secondMachineArrayForDay)){
                            if(!in_array($vehicleNumber, $thirdMachineArrayForDay)){
                                if (!in_array($vehicleNumber, $thirdMachineArray)) {
                                    $thirdMachineArray[] = $vehicleNumber;
                                }
                                $thirdMachineArrayForDay[] = $vehicleNumber;
                                $thirdMachineCount++;
                            }
                        }
                    }
                }
            }
        }
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
        $allowanceArray = [];
        $allowanceCountArray = [];

        foreach($shiftProjectVehicles as $spv){
            $totalSalary += $spv->driver_price;
            $totalAllowance += $spv->total_allowance;
            $totalParking += $spv->parking_fee;
            $totalExpressWay += $spv->expressway_fee;
            // $totalOverTime += $spv->overtime_fee;

            if($spv->project){
                // 手当計算
                if($spv->shiftAllowance){
                    $allowances = $spv->shiftAllowance()->get();
                    foreach($allowances as $allowance){
                        if(!isset($allowanceArray[$allowance->name])){
                            $allowanceArray[$allowance->name]['amount'] = $allowance->driver_amount;
                            $allowanceArray[$allowance->name]['unit'] = $allowance->driver_amount;
                            $allowanceArray[$allowance->name]['count'] = 1;
                        }else{
                            $allowanceArray[$allowance->name]['amount'] += $allowance->driver_amount;
                            $allowanceArray[$allowance->name]['count']++;
                        }
                    }
                }
                // 残業代
                if($spv->overtime_type != null){
                    if($spv->overtime_type == 'amount'){
                        $totalOverTime += $spv->overtime_fee;
                    }else{
                        $hourly_wage = $spv->project->overtime_hourly_wage;
                        $hourly = $spv->overtime_fee;
                        $calc = $hourly * $hourly_wage;
                        $totalOverTime += round($calc);
                    }
                }

            }
        }

        return [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime, $allowanceArray];
    }


    //*********************************************************************
    //******************************************************************* */



    public function projectShift()
    {
        $sagawa = Client::where('name', '佐川急便株式会社')->first();
        $sagawaId = $sagawa ? $sagawa->id : 1;
        $clients = Client::where('id', '!=', 1)
                        ->where('id', '!=' , $sagawaId)
                        ->get();

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

        // 検索された最初は絞り込みがないため、初期値はfalse
        $hasNarrow = $request->input('hasNarrow', false);

        // 列の表示・非表示
        $selectedDisplayCheck = $request->input('displayCheck', []);
        $selectedDisplayCoCheck = $request->input('displayCoCheck', []);
        // 案件の絞り込み
        $narrowProjectIds = $request->input('narrowProjects', []);
        // 所属で分けるか
        $separateByCompany = $request->input('separateByCompany', 'true');

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
                    $companyIds[] = $company->id;
                }
                if(!$hasNarrow){
                    if (!in_array($company->id, $selectedDisplayCoCheck)) {
                        $selectedDisplayCoCheck[] = $company->id;
                    }
                }
            }
        }
        $getCompanies = Company::whereIn('id', $companyIds)->get();

        if(!$hasNarrow){
            $selectedDisplayCheck = ['salaryClm','retailClm','expressClm','parkingClm'];
        }

        // 全日にちを取得
        $dates = $this->createMontDate($getYear, $getMonth);

        $warning = null;
        if ($ShiftProjectVehiclesByClient == null || $ShiftProjectVehiclesByClient->isEmpty()) {
            $warning = "選択されたシフトは登録されていません";
        }

        // 一時的にメモリ制限を増やす
        ini_set('memory_limit', '256M');

        return view('invoice.projectShift',
            compact('projects', 'clients', 'clientId', 'getClient', 'ShiftProjectVehicles', 'getCompanies',
                    'getYear', 'getMonth', 'dates', 'warning',
                    'selectedDisplayCheck', 'selectedDisplayCoCheck', 'narrowProjects', 'narrowProjectIds', 'hasNarrow', 'separateByCompany'));
    }

    public function projectCalendarPDF(Request $request)
    {
        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        $selectedDisplayCheck = $request->selectedDisplayCheck;
        $selectedCompanies = $request->input('narrowCompany', []);
        $selectedProjectIds = $request->input('narrowProjectIds', []);

        // 所属分割
        $separateByCompany = $request->separateByCompany;

        $retailCheck = 0;
        if(in_array('retailClm', $selectedDisplayCheck)){
            $retailCheck = 1;
        }

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
        $dates = $this->createMontDate($getYear, $getMonth);

        $clientName = $client->name;

        // pdfの向きの設定
        $direction = '';
        if($request->action == "beside" ){
            $direction = 'landscape';
        }
        $pdf =  PDF::loadView('issue-calendar-pdf.project-calendar', compact('projects', 'clients', 'client', 'ShiftProjectVehicles', 'getYear', 'getMonth', 'dates', 'retailCheck', 'selectedDisplayCheck', 'getCompanies', 'selectedCompanies', 'separateByCompany'))->setPaper('a4', $direction);
        $fileName = "{$getMonth}月_{$clientName}.pdf";

        // 一時的にメモリ制限を増やす
        ini_set('memory_limit', '256M');

        return $pdf->download($fileName); //生成されるファイル名

        return view('issue-calendar-pdf.project-calendar', compact('projects', 'clients', 'client', 'ShiftProjectVehicles', 'getYear', 'getMonth', 'dates', 'retailCheck', 'selectedDisplayCheck', 'getCompanies', 'selectedCompanies', 'separateByCompany'));
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
            'year' => $getYear,
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

    public function sortShift($dates, $shiftArray, $getYear, $getMonth)
    {
        // 新しい配列を作成
        $arrangeShiftArray = [];
        $tmpItem = null; // 外側のスコープで変数を宣言
        $skipIndex = [];

        foreach ($shiftArray as $index => $data) {
            if (in_array($data['id'], $skipIndex)) {
                continue;
            }

            // 紐付き先があるか
            if(isset($data['related_shift_project_vehicle'])){
                $relatedData = Carbon::createFromFormat('Y-m-d', $data['related_shift_project_vehicle']['shift']['date']);
                $getYearMonth = sprintf('%04d%02d', $getYear, $getMonth);
                // 紐付け先のシフトが現在の月以下か
                if($relatedData->format('Ym') <= $getYearMonth){
                    // 紐付け先のシフトが現在の月より前か
                    if($relatedData->format('Ym') < $getYearMonth){
                        // 新しい配列に要素を追加
                        $arrangeShiftArray[] = $data['related_shift_project_vehicle'];
                        $arrangeShiftArray[] = $data;
                    }else{
                        // 新しい配列に要素を追加
                        $arrangeShiftArray[] = $data;
                        $arrangeShiftArray[] = $data['related_shift_project_vehicle'];
                    }
                    $skipIndex[] = $data['id'];
                    $skipIndex[] = $data['related_shift_project_vehicle']['id'];
                }else{
                    continue;
                }
            }else{
                // 新しい配列に要素を追加
                $arrangeShiftArray[] = $data;
            }
        }

        return $arrangeShiftArray;
    }

    public function findCharterDate(Request $request)
    {
        $getYear = $request->year ?? session('year');
        $getMonth = $request->month ?? session('month');
        $narrowClientId = $request->input('narrowClientId', []);

        // チャーター案件が含まれるシフト
        $basicShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client', 'relatedShiftProjectVehicle', 'relatedShiftProjectVehicle.shift', 'relatedShiftProjectVehicle.project','relatedShiftProjectVehicle.project.client', 'relatedShiftProjectVehicle.shift.employee')
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
        $dates = $this->createYearDate($getYear);

        // チャーター案件が含まれるシフトを配列に変換
        $shiftArray = $ShiftProjectVehicles->toArray();

        // チャーター案件があるクライアントを取得
        $clients = Client::where('id', '!=', 1)->get();

        // 元の配列に代入
        $shiftArray = array_values($this->sortShift($dates, $shiftArray, $getYear, $getMonth));

        $warning = null;
        if (empty($shiftArray) && $unregisterProjectShift->isEmpty()) {
            $warning = "選択されたシフトは登録されていません";
        }

        $projectsByCharter = project::where('is_charter', 1)
                                    ->where('is_suspended', '!=', '1')
                                    ->get();
        $employees = Employee::all();

        return view('invoice.charterShift', compact('ShiftProjectVehicles', 'shiftArray', 'unregisterProjectShift', 'clients', 'getYear', 'getMonth', 'warning', 'dates', 'projectsByCharter', 'employees', 'includedClients', 'narrowClientId'));
    }

    public function charterCalendarPDF(Request $request)
    {
        $getYear = $request->year;
        $getMonth = $request->month;
        $narrowClientId = $request->input('narrowClientId', []);

        // チャーター案件が含まれるシフト
        $basicShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client', 'relatedShiftProjectVehicle', 'relatedShiftProjectVehicle.shift', 'relatedShiftProjectVehicle.project','relatedShiftProjectVehicle.project.client', 'relatedShiftProjectVehicle.shift.employee')
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
        $dates = $this->createYearDate($getYear);

        // チャーター案件が含まれるシフトを配列に変換
        $shiftArray = $ShiftProjectVehicles->toArray();

        // チャーター案件があるクライアントを取得
        $clients = Client::where('id', '!=', 1)->get();

        // 元の配列に代入
        $shiftArray = array_values($this->sortShift($dates, $shiftArray, $getYear, $getMonth));

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
        $basicShiftProjectVehicles = ShiftProjectVehicle::with('shift', 'shift.employee', 'project', 'project.client', 'relatedShiftProjectVehicle', 'relatedShiftProjectVehicle.shift', 'relatedShiftProjectVehicle.project','relatedShiftProjectVehicle.project.client', 'relatedShiftProjectVehicle.shift.employee')
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
        $dates = $this->createYearDate($getYear);

        // チャーター案件が含まれるシフトを配列に変換
        $shiftArray = $ShiftProjectVehicles->toArray();

        // チャーター案件があるクライアントを取得
        $clients = Client::where('id', '!=', 1)->get();

        // 元の配列に代入
        $shiftArray = array_values($this->sortShift($dates, $shiftArray, $getYear, $getMonth));

        // 項目を設定
        $csvHeader = [
            '日付','案件名','配送料金','高速料金','駐車料金','ドライバー','ドライバー価格','クライアント名'
        ];

        $temps = []; //一時的に配列に格納
        array_push($temps, $csvHeader); //ヘッダーを設定

        foreach($shiftArray as $data){
            // 案件名
            if($data['custom_project_name'] != null){
                $projectName = $data['project']['name'].$data['custom_project_name'];
            }elseif($data['initial_project_name'] != null){
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

    public function createYearDate($year)
    {
        // 年初の日付を作成
        $startDate = Carbon::createFromDate($year, 1, 1);
        // 年末の日付を作成
        $endDate = Carbon::createFromDate($year, 12, 31);

        // 日付を保持する配列
        $dates = [];

        // 期間内の日付を配列に追加
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy();
        }

        return $dates;
    }

    public function createMontDate($year, $month)
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
