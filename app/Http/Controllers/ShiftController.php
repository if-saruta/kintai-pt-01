<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\ShiftProjectVehicle;
use App\Models\ProjectEmployeePayment;
use App\Models\AllowanceByOther;
use App\Models\AllowanceByProject;
use Illuminate\Database\Console\DumpCommand;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;
use Carbon\Carbon;
use Svg\Tag\Rect;
use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class ShiftController extends Controller
{
    public function index()
    {
        // 現在の日付を取得
        $date = Carbon::today();

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function($shift){
            return $shift->employee_id;
        });

        $payments = ProjectEmployeePayment::all();

        return view('shift.index',compact('shiftDataByEmployee','payments','startOfWeek','endOfWeek'));
    }

    public function selectWeek(Request $request)
    {
        $date = new Carbon($request->input('date', Carbon::today()));
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function($shift){
            return $shift->employee_id;
        });

        $payments = ProjectEmployeePayment::all();

        return view('shift.index',compact('shiftDataByEmployee','payments','startOfWeek','endOfWeek'));
    }

    public function store(Request $request,$id)
    {
        if($request->switch == 1){ //既存の案件を選択した場合
            $shiftPV = ShiftProjectVehicle::create([
                'shift_id' => $id,
                'project_id' => $request->selectProject,
                'time_of_day' => $request->time_of_day,
            ]);
        }elseif($request->switch == 0){ //新案件を入力した場合
            $shiftPV = ShiftProjectVehicle::create([
                'shift_id' => $id,
                'unregistered_project' => $request->inputProject,
                'time_of_day' => $request->time_of_day,
            ]);
        }

        $shift = Shift::find($id);

        $shiftPV->vehicle_rental_type = $shift->employee->vehicle_rental_type;
        $shiftPV->rental_vehicle_id = $shift->employee->vehicle_id;
        $shiftPV->save();

        $date = $request->date;

        return redirect()->route('shift.edit', ['date' => $date]);
    }

    public function edit($date)
    {
        // 現在の日付を取得
        $date = Carbon::parse($date);

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.project.payments')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();

        $shiftDataByEmployee = $shifts->groupBy(function($shift){
            return $shift->employee_id;
        });
        $projects = Project::all();
        $vehicles = Vehicle::all();
        $payments = ProjectEmployeePayment::all();

        return view('shift.edit', compact('shiftDataByEmployee','projects','vehicles','payments','startOfWeek','endOfWeek'));
    }

    public function selectWeekByEdit(Request $request)
    {
        $date = new Carbon($request->input('date', Carbon::today()));
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.project.payments')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
            $shiftDataByEmployee = $shifts->groupBy(function($shift){
                return $shift->employee_id;
            });

        $projects = Project::all();
        $vehicles = Vehicle::all();
        $payments = ProjectEmployeePayment::all();

        return view('shift.edit', compact('shiftDataByEmployee','projects','vehicles','payments','startOfWeek','endOfWeek'));
    }

    public function update(Request $request,$id)
    {
        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::find($id);

        if($request->switch == 1){ //既存の案件を選択した場合
            $shiftMiddle->project_id = $request->selectProject;
            // 未登録の方はnullを保存
            $shiftMiddle->unregistered_project = null;
            $shiftMiddle->save();
        }elseif($request->switch == 0){ //新案件を入力した場合
            $shiftMiddle->unregistered_project = $request->inputProject;
            // 未登録の方はnullを保存
            $shiftMiddle->project_id = null;
            $shiftMiddle->save();
        }

        $date = $request->date;

        return redirect()->route('shift.edit', ['date' => $date]);
    }

    public function delete(Request $request,$id)
    {
        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::find($id);
        $shiftMiddle->delete();

        $date = $request->date;

        return redirect()->route('shift.edit', ['date' => $date]);
    }

    public function updateVehicle(Request $request,$id)
    {
        // dd('stop');
        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::find($id);

        if($request->switch == 1){ //既存の案件を選択した場合
            $shiftMiddle->vehicle_id = $request->selectVehicle;
            // 未登録の方はnullを保存
            $shiftMiddle->unregistered_vehicle = null;
            $shiftMiddle->save();
        }elseif($request->switch == 0){ //新案件を入力した場合
            $shiftMiddle->unregistered_vehicle = $request->inputVehicle;
            // 未登録の方はnullを保存
            $shiftMiddle->vehicle_id = null;
            $shiftMiddle->save();
        }

        $date = $request->date;

        return redirect()->route('shift.edit', ['date' => $date]);
    }

    public function updateRetailPrice(Request $request,$id)
    {
        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::find($id);

        // 変更内容を登録
        $shiftMiddle->retail_price = $request->inputRetail;
        $shiftMiddle->save();

        $date = $request->date;

        return redirect()->route('shift.edit', ['date' => $date]);
    }

    public function updateDriverPrice(Request $request,$id)
    {
        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::find($id);

        // 変更内容を登録
        $shiftMiddle->driver_price = $request->inputDriver;
        $shiftMiddle->save();

        $date = $request->date;

        return redirect()->route('shift.edit', ['date' => $date]);
    }

    public function employeeShowShift()
    {
        // 現在の日付を取得
        $date = Carbon::today();

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function($shift){
            return $shift->employee_id;
        });

        return view('shift.employeeShowShift', compact('shiftDataByEmployee','startOfWeek','endOfWeek'));
    }

    public function employeeShowShiftSelectWeek(Request $request)
    {
        $date = new Carbon($request->input('date', Carbon::today()));
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.project.payments')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
            $shiftDataByEmployee = $shifts->groupBy(function($shift){
                return $shift->employee_id;
            });

        $projects = Project::all();
        $vehicles = Vehicle::all();
        $payments = ProjectEmployeePayment::all();

        return view('shift.employeeShowShift', compact('shiftDataByEmployee','projects','vehicles','payments','startOfWeek','endOfWeek'));
    }

    public function employeePriceShift()
    {
        // 現在の日付を取得
        $date = Carbon::today();

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function($shift){
            return $shift->employee_id;
        });

        $payments = ProjectEmployeePayment::all();

        return view('shift.employeePriceShift', compact('shiftDataByEmployee','startOfWeek','endOfWeek','payments'));
    }

    public function employeePriceShiftSelectWeek(Request $request)
    {
        $date = new Carbon($request->input('date', Carbon::today()));
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.project.payments')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
            $shiftDataByEmployee = $shifts->groupBy(function($shift){
                return $shift->employee_id;
            });

        $projects = Project::all();
        $vehicles = Vehicle::all();
        $payments = ProjectEmployeePayment::all();

        return view('shift.employeePriceShift', compact('shiftDataByEmployee','projects','vehicles','payments','startOfWeek','endOfWeek'));
    }

    public function projectPriceShift()
    {
        // 現在の日付を取得
        $date = Carbon::today();

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function($shift){
            return $shift->employee_id;
        });

        $payments = ProjectEmployeePayment::all();

        return view('shift.projectPriceShift', compact('shiftDataByEmployee','startOfWeek','endOfWeek','payments'));
    }

    public function projectPriceShiftSelectWeek(Request $request)
    {
        $date = new Carbon($request->input('date', Carbon::today()));
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle','projectsVehicles.project.payments')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
            $shiftDataByEmployee = $shifts->groupBy(function($shift){
                return $shift->employee_id;
            });

        $projects = Project::all();
        $vehicles = Vehicle::all();
        $payments = ProjectEmployeePayment::all();

        return view('shift.projectPriceShift', compact('shiftDataByEmployee','projects','vehicles','payments','startOfWeek','endOfWeek'));
    }

    public function projectCount()
    {

        // 現在の日付を取得
        $date = Carbon::today();

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            // ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
        $shiftDataByDay = $shifts->groupBy(function($shift){
            return $shift->date;
        });

        $unregistered_project = [];
        foreach($shiftDataByDay as $date => $shiftData){
            foreach($shiftData as $shift){
                foreach($shift->projectsVehicles as $spv){
                    if(!$spv->project){
                        if($spv->unregistered_project){
                            if(!in_array($spv->unregistered_project, $unregistered_project)){
                                $unregistered_project[] = $spv->unregistered_project;
                            }
                        }
                    }
                }
            }
        }

        $projects = Project::all();


        return view('shift.projectCountShift',compact('shiftDataByDay','projects','unregistered_project','startOfWeek','endOfWeek'));
    }

    public function projectCountSelectWeek(Request $request)
    {
        $date = new Carbon($request->input('date', Carbon::today()));
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        $shifts = Shift::with('employee','projectsVehicles.project','projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'asc')
            ->get();
        $shiftDataByDay = $shifts->groupBy(function($shift){
            return $shift->date;
        });

        $unregistered_project = [];
        foreach($shiftDataByDay as $date => $shiftData){
            foreach($shiftData as $shift){
                foreach($shift->projectsVehicles as $spv){
                    if(!$spv->project){
                        if($spv->unregistered_project){
                            if(!in_array($spv->unregistered_project, $unregistered_project)){
                                $unregistered_project[] = $spv->unregistered_project;
                            }
                        }
                    }
                }
            }
        }

        $projects = Project::all();


        return view('shift.projectCountShift',compact('shiftDataByDay','projects', 'unregistered_project','startOfWeek','endOfWeek'));
    }




    public function employeeShift()
    {

        return view('shift.employeeShift');
    }


    public function shiftImport()
    {
        return view('shiftImport.index');
    }

    public function shiftConfirmCsv(Request $request)
    {
        // CSVファイルのパスを取得
        $path = $request->file('csv_file')->getRealPath();

        // CSVファイルを読み込む
        $csv = Reader::createFromPath($path, 'r');
        // $headers = $csv->fetchOne();

        $records = [];
        foreach ($csv as $record) {
            // 各レコードから0番目の要素を削除
            array_shift($record);
            $records[] = $record;
        }

        // 日付の行を整形・取得
        $dateRow = $records[0];
        $tmpDate = "";
        foreach($dateRow as $index => &$date){ // 参照による代入を使用
            // ０番目は空のためスキップ
            if($index == 0) continue;
            // 日付を格納
            if(empty($date)){
                $date = $tmpDate;
            }else{
                $tmpDate = $date;
            }
        }
        unset($date);

        $hasShift = Shift::whereIn('date', $dateRow)->get()->pluck('date');
        // すでに登録されている日付が含まれている場合
        if(!$hasShift->isEmpty()){
            // ファイルを一時的に保存し、そのパスを取得
            $path = $request->file('csv_file')->store('temp');

            // 一時保存したファイルのパスをセッションに保存
            $request->session()->put('csv_file_path', $path);

            // 確認画面へリダイレクト
            return view('shiftImport.confirm');

        }else{
            return $this->shiftImportCsv($request);
        }
    }

    public function shiftImportCsv(Request $request)
    {

        $employees = Employee::all();
        $projects = Project::all();
        $vehicles = Vehicle::all();

        // if($request->input('confirm') === 'ok'){
        //     // セッションからCSVファイルのパスを取得
        //     $path = $request->session()->get('csv_file_path');
        //     $fullPath = Storage::path($path);
        // }else if($request->input('confirm') === 'キャンセル'){
        //     return view('shiftImport.index');
        // }else{
        //     // CSVファイルのパスを取得
        //     $fullPath = $request->file('csv_file')->getRealPath();
        // }
        $path = $request->file('csv_file')->getRealPath();

        // CSVファイルを読み込む
        $csv = Reader::createFromPath($path, 'r');

        $records = [];
        foreach ($csv as $record) {
            // 各レコードから0番目の要素を削除
            array_shift($record);
            $records[] = $record;
        }

        // 日付の行を整形・取得
        $dateRow = $records[0];
        $tmpDate = "";
        foreach($dateRow as $index => &$date){ // 参照による代入を使用
            // ０番目は空のためスキップ
            if($index == 0) continue;
            // 日付を格納
            if(empty($date)){
                $date = $tmpDate;
            }else{
                $tmpDate = $date;
            }
        }
        unset($date);

        // すでに登録してある日付のシフトを削除
        $hasShift = Shift::whereIn('date', $dateRow)->get();
        if(!$hasShift->isEmpty()){
            foreach($hasShift as $shift){
                $shift->delete();
            }
        }

        // シフトを格納する配列を初期化
        $organizedData = [];
        // 従業員名を格納する変数
        $employeeName = "";

        // レコードをDBに格納するために整形
        foreach ($records as $index => $record) {
            // インデックス0は日付のためスキップ
            if ($index !== 0) {
                // var_dump($index);
                if($record[0] !== ""){
                    // 従業員名を格納
                    $employeeName = $record[0];
                }

                // 奇数行は案件が格納
                if($index % 2 != 0){
                    // 日付ごとにデータを整理
                    foreach ($record as $colIndex => $value) {

                        // 0番目の列は従業員名なのでスキップ
                        if ($colIndex == 0) continue;

                        // 日付を取得
                        $date = $dateRow[$colIndex];

                        // 日付が設定されていない場合はスキップ
                        if (empty($date)) continue;

                        if($colIndex % 2 != 0){ // 午前の案件
                            // スラッシュで案件を分割
                            $projectNames = explode('/', $value);
                            foreach($projectNames as $index => $projectName){
                                $cleanProjectName = $this->removeSpaces($projectName);
                                $organizedData[$date][$employeeName][0][$index][] = $cleanProjectName;
                            }
                        }else{ // 午後の案件
                            // スラッシュで案件を分割
                            $projectNames = explode('/', $value);
                            foreach($projectNames as $index => $projectName){
                                $cleanProjectName = $this->removeSpaces($projectName);
                                $organizedData[$date][$employeeName][1][$index][] = $cleanProjectName;
                            }
                        }
                    }
                }else{ //偶数行は車両が格納
                    foreach ($record as $colIndex => $value) {
                        // 0番目の列は従業員名なのでスキップ
                        if ($colIndex == 0) continue;

                        // 日付を取得
                        $date = $dateRow[$colIndex];

                        // 日付が設定されていない場合はスキップ
                        if (empty($date)) continue;

                        if($colIndex % 2 != 0){
                            // 午前の車両
                            // スラッシュで案件を分割
                            $value = $this->cleanString($value);
                            $vehicleNumbers = explode('/', $value);
                            foreach($vehicleNumbers as $index => $vehicleNumber){
                                $organizedData[$date][$employeeName][0][$index][] = $vehicleNumber;
                            }
                        }else{
                            // 午後の車両
                            // スラッシュで案件を分割
                            $value = $this->cleanString($value);
                            $vehicleNumbers = explode('/', $value);
                            foreach($vehicleNumbers as $index => $vehicleNumber){
                                $organizedData[$date][$employeeName][1][$index][] = $vehicleNumber;
                            }
                        }
                    }
                }
            }
        }

        // デバック用
        // dd($organizedData);
        // dd('stop');

        foreach($organizedData as $date => $employeeData){
            foreach($employeeData as $employee_r => $row){

                //** 従業員登録 ****************************/

                $employees = Employee::all();
                $employeeIdTmp = null;

                $cleanCsvEmployee = $this->removeSpaces($employee_r);// スペースなどを除去
                $isEmployeeCheck = false;//登録済みの従業員なのか判定の変数

                foreach($employees as $employee){
                    $cleanEmployee = $this->removeSpaces($employee->name);
                    // 登録済みの従業員
                    if($cleanCsvEmployee === $cleanEmployee){
                        $shift = Shift::create([
                            'date' => $date,
                            'employee_id' => $employee->id,
                        ]);
                        $isEmployeeCheck = true;
                        $employeeIdTmp = $employee->id;
                    }
                }
                    // 未登録の従業員
                    if(!$isEmployeeCheck){
                        $shift = Shift::create([
                            'date' => $date,
                            'unregistered_employee' => $employee_r,
                        ]);
                    }


                /**
                 * $recordIndex→0には案件のデータ・1には車両のデータ
                 */

                //** シフトに関する情報を登録 *******************/

                foreach($row as $index => $data){
                    // 午前シフト情報登録
                    if($index == 0){
                        foreach($data as $dataIndex => $record){
                            foreach($record as $recordIndex => $recordData){
                                if(!$recordData == ""){
                                    if($recordIndex == 0){
                                        $projects = Project::all();
                                        $isProjectCheck = false;
                                        $projectIdTmp = null;
                                        foreach($projects as $project){
                                            $projectName = '';
                                            $projectName = $this->removeSpaces($project->name);
                                            if($recordData === $projectName){
                                                $projectIdTmp = $project->id;

                                                // 従業員の詳細のデータを取得
                                                $employeeInfo = $this->getEmployeeInfo($employeeIdTmp,$projectIdTmp);

                                                $middleShift = ShiftProjectVehicle::create([
                                                    'shift_id' => $shift->id,
                                                    'project_id' => $project->id,
                                                    'retail_price' => $employeeInfo[0],
                                                    'driver_price' => $employeeInfo[1],
                                                    // 'total_allowance' => $employeeInfo[2],
                                                    'vehicle_rental_type' => $employeeInfo[2],
                                                    'rental_vehicle_id' => $employeeInfo[3],
                                                    'time_of_day' => 0,
                                                ]);
                                                $isProjectCheck = true;
                                            }
                                        }
                                        if(!$isProjectCheck){
                                            // 従業員の詳細のデータを取得
                                            $employeeInfo = $this->getEmployeeInfo($employeeIdTmp,$projectIdTmp);

                                            $middleShift = ShiftProjectVehicle::create([
                                                'shift_id' => $shift->id,
                                                'unregistered_project' => $recordData,
                                                'retail_price' => $employeeInfo[0],
                                                'driver_price' => $employeeInfo[1],
                                                // 'total_allowance' => $employeeInfo[2],
                                                'vehicle_rental_type' => $employeeInfo[2],
                                                'rental_vehicle_id' => $employeeInfo[3],
                                                'time_of_day' => 0,
                                            ]);
                                        }
                                    }
                                    if($recordIndex == 1){
                                        $vehicles = Vehicle::all();
                                        $isVehicleCheck = false;
                                        foreach($vehicles as $vehicle){
                                            $vehicleNumber = $this->cleanString($vehicle->number);
                                            if($recordData === $vehicleNumber){
                                                $createdMiddleShift = ShiftProjectVehicle::where('id', $middleShift->id)->first();
                                                $createdMiddleShift->vehicle_id = $vehicle->id;
                                                $createdMiddleShift->save();
                                                $isVehicleCheck = true;
                                            }
                                        }
                                        if(!$isVehicleCheck){
                                            $createdMiddleShift = ShiftProjectVehicle::where('id', $middleShift->id)->first();
                                            $createdMiddleShift->unregistered_vehicle = $recordData;
                                            $createdMiddleShift->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // 午後シフト情報登録
                    if($index == 1){
                        foreach($data as $dataIndex => $record){
                            foreach($record as $recordIndex => $recordData){
                                if(!$recordData == ""){
                                    if($recordIndex == 0){
                                        $projects = Project::all();
                                        $isProjectCheck = false;
                                        $projectIdTmp = null;
                                        foreach($projects as $project){
                                            $projectName = '';
                                            $projectName = $this->removeSpaces($project->name);
                                            if($recordData === $projectName){
                                                $projectIdTmp = $project->id;

                                                // 従業員の詳細のデータを取得
                                                $employeeInfo = $this->getEmployeeInfo($employeeIdTmp,$projectIdTmp);


                                                $middleShift = ShiftProjectVehicle::create([
                                                    'shift_id' => $shift->id,
                                                    'project_id' => $project->id,
                                                    'retail_price' => $employeeInfo[0],
                                                    'driver_price' => $employeeInfo[1],
                                                    // 'total_allowance' => $employeeInfo[2],
                                                    'vehicle_rental_type' => $employeeInfo[2],
                                                    'rental_vehicle_id' => $employeeInfo[3],
                                                    'time_of_day' => 1,
                                                ]);
                                                $isProjectCheck = true;
                                            }
                                        }
                                        if(!$isProjectCheck){

                                            // 従業員の詳細のデータを取得
                                            $employeeInfo = $this->getEmployeeInfo($employeeIdTmp,$projectIdTmp);

                                            $middleShift = ShiftProjectVehicle::create([
                                                'shift_id' => $shift->id,
                                                'unregistered_project' => $recordData,
                                                'retail_price' => $employeeInfo[0],
                                                'driver_price' => $employeeInfo[1],
                                                // 'total_allowance' => $employeeInfo[2],
                                                'vehicle_rental_type' => $employeeInfo[2],
                                                'rental_vehicle_id' => $employeeInfo[3],
                                                'time_of_day' => 1,
                                            ]);
                                        }
                                    }
                                    if($recordIndex == 1){
                                        $vehicles = Vehicle::all();
                                        $isVehicleCheck = false;
                                        foreach($vehicles as $vehicle){
                                            $vehicleNumber = $this->cleanString($vehicle->number);
                                            if($recordData === $vehicleNumber){
                                                $createdMiddleShift = ShiftProjectVehicle::where('id', $middleShift->id)->first();
                                                $createdMiddleShift->vehicle_id = $vehicle->id;
                                                $createdMiddleShift->save();
                                                $isVehicleCheck = true;
                                            }
                                        }
                                        if(!$isVehicleCheck){
                                            $createdMiddleShift = ShiftProjectVehicle::where('id', $middleShift->id)->first();
                                            $createdMiddleShift->unregistered_vehicle = $recordData;
                                            $createdMiddleShift->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        Storage::delete($path);
        $request->session()->forget('csv_file_path');

        // 現在の日付を取得
        // $date = Carbon::today();

        // // 週の始まり（月曜日）と終わり（日曜日）を取得
        // $startOfWeek = $date->startOfWeek()->toDateString();
        // $endOfWeek = $date->endOfWeek()->toDateString();

        // return view('shift.index',compact('startOfWeek', 'endOfWeek'));
        return redirect()->route('shift.');
    }


    // テキストの半角・全角を排除
    public function removeSpaces($string)
    {
        // 半角スペースと全角スペースを除去
        $string = str_replace(["\r\n", "\r", "\n"], "", $string); // 全角スペースの除去
        $string = str_replace(" ", "", $string); // 半角スペースの除去
        $string = str_replace("　", "", $string); // 全角スペースの除去

        return $string;
    }

    // csvデータの車両番号を整形
    public function cleanString($string)
    {
        // 半角スペースと全角スペースを除去
        $string = str_replace(" ", "", $string); // 半角スペースの除去
        $string = str_replace("　", "", $string); // 全角スペースの除去

        $string = str_replace("No.", "", $string);

        return $string;
    }

    public function getEmployeeInfo($employeeId, $projectId)
    {

        $retail_price = null;//上代
        $driver_price = null;//給与
        // $total_allowance = null;//手当合計
        $vehicle_rental_type = null;//車両貸出形態
        $rental_vehicle_id = null;//貸出車両

        $project = Project::find($projectId);
        $employee = Employee::find($employeeId);

        // 上代の情報を抽出
        if($project){
            $retail_price = $project->retail_price;
        }

        // 給与の情報を抽出
        $employeePayment = ProjectEmployeePayment::where('employee_id', $employeeId)
        ->where('project_id', $projectId)
        ->first();
        if(!empty($employeePayment->driver_price)){
            $driver_price = $employeePayment->driver_price;
        }else{
            if($project){
                $driver_price = $project->driver_price;
            }
        }

        // 車両の情報を抽出
        if($employee){
            $vehicle_rental_type = $employee->vehicle_rental_type;
            $rental_vehicle_id = $employee->vehicle_id;
        }

        return array($retail_price, $driver_price, $vehicle_rental_type, $rental_vehicle_id);

    }

}

