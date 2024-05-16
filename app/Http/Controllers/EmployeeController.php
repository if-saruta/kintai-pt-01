<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AllowanceByOther;
use App\Models\User;
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
        $projects = Project::where('client_id', '!=', 1)->get();
        $vehicles = Vehicle::all();

        // 日給登録してある案件を取得
        $projectPayments = Project::where('payment_type', 1)->get();

        // すでに使用者として登録してある車両を配列に格納
        $vehiclesUsed = Vehicle::whereNotNull('employee_id')->get();
        $vehicleUsedArray = [];
        foreach($vehiclesUsed as $vehicleUsed){
            $vehicleUsedArray[] = $vehicleUsed->id;
        }

        return view('employee.create', compact('companies','projects','vehicles','projectPayments', 'vehicleUsedArray'));
    }

    public function store(Request $request)
    {

        // 半角および全角カンマを除去し、intにキャストする関数
        $removeCommasAndCastToInt = function ($value) {
            $valueWithoutCommas = str_replace([',', '，'], '', $value);
            return (int)$valueWithoutCommas; // 文字列を整数型にキャスト
        };

        if($request->rental_type == 1){
            $employee = Employee::create([
                'company_name' => $request->company_name,
                'register_number' => $request->register_number,
                'name' => $request->name,
                'initials' => $request->initial,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building_name' => $request->building_name,
                'phone_number' => $request->phone,
                'employment_status' => $request->status,
                'transfer_fee' => $removeCommasAndCastToInt($request->transfer_fee),
                'company_id' => $request->company,
                'is_invoice' => $request->invoice,
                'vehicle_rental_type' => $request->rental_type,
                'vehicle_id' => $request->vehicle,
                'remarks' => $request->remarks,
            ]);
        }else{
            $employee = Employee::create([
                'company_name' => $request->company_name,
                'register_number' => $request->register_number,
                'name' => $request->name,
                'initials' => $request->initial,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building_name' => $request->building_name,
                'phone_number' => $request->phone,
                'employment_status' => $request->status,
                'transfer_fee' => $removeCommasAndCastToInt($request->transfer_fee),
                'company_id' => $request->company,
                'is_invoice' => $request->invoice,
                'vehicle_rental_type' => $request->rental_type,
                'remarks' => $request->remarks,
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
                    'amount' => $removeCommasAndCastToInt($price),
                ]);
            }
        }

        // 案件別手当の登録
        // $allowanceNames = $request->input('allowanceName');
        // $allowanceAmounts = $request->input('allowanceAmount');
        // // 各プロジェクトと手当を処理
        // if($allowanceNames){
        //     foreach ($allowanceNames as $projectId => $names) {
        //         foreach ($names as $index => $name) {
        //             $amount = $allowanceAmounts[$projectId][$index] ?? null;

        //             AllowanceByProject::create([
        //                 'employee_id' => $employee->id,
        //                 'project_id' => $projectId,
        //                 'allowanceName' => $name,
        //                 'amount' => $removeCommasAndCastToInt($amount),
        //             ]);
        //         }
        //     }
        // }

        // その他手当の登録
        // $allowanceOtherNames = $request->input('allowanceOtherName');
        // $allowanceOtherAmounts = $request->input('allowanceOtherAmount');
        // // 各手当名と金額を処理
        // if($allowanceOtherNames){
        //     foreach ($allowanceOtherNames as $index => $name) {
        //         $amount = $allowanceOtherAmounts[$index] ?? null;

        //         AllowanceByOther::create([
        //             'employee_id' => $employee->id,
        //             'allowanceName' => $name,
        //             'amount' => $removeCommasAndCastToInt($amount),
        //         ]);
        //     }
        // }

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
        $projects = Project::where('client_id', '!=', 1)->get();
        $vehicles = Vehicle::all();
        // 日給登録してある案件を取得
        $projectPayments = Project::where('payment_type', 1)->get();
        $projectEmployeePayments = ProjectEmployeePayment::where('employee_id', $id)->get();
        $allowanceProjects = AllowanceByProject::where('employee_id', $id)->get();
        $allowanceOthers = AllowanceByOther::where('employee_id', $id)->get();

        // すでに使用者として登録してある車両を配列に格納
        $vehiclesUsed = Vehicle::whereNotNull('employee_id')->get();
        $vehicleUsedArray = [];
        foreach($vehiclesUsed as $vehicleUsed){
            if($vehicleUsed->employee_id != $employee->id){
                $vehicleUsedArray[] = $vehicleUsed->id;
            }
        }

        return view('employee.edit', compact('employee','companies','projects','vehicles','projectPayments', 'projectEmployeePayments', 'allowanceProjects', 'allowanceOthers', 'vehicleUsedArray'));
    }

    public function update(Request $request, $id)
    {

        // 半角および全角カンマを除去し、intにキャストする関数
        $removeCommasAndCastToInt = function ($value) {
            $valueWithoutCommas = str_replace([',', '，'], '', $value);
            return (int)$valueWithoutCommas; // 文字列を整数型にキャスト
        };

        $employee = Employee::find($id);
        $oldVehicleId = $employee->vehicle_id;

        // 従業員データを更新
        $employee->company_name = $request->company_name;
        $employee->register_number = $request->register_number;
        $employee->name = $request->name;
        $employee->initials = $request->initial;
        $employee->post_code = $request->post_code;
        $employee->address = $request->address;
        $employee->building_name = $request->building_name;
        $employee->phone_number = $request->phone;
        $employee->employment_status = $request->status;
        $employee->company_id = $request->company;
        $employee->employment_status = $request->status;
        $employee->transfer_fee = $removeCommasAndCastToInt($request->transfer_fee);
        $employee->is_invoice = $request->invoice;
        $employee->vehicle_rental_type = $request->rental_type;
        $employee->remarks = $request->remarks;
        if($request->rental_type == 1){
            $employee->vehicle_id = $request->vehicle;
        }else{
            $employee->vehicle_id = null;
        }
        $employee->save();

        BankAccount::updateOrCreate(
            ['employee_id' => $employee->id],
            ['bank_name' => $request->bank_name, 'account_holder_name' => $request->account_holder_name]
        );
        // $bank_account = BankAccount::where('employee_id', $employee->id)->first();
        // $bank_account->bank_name = $request->bank_name;
        // $bank_account->account_holder_name = $request->account_holder_name;
        // $bank_account->save();


        // 案件別給与データ更新
        $employeePrices = $request->input('employeePrice');
        if($employeePrices){
            foreach ($employeePrices as $projectId => $price) {
                ProjectEmployeePayment::updateOrCreate(
                    ['project_id' => $projectId, 'employee_id' => $employee->id],
                    ['amount' => $removeCommasAndCastToInt($price)]
                );
            }
        }

        // 案件別手当データを更新
        // $allowanceProjectNamesEdit = $request->input('allowanceNameByEdit');
        // $allowanceProjectAmountsEdit = $request->input('allowanceAmountByEdit');
        // if($allowanceProjectNamesEdit){
        //     foreach($allowanceProjectNamesEdit as $id => $value) {
        //         $allowanceProject = AllowanceByProject::where('id', $id)
        //         ->first();

        //         $allowanceProject->allowanceName = $value;
        //         $allowanceProject->amount = $removeCommasAndCastToInt($allowanceProjectAmountsEdit[$id]);

        //         $allowanceProject->save();
        //     }
        // }

        // 新しく追加された案件別手当を登録
        // $allowanceNames = $request->input('allowanceName');
        // $allowanceAmounts = $request->input('allowanceAmount');
        // if($allowanceNames){
        //     // 各プロジェクトと手当を処理
        //     foreach ($allowanceNames as $projectId => $names) {
        //         foreach ($names as $index => $name) {
        //             $amount = $allowanceAmounts[$projectId][$index] ?? null;

        //             AllowanceByProject::create([
        //                 'employee_id' => $employee->id,
        //                 'project_id' => $projectId,
        //                 'allowanceName' => $name,
        //                 'amount' => $removeCommasAndCastToInt($amount),
        //             ]);
        //         }
        //     }
        // }

        // 既存案件別手当の削除
        // $deleteId = $request->input('allowanceProjectDeleteId');
        // if($deleteId){
        //     foreach($deleteId as $id){
        //         $allowanceProject = AllowanceByProject::where('id', $id);
        //         $allowanceProject->delete();
        //     }
        // }

        // その他手当のデータを更新
        // $allowanceOtherNamesEdit = $request->input('allowanceOtherNameEdit');
        // $allowanceOtherAmountsEdit = $request->input('allowanceOtherAmountEdit');
        // if($allowanceOtherNamesEdit){
        //     foreach($allowanceOtherNamesEdit as $id => $value){
        //         $allowanceOther = AllowanceByOther::where('id', $id)
        //         ->first();

        //         $allowanceOther->allowanceName = $value;
        //         $allowanceOther->amount = $removeCommasAndCastToInt($allowanceOtherAmountsEdit[$id]);

        //         $allowanceOther->save();
        //     }
        // }

        // その他手当の登録
        // $allowanceOtherNames = $request->input('allowanceOtherName');
        // $allowanceOtherAmounts = $request->input('allowanceOtherAmount');
        // // 各手当名と金額を処理
        // if($allowanceOtherNames){
        //     foreach ($allowanceOtherNames as $index => $name) {
        //         $amount = $allowanceOtherAmounts[$index] ?? null;

        //         AllowanceByOther::create([
        //             'employee_id' => $employee->id,
        //             'allowanceName' => $name,
        //             'amount' => $removeCommasAndCastToInt($amount),
        //         ]);
        //     }
        // }

        // // 既存その他手当の削除
        // $deleteId = $request->input('allowanceOtherDeleteId');
        // if($deleteId){
        //     foreach($deleteId as $id){
        //         $allowanceOther = AllowanceByOther::where('id', $id);
        //         $allowanceOther->delete();
        //     }
        // }

        // 車両の変更があった場合、
        if($oldVehicleId != $employee->vehicle_id){
            // 既存登録されている車両を変更
            if($oldVehicleId){
                $vehicle = Vehicle::find($oldVehicleId);
                $vehicle->employee_id = null;
                $vehicle->save();
            }

            if($employee->vehicle_id != null){
                // 新規で登録されている車両を登録
                $newVehicle = Vehicle::find($employee->vehicle_id);
                $newVehicle->employee_id = $employee->id;
                $newVehicle->save();
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

    public function register($id)
    {
        $employee = Employee::find($id);
        $user = User::where('employee_id', $id)->first();

        return view('employee.register', compact('employee', 'user'));
    }

    public function registerStore(Request $request)
    {
        // バリデーションを追加
        $request->validate([
            'employee_id' => 'required|integer|exists:employees,id', // 既存の従業員IDであることを確認
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->employee_id . ',employee_id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::updateOrCreate(
            ['employee_id' => $request->employee_id],
            ['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'employee_id' => $request->employee_id, 'role' => 3]
        );

        return redirect()->route('employee.');
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
                'initials' => $row['initials'],
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
