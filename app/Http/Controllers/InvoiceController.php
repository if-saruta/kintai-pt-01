<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Shift;
use App\Models\ShiftProjectVehicle;

use Carbon\Carbon;

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

        return view('invoice.driverShift', compact('employees', 'shifts'));
    }

    public function driverShiftUpdate(Request $request)
    {

        $expressway = $request->expressway_fee;
        $parking = $request->parking_fee;

        foreach($expressway as $id => $value){
            $getShift = ShiftProjectVehicle::find($id);
            $getShift->expressway_fee = $value;
            $getShift->parking_fee = $parking[$id];
            $getShift->save();
        }

        // リダイレクトで使用
        $employeeId = $request->employee;
        $getYear = $request->year;
        $getMonth = $request->month;

        $employees = Employee::all();
        $employeeName = Employee::find($employeeId);

        $projects = Project::all();

        // シフトを検索・取得
        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.rentalVehicle')
                        ->where('employee_id', $employeeId)
                        ->whereYear('date', $getYear)
                        ->whereMonth('date', $getMonth)
                        ->get();



        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        return view('invoice.driverShift', compact('employees', 'employeeName','projects', 'shifts', 'getYear', 'getMonth', 'dates'));
    }

    public function searchShift(Request $request)
    {
        $employeeId = $request->employeeId;
        $getYear = $request->year;
        $getMonth = $request->month;

        $employees = Employee::all();
        $employeeName = Employee::find($employeeId);

        $projects = Project::all();

        // シフトを検索・取得
        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.rentalVehicle')
                        ->where('employee_id', $employeeId)
                        ->whereYear('date', $getYear)
                        ->whereMonth('date', $getMonth)
                        ->get();



        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        return view('invoice.driverShift', compact('employees', 'employeeName','projects', 'shifts', 'getYear', 'getMonth', 'dates'));
    }

    public function projectShift()
    {
        $projectClients = Project::whereNotNull('clientName')->distinct()->pluck('clientName');
        $ShiftProjectVehicles = null;

        return view('invoice.projectShift',compact('projectClients', 'ShiftProjectVehicles'));
    }

    public function projectShiftUpdate(Request $request)
    {
        $expressway = $request->expressway_fee;
        $parking = $request->parking_fee;

        foreach($expressway as $id => $value){
            $getShift = ShiftProjectVehicle::find($id);
            $getShift->expressway_fee = $value;
            $getShift->parking_fee = $parking[$id];
            $getShift->save();
        }

        $projectClientName = $request->projectClient;
        $getYear = $request->year;
        $getMonth = $request->month;

        $projects = Project::all();

        // 検索用
        $projectClients = Project::whereNotNull('clientName')->distinct()->pluck('clientName');

        // 検索結果の基づく案件のidを取得
        $searchProjectIds = Project::where('clientName', $projectClientName)
                           ->pluck('id')
                           ->toArray();

        // 取得した案件のコレクション
        $getProjects = Project::whereIn('id', $searchProjectIds)->get();

        // シフトを検索・取得
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift','shift.employee.company')
        ->whereIn('project_id', $searchProjectIds)
        ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
            $query->whereYear('date', $getYear)
                ->whereMonth('date', $getMonth);
        })
        ->get();

        // クライアント名取得
        $projectNames = [];
        foreach($ShiftProjectVehicles as $shift){
            if($shift->project){
                $projectClientNameByPdf = $shift->project->clientNameByPDF;
            }
        }

        // 所属先取得
        $companyIds = [];
        foreach ($ShiftProjectVehicles as $spv) {
            if ($spv->shift && $spv->shift->employee && $spv->shift->employee->company) {
                // Company にアクセス
                $company = $spv->shift->employee->company;
                if(!in_array($company->id,$companyIds)){
                    $companyIds[] = $company->id;;
                }
            }
        }
        $getCompanies = Company::whereIn('id', $companyIds)->get();

        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        return view('invoice.projectShift', compact('projects', 'projectClients', 'getProjects', 'ShiftProjectVehicles', 'projectClientNameByPdf', 'projectClientName', 'getCompanies', 'getYear', 'getMonth', 'dates'));
    }

    public function searchProjectShift(Request $request)
    {
        $projectClientName = $request->projectClient;
        $getYear = $request->year;
        $getMonth = $request->month;

        $projects = Project::all();

        // 検索用
        $projectClients = Project::whereNotNull('clientName')->distinct()->pluck('clientName');

        // 検索結果の基づく案件のidを取得
        $searchProjectIds = Project::where('clientName', $projectClientName)
                           ->pluck('id')
                           ->toArray();

        // 取得した案件のコレクション
        $getProjects = Project::whereIn('id', $searchProjectIds)->get();

        // シフトを検索・取得
        $ShiftProjectVehicles = ShiftProjectVehicle::with('shift','shift.employee.company')
        ->whereIn('project_id', $searchProjectIds)
        ->whereHas('shift', function ($query) use ($getYear, $getMonth) {
            $query->whereYear('date', $getYear)
                ->whereMonth('date', $getMonth);
        })
        ->get();

        // クライアント名取得
        $projectNames = [];
        foreach($ShiftProjectVehicles as $shift){
            if($shift->project){
                $projectClientNameByPdf = $shift->project->clientNameByPDF;
            }
        }

        // 所属先取得
        $companyIds = [];
        foreach ($ShiftProjectVehicles as $spv) {
            if ($spv->shift && $spv->shift->employee && $spv->shift->employee->company) {
                // Company にアクセス
                $company = $spv->shift->employee->company;
                if(!in_array($company->id,$companyIds)){
                    $companyIds[] = $company->id;;
                }
            }
        }
        $getCompanies = Company::whereIn('id', $companyIds)->get();

        // 全日にちを取得
        $dates = $this->createDate($getYear, $getMonth);

        return view('invoice.projectShift', compact('projects', 'projectClients', 'getProjects', 'ShiftProjectVehicles', 'projectClientNameByPdf', 'projectClientName', 'getCompanies', 'getYear', 'getMonth', 'dates'));
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
}
