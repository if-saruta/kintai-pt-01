<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="caleder">
            <form action="{{route('shift.selectWeekByEdit')}}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{$startOfWeek}}">
                <input type="hidden" name="action" value="previous">
                <button type="submit" class="prev-week action-weekBtn">
                    前の週
                </button>
            </form>
            <form action="{{route('shift.selectWeekByEdit')}}" class="calender-view" method="POST">
                @csrf
                <input type="date" id="date" name="date">
                <button type="submit" class="calender-view__button">
                    検索
                </button>
            </form>
            <form action="{{route('shift.selectWeekByEdit')}}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{$endOfWeek}}">
                <input type="hidden" name="action" value="next">
                <button type="submit" class="prev-week action-weekBtn">
                    次の週
                </button>
            </form>
        </div>
        @if (!$shiftDataByEmployee->isEmpty())
            <div>
                <div class="x-scroll">
                    <div class="shift-edit">
                        @foreach ($shiftDataByEmployee as $employeeId => $shiftData)
                            <?php $checkCnt = true?>
                                <div class="shift-edit__employee-shift">
                                    @foreach ($shiftData as $shift)
                                    <div class="">
                                        {{-- 日付表示 --}}
                                        <p class="shift-edit-date">{{$shift->date}}</p>
                                        <div class="shift-edit__employee-shift__day">
                                            {{-- 従業員表示 --}}
                                            @if ($checkCnt)
                                                <div class="shift-edit__employee">
                                                    @if ($shift->employee)
                                                        <p>{{$shift->employee->name}}</p>
                                                    @else
                                                        <p style="color:red;">{{$shift->unregistered_employee}}</p>
                                                    @endif
                                                </div>
                                                <?php $checkCnt = false; ?>
                                            @endif
                                            {{-- 1日のシフト表示 --}}
                                            <div class="shift-edit__day">
                                                <div class="shift-edit__day__time-of-day">
                                                    {{-- 午前 --}}
                                                    <div class="shift-edit__am-pm">
                                                        <p>午前</p>
                                                        {{-- 案件表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 0)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isProject = false?>
                                                                        @if ($spv->project)
                                                                            <p>{{$spv->project->name}}</p>
                                                                            <?php $isProject = true?>
                                                                        @elseif(!empty($spv->unregistered_project))
                                                                            <p style="color:red;">{{$spv->unregistered_project}}</p>
                                                                            <?php $isProject = true?>
                                                                        @endif
                                                                        @if(!$isProject)
                                                                            <p>案件未登録</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">午前の案件</p>
                                                                                    {{-- ラジオボタン --}}
                                                                                    <div>
                                                                                        <div class="flex items-center mb-4">
                                                                                            <input checked id="default-radio-1" type="radio" value="0" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                            <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">新規案件</label>
                                                                                        </div>
                                                                                        <div class="flex items-center">
                                                                                            <input id="default-radio-2" type="radio" value="1" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                            <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">既存案件</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        @if ($spv->project)
                                                                                            <?php $registerProject = $spv->project->name?>
                                                                                        @else
                                                                                            <?php $registerProject = $spv->unregistered_project?>
                                                                                        @endif
                                                                                        <input type="text" name="inputProject" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{$registerProject}}">
                                                                                    </div>
                                                                                    <div class="edit-project-input">
                                                                                        <select id="countries" name="selectProject" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                            <option value="">選択してください</option>
                                                                                            @foreach ($projects as $project)
                                                                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                            {{-- 削除フォーム --}}
                                                                            <form action="{{route('shift.delete',['id'=>$spv->id])}}" class="delete-form" method="POST">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <button type="submit" class="edit-button bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                                                                    削除する
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 車両表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 0)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isVehicle = false; ?>
                                                                        @if ($spv->vehicle)
                                                                            <p>NO.{{$spv->vehicle->number}}</p>
                                                                            <?php $isVehicle = true; ?>
                                                                        @elseif(!empty($spv->unregistered_vehicle))
                                                                            <p style="color:red;">NO.{{$spv->unregistered_vehicle}}</p>
                                                                            <?php $isVehicle = true; ?>
                                                                        @endif
                                                                        @if (!$isVehicle)
                                                                            <p>NO.車両未登録</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update-vehicle',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">午前の車両</p>
                                                                                    {{-- ラジオボタン --}}
                                                                                    <div>
                                                                                        <div class="flex items-center mb-4">
                                                                                            <input checked id="default-radio-1" type="radio" value="0" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                            <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">新規車両</label>
                                                                                        </div>
                                                                                        <div class="flex items-center">
                                                                                            <input id="default-radio-2" type="radio" value="1" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                            <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">既存車両</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        @if ($spv->vehicle)
                                                                                            <?php $registerVehicle = $spv->vehicle->number?>
                                                                                        @else
                                                                                            <?php $registerVehicle = $spv->unregistered_vehicle?>
                                                                                        @endif
                                                                                        <input type="text" name="inputVechile" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{$registerVehicle}}">
                                                                                    </div>
                                                                                    <div class="edit-project-input">
                                                                                        <select id="countries" name="selectVehicle" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                            <option value="">選択してください</option>
                                                                                            @foreach ($vehicles as $vehicle)
                                                                                                <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 上代表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 0)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isVehicle = false; ?>
                                                                        @if ($spv->retail_price)
                                                                            <p>R : {{$spv->retail_price}}円</p>
                                                                            <?php $isVehicle = true; ?>
                                                                        @endif
                                                                        @if (!$isVehicle)
                                                                            <p>R : 円</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update-retailPrice',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">
                                                                                        @if ($spv->project)
                                                                                            案件名 : {{$spv->project->name}}
                                                                                        @elseif(!empty($spv->unregistered_project))
                                                                                            案件名 : {{$spv->unregistered_project}}
                                                                                        @endif
                                                                                    </p>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        <input type="text" name="inputRetail" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="上代を記入">
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 給与表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 0)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isDirverPriceCheck = false;?>
                                                                        @if ($spv->driver_price)
                                                                            <p>D : {{$spv->driver_price}}円</p>
                                                                            <?php $isDirverPriceCheck = true;?>
                                                                        @endif
                                                                        @if (!$isDirverPriceCheck)
                                                                                <p>D : 円</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update-driverPrice',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">
                                                                                        @if ($spv->project)
                                                                                            案件名 : {{$spv->project->name}}
                                                                                        @elseif(!empty($spv->unregistered_project))
                                                                                            案件名 : {{$spv->unregistered_project}}
                                                                                        @endif
                                                                                    </p>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        <input type="text" name="inputDriver" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="給与を記入">
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 新規追加ボタン --}}
                                                        <div class="create-shift-btn js-project">
                                                            <p class="">新規作成</p>
                                                        </div>
                                                        {{-- 新規追加モーダル --}}
                                                        <div class="edit-form-wrap js-modal">
                                                            <span class="edit-form-wrap__bg form-bg"></span>
                                                            <div class="edit-form">
                                                                <form action="{{ route('shift.store',['id'=>$shift->id]) }}" method="POST" class="form">
                                                                    @csrf
                                                                    <input hidden value="0" name="time_of_day" type="text">
                                                                    <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                    <div class="edit-form__inner">
                                                                        <div class="edit-form__inner__shift-info">
                                                                            <p>{{$shift->date}}</p>
                                                                            @if ($shift->employee)
                                                                                <p>{{$shift->employee->name}}</p>
                                                                            @else
                                                                                <p>{{$shift->unregistered_employee}}</p>
                                                                            @endif
                                                                        </div>
                                                                        <p class="edit-form__inner__time-of_day">午前の案件</p>
                                                                        {{-- ラジオボタン --}}
                                                                        <div>
                                                                            <div class="flex items-center mb-4">
                                                                                <input checked id="default-radio-1" type="radio" value="0" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">新規案件</label>
                                                                            </div>
                                                                            <div class="flex items-center">
                                                                                <input id="default-radio-2" type="radio" value="1" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">既存案件</label>
                                                                            </div>
                                                                        </div>
                                                                        {{-- 入力 --}}
                                                                        <div class="edit-project-input radio-open">
                                                                            <input type="text" name="inputProject" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="案件を入力してください">
                                                                        </div>
                                                                        <div class="edit-project-input">
                                                                            <select id="countries" name="selectProject" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                <option value="">選択してください</option>
                                                                                @foreach ($projects as $project)
                                                                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <p style="color:red;" class="warning">必須項目です</p>
                                                                        <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                            新規作成する
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- 午後 --}}
                                                    <div class="shift-edit__am-pm">
                                                        <p>午後</p>
                                                        @foreach ($shift->projectsVehicles as $spv)
                                                            @if ($spv->time_of_day == 1)
                                                            {{-- 案件表示 --}}
                                                                <div class="shift-edit__day__time_of_day__project js-project">
                                                                    <?php $isProject = false?>
                                                                        @if ($spv->project)
                                                                            <p>{{$spv->project->name}}</p>
                                                                            <?php $isProject = true?>
                                                                        @elseif(!empty($spv->unregistered_project))
                                                                            <p style="color:red;">{{$spv->unregistered_project}}</p>
                                                                            <?php $isProject = true?>
                                                                        @endif
                                                                        @if(!$isProject)
                                                                            <p>案件未登録</p>
                                                                        @endif
                                                                </div>
                                                                {{-- モーダル表示 --}}
                                                                <div class="edit-form-wrap js-modal">
                                                                    <span class="edit-form-wrap__bg form-bg"></span>
                                                                    <div class="edit-form">
                                                                        <form action="{{ route('shift.update',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                            @csrf
                                                                            <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                            <div class="edit-form__inner">
                                                                                <div class="edit-form__inner__shift-info">
                                                                                    <p>{{$shift->date}}</p>
                                                                                    @if ($shift->employee)
                                                                                        <p>{{$shift->employee->name}}</p>
                                                                                    @else
                                                                                        <p>{{$shift->unregistered_employee}}</p>
                                                                                    @endif
                                                                                </div>
                                                                                <p class="edit-form__inner__time-of_day">午前の案件</p>
                                                                                {{-- ラジオボタン --}}
                                                                                <div>
                                                                                    <div class="flex items-center mb-4">
                                                                                        <input checked id="default-radio-1" type="radio" value="0" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">新規案件</label>
                                                                                    </div>
                                                                                    <div class="flex items-center">
                                                                                        <input id="default-radio-2" type="radio" value="1" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                        <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">既存案件</label>
                                                                                    </div>
                                                                                </div>
                                                                                {{-- 入力 --}}
                                                                                <div class="edit-project-input radio-open">
                                                                                    @if ($spv->project)
                                                                                        <?php $registerProject = $spv->project->name?>
                                                                                    @else
                                                                                        <?php $registerProject = $spv->unregistered_project?>
                                                                                    @endif
                                                                                    <input type="text" name="inputProject" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{$registerProject}}">
                                                                                </div>
                                                                                <div class="edit-project-input">
                                                                                    <select id="countries" name="selectProject" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                        <option value="">選択してください</option>
                                                                                        @foreach ($projects as $project)
                                                                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <p style="color:red;" class="warning">必須項目です</p>
                                                                                <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                    登録する
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                        {{-- 削除フォーム --}}
                                                                        <form action="{{route('shift.delete',['id'=>$spv->id])}}" class="delete-form" method="POST">
                                                                            @csrf
                                                                            <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                            <button type="submit" class="edit-button bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                                                                削除する
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        {{-- 車両表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 1)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isVehicle = false; ?>
                                                                        @if ($spv->vehicle)
                                                                            <p>NO.{{$spv->vehicle->number}}</p>
                                                                            <?php $isVehicle = true; ?>
                                                                        @elseif(!empty($spv->unregistered_vehicle))
                                                                            <p style="color:red;">NO.{{$spv->unregistered_vehicle}}</p>
                                                                            <?php $isVehicle = true; ?>
                                                                        @endif
                                                                        @if (!$isVehicle)
                                                                            <p>NO.未登録</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update-vehicle',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">午前の車両</p>
                                                                                    {{-- ラジオボタン --}}
                                                                                    <div>
                                                                                        <div class="flex items-center mb-4">
                                                                                            <input checked id="default-radio-1" type="radio" value="0" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                            <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">新規車両</label>
                                                                                        </div>
                                                                                        <div class="flex items-center">
                                                                                            <input id="default-radio-2" type="radio" value="1" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                            <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">既存車両</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        @if ($spv->vehicle)
                                                                                            <?php $registerVehicle = $spv->vehicle->number?>
                                                                                        @else
                                                                                            <?php $registerVehicle = $spv->unregistered_vehicle?>
                                                                                        @endif
                                                                                        <input type="text" name="inputVehicle" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{$registerVehicle}}">
                                                                                    </div>
                                                                                    {{-- セレクト --}}
                                                                                    <div class="edit-project-input">
                                                                                        <select id="countries" name="selectVehicle" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                            <option value="">選択してください</option>
                                                                                            @foreach ($vehicles as $vehicle)
                                                                                                <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 上代表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 1)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isVehicle = false; ?>
                                                                        @if ($spv->retail_price)
                                                                            <p>SR : {{$spv->retail_price}}円</p>
                                                                            <?php $isVehicle = true; ?>
                                                                        @endif
                                                                        @if (!$isVehicle)
                                                                            <p>R : 円</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update-retailPrice',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">
                                                                                        @if ($spv->project)
                                                                                            案件名 : {{$spv->project->name}}
                                                                                        @elseif(!empty($spv->unregistered_project))
                                                                                            案件名 : {{$spv->unregistered_project}}
                                                                                        @endif
                                                                                    </p>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        <input type="text" name="inputRetail" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="上代を記入">
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 給与表示 --}}
                                                        <div class="">
                                                            @foreach ($shift->projectsVehicles as $spv)
                                                                @if ($spv->time_of_day == 1)
                                                                    <div class="shift-edit__day__time_of_day__project js-project">
                                                                        <?php $isDirverPriceCheck = false;?>
                                                                        @if ($spv->driver_price)
                                                                            <p>D : {{$spv->driver_price}}円</p>
                                                                            <?php $isDirverPriceCheck = true;?>
                                                                        @endif
                                                                        @if (!$isDirverPriceCheck)
                                                                                <p>D : 円</p>
                                                                        @endif
                                                                    </div>
                                                                    {{-- モーダル表示 --}}
                                                                    <div class="edit-form-wrap js-modal">
                                                                        <span class="edit-form-wrap__bg form-bg"></span>
                                                                        <div class="edit-form">
                                                                            <form action="{{ route('shift.update-driverPrice',['id'=>$spv->id]) }}" method="POST" class="form">
                                                                                @csrf
                                                                                <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                                <div class="edit-form__inner">
                                                                                    <div class="edit-form__inner__shift-info">
                                                                                        <p>{{$shift->date}}</p>
                                                                                        @if ($shift->employee)
                                                                                            <p>{{$shift->employee->name}}</p>
                                                                                        @else
                                                                                            <p>{{$shift->unregistered_employee}}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                    <p class="edit-form__inner__time-of_day">
                                                                                        @if ($spv->project)
                                                                                            案件名 : {{$spv->project->name}}
                                                                                        @elseif(!empty($spv->unregistered_project))
                                                                                            案件名 : {{$spv->unregistered_project}}
                                                                                        @endif
                                                                                    </p>
                                                                                    {{-- 入力 --}}
                                                                                    <div class="edit-project-input radio-open">
                                                                                        <input type="text" name="inputDriver" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="給与を記入">
                                                                                    </div>
                                                                                    <p style="color:red;" class="warning">必須項目です</p>
                                                                                    <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                                        登録する
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- 新規追加ボタン --}}
                                                        <div class="create-shift-btn js-project">
                                                            <p class="">新規作成</p>
                                                        </div>
                                                        {{-- 新規追加モーダル --}}
                                                        <div class="edit-form-wrap js-modal">
                                                            <span class="edit-form-wrap__bg form-bg"></span>
                                                            <div class="edit-form">
                                                                <form action="{{ route('shift.store',['id'=>$shift->id]) }}" method="POST" class="form">
                                                                    @csrf
                                                                    <input hidden value="1" name="time_of_day" type="text">
                                                                    <input hidden value="{{$startOfWeek}}" name="date" type="date">
                                                                    <div class="edit-form__inner">
                                                                        <div class="edit-form__inner__shift-info">
                                                                            <p>{{$shift->date}}</p>
                                                                            @if ($shift->employee)
                                                                                <p>{{$shift->employee->name}}</p>
                                                                            @else
                                                                                <p>{{$shift->unregistered_employee}}</p>
                                                                            @endif
                                                                        </div>
                                                                        <p class="edit-form__inner__time-of_day">午後の案件</p>
                                                                        {{-- ラジオボタン --}}
                                                                        <div>
                                                                            <div class="flex items-center mb-4">
                                                                                <input checked id="default-radio-1" type="radio" value="0" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">新規案件</label>
                                                                            </div>
                                                                            <div class="flex items-center">
                                                                                <input id="default-radio-2" type="radio" value="1" name="switch" class="projectSwitch w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                                <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">既存案件</label>
                                                                            </div>
                                                                        </div>
                                                                        {{-- 入力 --}}
                                                                        <div class="edit-project-input radio-open">
                                                                            <input type="text" name="inputProject" id="first_name" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="案件を入力してください">
                                                                        </div>
                                                                        <div class="edit-project-input">
                                                                            <select id="countries" name="selectProject" class="edit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                <option value="">選択してください</option>
                                                                                @foreach ($projects as $project)
                                                                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <p style="color:red;" class="warning">必須項目です</p>
                                                                        <button type="submit" class="edit-button bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                                            新規作成する
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
        @else
            <p>{{$startOfWeek}}〜{{$endOfWeek}}のシフトはありません</p>
        @endif

    {{-- script --}}
    <script src="{{asset('js/edit-form.js')}}"></script>

</x-app-layout>
