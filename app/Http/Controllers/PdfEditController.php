<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Employee;
use App\Models\ShiftProjectVehicle;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\Shift;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\InvoiceController;

class PdfEditController extends Controller
{
    public function driver_edit_pdf(Request $request)
    {
        $invoiceAmountCheck = $request->invoiceAmountCheck;
        $invoiceAllowanceCheck = $request->invoiceAllowanceCheck;
        $invoiceExpresswayCheck = $request->invoiceExpresswayCheck;
        $invoiceParkingCheck = $request->invoiceParkingCheck;
        $invoiceVehicleCheck = $request->invoiceVehicleCheck;
        $invoiceOvertimeCheck = $request->invoiceOvertimeCheck;

        // 合計テーブルのデータを受け取り
        $totalSalaryName = $request->input('totalSalary.name'); //給与合計
        $totalSalaryAmount = $request->input('totalSalary.amount'); //給与合計
        $allowanceName = $request->input('allowance.name'); //手当
        $allowanceAmount = $request->input('allowance.amount'); //手当
        $taxName = $request->input('tax.name'); //消費税
        $taxAmount = $request->input('tax.amount'); //消費税
        $expressWayAmount = $request->input('expressWayAmount'); //高速代
        $parkingAmount = $request->input('parkingAmount'); //パーキング代
        $overtimeAmount = $request->input('overtimeAmount'); //残業代
        $otherNames = $request->input('otherName', []); //追加項目名
        $otherAmounts = $request->input('otherAmount', []); //追加項目金額
        // 追加項目の空でない名前と金額のペアを新しい配列に追加
        $others = [];
        foreach ($otherNames as $index => $name) {
            if (!empty($name) && !empty($otherAmounts[$index])) {
                $others[] = [
                    'name' => $name,
                    'amount' => $otherAmounts[$index]
                ];
            }
        }

        // 費用テーブルのデータを受け取り
        $administrativeOutsourcingName = $request->input('administrativeOutsourcing.name'); //業務委託手数料
        $administrativeOutsourcingAmount = $request->input('administrativeOutsourcing.amount'); //業務委託手数料
        $administrativeName = $request->input('administrative.name'); //事務手数料
        $administrativeAmount = $request->input('administrative.amount'); //事務手数料
        $transferName = $request->input('transfer.name'); //振り込み手数料
        $transferAmount = $request->input('transfer.amount'); //振り込み手数料
        // 条件に応じてリクエストから値を受け取る
        $monthLeaseName = $request->input('monthLease.name'); //月リース
        $monthLeaseAmount = $request->input('monthLease.amount'); //月リース
        $secondLeaseName = $request->input('secondLease.name'); //二代目りーす
        $secondLeaseAmount = $request->input('secondLease.amount'); //二代目りーす
        $thirdLeaseName = $request->input('thirdLease.name'); //三台目リース
        $thirdLeaseAmount = $request->input('thirdLease.amount'); //三台目リース
        $monthInsuranceName = $request->input('monthInsurance.name'); //月保険料
        $monthInsuranceAmount = $request->input('monthInsurance.amount'); //月保険料
        $secondInsuranceName = $request->input('secondInsurance.name'); //二代目以降保険料
        $secondInsuranceAmount = $request->input('secondInsurance.amount'); //二代目以降保険料
        $otherCostNames = $request->input('otherCostName', []); //費用追加項目名
        $otherCostAmonts = $request->input('otherCostAmont', []); //費用追加項目金額
        // 追加項目の空でない名前と金額のペアを新しい配列に追加
        $CostOthers = [];
        foreach ($otherCostNames as $index => $name) {
            if (!empty($name) && !empty($otherCostAmonts[$index])) {
                $CostOthers[] = [
                    'name' => $name,
                    'amount' => $otherCostAmonts[$index]
                ];
            }
        }


        $employeeId = $request->employeeId;

        $getYear = $request->year;
        $getMonth = $request->month;

        $employeeInfo = Employee::find($employeeId);
        $employeeName = $employeeInfo->name;

        $banks = BankAccount::where('employee_id', $employeeInfo->id)->get();

        $companies = Company::all();

        $today = Carbon::now();

        // // シフトを検索・取得
        // $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle', 'projectsVehicles.rentalVehicle')
        // ->where('employee_id', $employeeId)
        // ->whereYear('date', $getYear)
        // ->whereMonth('date', $getMonth)
        // ->get();

        // $rentalType = null;
        // foreach($shifts as $shift){
        //     foreach($shift->projectsVehicles as $spv){
        //         $rentalType = $spv->vehicle_rental_type;
        //     }
        // }

        // 絞り込み情報
        // $clientsId = $request->input('invoiceClientsId', []);
        // $projectsId = $request->input('invoiceProjectsId', []);
        // $query = ShiftProjectVehicle::query()
        // ->with(['shift','shift.employee', 'project', 'vehicle', 'rentalVehicle'])
        // ->whereHas('shift', function ($query) use ($employeeId, $getYear, $getMonth) {
        //     // Employee ID、年、月でフィルタリング
        //     $query->where('employee_id', $employeeId)
        //             ->whereYear('date', $getYear)
        //             ->whereMonth('date', $getMonth);
        // });


        // $shiftProjectVehiclesByEmployee = $query->get();
        // // コレクションのfilterメソッドを使用してフィルタリング
        // $shiftProjectVehicles = $shiftProjectVehiclesByEmployee->filter(function ($spv) use ($projectsId, $clientsId) {
        //     if(!empty($clientsId) || !empty($projectsId)){
        //         if($spv->project){
        //             return in_array($spv->project->id, $projectsId) || in_array($spv->project->client_id, $clientsId);
        //         }
        //     }else{
        //         return $spv;
        //     }
        // });

        // $invoiceController = new InvoiceController();
        // // 全日にちを取得
        // $dates = $invoiceController->createDate($getYear, $getMonth);
        // // 集計表情報を取得
        // [$totalSalary, $totalAllowance, $totalParking, $totalExpressWay, $totalOverTime] = $invoiceController->totallingInfoExtract($shiftProjectVehicles);
        // // 二代目以降の情報を取得
        // [$secondMachineArray, $thirdMachineArray, $secondMachineCount, $thirdMachineCount] = $invoiceController->machineInfoExtract($shiftProjectVehicles, $dates);

        // チェックボックスの判定の金額の制御
        // if($invoiceAmountCheck == 1){
        //     $totalSalary = 0;
        // }
        // if($invoiceAllowanceCheck == 1){
        //     $totalAllowance = 0;
        // }
        if($invoiceExpresswayCheck == 1){
            $totalExpressWay = 0;
        }
        if($invoiceParkingCheck == 1){
            $parkingAmount = 0;
        }
        // if($invoiceVehicleCheck == 1){
        //     $secondMachineCount = 0;
        //     $thirdMachineCount = 0;
        // }
        if($invoiceOvertimeCheck == 1){
            $overtimeAmount = 0;
        }
        // 高速代他の計算
        $etc = $expressWayAmount + $parkingAmount + $overtimeAmount;

        // 費用の計算
        $CostOtherTotal = 0;
        foreach($CostOthers as $CostOther){
            $CostOtherTotal += $CostOther['amount'];
        }
        $subTotalCost = $monthLeaseAmount + $secondLeaseAmount + $thirdLeaseAmount + $monthInsuranceAmount + $secondInsuranceAmount + $administrativeOutsourcingAmount + $administrativeAmount + $transferAmount + $CostOtherTotal;
        $totalLease = $monthLeaseAmount + $secondLeaseAmount + $thirdLeaseAmount;
        $totalInsurance = $monthInsuranceAmount + $secondInsuranceAmount;


        return view('edit-pdf.driver-edit-pdf',
                compact('getYear', 'getMonth', 'today', 'employeeInfo', 'companies', 'banks'
                        ,'allowanceName'
                        ,'totalSalaryAmount', 'allowanceAmount','taxAmount', 'etc', 'others'
                        ,'invoiceAmountCheck', 'invoiceAllowanceCheck'
                        // 費用項目
                        ,'administrativeOutsourcingName', 'administrativeOutsourcingAmount',
                        'administrativeName', 'administrativeAmount',
                        'transferName', 'transferAmount',
                        'totalLease','totalInsurance', 'subTotalCost','CostOthers',
                ));

        // return view('edit-pdf.driver-edit-pdf',
        //         compact('getYear', 'getMonth', 'today', 'employeeInfo', 'companies'
        //                 , 'banks', 'totalSalary', 'totalAllowance', 'totalExpressWay'
        //                 , 'totalParking', 'totalOverTime', 'secondMachineCount'
        //                 , 'thirdMachineCount', 'rentalType'));
    }




    public function project_edit_pdf(Request $request)
    {
        $today = Carbon::now();

        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        // 表示・非表示
        $retailCheck = $request->input('salary_check');
        $salaryCheck = $request->input('retail_check');
        $expresswayCheck = $request->input('expressway_check');
        $parkingCheck = $request->input('parking_check');
        $selectedCompanies = $request->input('companyByInvoice', []);
        $selectedProjectIds = $request->input('narrowInvoiceProjectIds', []);

        $client = Client::find($clientId);

        $projects = Project::where('client_id', $clientId)->get();

        // 検索用
        $clients = Client::all();
        $getClient = Client::find($clientId);

        // シフトを検索・取得
        $ShiftProjectVehiclesByClient = ShiftProjectVehicle::with('shift', 'shift.employee.company', 'project')
            ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
                $query->whereYear('date', $getYear)
                    ->whereMonth('date', $getMonth);
            })
            ->whereHas('project', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->join('shifts', 'shift_project_vehicle.shift_id', '=', 'shifts.id')
            ->orderBy('shifts.date', 'asc')
            ->select('shift_project_vehicle.*') // ShiftProjectVehicleのカラムのみを選択
            ->get();
        // 案件での絞り込み
        $ShiftProjectVehicles = $ShiftProjectVehiclesByClient->filter(function ($spv) use ($selectedProjectIds){
            if(!empty($selectedProjectIds)){
                if($spv->project){
                    return in_array($spv->project->id, $selectedProjectIds);
                }
            }else{
                return $spv;
            }
        });

        $total_count = $ShiftProjectVehicles->count();

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
        // 所属先の絞り込み
        $getCompanies = Company::when(!empty($selectedCompanies), function ($query) use ($selectedCompanies){
                return $query->whereIn('id', $selectedCompanies);
            })->when(empty($selectedCompanies), function ($query) use ($companyIds){
                return $query->whereIn('id', $companyIds);
            })->get();


        $total_retail = 0;
        $projectData = [];
        foreach ($ShiftProjectVehicles as $spv) {
            foreach($getCompanies as $company){
                if($spv->shift->employee){
                    if($spv->shift->employee->company->id == $company->id){
                        $projectName = $spv->project->name; // プロジェクト名
                        $date = Carbon::parse($spv->shift->date); // シフトの日付
                        $retailPrice = $spv->retail_price; // 上代単価

                        if (!isset($projectData[$projectName])) {
                            $projectData[$projectName] = [
                                'dates' => '',
                                'count' => 0,
                                'unit_price' => ceil($retailPrice),
                                'total_price' => 0
                            ];
                        }

                        // 引取が含まれるか、またはcharter_project_nameが存在しない場合の条件をチェック
                        $containsHikitori = $spv->charter_project_name && str_contains($spv->charter_project_name, '引取');
                        // 日付を文字列として追加
                        if($containsHikitori || !$spv->charter_project_name){
                            $formattedDate = $date->format('j'); // 日付のみ
                            if (empty($projectData[$projectName]['dates'])) {
                                $formattedDate = $date->format('m/d'); // 最初の日付は月/日
                            }
                            if (!str_contains($projectData[$projectName]['dates'], $formattedDate)) {
                                $projectData[$projectName]['dates'] .= (empty($projectData[$projectName]['dates']) ? '' : ',') . $formattedDate;
                            }
                        }

                        // 案件数と上代の合計を更新
                        $projectData[$projectName]['count']++;
                        $projectData[$projectName]['total_price'] += $retailPrice;

                        $total_retail += $retailPrice;
                    }
                }
            }
        }
        // dd($projectData);

        $expresswayData = [];

        if($expresswayCheck == 1){
            foreach ($ShiftProjectVehicles as $spv) {
                $projectName = $spv->project->name;
                $date = Carbon::parse($spv->shift->date);
                $expresswayFee = $spv->expressway_fee; // 高速代

                if($spv->expressway_fee){
                    foreach($getCompanies as $company){
                        if($spv->shift->employee->company->id == $company->id){
                            if (!isset($expresswayData[$projectName])) {
                                $expresswayData[$projectName] = [
                                    'dates' => '',
                                    'expressway_dates' => '', // 高速代の日付
                                    'expressway_count' => 0, // 高速代の数量
                                    'expressway_unit_price' => ceil($expresswayFee), // 高速代の単価
                                    'total_expressway_fee' => 0 // 高速代の合計
                                ];
                            }

                            // 日付を文字列として追加
                            $formattedDate = $date->format('j'); // 日付のみ
                            if (empty($expresswayData[$projectName]['dates'])) {
                                $formattedDate = $date->format('m/d'); // 最初の日付は月/日
                            }
                            if (!str_contains($expresswayData[$projectName]['dates'], $formattedDate)) {
                                $expresswayData[$projectName]['dates'] .= (empty($expresswayData[$projectName]['dates']) ? '' : ',') . $formattedDate;
                            }

                            // 高速代の日付を追加
                            if ($expresswayFee && !str_contains($expresswayData[$projectName]['expressway_dates'], $formattedDate)) {
                                $expresswayData[$projectName]['expressway_dates'] .= (empty($expresswayData[$projectName]['expressway_dates']) ? '' : ',') . $formattedDate;
                                $expresswayData[$projectName]['expressway_count']++;
                                $expresswayData[$projectName]['total_expressway_fee'] += $expresswayFee;

                                $total_retail += $expresswayFee;
                            }
                        }
                    }
                }

            }
        }

        $parkingData = [];

        if($parkingCheck == 1){
            foreach ($ShiftProjectVehicles as $spv) {
                $projectName = $spv->project->name;
                $date = Carbon::parse($spv->shift->date);
                $parkingFee = $spv->parking_fee; // パーキング料金

                if($spv->parking_fee){
                    foreach($getCompanies as $company){
                        if($spv->shift->employee->company->id == $company->id){
                            if (!isset($parkingData[$projectName])) {
                                $parkingData[$projectName] = [
                                    'dates' => '',
                                    'parking_dates' => '', // パーキング料金の日付
                                    'parking_count' => 0, // パーキング料金の数量
                                    'parking_unit_price' => ceil($parkingFee), // パーキング料金の単価
                                    'total_parking_fee' => 0 // パーキング料金の合計
                                ];
                            }

                            // 日付を文字列として追加
                            $formattedDate = $date->format('j'); // 日付のみ
                            if (empty($parkingData[$projectName]['dates'])) {
                                $formattedDate = $date->format('m/d'); // 最初の日付は月/日
                            }
                            if (!str_contains($parkingData[$projectName]['dates'], $formattedDate)) {
                                $parkingData[$projectName]['dates'] .= (empty($parkingData[$projectName]['dates']) ? '' : ',') . $formattedDate;
                            }

                            // パーキング料金の日付を追加
                            if ($parkingFee && !str_contains($parkingData[$projectName]['parking_dates'], $formattedDate)) {
                                $parkingData[$projectName]['parking_dates'] .= (empty($parkingData[$projectName]['parking_dates']) ? '' : ',') . $formattedDate;
                                $parkingData[$projectName]['parking_count']++;
                                $parkingData[$projectName]['total_parking_fee'] += $parkingFee;

                                $total_retail += $parkingFee;
                            }
                        }
                    }
                }
            }
        }

        return view('edit-pdf.project-edit-pdf', compact('total_retail', 'total_count', 'today', 'projectData', 'expresswayData', 'parkingData', 'getClient', 'getCompanies'));
    }
}
