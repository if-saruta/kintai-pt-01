<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <main class="main --shift-main">
        <div class="main__link-block --shift-link-block">
            <div class="main__link-block__tags">
                <a href="{{route('invoice.driverShift')}}" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.driverShift','invoice.searchShift','invoice.driver-edit-pdf') ? 'active' : '' }} link">
                        <span class="">ドライバー</span>
                    </button>
                </a>
                <a href="{{route('invoice.projectShift')}}" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.projectShift','invoice.searchProjectShift', 'invoice.project-edit-pdf') ? 'active' : '' }} link">
                        <span class="">案件</span>
                    </button>
                </a>
                <a href="{{route('invoice.charterShift')}}"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button class="{{ request()->routeIs('invoice.charterShift','invoice.findCharterShift') ? 'active' : '' }} link">
                        <span class="">チャーター</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="main__white-board --invoice-white-board">
            <div class="invoice">
                <div class="invoice__driver-shift">
                    <form action="{{ route('invoice.searchShift') }}" method="POST">
                        @csrf
                        <div class="select-area">
                            <div class="select-area__block">
                                <select name="year" id="" class="c-select year-select" required>
                                    {{-- <option value="">選択してください</option> --}}
                                    @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <label for="">月</label>
                            </div>
                            <div class="select-area__block name-block">
                                <select name="employeeId" id="" class="c-select name-select" required>
                                    <option value="">選択してください</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>
                    @if ($shifts !== null && !$shifts->isEmpty())
                        {{-- 手当モーダル --}}
                        <div class="allowance-modal-wrap" id="allowance-modal">
                            <span class="allowance-modal-wrap__bg allowanceModalBg"></span>
                            <div class="allowance-modal-wrap__modal">
                                <div class="allowance-modal-wrap__modal__inner">
                                    <div class="radio-area">
                                        <div class="radio-area__item">
                                            <input name="swichAllowance" class="allowanceRadio" type="radio" id="allowanceS" checked>
                                            <label for="allowanceS">手当選択</label>
                                        </div>
                                        <div class="radio-area__item">
                                            <input name="swichAllowance" class="allowanceRadio" type="radio" id="allowance">
                                            <label for="allowance">手当入力</label>
                                        </div>
                                    </div>
                                    <div class="input-area">
                                        <select name="" id="allowanceSelect" class="input-area__select radioRalation">
                                            <option value="">選択してください</option>
                                        </select>
                                        <input type="text" id="allowanceInput" class="input-area__input input radioRalation" placeholder="金額を入力">
                                    </div>
                                    <div class="allowance-modal-btn">
                                        <p class="">保存</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- 車両モーダル --}}
                        <div class="vehicle-modal-wrap" id="vehicleModal">
                            <span class="vehicle-modal-wrap__bg vehicleModalBg"></span>
                            <div class="vehicle-modal-wrap__modal">
                                <div class="vehicle-modal-wrap__modal__inner">
                                    <div class="radio-area">
                                        <div class="radio-area__item">
                                            <input name="swichVehicle" class="vehicleRadio" type="radio" id="vehicle-01" checked>
                                            <label for="vehicle-01">車両選択</label>
                                        </div>
                                        <div class="radio-area__item">
                                            <input name="swichVehicle" class="vehicleRadio" type="radio" id="vehicle-02">
                                            <label for="vehicle-02">車両入力</label>
                                        </div>
                                    </div>
                                    <div class="input-area">
                                        <select name="" id="vehicleSelect" class="input-area__select vehicleRadioRalation">
                                            <option value="">選択してください</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{$vehicle->number}}">{{$vehicle->number}}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" id="vehicleInput" class="input-area__input input vehicleRadioRalation" placeholder="金額を入力">
                                    </div>
                                    <div class="vehicle-modal-btn">
                                        <p class="">保存</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="info-pdf-wrap">
                                <div class="employee-date">
                                    <div class="employee-date__year-month">
                                        <p>{{$getYear}}年</p>
                                        <p>{{$getMonth}}月</p>
                                    </div>
                                    <div class="employee-date__employee">
                                        <p class="">{{$employeeName->name}}</p>
                                    </div>
                                </div>
                                <form action="{{route('invoice.driver-calendar-pdf')}}" method="POST">
                                    @csrf
                                    <button class="calendar-pdf-button">
                                        <p>カレンダーPDF</p>
                                    </button>
                                    <input hidden type="text" name="employee" value="{{$employeeName->id}}">
                                    <input hidden type="text" name="year" value="{{$getYear}}">
                                    <input hidden type="text" name="month" value="{{$getMonth}}">
                                </form>
                            </div>
                            <div class="scroll-x">
                                <form action="{{route('invoice.driverShiftUpdate')}}" method="POST">
                                    @csrf
                                    <div class="driver-shift">
                                        <input hidden type="text" name="employeeId" value="{{$employeeName->id}}">
                                        <input hidden type="text" name="year" value="{{$getYear}}">
                                        <input hidden type="text" name="month" value="{{$getMonth}}">
                                        <button class="save-btn">
                                            <p class="">保存</p>
                                        </button>
                                        <div class="driver-shift__head">
                                            <div class="driver-shift__head__item --date">
                                                <p class="">日付</p>
                                            </div>
                                            <div class="driver-shift__head__item --project">
                                                <p class="">案件名</p>
                                            </div>
                                            <div class="driver-shift__head__item --common amountRow">
                                                <p class="">金額</p>
                                            </div>
                                            <div class="driver-shift__head__item --common">
                                                <p class="">手当</p>
                                            </div>
                                            <div class="driver-shift__head__item --common expresswayRow">
                                                <p class="">高速代</p>
                                            </div>
                                            <div class="driver-shift__head__item --common parkingRow">
                                                <p class="">パーキング代</p>
                                            </div>
                                            <div class="driver-shift__head__item --common vehicleRow">
                                                <p class="">2台目以降</p>
                                            </div>
                                            <div class="driver-shift__head__item --common overtimeRow">
                                                <p class="">残業代</p>
                                            </div>
                                            <div class="driver-shift__head__item --date">
                                                <p class="">日付</p>
                                            </div>
                                            <div class="driver-shift__head__item --project">
                                                <p class="">案件名</p>
                                            </div>
                                            <div class="driver-shift__head__item --common amountRow">
                                                <p class="">金額</p>
                                            </div>
                                            <div class="driver-shift__head__item --common">
                                                <p class="">手当</p>
                                            </div>
                                            <div class="driver-shift__head__item --common expresswayRow">
                                                <p class="">高速代</p>
                                            </div>
                                            <div class="driver-shift__head__item --common parkingRow">
                                                <p class="">パーキング代</p>
                                            </div>
                                            <div class="driver-shift__head__item --common vehicleRow">
                                                <p class="">2台目以降</p>
                                            </div>
                                            <div class="driver-shift__head__item --common overtimeRow">
                                                <p class="">残業代</p>
                                            </div>
                                        </div>

                                        <?php
                                        $total_salary = 0; //合計給与
                                        $total_allowance = 0; //合計手当
                                        $total_parking = 0; //合計パーキング代
                                        $total_expressway = 0; //合計高速代
                                        $total_overtime = 0; //合計残業代
                                        $projectCount = []; //合計案件数
                                        $total_working_days = 0; //稼働日数
                                        //   2台目・3台目の確認変数
                                        $second_machine_check = true;
                                        $second_vehicle_use = []; //2台目に使用した車両
                                        $secound_vehicle_array = [];
                                        $vehicle_second_count = 0; //2台目に使用した車両の数
                                        $third_vehicle_array = [];
                                        $third_machine_count = 0;
                                        $vehicle_rental_type = null; //その月の貸出形態
                                        $rental_vehicle_number = null; //その月の貸出車両
                                        ?>

                                    <div class="flex">
                                        <div class="driver-shift__calender-shift">
                                            @foreach ($dates as $date)
                                                @if($date->format('d') < 16)
                                                <div class="driver-shift__calender-shift__day">
                                                    {{-- 日付 --}}
                                                    <div class="driver-shift__calender-shift__day__item --date">
                                                        <p class="center-txt">{{ $date->format('d') }}</p>
                                                    </div>
                                                    <?php
                                                        $mustCnt = 3;
                                                        $count = 0;
                                                        $isCheck = false;
                                                    ?>
                                                    @php
                                                        $hasShift = true;
                                                        $second_machine_check = true;
                                                    @endphp
                                                    @foreach ($shifts as $shift)
                                                        @if($shift->date == $date->format('Y-m-d'))
                                                            <?php $isCheck = true; ?>

                                                            <div class="driver-shift__calender-shift__day__info">
                                                                @foreach ( $shift->projectsVehicles as $spv )
                                                                    @php
                                                                        if($hasShift){
                                                                            if($spv->project){
                                                                                if($spv->project->name !== '休み'){
                                                                                    $total_working_days++;
                                                                                    $hasShift = false;
                                                                                }
                                                                            }else{
                                                                                $total_working_days++;
                                                                                $hasShift = false;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <div class="driver-shift__calender-shift__day__info__row">
                                                                        {{-- 案件表示 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --project">
                                                                            @if ($spv->project)
                                                                                <p class="--project-name">{{$spv->project->name}}</p>
                                                                                @if (isset($projectCount[$spv->project->name][$spv->retail_price]))
                                                                                    <?php $projectCount[$spv->project->name][$spv->retail_price]++; ?>
                                                                                @else
                                                                                    <?php $projectCount[$spv->project->name][$spv->retail_price] = 1; ?>
                                                                                @endif
                                                                            @elseif(!empty($spv->unregistered_project))
                                                                                <p style="color:red;" class="--project-name">{{$spv->unregistered_project}}</p>
                                                                                @if (isset($projectCount[$spv->unregistered_project][$spv->retail_price]))
                                                                                    <?php $projectCount[$spv->unregistered_project][$spv->retail_price]++; ?>
                                                                                @else
                                                                                    <?php $projectCount[$spv->unregistered_project][$spv->retail_price] = 1; ?>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        {{-- 給与 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common amountRow">
                                                                            <input type="text" name="driver_price[{{$spv->id}}]" value="{{$spv->driver_price}}">
                                                                            <?php $total_salary += $spv->driver_price?>
                                                                        </div>
                                                                        {{-- 手当 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common allowance-area">
                                                                            <input type="text" name="allowance[{{$spv->id}}]" value="{{$spv->total_allowance}}" class="allowance-input">
                                                                            <?php $total_allowance += $spv->total_allowance?>
                                                                            @if ($spv->project)
                                                                                @foreach ($allowanceProject as $value)
                                                                                    @if ($value->project_id == $spv->project->id)
                                                                                        <input hidden class="allowanceName" type="text" value="{{$value->allowanceName}}">
                                                                                        <input hidden class="amount" type="text" value="{{$value->amount}}">
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                        {{-- 高速代 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common expresswayRow">
                                                                            <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{$spv->expressway_fee}}">
                                                                            <?php $total_expressway += $spv->expressway_fee?>
                                                                        </div>
                                                                        {{-- パーキング --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common parkingRow">
                                                                            <input type="text" name="parking_fee[{{$spv->id}}]" value="{{$spv->parking_fee}}">
                                                                            <?php $total_parking += $spv->parking_fee?>
                                                                        </div>
                                                                        {{-- 2台目 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common vehicleRow">
                                                                            @if ($spv->vehicle_rental_type != null) {{-- 車両形態が登録されいるか --}}
                                                                                @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1) {{-- 自車か月リースなのか --}}
                                                                                    <?php $vehicle_rental_type = $spv->vehicle_rental_type;?>
                                                                                    @if ($spv->rentalVehicle)
                                                                                        <?php $rental_vehicle_number = $spv->rentalVehicle->number?>
                                                                                    @endif
                                                                                    @if($spv->vehicle_id) {{-- 登録されてある車両なら、月リースの契約車両と比較 --}}
                                                                                        @if ($spv->vehicle->number !== '自車')
                                                                                            @if($spv->vehicle_id != $spv->rental_vehicle_id)
                                                                                                <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->vehicle->number}}" readonly>
                                                                                            @endif
                                                                                        @endif
                                                                                    @else {{-- 未登録車両 --}}
                                                                                        <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->unregistered_vehicle}}" readonly style="color:red;">
                                                                                    @endif
                                                                                @endif
                                                                            @else {{-- 未登録従業員の車両 --}}
                                                                                @if ($spv->vehicle_id)
                                                                                    <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->vehicle->number}}" readonly>
                                                                                @else
                                                                                    <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->unregistered_vehicle}}" readonly style="color:red;">
                                                                                @endif
                                                                            @endif

                                                                            @php
                                                                                // 自車
                                                                                if($spv->vehicle_rental_type == 0){
                                                                                    if ($spv->vehicle_id) {
                                                                                        if($spv->vehicle->number !== '自車'){
                                                                                            if($second_machine_check){
                                                                                                $second_machine_check = false;
                                                                                                $vehicle_second_count++;
                                                                                                if(!in_array($spv->vehicle->number, $secound_vehicle_array)){
                                                                                                    $secound_vehicle_array[] = $spv->vehicle->number;
                                                                                                }
                                                                                            }else{
                                                                                                $third_machine_count++;
                                                                                                if(!in_array($spv->vehicle->number, $third_vehicle_array)){
                                                                                                    $third_vehicle_array[] = $spv->vehicle->number;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            {{-- 月リース --}}
                                                                            @if ($spv->vehicle_rental_type == 1)
                                                                                @if ($spv->vehicle_id)
                                                                                    @if ($spv->vehicle->number !== '自車')
                                                                                        @if ($spv->vehicle_id != $spv->rental_vehicle_id)
                                                                                            @php
                                                                                                if($second_machine_check){
                                                                                                    $vehicle_second_count++;
                                                                                                    $second_machine_check = false;
                                                                                                    if(!in_array($spv->vehicle->number, $secound_vehicle_array)){
                                                                                                        $secound_vehicle_array[] = $spv->vehicle->number;
                                                                                                    }
                                                                                                }else{
                                                                                                    $third_machine_count++;
                                                                                                    if(!in_array($spv->vehicle->number, $third_vehicle_array)){
                                                                                                        $third_vehicle_array[] = $spv->vehicle->number;
                                                                                                    }
                                                                                                }
                                                                                            @endphp
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        {{-- 残業代 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common overtimeRow">
                                                                            <input type="text" name="overtime_fee[{{$spv->id}}]" value="{{$spv->overtime_fee}}">
                                                                            <?php $total_overtime += $spv->overtime_fee?>
                                                                        </div>
                                                                    </div>
                                                                    <?php $count++; ?>
                                                                @endforeach

                                                                @for ($i = 0; $i < ($mustCnt - $count); $i++)
                                                                    <div class="driver-shift__calender-shift__day__info__row">
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --project">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common amountRow">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common expresswayRow">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common parkingRow">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common vehicleRow">

                                                                        </div>
                                                                        {{-- 残業代 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common overtimeRow">

                                                                        </div>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                    @if (!$isCheck)
                                                        <div class="driver-shift__calender-shift__day__info">
                                                            @for ($i = 0; $i < $mustCnt; $i++)
                                                                <div class="driver-shift__calender-shift__day__info__row">
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --project">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common amountRow">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common expresswayRow">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common parkingRow">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common vehicleRow">

                                                                    </div>
                                                                    {{-- 残業代 --}}
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common overtimeRow">

                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    @endif
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        {{-- 後半 --}}
                                        <div class="driver-shift__calender-shift">
                                            @foreach ($dates as $date)
                                                @if($date->format('d') >= 16)
                                                    <div class="driver-shift__calender-shift__day">
                                                        {{-- 日付 --}}
                                                        <div class="driver-shift__calender-shift__day__item latter-item --date">
                                                            <p class="center-txt">{{ $date->format('d') }}</p>
                                                        </div>
                                                        <?php
                                                            $mustCnt = 3;
                                                            $count = 0;
                                                            $isCheck = false;
                                                        ?>
                                                        @php
                                                            $hasShift = true;
                                                            $second_machine_check = true;
                                                        @endphp
                                                        @foreach ($shifts as $shift)
                                                            @if($shift->date == $date->format('Y-m-d'))
                                                                <?php $isCheck = true; ?>

                                                                <div class="driver-shift__calender-shift__day__info">
                                                                    @foreach ( $shift->projectsVehicles as $spv )
                                                                        @php
                                                                            if($hasShift){
                                                                                if($spv->project){
                                                                                    if($spv->project->name !== '休み'){
                                                                                        $total_working_days++;
                                                                                        $hasShift = false;
                                                                                    }
                                                                                }else{
                                                                                    $total_working_days++;
                                                                                    $hasShift = false;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <div class="driver-shift__calender-shift__day__info__row">
                                                                            {{-- 案件表示 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --project">
                                                                                @if ($spv->project)
                                                                                    <p class="--project-name">{{$spv->project->name}}</p>
                                                                                    @if (isset($projectCount[$spv->project->name][$spv->retail_price]))
                                                                                        <?php $projectCount[$spv->project->name][$spv->retail_price]++; ?>
                                                                                    @else
                                                                                        <?php $projectCount[$spv->project->name][$spv->retail_price] = 1; ?>
                                                                                    @endif
                                                                                @elseif(!empty($spv->unregistered_project))
                                                                                    <p style="color:red;" class="--project-name">{{$spv->unregistered_project}}</p>
                                                                                    @if (isset($projectCount[$spv->unregistered_project][$spv->retail_price]))
                                                                                        <?php $projectCount[$spv->unregistered_project][$spv->retail_price]++; ?>
                                                                                    @else
                                                                                        <?php $projectCount[$spv->unregistered_project][$spv->retail_price] = 1; ?>
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                            {{-- 給与 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common amountRow">
                                                                                <input type="text" name="driver_price[{{$spv->id}}]" value="{{$spv->driver_price}}">
                                                                                <?php $total_salary += $spv->driver_price?>
                                                                            </div>
                                                                            {{-- 手当 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common allowance-area">
                                                                                <input type="text" name="allowance[{{$spv->id}}]" value="{{$spv->total_allowance}}" class="allowance-input">
                                                                                <?php $total_allowance += $spv->total_allowance?>
                                                                                @if ($spv->project)
                                                                                    @foreach ($allowanceProject as $value)
                                                                                        @if ($value->project_id == $spv->project->id)
                                                                                            <input hidden class="allowanceName" type="text" value="{{$value->allowanceName}}">
                                                                                            <input hidden class="amount" type="text" value="{{$value->amount}}">
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                            {{-- 高速代 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common expresswayRow">
                                                                                <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{$spv->expressway_fee}}">
                                                                                <?php $total_expressway += $spv->expressway_fee?>
                                                                            </div>
                                                                            {{-- パーキング --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common parkingRow">
                                                                                <input type="text" name="parking_fee[{{$spv->id}}]" value="{{$spv->parking_fee}}">
                                                                                <?php $total_parking += $spv->parking_fee?>
                                                                            </div>
                                                                            {{-- 2台目 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common vehicleRow">
                                                                                @if ($spv->vehicle_rental_type != null) {{-- 車両形態が登録されいるか --}}
                                                                                    @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1) {{-- 自車か月リースなのか --}}
                                                                                        <?php $vehicle_rental_type = $spv->vehicle_rental_type;?>
                                                                                        @if ($spv->rentalVehicle)
                                                                                            <?php $rental_vehicle_number = $spv->rentalVehicle->number?>
                                                                                        @endif
                                                                                        @if($spv->vehicle_id) {{-- 登録されてある車両なら、月リースの契約車両と比較 --}}
                                                                                            @if ($spv->vehicle->number !== '自車')
                                                                                                @if($spv->vehicle_id != $spv->rental_vehicle_id)
                                                                                                    <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->vehicle->number}}" readonly>
                                                                                                @endif
                                                                                            @endif
                                                                                        @else {{-- 未登録車両 --}}
                                                                                            <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->unregistered_vehicle}}" readonly style="color:red;">
                                                                                        @endif
                                                                                    @endif
                                                                                @else {{-- 未登録従業員の車両 --}}
                                                                                    @if ($spv->vehicle_id)
                                                                                        <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->vehicle->number}}" readonly>
                                                                                    @else
                                                                                        <input type="text" class="mainVehicle" name="vehicle[{{$spv->id}}]" value="{{$spv->unregistered_vehicle}}" readonly style="color:red;">
                                                                                    @endif
                                                                                @endif

                                                                                @php
                                                                                    // 自車
                                                                                    if($spv->vehicle_rental_type == 0){
                                                                                        if ($spv->vehicle_id) {
                                                                                            if($spv->vehicle->number !== '自車'){
                                                                                                if($second_machine_check){
                                                                                                    $second_machine_check = false;
                                                                                                    $vehicle_second_count++;
                                                                                                    if(!in_array($spv->vehicle->number, $secound_vehicle_array)){
                                                                                                        $secound_vehicle_array[] = $spv->vehicle->number;
                                                                                                    }
                                                                                                }else{
                                                                                                    $third_machine_count++;
                                                                                                    if(!in_array($spv->vehicle->number, $third_vehicle_array)){
                                                                                                        $third_vehicle_array[] = $spv->vehicle->number;
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                                {{-- 月リース --}}
                                                                                @if ($spv->vehicle_rental_type == 1)
                                                                                    @if ($spv->vehicle_id)
                                                                                        @if ($spv->vehicle->number !== '自車')
                                                                                            @if ($spv->vehicle_id != $spv->rental_vehicle_id)
                                                                                                @php
                                                                                                    if($second_machine_check){
                                                                                                        $vehicle_second_count++;
                                                                                                        $second_machine_check = false;
                                                                                                        if(!in_array($spv->vehicle->number, $secound_vehicle_array)){
                                                                                                            $secound_vehicle_array[] = $spv->vehicle->number;
                                                                                                        }
                                                                                                    }else{
                                                                                                        $third_machine_count++;
                                                                                                        if(!in_array($spv->vehicle->number, $third_vehicle_array)){
                                                                                                            $third_vehicle_array[] = $spv->vehicle->number;
                                                                                                        }
                                                                                                    }
                                                                                                @endphp
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                            {{-- 残業代 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common overtimeRow">
                                                                                <input type="text" name="overtime_fee[{{$spv->id}}]" value="{{$spv->overtime_fee}}">
                                                                                <?php $total_overtime += $spv->overtime_fee?>
                                                                            </div>
                                                                        </div>
                                                                        <?php $count++; ?>
                                                                    @endforeach

                                                                    @for ($i = 0; $i < ($mustCnt - $count); $i++)
                                                                        <div class="driver-shift__calender-shift__day__info__row">
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --project">

                                                                            </div>
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common amountRow">

                                                                            </div>
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                            </div>
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common expresswayRow">

                                                                            </div>
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common parkingRow">

                                                                            </div>
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --commonvehicleRow">

                                                                            </div>
                                                                            {{-- 残業代 --}}
                                                                            <div class="driver-shift__calender-shift__day__info__row__data --common overtimeRow">

                                                                            </div>
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if (!$isCheck)
                                                            <div class="driver-shift__calender-shift__day__info">
                                                                @for ($i = 0; $i < $mustCnt; $i++)
                                                                    <div class="driver-shift__calender-shift__day__info__row">
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --project">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common amountRow">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common expresswayRow">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common parkingRow">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common vehicleRow">

                                                                        </div>
                                                                        {{-- 残業代 --}}
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common overtimeRow">

                                                                        </div>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        {{-- 案件集計表 --}}
                                        <?php $total_project_count = 0; ?>
                                        <div class="flex">
                                            <div class="column">
                                                <div class="total-project-table">
                                                    <div class="total-project-table__head">
                                                        <div class="total-project-table__head__item --project"><p class="">案件名</p></div>
                                                        <div class="total-project-table__head__item --common"><p class="">金額</p></div>
                                                        <div class="total-project-table__head__item --common"><p class="">件数</p></div>
                                                    </div>
                                                    @foreach ($projectCount as $projectName => $amountKey)
                                                        @foreach ($amountKey as $amount => $count)
                                                            <div class="total-project-table__row">
                                                                <div class="total-project-table__row__item --project"><p>{{$projectName}}</p></div>
                                                                <div class="total-project-table__row__item --common"><p>{{$amount}}</p></div>
                                                                <div class="total-project-table__row__item --common"><p>{{$count}}</p></div>
                                                                <?php $total_project_count += $count?>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                    <div class="total-project-table__row">
                                                        <div class="total-project-table__row__item --project"><p></p></div>
                                                        <div class="total-project-table__row__item --common"><p></p></div>
                                                        <div class="total-project-table__row__item --common --total-count"><p>{{$total_project_count}}</p></div>
                                                    </div>
                                                </div>
                                                <div class="total-working-days">
                                                    <p class="">稼働日数</p>
                                                    <p>{{$total_working_days}}</p>
                                                </div>
                                            </div>
                                            <div class="vehicle-use">
                                                <div class="vehicle-use__head text-center">
                                                    <p class="">2台目車両</p>
                                                </div>
                                                @foreach ($secound_vehicle_array as $number)
                                                    <div class="vehicle-use__row text-center">
                                                        {{$number}}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="vehicle-use">
                                                <div class="vehicle-use__head text-center">
                                                    <p class="">3台目車両</p>
                                                </div>
                                                @foreach ($third_vehicle_array as $number)
                                                    <div class="vehicle-use__row text-center">
                                                        {{$number}}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                        {{-- 金額集計表 --}}
                                        <?php
                                            $tax = 0.1;//消費税
                                            $administrative_rate = 0.15;//事務委託手数料
                                            $administrative_fee = 10000;//事務手数料
                                            $transfer_fee = 600;//振込手数料
                                            $month_lease_fee = 30992;//月額リース料
                                            $second_lease = 1000;//2台目リース料　リース契約時
                                            $second_lease_myCar = 1500;//2台目リース料　自車契約時
                                            $three_lease = 1000;//3台目リース料
                                            $lease_insurance = 9818;//月額リース保険料
                                            $month_lease_insurance = 410;//2台目以降保険料
                                            $daily_lease_rate = 1364;//日割りリース料
                                            $daily_insurance_rate = 410.;//日割り保険料

                                            $sent_total_lease = 0;
                                            $sent_total_insurance = 0;

                                            if($vehicle_rental_type == 0){
                                                $second_lease = 1500;
                                            }
                                        ?>
                                        <div class="total-amount-table">
                                            <div class="total-amount-table__row amountRow">
                                                <div class="total-amount-table__row__item"><p class="">合計金額</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$total_salary}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row">
                                                <div class="total-amount-table__row__item"><p class="">案件別手当合計</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$total_allowance}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row">
                                                <div class="total-amount-table__row__item"><p class="">消費税10%</p></div>
                                                <?php $tax = ($total_salary + $total_allowance) * $tax?>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{ceil($tax)}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row parkingRow">
                                                <div class="total-amount-table__row__item"><p class="">パーキング代</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$total_parking}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row expresswayRow">
                                                <div class="total-amount-table__row__item"><p class="">高速代</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$total_expressway}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row overtimeRow">
                                                <div class="total-amount-table__row__item"><p class="">残業代</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$total_overtime}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row">
                                                <div class="total-amount-table__row__item"><p class="">事務委託手数料(15%)</p></div>
                                                <?php $administrative_commission_fee = $total_salary * $administrative_rate?>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{ceil($administrative_commission_fee)}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row">
                                                <div class="total-amount-table__row__item"><p class="">事務手数料</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$administrative_fee}}</p></div>
                                            </div>
                                            <div class="total-amount-table__row">
                                                <div class="total-amount-table__row__item"><p class="">振込手数料</p></div>
                                                <div class="total-amount-table__row__item --amount"><p class="">{{$transfer_fee}}</p></div>
                                            </div>
                                            @if ($vehicle_rental_type == 1 || $vehicle_rental_type == 2)
                                                <div class="total-amount-table__row">
                                                    <div class="total-amount-table__row__item"><p class="">リース代　月契約 No.{{$rental_vehicle_number}}</p></div>
                                                    <div class="total-amount-table__row__item --amount"><p class="">{{$month_lease_fee}}</p></div>
                                                </div>
                                                @php
                                                    $sent_total_lease += $month_lease_fee;
                                                @endphp
                                            @endif
                                            @if ($vehicle_rental_type == 0 || $vehicle_rental_type == 1)
                                                @if ($vehicle_second_count > 0)
                                                    <div class="total-amount-table__row">
                                                        <div class="total-amount-table__row__item"><p class="">リース　2台目(日割り)</p></div>
                                                        <div class="total-amount-table__row__item --amount"><p class="">{{$second_lease * $vehicle_second_count}}</p></div>
                                                    </div>
                                                    @php
                                                        $sent_total_lease += ($second_lease * $vehicle_second_count);
                                                    @endphp
                                                @endif
                                            @endif
                                            @if ($third_machine_count > 0)
                                                <div class="total-amount-table__row">
                                                    <div class="total-amount-table__row__item"><p class="">リース　3台目(日割り)</p></div>
                                                    <div class="total-amount-table__row__item --amount"><p class="">{{$three_lease * $third_machine_count}}</p></div>
                                                </div>
                                                @php
                                                    $sent_total_lease += ($three_lease * $third_machine_count);
                                                @endphp
                                            @endif

                                            @if ($vehicle_rental_type == 1 || $vehicle_rental_type == 2)
                                                <div class="total-amount-table__row">
                                                    <div class="total-amount-table__row__item"><p class="">保険料　月契約 No.{{$rental_vehicle_number}}</p></div>
                                                    <div class="total-amount-table__row__item --amount"><p class="">{{$lease_insurance}}</p></div>
                                                </div>
                                                @php
                                                    $sent_total_insurance += $lease_insurance;
                                                @endphp

                                            @endif
                                            @if ($vehicle_second_count > 0)
                                                <div class="total-amount-table__row">
                                                    <div class="total-amount-table__row__item"><p class="">保険料　2台目(日割り)</p></div>
                                                    <div class="total-amount-table__row__item --amount"><p class="">{{$daily_insurance_rate * ($vehicle_second_count + $third_machine_count)}}</p></div>
                                                </div>
                                                @php
                                                    $sent_total_insurance += $daily_insurance_rate * ($vehicle_second_count + $third_machine_count);
                                                @endphp
                                            @endif

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if ($warning !== null)
                        <p class="warning-txt">{{$warning}}</p>
                    @endif

                    @if ($shifts !== null && !$shifts->isEmpty())
                    <div class="ab-elem">
                        <form action="{{route('invoice.driver-edit-pdf')}}" method="POST">
                            @csrf
                            {{-- 業務委託手数料 --}}
                            <input hidden type="text" name="administrative_commission_fee" value="{{$administrative_commission_fee}}">
                            {{-- 合計リース代 --}}
                            <input hidden type="text" name="total_lease" value="{{$sent_total_lease}}">
                            {{-- 合計保険代 --}}
                            <input hidden type="text" name="total_insurance" value="{{$sent_total_insurance}}">
                            {{-- 事務手数料 --}}
                            <input hidden type="text" name="administrative_fee" value="{{$administrative_fee}}">
                            {{-- 振込手数料 --}}
                            <input hidden type="text" name="transfer_fee" value="{{$transfer_fee}}">
                            {{-- 給与合計 --}}
                            <input hidden type="text" name="total_salary" value="{{$total_salary}}">
                            {{-- 手当 --}}
                            <input hidden type="text" name="total_allowance" value="{{$total_allowance}}">
                            {{-- 高速代・パーキング代 --}}
                            <input hidden type="text" name="total_expressway" value="{{$total_expressway}}">
                            <input hidden type="text" name="total_parking" value="{{ $total_parking}}">
                            <input hidden type="text" name="total_overtime" value="{{$total_overtime}}">
                            {{-- 従業員ID --}}
                            <input hidden type="text" name="employee" value="{{$employeeName->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <button class="calendar-pdf-button ab-button">
                                <p>請求書確認画面</p>
                            </button>

                            <div class="ab-check-area">
                                <div class="item-check-wrap">
                                    <div class="item-check-wrap__block">
                                        <input checked type="checkbox" name="amountCheck" value="1" id="amountCheck">
                                        <label for="">金額</label>
                                    </div>
                                    <div class="item-check-wrap__block">
                                        <input checked type="checkbox" name="expresswayCheck" value="1" id="expresswayCheck">
                                        <label for="">高速代</label>
                                    </div>
                                    <div class="item-check-wrap__block">
                                        <input checked type="checkbox" name="parkingCheck" value="1" id="parkingCheck">
                                        <label for="">パーキング代</label>
                                    </div>
                                    <div class="item-check-wrap__block">
                                        <input checked type="checkbox" name="" value="1" id="vehicleCheck">
                                        <label for="">2台目</label>
                                    </div>
                                    <div class="item-check-wrap__block">
                                        <input checked type="checkbox" name="overtimeCheck" value="1" id="overtimeCheck">
                                        <label for="">残業代</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @endif

                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/invoice-driver.js')}}"></script>
