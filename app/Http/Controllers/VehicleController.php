<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Company;
use App\Models\Employee;
use League\Csv\Reader;
use League\Csv\Statement;

use Barryvdh\DomPDF\Facade\Pdf;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        $vehiclesGroupByOwnerType = $vehicles->groupBy(function($vehicle){
            return $vehicle->ownership_type ? $vehicle->ownership_type : '未登録';
        });

        //所属先・従業員・未登録の順番にソート
        $sortOrder = ['App\Models\Company' => 1, 'App\Models\Employee' => 2, '未登録' => 3];
        $vehiclesGroupByOwnerSorted = $vehiclesGroupByOwnerType->sortBy(function ($group, $key) use ($sortOrder) {
            return $sortOrder[$key] ?? max($sortOrder) + 1;
        });

        // オーナーごとにグループ分け
        $vehiclesGroupedByOwner = $vehiclesGroupByOwnerSorted->map(function ($vehiclesInOwnerGroup) {
            return $vehiclesInOwnerGroup->groupBy(function ($vehicle) {
                // 車両タイプリレーションシップが存在する場合、その名前でグループ化
                // リレーションシップがロードされていない、または車両タイプがない場合は '未分類' として扱う
                return $vehicle->ownership ? $vehicle->ownership->name : '未登録';
            });
        });

        return view('vehicle.index', compact('vehiclesGroupedByOwner'));
    }

    public function allShow(Request $request)
    {
        // 絞り込みの所有者を受け取る
        $narrowOwnerArray = $request->narrowOwner;

        $vehicles = Vehicle::all();
        $vehiclesGroupByOwnerType = $vehicles->groupBy(function($vehicle){
            return $vehicle->ownership_type ? $vehicle->ownership_type : '未登録';
        });

        //所属先・従業員・未登録の順番にソート
        $sortOrder = ['App\Models\Company' => 1, 'App\Models\Employee' => 2, '未登録' => 3];
        $vehiclesGroupByOwnerSorted = $vehiclesGroupByOwnerType->sortBy(function ($group, $key) use ($sortOrder) {
            return $sortOrder[$key] ?? max($sortOrder) + 1;
        });

        // オーナーごとにグループ分け
        $vehiclesGroupedByOwner = $vehiclesGroupByOwnerSorted->map(function ($vehiclesInOwnerGroup) {
            return $vehiclesInOwnerGroup->groupBy(function ($vehicle) {
                // 車両タイプリレーションシップが存在する場合、その名前でグループ化
                // リレーションシップがロードされていない、または車両タイプがない場合は '未登録' として扱う
                return $vehicle->ownership ? $vehicle->ownership->name : '未登録';
            });
        });

        // オーナー配列
        $ownerArray = [];
        foreach($vehiclesGroupByOwnerSorted as $ownerType => $inVehilces){
            foreach($inVehilces as $inVehicle){
                if($inVehicle->ownership){
                    if(!in_array($inVehicle->ownership->name, $ownerArray)){
                        $ownerArray[] = $inVehicle->ownership->name;
                    }
                }
            }
        }
        $ownerArray[] = '未登録'; //最後の未登録を追加
        // checkboxでなにもチェックされなかったら、全オーナーを代入
        if($narrowOwnerArray == null){
            $narrowOwnerArray = $ownerArray;
        }
        return view('vehicle.allShow', compact('vehiclesGroupedByOwner', 'ownerArray', 'narrowOwnerArray'));
    }

    public function downloadPdf(Request $request)
    {
        $narrowOwnerArray = $request->narrowOwnerName;

        $vehicles = Vehicle::all();
        $vehiclesGroupByOwnerType = $vehicles->groupBy(function($vehicle){
            return $vehicle->ownership_type ? $vehicle->ownership_type : '未登録';
        });

        //所属先・従業員・未登録の順番にソート
        $sortOrder = ['App\Models\Company' => 1, 'App\Models\Employee' => 2, '未登録' => 3];
        $vehiclesGroupByOwnerSorted = $vehiclesGroupByOwnerType->sortBy(function ($group, $key) use ($sortOrder) {
            return $sortOrder[$key] ?? max($sortOrder) + 1;
        });

        // オーナーごとにグループ分け
        $vehiclesGroupedByOwner = $vehiclesGroupByOwnerSorted->map(function ($vehiclesInOwnerGroup) {
            return $vehiclesInOwnerGroup->groupBy(function ($vehicle) {
                // 車両タイプリレーションシップが存在する場合、その名前でグループ化
                // リレーションシップがロードされていない、または車両タイプがない場合は '未登録' として扱う
                return $vehicle->ownership ? $vehicle->ownership->name : '未登録';
            });
        });

        $pdf = PDF::loadView('vehicle.downloadPdf', compact('vehiclesGroupedByOwner', 'narrowOwnerArray'));

        $fileName = "車両一覧.pdf";

        return $pdf->download($fileName); //生成されるファイル名
    }

    public function create()
    {
        $companies = Company::all();
        $employees = Employee::all();

        // すでに使用者として登録してある従業員を配列に格納
        $vehicleUsers = Vehicle::whereNotNull('employee_id')->get();
        $vehicleUserArray = [];
        foreach($vehicleUsers as $vehicleUser){
            $vehicleUserArray[] = $vehicleUser->employee_id;
        }

        return view('vehicle.create', compact('companies', 'employees', 'vehicleUserArray'));
    }

    public function store(Request $request)
    {
        $vehicle = Vehicle::create([
            'place_name' => $request->place_name,
            'class_number' => $request->class_number,
            'hiragana' => $request->hiragana,
            'number' => $request->number,
            'vehicle_type' => $request->vehicle_type,
            'category' => $request->category,
            'brand_name' => $request->brand_name,
            'model' => $request->model,
            'inspection_expiration_date' => $request->inspection_expiration_date,
            'ownership_type' => $request->ownership_type,
            'employee_id' => $request->employee_id,
        ]);

        if($request->ownership_type == 'App\Models\Company'){
            $owner_id = $request->owner_company_id;
        }else{
            $owner_id = $request->owner_employee_id;
        }
        $vehicle->ownership_id = $owner_id;
        $vehicle->save();

        // 使用者が月リースを選択してる場合、使用車両を変更
        $employee = Employee::find($request->employee_id);
        if($employee->vehicle_rental_type == 1){
            $employee->vehicle_id = $vehicle->id;
            $employee->save();
        }

        return redirect()->route('vehicle.');
    }

    public function edit($id)
    {
        $findVehicle = Vehicle::find($id);
        $companies = Company::all();
        $employees = Employee::all();

        $findVehicleUsingEmployeeId = $findVehicle->employee_id;

        // すでに使用者として登録してある従業員を配列に格納
        $vehicleUsers = Vehicle::whereNotNull('employee_id')->get();
        $vehicleUserArray = [];
        foreach($vehicleUsers as $vehicleUser){
            // 取得した車両の使用してる従業員のidは配列には格納しない
            if($findVehicleUsingEmployeeId != $vehicleUser->employee_id){
                $vehicleUserArray[] = $vehicleUser->employee_id;
            }
        }

        return view('vehicle.edit', compact('findVehicle', 'companies', 'employees', 'vehicleUserArray'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        $vehicle->place_name = $request->place_name;
        $vehicle->class_number = $request->class_number;
        $vehicle->hiragana = $request->hiragana;
        $vehicle->number = $request->number;
        $vehicle->vehicle_type = $request->vehicle_type;
        $vehicle->category = $request->category;
        $vehicle->brand_name = $request->brand_name;
        $vehicle->model = $request->model;
        $vehicle->inspection_expiration_date = $request->inspection_expiration_date;
        $vehicle->ownership_type = $request->ownership_type;
        $vehicle->employee_id = $request->employee_id;
        if($request->ownership_type == 'App\Models\Company'){
            $owner_id = $request->owner_company_id;
        }else{
            $owner_id = $request->owner_employee_id;
        }
        $vehicle->ownership_id = $owner_id;
        $vehicle->save();

        // 使用者が月リースを選択してる場合、使用車両を変更
        $employee = Employee::find($request->employee_id);
        if($employee->vehicle_rental_type == 1){
            $employee->vehicle_id = $vehicle->id;
            $employee->save();
        }

        return redirect()->route('vehicle.');
    }

    public function delete($id)
    {
        try{
            $vehicle = Vehicle::find($id);

            $vehicle->delete();

            return redirect()->route('vehicle.');
        }catch (\Exception $e){
            return redirect()->route('vehicle.')->with('alert', 'シフトに登録されている車両は現在は削除できない仕様にしてあります。ご了承ください。');
        }
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            Vehicle::create([
                'number' => $row['number'],
                'company_id' => $row['company_id']
            ]);
        }

        return redirect()->route('vehicle.');
    }
}
