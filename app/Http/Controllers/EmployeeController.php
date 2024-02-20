<?php

namespace App\Http\Controllers;

use App\Models\AllowanceByOther;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectEmployeePayment;
use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\AllowanceByProject;
use League\Csv\Reader;
use League\Csv\Statement;
use PhpParser\Node\Stmt\TryCatch;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $employeesByCompany = $employees->groupBy(function ($employee){
            return $employee->company->name;
        });

        $companies = Company::all();

        return view('employee.index', compact('employeesByCompany'));
    }

    public function create()
    {
        $companies = Company::all();
        $projects = Project::all();
        $vehicles = Vehicle::all();
        // 日給登録してある案件を取得
        $projectPayments = Project::where('payment_type', 1)->get();

        return view('employee.create', compact('companies','projects','vehicles','projectPayments'));
    }

    public function store(Request $request)
    {

        // $validated = $request->validate([
        //     'name' => 'required'
        // ]);

        if($request->rental_type == 1){
            $employee = Employee::create([
                'register_number' => $request->register_number,
                'name' => $request->name,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'employment_status' => $request->status,
                'company_id' => $request->company,
                'is_invoice' => $request->invoice,
                'vehicle_rental_type' => $request->rental_type,
                'vehicle_id' => $request->vehicle,
            ]);
        }else{
            $employee = Employee::create([
                'register_number' => $request->register_number,
                'name' => $request->name,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'employment_status' => $request->status,
                'company_id' => $request->company,
                'is_invoice' => $request->invoice,
                'vehicle_rental_type' => $request->rental_type,
            ]);
        }

        BankAccount::create([
            'employee_id' => $employee->id,
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
        ]);

        // 案件別給与の登録
        $employeePrices = $request->input('employeePrice');
        if($employeePrices){
            foreach ($employeePrices as $projectId => $price) {
                ProjectEmployeePayment::create([
                    'employee_id' => $employee->id,
                    'project_id' => $projectId,
                    'amount' => $price,
                ]);
            }
        }

        // 案件別手当の登録
        $allowanceNames = $request->input('allowanceName');
        $allowanceAmounts = $request->input('allowanceAmount');
        // 各プロジェクトと手当を処理
        if($allowanceNames){
            foreach ($allowanceNames as $projectId => $names) {
                foreach ($names as $index => $name) {
                    $amount = $allowanceAmounts[$projectId][$index] ?? null;

                    AllowanceByProject::create([
                        'employee_id' => $employee->id,
                        'project_id' => $projectId,
                        'allowanceName' => $name,
                        'amount' => $amount,
                    ]);
                }
            }
        }

        // その他手当の登録
        $allowanceOtherNames = $request->input('allowanceOtherName');
        $allowanceOtherAmounts = $request->input('allowanceOtherAmount');
        // 各手当名と金額を処理
        if($allowanceOtherNames){
            foreach ($allowanceOtherNames as $index => $name) {
                $amount = $allowanceOtherAmounts[$index] ?? null;

                AllowanceByOther::create([
                    'employee_id' => $employee->id,
                    'allowanceName' => $name,
                    'amount' => $amount,
                ]);
            }
        }

        // 登録済みのシフトの日付を取得
        $unDate = Shift::query()
                ->select('date')
                ->groupBy('date')
                ->get()
                ->pluck('date');

        foreach($unDate as $date){
            Shift::create([
                'date' => $date,
                'employee_id' => $employee->id
            ]);
        }

        return redirect()->route('employee.');
    }

    public function edit($id)
    {
        $employee = Employee::find($id);
        $companies = Company::all();
        $projects = Project::all();
        $vehicles = Vehicle::all();
        // 日給登録してある案件を取得
        $projectPayments = Project::where('payment_type', 1)->get();
        $projectEmployeePayments = ProjectEmployeePayment::where('employee_id', $id)->get();
        $allowanceProjects = AllowanceByProject::where('employee_id', $id)->get();
        $allowanceOthers = AllowanceByOther::where('employee_id', $id)->get();

        return view('employee.edit', compact('employee','companies','projects','vehicles','projectPayments', 'projectEmployeePayments', 'allowanceProjects', 'allowanceOthers'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        // 従業員データを更新
        $employee->register_number = $request->register_number;
        $employee->name = $request->name;
        $employee->post_code = $request->post_code;
        $employee->address = $request->address;
        $employee->employment_status = $request->status;
        $employee->company_id = $request->company;
        $employee->employment_status = $request->status;
        $employee->is_invoice = $request->invoice;
        $employee->vehicle_rental_type = $request->rental_type;
        if($request->rental_type == 1){
            $employee->vehicle_id = $request->vehicle;
        }else{
            $employee->vehicle_id = null;
        }
        $employee->save();

        $bank_account = BankAccount::where('employee_id', $employee->id)->first();
        $bank_account->bank_name = $request->bank_name;
        $bank_account->account_holder_name = $request->account_holder_name;
        $bank_account->save();


        // 案件別給与データ更新
        $employeePrices = $request->input('employeePrice');
        if($employeePrices){
            foreach ($employeePrices as $projectId => $price) {
                ProjectEmployeePayment::updateOrCreate(
                    ['project_id' => $projectId, 'employee_id' => $employee->id],
                    ['amount' => $price]
                );
            }
        }

        // 案件別手当データを更新
        $allowanceProjectNamesEdit = $request->input('allowanceNameByEdit');
        $allowanceProjectAmountsEdit = $request->input('allowanceAmountByEdit');
        if($allowanceProjectNamesEdit){
            foreach($allowanceProjectNamesEdit as $id => $value) {
                $allowanceProject = AllowanceByProject::where('id', $id)
                ->first();

                $allowanceProject->allowanceName = $value;
                $allowanceProject->amount = $allowanceProjectAmountsEdit[$id];

                $allowanceProject->save();
            }
        }

        // 新しく追加された案件別手当を登録
        $allowanceNames = $request->input('allowanceName');
        $allowanceAmounts = $request->input('allowanceAmount');
        if($allowanceNames){
            // 各プロジェクトと手当を処理
            foreach ($allowanceNames as $projectId => $names) {
                foreach ($names as $index => $name) {
                    $amount = $allowanceAmounts[$projectId][$index] ?? null;

                    AllowanceByProject::create([
                        'employee_id' => $employee->id,
                        'project_id' => $projectId,
                        'allowanceName' => $name,
                        'amount' => $amount,
                    ]);
                }
            }
        }

        // 既存案件別手当の削除
        $deleteId = $request->input('allowanceProjectDeleteId');
        if($deleteId){
            foreach($deleteId as $id){
                $allowanceProject = AllowanceByProject::where('id', $id);
                $allowanceProject->delete();
            }
        }

        // その他手当のデータを更新
        $allowanceOtherNamesEdit = $request->input('allowanceOtherNameEdit');
        $allowanceOtherAmountsEdit = $request->input('allowanceOtherAmountEdit');
        if($allowanceOtherNamesEdit){
            foreach($allowanceOtherNamesEdit as $id => $value){
                $allowanceOther = AllowanceByOther::where('id', $id)
                ->first();

                $allowanceOther->allowanceName = $value;
                $allowanceOther->amount = $allowanceOtherAmountsEdit[$id];

                $allowanceOther->save();
            }
        }

        // その他手当の登録
        $allowanceOtherNames = $request->input('allowanceOtherName');
        $allowanceOtherAmounts = $request->input('allowanceOtherAmount');
        // 各手当名と金額を処理
        if($allowanceOtherNames){
            foreach ($allowanceOtherNames as $index => $name) {
                $amount = $allowanceOtherAmounts[$index] ?? null;

                AllowanceByOther::create([
                    'employee_id' => $employee->id,
                    'allowanceName' => $name,
                    'amount' => $amount,
                ]);
            }
        }

        // 既存その他手当の削除
        $deleteId = $request->input('allowanceOtherDeleteId');
        if($deleteId){
            foreach($deleteId as $id){
                $allowanceOther = AllowanceByOther::where('id', $id);
                $allowanceOther->delete();
            }
        }




        return redirect()->route('employee.');
    }

    public function delete($id)
    {
        try{
            $employee = Employee::find($id);

            $employee->delete();

            return redirect()->route('employee.');

        }catch (\Exception $e){
            return redirect()->route('employee.')->with('alert', 'シフトに登録されている従業員は現在は削除できない仕様にしてあります。ご了承ください。');
        }
    }


    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            $employee = Employee::create([
                'register_number' => $row['register_number'],
                'company_id' => $row['company_id'],
                'name' => $row['name'],
                'post_code' => $row['post_code'],
                'address' => $row['address'],
                'employment_status' => $row['employment_status'],
                'is_invoice' => $row['is_invoice'],
                'vehicle_rental_type' => $row['vehicle_rental_type'],
            ]);

            BankAccount::create([
                'employee_id' => $employee->id,
                'bank_name' => $row['bank_name'],
                'account_holder_name' => $row['account_holder_name'],
            ]);
        }

        return redirect()->route('employee.');
    }

}
