<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Employee;
use App\Models\ShiftProjectVehicle;
use App\Models\BankAccount;

use Illuminate\Http\Request;

class PdfEditController extends Controller
{
    public function driver_edit_pdf(Request $request)
    {
        $administrative_commission_fee = $request->administrative_commission_fee;
        $total_lease = $request->total_lease;
        $total_insurance = $request->total_insurance;
        $administrative_fee = $request->administrative_fee;
        $transfer_fee = $request->transfer_fee;
        $total_salary = $request->total_salary;
        $total_allowance = $request->total_allowance;
        $total_expressway = $request->total_expressway;
        $total_parking = $request->total_parking;
        $total_overtime = $request->total_overtime;
        $employee = $request->employee;

        $year = $request->year;
        $month = $request->month;

        $employeeInfo = Employee::find($employee);
        $employeeName = $employeeInfo->name;

        $banks = BankAccount::where('employee_id', $employeeInfo->id)->get();

        $companies = Company::all();

        $today = Carbon::now();

        $amountCheck = $request->input('amountCheck', 0);
        $expresswayCheck = $request->input('expresswayCheck', 0);
        $parkingCheck = $request->input('parkingCheck', 0);
        $overtimeCheck = $request->input('overtimeCheck', 0);

        if($amountCheck == 0){
            $total_salary = 0;
        }
        if($expresswayCheck == 0){
            $total_expressway = 0;
        }
        if($parkingCheck == 0){
            $total_parking = 0;
        }
        if($overtimeCheck == 0){
            $total_overtime = 0;
        }

        $etc = $total_expressway + $total_parking + $total_overtime;

        return view('edit-pdf.driver-edit-pdf', compact( 'companies','administrative_commission_fee','total_lease','total_insurance','administrative_fee','transfer_fee','total_allowance','etc','total_salary','employeeInfo', 'banks','today', 'year', 'month'));
    }




    public function project_edit_pdf(Request $request)
    {
        $get_total_retail = $request->total_retail;
        $total_count = $request->total_count;
        $pdf_retail = $request->pdf_retail;
        $projectClientNameByPdf = $request->projectClientNameByPdf;
        $total_express_way_fee = $request->total_express_way__fee;
        $total_parking_fee = $request->total_parking_fee;
        $today = Carbon::now();

        $clientId = $request->client;
        $getYear = $request->year;
        $getMonth = $request->month;

        // チェックされいなければ0と判定
        $company_check = $request->input('company_check', 0);
        $retail_check = $request->input('retail_check', 0);
        $salary_check = $request->input('salary_check', 0);
        $expressway_check = $request->input('expressway_check', 0);
        $parking_check = $request->input('parking_check', 0);

        // 所属先IDだけ抽出
        $companyIds = [];
        foreach($company_check as $value){
            $replaceValue = str_replace("project", "", $value);
            $companyIds[] = $replaceValue;
        }

        // $total_retail = $get_total_retail + $total_express_way_fee + $total_parking_fee;

        // シフトを検索・取得
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift','shift.employee.company','project')
        ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
            $query->whereYear('date', $getYear)
                ->whereMonth('date', $getMonth);
        })
        ->whereHas('project', function ($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })
        ->get();


        $total_retail = 0;
        $projectData = [];
        foreach ($ShiftProjectVehicles as $spv) {
            foreach($companyIds as $id){
                if($spv->shift->employee->company->id == $id){
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

                    // 日付を文字列として追加
                    $formattedDate = $date->format('j'); // 日付のみ
                    if (empty($projectData[$projectName]['dates'])) {
                        $formattedDate = $date->format('m/d'); // 最初の日付は月/日
                    }
                    if (!str_contains($projectData[$projectName]['dates'], $formattedDate)) {
                        $projectData[$projectName]['dates'] .= (empty($projectData[$projectName]['dates']) ? '' : ',') . $formattedDate;
                    }

                    // 案件数と上代の合計を更新
                    $projectData[$projectName]['count']++;
                    $projectData[$projectName]['total_price'] += $retailPrice;

                    $total_retail += $retailPrice;
                }
            }
        }

        $expresswayData = [];

        foreach ($ShiftProjectVehicles as $spv) {
            $projectName = $spv->project->name;
            $date = Carbon::parse($spv->shift->date);
            $expresswayFee = $spv->expressway_fee; // 高速代

            if($spv->expressway_fee && $expressway_check !== 0){
                foreach($companyIds as $id){
                    if($spv->shift->employee->company->id == $id){
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

        $parkingData = [];

        foreach ($ShiftProjectVehicles as $spv) {
            $projectName = $spv->project->name;
            $date = Carbon::parse($spv->shift->date);
            $parkingFee = $spv->parking_fee; // パーキング料金

            if($spv->parking_fee && $parking_check !== 0){
                foreach($companyIds as $id){
                    if($spv->shift->employee->company->id == $id){
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

        return view('edit-pdf.project-edit-pdf', compact('total_retail', 'total_count', 'pdf_retail', 'projectClientNameByPdf', 'today', 'projectData', 'expresswayData', 'parkingData'));
    }
}
