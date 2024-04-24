<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\ShiftProjectVehicle;
use App\Models\ProjectEmployeePayment;
use App\Models\AllowanceByOther;
use App\Models\AllowanceByProject;
use App\Models\ProjectHoliday;

use Illuminate\Database\Console\DumpCommand;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;
use Carbon\Carbon;
use DateTime;
use Svg\Tag\Rect;
use Symfony\Component\VarDumper\VarDumper;
use Illuminate\Support\Facades\Storage;
use League\Csv\CharsetConverter;
use Yasumi\Yasumi;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;

use function PHPUnit\Framework\isEmpty;

class ShiftController extends Controller
{
    public function index()
    {

        // 現在の日付を取得
        $date = Carbon::today();
        $holidays = $this->getHoliday($date->format('Y'));

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        // その週の月曜日を取得（新しいインスタンスを作成）
        $monday = $date->copy()->startOfWeek();

        // その週の日曜日を取得（新しいインスタンスを作成）
        $sunday = $date->copy()->endOfWeek();


        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereNotNull('employee_id')
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function ($shift) {
            return $shift->employee_id;
        });
        // 従業員IDでグループ化し、登録されている従業員を先に、登録されていない従業員を後にソート
        $sortedShiftDataByEmployee = $shifts->groupBy('employee_id')->sortBy(function ($group, $key) {
            // グループ内の最初のシフトから従業員を取得
            $employee = $group->first()->employee;
            if ($employee) {
                // 従業員が登録されている場合、会社IDを返し、昇順にソート
                return $employee->company_id ?? PHP_INT_MAX; // 従業員が会社に所属していない場合、大きな値を割り当て
            } else {
                // 従業員が登録されていない場合、非常に大きな値を返して、リストの最後に配置
                return PHP_INT_MAX;
            }
        }, $preserveKeys = true);


        $unShifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->whereNull('employee_id')
        ->get();
        // dd($unShifts);
        $shiftDataByUnEmployee = $unShifts->groupBy(function ($unShift) {
            return $unShift->unregistered_employee;
        });
        // dd($shiftDataByUnEmployee);

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

        $payments = ProjectEmployeePayment::all();

        // 同じに日に同一の車両が複数使用されてた場合の車両を格納
        $MultipleDailyUsesVehiclesArray = $this->findMultipleDailyUsesVehicles($startOfWeek,$endOfWeek);

        $user = auth()->user();
        if($user->role == 1){
            return view('shift.index', compact('shiftDataByEmployee', 'shiftDataByUnEmployee', 'sortedShiftDataByEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates', 'holidays', 'MultipleDailyUsesVehiclesArray'));
        }else{
            return redirect()->route('shift.employeeShowShift');
        }
    }

    public function selectWeek(Request $request)
    {
        // リクエストからdateを取得、なければセッションから取得、それでもなければ今日の日付を使用
        $dateInput = $request->input('date') ?? session('date', Carbon::today()->toDateString());
        $date = new Carbon($dateInput);
        $action = $request->input('action');

        if ($action == 'previous') {
            $date->subDay();
        } elseif ($action == 'next') {
            $date->addDay();
        }

        // 週の始まり（月曜日）と終わり（日曜日）を取得
        $startOfWeek = $date->startOfWeek()->toDateString();
        $endOfWeek = $date->endOfWeek()->toDateString();

        // 祝日を取得
        $holidays = $this->getHoliday($date->format('Y'));

        // その週の月曜日を取得（新しいインスタンスを作成）
        $monday = $date->copy()->startOfWeek();

        // その週の日曜日を取得（新しいインスタンスを作成）
        $sunday = $date->copy()->endOfWeek();

        $this->findMultipleDailyUsesVehicles($startOfWeek, $endOfWeek);

        // 登録従業員シフト抽出
        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereNotNull('employee_id')
            ->get();
        $shiftDataByEmployee = $shifts->groupBy(function ($shift) {
            return $shift->employee_id;
        });
        // 従業員IDでグループ化し、登録されている従業員を先に、登録されていない従業員を後にソート
        $sortedShiftDataByEmployee = $shifts->groupBy('employee_id')->sortBy(function ($group, $key) {
            // グループ内の最初のシフトから従業員を取得
            $employee = $group->first()->employee;
            if ($employee) {
                // 従業員が登録されている場合、会社IDを返し、昇順にソート
                return $employee->company_id ?? PHP_INT_MAX; // 従業員が会社に所属していない場合、大きな値を割り当て
            } else {
                // 従業員が登録されていない場合、非常に大きな値を返して、リストの最後に配置
                return PHP_INT_MAX;
            }
        }, $preserveKeys = true);

        // 未登録従業員シフト抽出
        $unShifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->whereNull('employee_id')
        ->get();
        $shiftDataByUnEmployee = $unShifts->groupBy(function ($unShift) {
            return $unShift->unregistered_employee;
        });

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

        // 同じに日に同一の車両が複数使用されてた場合の車両を格納
        $MultipleDailyUsesVehiclesArray = $this->findMultipleDailyUsesVehicles($startOfWeek,$endOfWeek);

        $payments = ProjectEmployeePayment::whereNotNull('amount')->get();

        $page = $request->input('witch') ?? session('page');

        $user = auth()->user();

        if ($page) {
            if ($page == 'page01') {
                return view('shift.index', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates', 'holidays', 'MultipleDailyUsesVehiclesArray'));
            } elseif ($page == 'page02') {
                return view('shift.employeeShowShift', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates', 'holidays', 'MultipleDailyUsesVehiclesArray'));
            } elseif ($page == 'page03') {
                return view('shift.employeePriceShift', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates', 'holidays'));
            } elseif ($page == 'page04') {
                return view('shift.projectPriceShift', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates', 'holidays'));
            } elseif ($page == 'page05') {
                $shiftDataByDay = $shifts->groupBy(function ($shift) {
                    return $shift->date;
                });

                $unregistered_project = [];
                foreach ($shiftDataByDay as $date => $shiftData) {
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

                return view('shift.projectCountShift', compact('shifts', 'unregistered_project', 'projects', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates','holidays'));
            } elseif ($page == 'page06') {
                $projects = Project::all();
                $vehicles = Vehicle::all();
                $clients = Client::where('id', '!=', 1)->get();
                return view('shift.edit', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'clients', 'projects', 'vehicles', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates','holidays', 'MultipleDailyUsesVehiclesArray'));
            }
        } else {
            if($user->role <= 10){
                return view('shift.index', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates','holidays'));
            }else if($user->role > 10){
                return view('shift.employeeShowShift', compact('shiftDataByEmployee', 'sortedShiftDataByEmployee', 'shiftDataByUnEmployee', 'payments', 'startOfWeek', 'endOfWeek', 'monday', 'sunday', 'convertedDates', 'holidays'));
            }
        }
    }

    public function findMultipleDailyUsesVehicles($startOfWeek, $endOfWeek)
    {
        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->get();
        $shiftsByDay = $shifts->groupBy(function ($shift){
            return $shift->date;
        });

        // 複数使用されている車両を日にちごとに格納
        $MultipleDailyUsesVehiclesArray = [];
        foreach($shiftsByDay as $date => $shifts) { // $shiftsは、1日の全従業員のシフト
            foreach($shifts as $shiftIndex => $shift) { // $shiftは、特定の従業員のシフト
                if(!isset($MultipleDailyUsesVehiclesArray[$date])) { //keyをセット
                    $MultipleDailyUsesVehiclesArray[$date] = [];
                }
                foreach($shift->projectsVehicles as $spv) {
                    if($spv->vehicle_id) {
                        if($spv->vehicle->number == '自車') continue;

                        // 内回り
                        foreach($shifts as $innerShiftIndex => $innerShift) {
                            if($shiftIndex == $innerShiftIndex) continue;
                            foreach($innerShift->projectsVehicles as $innerSpv) {
                                if($innerSpv->vehicle_id && $spv->vehicle_id == $innerSpv->vehicle_id) {
                                    // ここで、既にそのvehicle_idが配列に含まれているかチェック
                                    if(!in_array($spv->vehicle_id, $MultipleDailyUsesVehiclesArray[$date])) {
                                        $MultipleDailyUsesVehiclesArray[$date][] = $spv->vehicle_id;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $MultipleDailyUsesVehiclesArray;
    }

    public function store(Request $request)
    {
        $id = $request->setId;

        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::create([
            'shift_id' => $id
        ]);

        // 「保存」ボタンがクリックされた時の処理
        if ($request->createProjectRadio == 0) {
            $shiftMiddle->project_id = $request->projectSelect;
            $shiftMiddle->unregistered_project = null;
        } else {
            if($request->clientSwitch == 0){
                $project = Project::create([
                    'client_id' => $request->clientExistingId,
                    'name' => $request->projectInput,
                    'payment_type' => 1,
                ]);
            }else if($request->clientSwitch == 1){
                $client = Client::create([
                    'name' => $request->clientName,
                    'pdfName' => $request->clientPdfName,
                ]);
                $project = Project::create([
                    'client_id' => $client->id,
                    'name' => $request->projectInput,
                    'payment_type' => 1,
                ]);
            }
            $shiftMiddle->project_id = $project->id;
            ProjectHoliday::create([
                'project_id' => $project->id
            ]);
            $employees = Employee::all();
            foreach($employees as $employee){
                ProjectEmployeePayment::create([
                    'employee_id' => $employee->id,
                    'project_id' => $project->id,
                    'amount' => null,
                ]);
            }
        }

        if ($request->createVehicleRadio == 0) {
            $shiftMiddle->vehicle_id = $request->vehicleSelect;
            $shiftMiddle->unregistered_vehicle = null;
        } else {
            $shiftMiddle->unregistered_vehicle = $request->vehicleInput;
            // 未登録の方はnullを保存
            $shiftMiddle->vehicle_id = null;
        }

        if ($request->part == 0) {
            $shiftMiddle->time_of_day = 0;
        } else {
            $shiftMiddle->time_of_day = 1;
        }

        // 半角および全角カンマを除去し、intにキャストする関数
        $removeCommasAndCastToInt = function ($value) {
            $valueWithoutCommas = str_replace([',', '，'], '', $value);
            return (int)$valueWithoutCommas; // 文字列を整数型にキャスト
        };

        $shiftMiddle->retail_price = $removeCommasAndCastToInt($request->retailInput);
        $shiftMiddle->driver_price = $removeCommasAndCastToInt($request->salaryInput);
        $shiftMiddle->save();



        $date = $request->startOfWeek;

        return redirect()->route('shift.edit')->with([
            'date' => $date,
            'page' => 'page06'
        ]);
    }


    public function update(Request $request)
    {
        $id = $request->setId;

        // 対象のシフト詳細を取得
        $shiftMiddle = ShiftProjectVehicle::find($id);

        if ($request->input('action') == 'save') {
            // 「保存」ボタンがクリックされた時の処理
            if ($request->projectRadio == 0) {
                $shiftMiddle->project_id = $request->projectSelect;
                $shiftMiddle->unregistered_project = null;
            } else {
                $shiftMiddle->unregistered_project = $request->projectInput;
                // 未登録の方はnullを保存
                $shiftMiddle->project_id = null;
            }

            if ($request->vehicleRadio == 0) {
                $shiftMiddle->vehicle_id = $request->vehicleSelect;
                $shiftMiddle->unregistered_vehicle = null;
            } else {
                $shiftMiddle->unregistered_vehicle = $request->vehicleInput;
                // 未登録の方はnullを保存
                $shiftMiddle->vehicle_id = null;
            }

            // 半角および全角カンマを除去し、intにキャストする関数
            $removeCommasAndCastToInt = function ($value) {
                $valueWithoutCommas = str_replace([',', '，'], '', $value);
                return (int)$valueWithoutCommas; // 文字列を整数型にキャスト
            };

            $shiftMiddle->retail_price = $removeCommasAndCastToInt($request->retailInput);
            $shiftMiddle->driver_price = $removeCommasAndCastToInt($request->salaryInput);
            $shiftMiddle->save();
        } elseif ($request->input('action') == 'delete') {
            // 「削除」ボタンがクリックされた時の処理
            $shiftMiddle->delete();
        }

        $date = $request->startOfWeek;

        return redirect()->route('shift.edit')->with([
            'date' => $date, // 例として固定の日付を設定
            'page' => 'page06'
        ]);
    }

    public function bulkReflection(Request $request)
    {
        $reflection_type = $request->reflection_type;
        $startOfWeek = $request->startOfWeek;
        $endOfWeek = $request->endOfWeek;

        if($reflection_type == 'driverReflection'){
            $this->driverReflection($startOfWeek, $endOfWeek);
        }elseif($reflection_type == 'clientReflection'){
            $this->clientReflection($startOfWeek, $endOfWeek);
        }else{
            $this->driverReflection($startOfWeek, $endOfWeek);
            $this->clientReflection($startOfWeek, $endOfWeek);
        }

        return redirect()->route('shift.edit')->with([
            'date' => $startOfWeek, // 例として固定の日付を設定
            'page' => 'page06'
        ]);
    }

    public function driverReflection($startOfWeek, $endOfWeek)
    {
        // 登録従業員シフト抽出
        $shifts = Shift::with('employee', 'projectsVehicles.project', 'projectsVehicles.vehicle')
        ->whereBetween('date', [$startOfWeek, $endOfWeek])
        ->whereNotNull('employee_id')
        ->get();

        $shiftGroupByEmployee = $shifts->groupBy(function ($shift) {
            return $shift->employee_id;
        });

        foreach($shiftGroupByEmployee as $employeeId => $shifts){
            $employee = Employee::find($employeeId);
            if($employee == null) continue; //従業員が見つからない場合スキップ

            foreach($shifts as $shift){
                foreach($shift->projectsVehicles as $spv){
                    $shiftPv = ShiftProjectVehicle::find($spv->id);
                    $shiftPv->vehicle_rental_type = $employee->vehicle_rental_type;
                    $shiftPv->rental_vehicle_id = $employee->vehicle_id;
                    $shiftPv->save();
                }
            }
        }
    }

    public function clientReflection($startOfWeek, $endOfWeek)
    {
        $shiftPv = ShiftProjectVehicle::whereHas('shift', function ($query) use ($startOfWeek, $endOfWeek) {
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        })
        ->whereNotNull('project_id')
        ->get();
        // 案件ごとにグループ分け
        $shiftPvGroupByProject = $shiftPv->groupBy(function ($data) {
            return $data->project_id;
        });

        foreach($shiftPvGroupByProject as $projectId => $shiftPvs){
            // 案件を取得
            $project = Project::find($projectId);
            if($project == null) continue; //案件が見つからない場合スキップ

            foreach($shiftPvs as $shiftPv => $spv){
                // 従業員idを取得
                $employeeId = $spv->shift->employee ? $spv->shift->employee_id : null;
                // 給与の情報を抽出
                $employeePayment = ProjectEmployeePayment::where('employee_id', $employeeId)
                ->where('project_id', $projectId)
                ->first();
                if($employeePayment != null && $employeePayment->amount != 0){
                    $driver_price = $employeePayment->amount;
                }else{
                    $driver_price = $project->driver_price;
                }
                $spv->retail_price = $project->retail_price;
                $spv->driver_price = $driver_price;
                $spv->save();
            }
        }
    }

    public function csv()
    {
        $warning = session('warning');

        return view('shift.csv', compact('warning'));
    }

    public function csvImport(Request $request)
    {
        $getDate = '';
        $errorDate = true;
        try{
            DB::transaction(function () use ($request, &$getDate, &$errorDate) {
                $employees = Employee::all();
                $projects = Project::all();
                $vehicles = Vehicle::all();


                $path = $request->file('csv_file')->getRealPath();

                // CSVファイルを読み込む
                $csv = Reader::createFromPath($path, 'r');

                $records = [];
                $checkRecordStart = false;
                foreach ($csv as $record) {
                    // 担当者の文字列があるまで行をスキップ
                    if($record[0] == '担当者'){
                        $checkRecordStart = true;
                    }
                    if(!$checkRecordStart) continue;
                    // 各レコードから0番目の要素を削除 0番目が所属先の列のため
                    array_shift($record);
                    $records[] = $record;
                }

                // 日付の行を整形・取得
                $dateRow = $records[0];
                $tmpDate = "";
                foreach ($dateRow as $index => &$date) { // 参照による代入を使用
                    // ０番目は空のためスキップ
                    if ($index == 0) continue;
                    // 日付を格納
                    if($index % 2 != 0){
                        $tmpDate = $date;
                    }else{
                        $date = $tmpDate;
                    }
                }
                unset($date);

                // 日付が有効な日付か判定
                $formats = ['Ymd', 'Y-m-d', 'Y/m/d','Y-n-j', 'Y/n/j'];
                foreach($dateRow as $index => $dateStr){
                    // 0番目は空のためスキップ
                    if($index == 0) continue;
                    $isCheckFormat = false;
                    // 日付が空ならスキップ
                    if($dateStr == '') continue;
                    foreach ($formats as $format) {
                        $date = \DateTime::createFromFormat($format, $dateStr);
                        if ($date && $date->format($format) == $dateStr) {
                            $isCheckFormat = true;
                            break;
                        }
                    }
                    // 有効ではない日付があった場合リダイレクト
                    if(!$isCheckFormat){
                        $errorDate = false;
                    }
                }

                // リダイレクトように日付を取得
                $getDate = $dateRow[2];

                // すでに登録してある日付のシフトを削除
                $hasShift = Shift::whereIn('date', $dateRow)->get();
                if (!$hasShift->isEmpty()) {
                    foreach ($hasShift as $shift) {
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
                        if ($record[0] !== "") {
                            // 従業員名を格納
                            $employeeName = $record[0];
                        }

                        // 奇数行は案件が格納
                        if ($index % 2 != 0) {
                            // 日付ごとにデータを整理
                            foreach ($record as $colIndex => $value) {

                                // 0番目の列は従業員名なのでスキップ
                                if ($colIndex == 0) continue;

                                // 日付を取得
                                $date = $dateRow[$colIndex];

                                // 日付が設定されていない場合はスキップ
                                if (empty($date)) continue;

                                if ($colIndex % 2 != 0) { // 午前の案件
                                    // スラッシュで案件を分割
                                    $projectNames = explode('/', $value);
                                    foreach ($projectNames as $index => $projectName) {
                                        $cleanProjectName = $this->removeSpaces($projectName);
                                        $organizedData[$date][$employeeName][0][$index][] = $cleanProjectName;
                                    }
                                } else { // 午後の案件
                                    // スラッシュで案件を分割
                                    $projectNames = explode('/', $value);
                                    foreach ($projectNames as $index => $projectName) {
                                        $cleanProjectName = $this->removeSpaces($projectName);
                                        $organizedData[$date][$employeeName][1][$index][] = $cleanProjectName;
                                    }
                                }
                            }
                        } else { //偶数行は車両が格納
                            foreach ($record as $colIndex => $value) {
                                // 0番目の列は従業員名なのでスキップ
                                if ($colIndex == 0) continue;

                                // 日付を取得
                                $date = $dateRow[$colIndex];

                                // 日付が設定されていない場合はスキップ
                                if (empty($date)) continue;

                                if ($colIndex % 2 != 0) {
                                    // 午前の車両
                                    // スラッシュで案件を分割
                                    $value = $this->cleanString($value);
                                    $vehicleNumbers = explode('/', $value);
                                    foreach ($vehicleNumbers as $index => $vehicleNumber) {
                                        $organizedData[$date][$employeeName][0][$index][] = $vehicleNumber;
                                    }
                                } else {
                                    // 午後の車両
                                    // スラッシュで案件を分割
                                    $value = $this->cleanString($value);
                                    $vehicleNumbers = explode('/', $value);
                                    foreach ($vehicleNumbers as $index => $vehicleNumber) {
                                        $organizedData[$date][$employeeName][1][$index][] = $vehicleNumber;
                                    }
                                }
                            }
                        }
                    }
                }

                $employeeArray = [];

                foreach ($organizedData as $date => $employeeData) {
                    foreach ($employeeData as $employee_r => $row) {

                        //** 従業員登録 ****************************/

                        $employees = Employee::all();
                        $employeeIdTmp = null;

                        $cleanCsvEmployee = $this->removeSpaces($employee_r); // スペースなどを除去
                        $isEmployeeCheck = false; //登録済みの従業員なのか判定の変数

                        foreach ($employees as $employee) {
                            $cleanEmployee = $this->removeSpaces($employee->name);
                            // 登録済みの従業員
                            if ($cleanCsvEmployee === $cleanEmployee) {
                                $shift = Shift::create([
                                    'date' => $date,
                                    'employee_id' => $employee->id,
                                ]);
                                $isEmployeeCheck = true;
                                $employeeIdTmp = $employee->id;
                                if(!in_array($employee->name, $employeeArray)){
                                    $employeeArray[] = $employee->name;
                                }
                            }
                        }
                        // 未登録の従業員
                        if (!$isEmployeeCheck) {
                            $shift = Shift::create([
                                'date' => $date,
                                'unregistered_employee' => $employee_r,
                            ]);
                        }


                        /**
                         * $recordIndex→0には案件のデータ・1には車両のデータ
                         */

                        //** シフトに関する情報を登録 *******************/

                        foreach ($row as $index => $data) {
                            // 午前シフト情報登録
                            if ($index == 0) {
                                foreach ($data as $dataIndex => $record) {
                                    foreach ($record as $recordIndex => $recordData) {
                                        if (!$recordData == "") {
                                            if ($recordIndex == 0) {
                                                $projects = Project::all();
                                                $isProjectCheck = false;
                                                $projectIdTmp = null;
                                                foreach ($projects as $project) {
                                                    $projectName = ''; //登録されている案件名を格納する変数
                                                    $projectName = $this->removeSpaces($project->name); //案件名のスペースを除去

                                                    // tmpProjectNameには、【】が除去された案件名・charterProjectNameは【】が含まれていれば【】が含まれた案件名、そうでなければNULL
                                                    [$tmpProjectName, $initialProjectName] = $this->isCharterOrCsCheck($recordData);
                                                    if ($tmpProjectName === $projectName) {
                                                        $projectIdTmp = $project->id;

                                                        // 従業員の詳細のデータを取得
                                                        $employeeInfo = $this->getEmployeeInfo($employeeIdTmp, $projectIdTmp);

                                                        $middleShift = ShiftProjectVehicle::create([
                                                            'shift_id' => $shift->id,
                                                            'project_id' => $project->id,
                                                            'initial_project_name' => $initialProjectName,
                                                            'retail_price' => $employeeInfo[0],
                                                            'driver_price' => $employeeInfo[1],
                                                            // 'total_allowance' => $employeeInfo[2],
                                                            'vehicle_rental_type' => $employeeInfo[2],
                                                            'rental_vehicle_id' => $employeeInfo[3],
                                                            'time_of_day' => 0,
                                                        ]);
                                                        $isProjectCheck = true;
                                                        break;
                                                    }
                                                }
                                                if (!$isProjectCheck) {
                                                    // 従業員の詳細のデータを取得
                                                    $employeeInfo = $this->getEmployeeInfo($employeeIdTmp, $projectIdTmp);

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
                                            if ($recordIndex == 1) {
                                                $vehicles = Vehicle::all();
                                                $isVehicleCheck = false;
                                                foreach ($vehicles as $vehicle) {
                                                    $vehicleNumber = $this->cleanString($vehicle->number);
                                                    if ($recordData === $vehicleNumber) {
                                                        $createdMiddleShift = ShiftProjectVehicle::where('id', $middleShift->id)->first();
                                                        $createdMiddleShift->vehicle_id = $vehicle->id;
                                                        $createdMiddleShift->save();
                                                        $isVehicleCheck = true;
                                                    }
                                                }
                                                if (!$isVehicleCheck) {
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
                            if ($index == 1) {
                                foreach ($data as $dataIndex => $record) {
                                    foreach ($record as $recordIndex => $recordData) {
                                        if (!$recordData == "") {
                                            if ($recordIndex == 0) {
                                                $projects = Project::all();
                                                $isProjectCheck = false;
                                                $projectIdTmp = null;
                                                foreach ($projects as $project) {
                                                    $projectName = '';
                                                    $projectName = $this->removeSpaces($project->name);
                                                    [$tmpProjectName, $initialProjectName] = $this->isCharterOrCsCheck($recordData);
                                                    if ($tmpProjectName === $projectName) {
                                                        $projectIdTmp = $project->id;

                                                        // 従業員の詳細のデータを取得
                                                        $employeeInfo = $this->getEmployeeInfo($employeeIdTmp, $projectIdTmp);

                                                        $middleShift = ShiftProjectVehicle::create([
                                                            'shift_id' => $shift->id,
                                                            'project_id' => $project->id,
                                                            'initial_project_name' => $initialProjectName,
                                                            'retail_price' => $employeeInfo[0],
                                                            'driver_price' => $employeeInfo[1],
                                                            // 'total_allowance' => $employeeInfo[2],
                                                            'vehicle_rental_type' => $employeeInfo[2],
                                                            'rental_vehicle_id' => $employeeInfo[3],
                                                            'time_of_day' => 1,
                                                        ]);
                                                        $isProjectCheck = true;
                                                        break;
                                                    }
                                                }
                                                if (!$isProjectCheck) {
                                                    // 従業員の詳細のデータを取得
                                                    $employeeInfo = $this->getEmployeeInfo($employeeIdTmp, $projectIdTmp);

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
                                            if ($recordIndex == 1) {
                                                $vehicles = Vehicle::all();
                                                $isVehicleCheck = false;
                                                foreach ($vehicles as $vehicle) {
                                                    $vehicleNumber = $this->cleanString($vehicle->number);
                                                    if ($recordData === $vehicleNumber) {
                                                        $createdMiddleShift = ShiftProjectVehicle::where('id', $middleShift->id)->first();
                                                        $createdMiddleShift->vehicle_id = $vehicle->id;
                                                        $createdMiddleShift->save();
                                                        $isVehicleCheck = true;
                                                    }
                                                }
                                                if (!$isVehicleCheck) {
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

                // csvにはいない登録従業員のシフト登録
                foreach($dateRow as $index => $date){
                    if ($index % 2 == 0) continue;
                    if ($date == null) continue;
                    foreach($employees as $employee){
                        if(!in_array($employee->name, $employeeArray)){
                            $shift = Shift::create([
                                'date' => $date,
                                'employee_id' => $employee->id,
                            ]);
                        }
                    }
                }

                Storage::delete($path);
                $request->session()->forget('csv_file_path');
            });
        }catch(Throwable $e){
            if(!$errorDate){
                return redirect()->route('shift.csv')->with('warning', '有効ではないまたは形式が異なる日付が含まれています。');
            }
            return redirect()->route('shift.csv')->with('warning', 'csvファイルの内容が正しくありません。');
        }
        return redirect()->route('shift.selectWeek')->with([
            'date' => $getDate, // リダイレクト先でインポートしたい日付のシフトを表示させるため渡す
            'page' => 'page01'
        ]);

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

        $retail_price = null; //上代
        $driver_price = null; //給与
        // $total_allowance = null;//手当合計
        $vehicle_rental_type = null; //車両貸出形態
        $rental_vehicle_id = null; //貸出車両

        $project = Project::find($projectId);
        $employee = Employee::find($employeeId);

        // 上代の情報を抽出
        if ($project) {
            $retail_price = $project->retail_price;
        }

        // 給与の情報を抽出
        $employeePayment = ProjectEmployeePayment::where('employee_id', $employeeId)
            ->where('project_id', $projectId)
            ->first();
        if (!empty($employeePayment->driver_price)) {
            $driver_price = $employeePayment->driver_price;
        } else {
            if ($project) {
                $driver_price = $project->driver_price;
            }
        }

        // 車両の情報を抽出
        if ($employee) {
            $vehicle_rental_type = $employee->vehicle_rental_type;
            $rental_vehicle_id = $employee->vehicle_id;
        }

        return array($retail_price, $driver_price, $vehicle_rental_type, $rental_vehicle_id);
    }

    public function isCharterOrCsCheck($CheckProjectName)
    {
        $initialProjectName = null;
        // すべての種類のカッコとその内容を削除
        $modifiedVariableMixed = preg_replace('/【.*?】|[\[].*?[\]]|［.*?］/u', '', $CheckProjectName);
        if($modifiedVariableMixed != $CheckProjectName){
            $initialProjectName = $CheckProjectName;
        }

        return [$modifiedVariableMixed, $initialProjectName];
    }


    public function getHoliday($year)
    {
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');

        return $holidays;
    }
}
