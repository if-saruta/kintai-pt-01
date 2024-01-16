<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Shift;
use App\Models\ShiftProject;
use App\Models\ProjectEmployeePayment;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
// use PDF;

class PdfputController extends Controller
{

    public function driver_issue_pdf(Request $request)
    {
        $administrative_commission_fee = $request->administrative_commission_fee;
        $total_lease = $request->total_lease;
        $total_insurance = $request->total_insurance;
        $administrative_fee = $request->administrative_fee;
        $transfer_fee = $request->transfer_fee;
        $total_salary = $request->total_salary;
        $total_allowance = $request->total_allowance;
        $etc = $request->etc;
        $employee = $request->employee;

        $employeeInfo = Employee::find($employee);
        $employeeName = $employeeInfo->name;

        $today = Carbon::now();

        $pdf = PDF::loadView('issue-pdf.driver-issue-pdf', compact('administrative_commission_fee','total_lease','total_insurance','administrative_fee','transfer_fee','total_allowance','etc','total_salary','employeeInfo','today'));

        $fileName = "{$today->format('Y-m-d')}_{$employeeName}.pdf";
        return $pdf->download($fileName); //生成されるファイル名
        // return view('issue-pdf.driver-issue-pdf', compact('administrative_commission_fee','total_lease','total_insurance','administrative_fee','transfer_fee','total_allowance','etc','total_salary','employeeInfo','today'));
    }

    public function company_issue_pdf(Request $request)
    {
        $administrative_commission_fee = $request->administrative_commission_fee;
        $total_lease = $request->total_lease;
        $total_insurance = $request->total_insurance;
        $administrative_fee = $request->administrative_fee;
        $transfer_fee = $request->transfer_fee;
        $total_salary = $request->total_salary;
        $total_allowance = $request->total_allowance;
        $etc = $request->etc;
        $employee = $request->employee;

        $employeeInfo = Employee::find($employee);

        $today = Carbon::now();

        $pdf = PDF::loadView('issue-pdf.company-issue-pdf', compact('administrative_commission_fee','total_lease','total_insurance','administrative_fee','transfer_fee','employeeInfo','today'));
        $employeeName = $employeeInfo->name;
        $fileName = "{$today->format('Y-m-d')}_{$employeeName}_相殺.pdf";
        return $pdf->download($fileName); //生成されるファイル名
    }

    public function project_issue_pdf(Request $request)
    {
        $total_retail = $request->total_retail;
        $total_count = $request->total_count;
        $pdf_retail = $request->pdf_retail;
        $projectClientNameByPdf = $request->projectClientNameByPdf;

        $today = Carbon::now();

        $pdf = PDF::loadView('issue-pdf.project-issue-pdf', compact('total_retail','total_count','pdf_retail','projectClientNameByPdf','today'));
        return $pdf->download($projectClientNameByPdf);
    }

    public function pdf_sample(Request $request)
    {
        $projectId = 1;
        $month = 11;

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

        // $pdf = PDF::loadView('dompdf.pdf',compact('shifts','getProject','dates','projectId','month'));
        // return $pdf->download('PDFダウンロード.pdf');
        // return view('dompdf.pdf',compact('shifts','getProject','dates','projectId','month'));
    }
}
