<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Shift;
use App\Models\ShiftProject;
use App\Models\ProjectEmployeePayment;

use function PHPUnit\Framework\isEmpty;

class CsvIssueController extends Controller
{

    public function index()
    {
        $projects = Project::all();

        return view('csv-issue.index', compact('projects'));
    }

    public function show(Request $request)
    {
        $projectId = $request->project;
        $month = $request->month;

        $getProject = Project::find($projectId);
        // 取得した月でフィルター
        $shifts = Shift::whereMonth('date', $month)
            ->get();

        // 月の全日付を取得
        Carbon::setLocale('ja');

        $dates = [];
        $year = Carbon::now()->year; // 現在の年を取得
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dates[] = [
                'display' => $date->format('m月d日') . '(' . $date->isoFormat('ddd') . ')',
                'compare' => $date->format('Y-m-d')
            ];
        }

        return view('csv-issue.show', compact('shifts','getProject','dates','projectId','month'));
    }

    public function csvExport($projectId,$month)
    {

        // 案件名を取得
        $getProject = Project::find($projectId);
        $projectName = $getProject->name;

        // 上代を取得
        $price = $getProject->retail_price;

        // 月に一致するシフトを取得
        $shifts = Shift::whereMonth('date', $month)
            ->get();

        // 月の全日付を取得
        Carbon::setLocale('ja');

        $dates = [];
        $year = Carbon::now()->year; // 現在の年を取得
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dates[] = [
                'display' => $date->format('m月d日') . '(' . $date->isoFormat('ddd') . ')',
                'compare' => $date->format('Y-m-d')
            ];
        }

        // StreamedResponseを使用してCSVを出力
        $response = new StreamedResponse(function() use ($dates, $shifts, $projectId, $price) {
            // 出力バッファを開く
            $handle = fopen('php://output', 'w');

            // CSVのヘッダーを書き込む
            fputcsv($handle, ['日付', '午前', '午後', '上代']);


            // 日付ごとにデータをループ
            foreach ($dates as $date) {
                $amName = $pmName = ''; // 午前と午後の名前を初期化
                $count = 0; // 1日の案件数

                // 午前のシフトを探す
                foreach ($shifts as $shift) {
                    if ($shift->date == $date['compare']) {
                        foreach ($shift->projects as $project) {
                            if ($project->id == $projectId && $project->pivot->time_of_day == 0) {
                                // $amName = $shift->employee->name;
                                if(!is_null($shift->employee->name)){
                                    if (!empty($amName)) {
                                        $amName .= "\n";
                                    }
                                    $amName .= $shift->employee->name;
                                }
                                $count++;
                                // break;
                            }
                        }
                    }
                }

                // 午後のシフトを探す
                foreach ($shifts as $shift) {
                    if ($shift->date == $date['compare']) {
                        foreach ($shift->projects as $project) {
                            if ($project->id == $projectId && $project->pivot->time_of_day == 1) {
                                if(!is_null($shift->employee->name)){
                                    if (!empty($pmName)) {
                                        $pmName .= "\n";
                                    }
                                    $pmName .= $shift->employee->name;
                                }
                                $count++;
                                // break;
                            }
                        }
                    }
                }

                if($count < 1){
                    $totalPrice = "";
                }else{
                    $totalPrice = $price * $count;
                }


                // CSVに1行書き込む
                fputcsv($handle, [$date['display'], $amName, $pmName, $totalPrice]);
            }

            // 出力バッファを閉じる
            fclose($handle);
        });

        // ファイル名
        $filename = $month.'月'.$projectName.'.csv';

        // レスポンスのヘッダーを設定
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        // レスポンスを返す
        return $response;

    }


    //
    public function employeeIndex()
    {
        $employees = Employee::all();

        return view('employee-csv.index', compact('employees'));
    }

    public function employeeShow(Request $request)
    {
        $employeeId = $request->employee;
        $year = $request->year;
        $month = $request->month;

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('employee_id', $employeeId)
            ->get();

        $startOfMonth = Carbon::createFromDate($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // 日付を生成
        $dates = [];
        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $payments = ProjectEmployeePayment::all();



        return view('employee-csv.show', compact('shifts','dates','payments','employeeId','month','year'));
    }

    public function employeeCsvExport($employeeId,$year,$month)
    {

        // 従業員名を取得
        $getEmployee = Employee::find($employeeId);
        $employeetName = $getEmployee->name;

        $payments = ProjectEmployeePayment::all();

        // 従業員別給与を取得
        $payments = ProjectEmployeePayment::where('employee_id', $employeeId)
            ->get();

        // 一致するシフトを取得
        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('employee_id', $employeeId)
            ->get();


        // 月の全日付を取得
        Carbon::setLocale('ja');

        $dates = [];
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dates[] = [
                'display' => $date->format('m月d日') . '(' . $date->isoFormat('ddd') . ')',
                'compare' => $date->format('Y-m-d')
            ];
        }

        // StreamedResponseを使用してCSVを出力
        $response = new StreamedResponse(function() use ($dates, $shifts, $employeeId, $payments) {
            // 出力バッファを開く
            $handle = fopen('php://output', 'w');

            // CSVのヘッダーを書き込む
            fputcsv($handle, ['日付', '午前', '午後', '上代']);


            // 日付ごとにデータをループ
            foreach ($dates as $date) {
                $amName = $pmName = ''; // 午前と午後の名前を初期化

                // シフトを格納
                $priceTotal = 0;
                foreach ($shifts as $shift) {
                    if ($shift->date == $date['compare']) {
                        foreach($shift->projectsVehicles as $spv){
                            if($spv->time_of_day == 0){
                                if (!empty($amName)) {
                                    $amName .= "\n";
                                }

                                if($spv->project){
                                    $amName .= $spv->project->name;
                                }elseif(!empty($spv->unregistered_project)){
                                    $amName .= $spv->unregistered_project;
                                }
                            }
                            if($spv->time_of_day == 1){
                                if (!empty($pmName)) {
                                    $pmName .= "\n";
                                }

                                if($spv->project){
                                    $pmName .= $spv->project->name;
                                }elseif(!empty($spv->unregistered_project)){
                                    $pmName .= $spv->unregistered_project;
                                }
                            }

                            $isDirverPriceCheck = false;
                            if($spv->driver_price){
                                $priceTotal += $spv->driver_price;
                                $isDirverPriceCheck = true;
                            }elseif($spv->project){
                                foreach($payments as $payment){
                                    if($payment->employee_id == $shift->employee_id && $payment->project_id == $spv->project_id){
                                        if(!empty($payment->amount)){
                                            $priceTotal += $payment->amount;
                                            $isDirverPriceCheck = true;
                                        }
                                    }
                                }
                            }
                            if(!$isDirverPriceCheck){
                                if($spv->project){
                                    $priceTotal += $spv->project->driver_price;
                                }
                            }
                        }
                    }
                }



                // CSVに1行書き込む
                fputcsv($handle, [$date['display'], $amName, $pmName, $priceTotal]);
            }

            // 出力バッファを閉じる
            fclose($handle);
        });

        // ファイル名
        $filename = $month.'月'.$employeetName .'.csv';

        // レスポンスのヘッダーを設定
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        // レスポンスを返す
        return $response;

    }
}
