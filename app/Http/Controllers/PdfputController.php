<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Shift;
use App\Models\Client;
use App\Models\ShiftProject;
use App\Models\ProjectEmployeePayment;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yasumi\Yasumi;

class PdfputController extends Controller
{

    public function driver_issue_pdf(Request $request)
    {

        // 給与項目
        $employeeId = $request->employeeId;
        $bankName = $request->bank_name;
        $bankAccountHolder = $request->bank_account_holder;
        $salaryNo = $request->input('salaryNo');
        $salaryMonth = $request->input('salaryMonth');
        $salaryProject = $request->input('salaryProject');
        $salaryEtc = $request->input('salaryEtc');
        $salaryCount = $request->input('salaryCount');
        $salaryUntil = $request->input('salaryUntil');
        $salaryAmount = $request->input('salaryAmount');
        $salarySubTotal = $request->salarySubTotal;
        $salaryTax = $request->salaryTax;
        $etcTotal = $request->etcTotal;
        $salaryTotal = $request->salaryTotal;
        $color = $request->pdfColor;
        $salaryCompanyInfo = $request->salaryCompanyInfo;
        $textWithBreaks = nl2br(e($salaryCompanyInfo));

        // 費用総裁項目
        $getCostNum = $request->getCostNum;
        $getCostUntil = $request->gtCostUntil;
        $getCostAmount = $request->getCostAmount;
        $salaryCostName = $request->input('salaryCostName');
        $salaryCostNum = $request->input('salaryCostNum');
        $salaryCostUntil = $request->input('salaryCostUntil');
        $salaryCostAmount = $request->input('salaryCostAmount');
        $salaryCostTotal = $request->salaryCostTotal;

        $allTotal = $request->allTotal;

        // 共通項目
        $invoiceNumber = $request->driver_invoice_number;
        $offSetInvoiceNumber = $request->offSetInvoiceNumber;
        $employee = Employee::find($employeeId);
        $today = Carbon::now();

        // ファイル名指定
        $name = $employee->name;

        $pdf = PDF::loadView('issue-pdf.driver-issue-pdf', compact('today', 'employee', 'textWithBreaks', 'invoiceNumber', 'offSetInvoiceNumber', 'bankName', 'bankAccountHolder', 'salaryNo', 'salaryMonth','salaryProject', 'salaryEtc', 'salaryCount', 'salaryUntil', 'salaryAmount', 'salarySubTotal', 'salaryTax', 'etcTotal', 'salaryTotal', 'getCostNum', 'getCostUntil', 'getCostAmount', 'salaryCostName', 'salaryCostNum', 'salaryCostUntil', 'salaryCostAmount', 'salaryCostTotal', 'allTotal', 'color'));

        $fileName = "{$today->format('Y-m-d')}_{$name}.pdf";

        return $pdf->download($fileName); //生成されるファイル名

        // return view('issue-pdf.driver-issue-pdf', compact('today', 'employee', 'textWithBreaks', 'invoiceNumber', 'offSetInvoiceNumber', 'bankName', 'bankAccountHolder', 'salaryNo', 'salaryMonth','salaryProject', 'salaryEtc', 'salaryCount', 'salaryUntil', 'salaryAmount', 'salarySubTotal', 'salaryTax', 'etcTotal', 'salaryTotal', 'getCostNum', 'getCostUntil', 'getCostAmount', 'salaryCostName', 'salaryCostNum', 'salaryCostUntil', 'salaryCostAmount', 'salaryCostTotal', 'allTotal', 'color'));
    }

    public function company_issue_pdf(Request $request)
    {
        // 費用項目
        $employeeId = $request->employeeId;
        $costItem = $request->input('costItem');
        $costNum = $request->input('costNum');
        $costUntil = $request->input('costUntil');
        $costAmount = $request->input('costAmount');
        $costSubTotal = $request->costSubTotal;
        $costTax = $request->costTax;
        $costTotal = $request->costTotal;
        $targetCost = $request->targetCost;
        $targetTax = $request->targetTax;
        $notTargetCost = $request->notTargetCost;

        $CompanyInfo = $request->costCompanyInfo;
        $textWithBreaks = nl2br(e($CompanyInfo));

        $bankInfo = $request->bank_info;
        $bankInfoWithBreaks = nl2br(e($bankInfo));


        // 共通項目
        $invoiceNumber = $request->invoice_number;
        $employee = Employee::find($employeeId);
        $today = Carbon::now();

        $image_path = storage_path('image/signature-stamp.png');
        $image_data = base64_encode(file_get_contents($image_path));

        // ファイル名指定
        $name = $employee->name;

        $pdf = PDF::loadView('issue-pdf.company-issue-pdf', compact('image_data','textWithBreaks', 'invoiceNumber', 'employee', 'today', 'costItem','costNum','costUntil','costAmount','costSubTotal','costTax','costTotal', 'targetCost', 'targetTax','notTargetCost','bankInfoWithBreaks'));

        $fileName = "{$today->format('Y-m-d')}_{$name}_相殺.pdf";

        return $pdf->download($fileName); //生成されるファイル名

        // return view('issue-pdf.company-issue-pdf', compact('image_data','textWithBreaks', 'invoiceNumber', 'employee', 'today', 'costItem','costNum','costUntil','costAmount','costSubTotal','costTax','costTotal', 'targetCost', 'targetTax','notTargetCost','bankInfoWithBreaks'));
    }

    public function project_issue_pdf(Request $request)
    {
        $clientId = $request->clientId;
        $invoiceNumber = $request->invoice_number;
        $name = $request->name;
        $subject = $request->subject;
        $companyInfo = $request->company_info;
        $item = $request->input('item');
        $number = $request->input('number');
        $until = $request->input('until');
        $amount = $request->input('amount');
        $subTotalRetail = $request->sub_total_retail;
        $tax = $request->tax;
        $totalRetail = $request->total_retail;
        $bankName = $request->bank_name;
        $taxTable01 = $request->input('tax_table01');
        $taxTable02 = $request->input('tax_table02');
        $taxTable03 = $request->input('tax_table03');
        $taxTable04 = $request->input('tax_table04');

        $subjectWithBreaks = nl2br(e($subject));
        $companyInfoWithBreaks = nl2br(e($companyInfo));
        $bankNameInfoWithBreaks = nl2br(e($bankName));

        $today = Carbon::now();
        $client = Client::find($clientId);
        $pdfName = $client->pdfName;

        // 印鑑の画像を読み込み
        $image_path = storage_path('image/signature-stamp.png');
        $image_data = base64_encode(file_get_contents($image_path));

        $pdf = PDF::loadView('issue-pdf.project-issue-pdf',
        compact('invoiceNumber', 'name', 'subjectWithBreaks', 'companyInfoWithBreaks'
                ,'item', 'number', 'until', 'amount', 'subTotalRetail'
                ,'tax', 'totalRetail', 'bankNameInfoWithBreaks', 'today'
                ,'taxTable01', 'taxTable02', 'taxTable03', 'taxTable04', 'image_data'));

        // return view('issue-pdf.project-issue-pdf', compact('invoiceNumber', 'name', 'subjectWithBreaks', 'companyInfoWithBreaks'
        // ,'item', 'number', 'until', 'amount', 'subTotalRetail'
        // ,'tax', 'totalRetail', 'bankNameInfoWithBreaks', 'today'
        // ,'taxTable01', 'taxTable02', 'taxTable03', 'taxTable04'));

        $fileName = "{$today->format('Y-m-d')}_{$pdfName}.pdf";

        return $pdf->download($fileName); //生成されるファイル名
    }

    public function allViewDownloadPdf(Request $request)
    {
        $startOfWeek = $request->startOfWeek;
        $endOfWeek = $request->endOfWeek;
        $projectHeight = $request->projectHeight;

        // 登録従業員シフト抽出
        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->whereNotNull('employee_id')
        ->get();
        $shiftDataByEmployee = $shifts->groupBy(function ($shift) {
            return $shift->employee_id;
        });

        // 未登録従業員シフト抽出
        $unShifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->whereNull('employee_id')
        ->get();
        $shiftDataByUnEmployee = $unShifts->groupBy(function ($unShift) {
            return $unShift->unregistered_employee;
        });

        // 日付を格納
        $dates = [];
        foreach ($shifts as $shift) {
            if (!in_array($shift->date, $dates)) {
                $dates[] = $shift->date;
            }
        }
        $convertedDates = [];
        foreach ($dates as $date) {
            $convertedDates[] = Carbon::createFromFormat('Y-m-d', $date);
        }

        $date = new Carbon($startOfWeek);

        // 祝日を取得
        $holidays = $this->getHoliday($date->format('Y'));

        // $projects = Project::all();
        // return view('shift-calendar-pdf.projectCountShift', compact('shifts', 'shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projects'));

        // 一時的にメモリ制限を増やす
        ini_set('memory_limit', '256M');

        $startOfWeekCarbon = new Carbon($startOfWeek);
        $endOfWeekCarbon = new Carbon($endOfWeek);

        // シフトのタイプを受け取り
        $shift_type = $request->shiftType;
        if($shift_type == 'all'){
            $pdf = PDF::loadView('shift-calendar-pdf.allViewShift', compact('shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projectHeight'))->setPaper('a4', 'landscape');
            $fileName = "{$date->format('Y')}年_{$startOfWeekCarbon->format('n')}月{$startOfWeekCarbon->format('j')}日~{$endOfWeekCarbon->format('n')}月{$endOfWeekCarbon->format('j')}日_全表示シフト";
        }elseif($shift_type == 'employeeShow'){
            $pdf = PDF::loadView('shift-calendar-pdf.employeeShowShift', compact('shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projectHeight'))->setPaper('a4', 'landscape');
            $fileName = "{$date->format('Y')}年_{$startOfWeekCarbon->format('n')}月{$startOfWeekCarbon->format('j')}日~{$endOfWeekCarbon->format('n')}月{$endOfWeekCarbon->format('j')}日_稼働表シフト";

        }elseif($shift_type == 'employeePrice'){
            $pdf = PDF::loadView('shift-calendar-pdf.employeePriceShift', compact('shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projectHeight'))->setPaper('a4', 'landscape');
            $fileName = "{$date->format('Y')}年_{$startOfWeekCarbon->format('n')}月{$startOfWeekCarbon->format('j')}日~{$endOfWeekCarbon->format('n')}月{$endOfWeekCarbon->format('j')}日_配送料金シフト";

        }elseif($shift_type == 'projectPrice'){
            $pdf = PDF::loadView('shift-calendar-pdf.projectPriceShift', compact('shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projectHeight'))->setPaper('a4', 'landscape');
            $fileName = "{$date->format('Y')}年_{$startOfWeekCarbon->format('n')}月{$startOfWeekCarbon->format('j')}日~{$endOfWeekCarbon->format('n')}月{$endOfWeekCarbon->format('j')}日_上代シフト";

        }elseif($shift_type == 'projectCount'){
            $shiftDataByDay = $shifts->groupBy(function ($shift) {
                return $shift->date;
            });

            $unregistered_project = [];
            foreach ($shiftDataByDay as $Shiftdate => $shiftData) {
                foreach ($shiftData as $shift) {
                    foreach ($shift->projectsVehicles as $spv) {
                        if (!$spv->project) {
                            if ($spv->unregistered_project) {
                                if (!in_array($spv->unregistered_project, $unregistered_project)) {
                                    $unregistered_project[] = $spv->unregistered_project;
                                }
                            }
                        }
                    }
                }
            }
            $projects = Project::all();

            $pdf = PDF::loadView('shift-calendar-pdf.projectCountShift', compact('shifts', 'shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projects', 'unregistered_project'))->setPaper('a4');
            $fileName = "{$date->format('Y')}年_{$startOfWeekCarbon->format('n')}月{$startOfWeekCarbon->format('j')}日~{$endOfWeekCarbon->format('n')}月{$endOfWeekCarbon->format('j')}日_案件数シフト";
        }else{
            $pdf = PDF::loadView('shift-calendar-pdf.allViewShift', compact('shiftDataByEmployee', 'shiftDataByUnEmployee', 'convertedDates', 'holidays', 'projectHeight'))->setPaper('a4', 'landscape');
            $fileName = "{$date->format('Y')}年_{$startOfWeekCarbon->format('n')}月{$startOfWeekCarbon->format('j')}日~{$endOfWeekCarbon->format('n')}月{$endOfWeekCarbon->format('j')}日_全表示シフト";
        }

        return $pdf->download($fileName);
    }

    // 祝日を取得
    public function getHoliday($year)
    {
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');

        return $holidays;
    }

}
