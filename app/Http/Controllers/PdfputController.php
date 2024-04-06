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

    $pdf->download($fileName); //生成されるファイル名

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

        // return view('shift-calendar-pdf.all-view-shift', compact('shiftDataByEmployee', 'unShifts', 'convertedDates', 'holidays', 'projectHeight'));

        $pdf = PDF::loadView('shift-calendar-pdf.all-view-shift', compact('shiftDataByEmployee', 'unShifts', 'convertedDates', 'holidays', 'projectHeight'))->setPaper('a4', 'landscape');;

        return $pdf->download();
    }

    // 祝日を取得
    public function getHoliday($year)
    {
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');

        return $holidays;
    }

}
