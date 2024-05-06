<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__link-block --invoice-link-block">
            <div class="main__link-block__tags">
                <a href="{{route('invoice.driverShift')}}" class="main__link-block__item --invoice-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.driverShift','invoice.searchShift','invoice.driver-edit-pdf') ? 'active' : '' }} link">
                        <span class="">ドライバー</span>
                    </button>
                </a>
                <a href="{{route('invoice.projectShift')}}" class="main__link-block__item --invoice-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.projectShift','invoice.searchProjectShift', 'invoice.project-edit-pdf') ? 'active' : '' }} link">
                        <span class="">案件</span>
                    </button>
                </a>
                <a href="{{route('invoice.charterShift')}}" class="main__link-block__item --invoice-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.charterShift','invoice.findCharterShift') ? 'active' : '' }} link">
                        <span class="">チャーター</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="main__white-board --invoice-white-board">
            <div class="c-invoice-common-wrap">
                <div class="driver-invoice-shift">
                    <form action="{{ route('invoice.searchShift') }}" method="POST">
                        @csrf
                        <div class="c-select-area">
                            <div class="c-select-area__block">
                                <select name="year" id="" class="c-select year-select" required>
                                    <option value="">----</option>
                                    @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                        @if ($year == $getYear)
                                            <option selected value="{{ $year }}">{{ $year }}</option>
                                        @else
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="c-select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
                                    <option value="">----</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        @if ($i == $getMonth)
                                            <option selected value="{{ $i }}">{{ $i }}</option>
                                        @else
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <label for="">月</label>
                            </div>

                            <div class="c-select-area__block name-block">
                                <select name="employeeId" id="" class="c-select name-select" required>
                                    <option value="">----</option>
                                    @foreach ($employees as $employee)
                                        @if ($employee->id == $employeeId)
                                            <option selected value="{{$employee->id}}">{{$employee->name}}</option>
                                        @else
                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button class="c-search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>

                    {{-- カレンダーメインエリア --}}
                    @if ($shifts !== null && !$shifts->isEmpty())
                        <div class="c-middle-head">
                            <div class="c-search-info">
                                <div class="c-search-info__date">
                                    <p>{{$getYear}}年</p>
                                    <p>{{$getMonth}}月</p>
                                </div>
                                <div class="c-search-info__name">
                                    <p class="">{{$findEmployee->name}}</p>
                                </div>
                            </div>
                            <button class="c-middle-head__button invoice-form formSubmit" id="invoiceBtnClickElem">
                                請求書確認
                            </button>
                        </div>
                        {{-- チェックエリア --}}
                        <div class="driver-invoice-shift__check-area">
                            @csrf
                            <input hidden name="amountCheck" class="setAmountCheck">
                            <input hidden name="allowanceCheck" class="setAllowanceCheck">
                            <input hidden name="expresswayCheck" class="setExpresswayCheck">
                            <input hidden name="parkingCheck" class="setParkingCheck">
                            <input hidden name="vehicleCheck" class="setVehicleCheck">
                            <input hidden name="overtimeCheck" class="setOvertimeCheck">
                            {{-- <input hidden name="setRowCount" type="text" class="setRowCount"> --}}
                            {{-- 絞り込み情報 --}}
                            @foreach ($clientsId as $clientId)
                                <input hidden type="text" name="calendarClientsId[]" value="{{ $clientId }}">
                            @endforeach
                            @foreach ($projectsId as $projectId)
                                <input hidden type="text" name="calendarProjectsId[]" value="{{ $projectId }}">
                            @endforeach

                            <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            {{-- <textarea hidden name="textarea" id="setTextArea" cols="30" rows="10"></textarea> --}}
                        </div>
                        <div class="driver-invoice-shift__top-btn-wrap">
                            <button class="c-middle-head__button pdf-form formSubmit c-pdf-download-btn">
                                ダウンロード
                            </button>
                            {{-- 設定ボタン --}}
                            <div class="setting-btn" id="settingBtn">
                                設定
                            </div>
                            {{-- カレンダー変更ボタン --}}
                            <button class="driver-invoice-shift__calendar__calendar-top-wrap__save-btn" id="calendarSaveBtn">
                                変更内容を保存
                            </button>
                        </div>
                        {{-- 設定モーダル --}}
                        <form action="{{ route('invoice.searchShift') }}" method="POST" class="setting-modal-wrap" id="settingModalWrap">
                            @csrf
                            <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <span class="setting-modal-wrap__bg settingCloseBtn"></span>
                            <div class="setting-modal-wrap__white-board">
                                <div class="setting-modal-wrap__white-board__inner">
                                    <p class="head">設定</p>
                                    <p class="title">非表示項目</p>

                                    <div class="check-area">
                                        <div class="check-item">
                                            <input type="checkbox" name="narrowCheck[]" value="1" class="" id="narrowAmountCheck" @if(in_array('1', $selectedNarrowCheck)) checked @endif>
                                            <label for="">金額</label>
                                        </div>
                                        <div class="check-item">
                                            <input type="checkbox" name="narrowCheck[]" value="2" class="" id="narrowAllowanceCheck" @if(in_array('2', $selectedNarrowCheck)) checked @endif>
                                            <label for="">手当</label>
                                        </div>
                                        <div class="check-item">
                                            <input type="checkbox" name="narrowCheck[]" value="3" class="" id="narrowExpresswayCheck" @if(in_array('3', $selectedNarrowCheck)) checked @endif>
                                            <label for="">高速代</label>
                                        </div>
                                        <div class="check-item">
                                            <input type="checkbox" name="narrowCheck[]" value="4" class="" id="narrowParkingCheck" @if(in_array('4', $selectedNarrowCheck)) checked @endif>
                                            <label for="">パーキング代</label>
                                        </div>
                                        <div class="check-item">
                                            <input type="checkbox" name="narrowCheck[]" value="5" class="" id="narrowVehicleCheck" @if(in_array('5', $selectedNarrowCheck)) checked @endif>
                                            <label for="">2台目以降</label>
                                        </div>
                                        <div class="check-item">
                                            <input type="checkbox" name="narrowCheck[]" value="6" class="" id="narrowOvertimeCheck" @if(in_array('6', $selectedNarrowCheck)) checked @endif>
                                            <label for="">残業代</label>
                                        </div>
                                    </div>
                                    <p class="title">クライアント絞り込み</p>
                                    <div class="check-area">
                                        @foreach ($findClients as $client)
                                            <div class="check-item">
                                                <input type="checkbox" class="" value="{{ $client->id }}" name="clientsId[]" @if(in_array($client->id, $clientsId)) checked @endif>
                                                <label for="">{{ $client->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="title">案件絞り込み</p>
                                    <div class="check-area">
                                        @foreach ($findProjects as $project)
                                            <div class="check-item">
                                                <input type="checkbox" class="" value="{{ $project->id }}" name="projectsId[]" @if(in_array($project->id, $projectsId)) checked @endif>
                                                <label for="">{{ $project->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="title">行数</p>
                                    <select name="rowNeedCount" id="" class="c-select row-select">
                                        <option value="">--</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            @if ($i == $needRowCount)
                                                <option selected value="{{ $i }}">{{ $i }}</option>
                                            @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                    <div class="button-area">
                                        <button class="">
                                            絞り込み
                                        </button>
                                        <div class="button-area__back settingCloseBtn">
                                            戻る
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- 新規追加モーダル --}}
                        <div class="shift-modal" id="shiftModal">
                            <span class="shift-modal__bg shiftModalClose"></span>
                            <div class="shift-modal__white-board">
                                <form action="{{ route('invoice.driverShiftCreate') }}" method="POST">
                                    @csrf
                                    <div class="shift-modal__white-board__inner">
                                        <p class="title shiftModalTitle">案件作成</p>
                                        <div class="shift-date">
                                            <p class="shift-date__year"><span class="big setYearTxt">2020</span>年</p>
                                            <p class="shift-date__month-date"><span class="big setMonthTxt">12</span>月<span class="big setDayTxt">12</span>日</p>
                                            <p class="shift-date__time-of-part shiftTimeOfPart"></p>
                                        </div>
                                        <div class="active-box">
                                            {{-- 警告文 --}}
                                            <p class="modal-warning-txt shiftModalWarningTxt">
                                                <span class="unProjectTxt"></span>は未登録案件です。<br>
                                                未登録案件を使用する場合は、<a href="{{ route('project.create') }}" class="modal-warning-txt__link">クライアント管理</a> から登録が必要です。
                                            </p>
                                            {{-- 従業員情報 --}}
                                            <input hidden type="text" name="employeeId" value="{{ $findEmployee->id }}">
                                            {{-- リダイレクト情報 --}}
                                            <input hidden type="text" name="year" value="{{$getYear}}">
                                            <input hidden type="text" name="month" value="{{$getMonth}}">
                                            {{-- 日付 --}}
                                            <input hidden type="text" name="createDate" value="" class="setDateValue">
                                            {{-- id --}}
                                            <input hidden type="text" name="shiftPvId" class="setShiftPvId">
                                            {{-- 午前・午後 --}}
                                            <div class="active-box__item shiftTimeOfPartRadioBox">
                                                <p class="">区分</p>
                                                <div class="radio-area">
                                                    <label for="am">
                                                        <input checked type="radio" name="dayOfPart" value="0" class="" id="am">
                                                        午前
                                                    </label>
                                                    <label for="pm">
                                                        <input type="radio"  name="dayOfPart" value="1" class="" id="pm">
                                                        午後
                                                    </label>
                                                </div>
                                            </div>
                                            {{-- 案件 --}}
                                            <div class="active-box__item">
                                                <p class="">案件</p>
                                                <select name="createProject" id="" class="c-select projectSelect">
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- 車両 --}}
                                            <div class="active-box__item">
                                                <p class="">車両</p>
                                                <select name="createVehicle" id="" class="c-select vehicleSelect">
                                                    @foreach ($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}">{{ $vehicle->number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- 上代 --}}
                                            <div class="active-box__item">
                                                <p class="">上代</p>
                                                <input type="text" name="createRetail" class="c-input setRetailPrice" placeholder="10000" required>
                                            </div>
                                            {{-- 給与 --}}
                                            <div class="active-box__item">
                                                <p class="">給与</p>
                                                <input type="text" name="createSalary" class="c-input setDriverPrice" placeholder="9000" required>
                                            </div>
                                        </div>
                                        <div class="button-box">
                                            <button name="action" value="update" class="btn --save">
                                                入力内容で案件を登録
                                            </button>
                                            <button name="action" value="delete" class="btn --delete shiftModalDelete">
                                                案件を削除する
                                            </button>
                                            <div class="btn --back shiftModalClose">
                                                閉じる
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @php
                            // 基本の行数
                            if($needRowCount == null){
                                $needRowCount = 3;
                            }
                            $needRowCountWarning = null;
                            foreach ($dates as $date) {
                                $dateCount = 0;
                                foreach ($shiftProjectVehicles as $spv) {
                                    if ($spv->shift->date == $date->format('Y-m-d')) {
                                        $dateCount++;
                                    }
                                }
                                if($dateCount > $needRowCount){
                                    $needRowCount = $dateCount;
                                    $needRowCountWarning = 'シフトの数が指定した行数を上回っています';
                                }
                            }
                        @endphp
                        <p class="need-row-count-warning-txt">{{ $needRowCountWarning }}</p>
                        {{-- カレンダー --}}
                        <div class="driver-invoice-shift__calendar">
                            <form action="{{route('invoice.driverShiftUpdate')}}" method="POST" class="driver-invoice-shift__calendar__calendar-top-wrap" id="calendarForm">
                                @csrf
                                <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                                <input hidden type="text" name="year" value="{{$getYear}}">
                                <input hidden type="text" name="month" value="{{$getMonth}}">
                                {{-- メインカレンダー --}}
                                @php
                                    $vehicle_rantal_type = null;
                                    $vehicle_rantal_number = null;
                                @endphp
                                <table class="driver-invoice-shift__calendar__calendar-top-wrap__table">
                                    <thead>
                                        <tr>
                                            <th>日付</th>
                                            <th>案件名</th>
                                            <th class="amountRow">金額</th>
                                            <th class="overtimeRow">残業代</th>
                                            <th class="allowanceRow">手当</th>
                                            <th class="expresswayRow">高速<br>料金</th>
                                            <th class="parkingRow">駐車<br>料金</th>
                                            <th class="vehicleRow">車両</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $dates as $date )
                                            @php
                                                // 行数カウント
                                                $rowCount = 0;
                                                $count = 0;
                                            @endphp
                                            @if ($date->format('d') < 16)
                                                {{-- 日付　休日、祝日の分岐 --}}
                                                @foreach ( $shiftProjectVehicles as $spv )
                                                    @if ($spv->shift->date == $date->format('Y-m-d'))
                                                        {{-- @foreach ( $shift->projectsVehicles as $spv ) --}}
                                                            <tr @if($count == 0) class="dayInfirst" @endif>
                                                                @if ($count == 0)
                                                                    @if ($holidays->isHoliday($date))
                                                                        <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @elseif ($date->isSaturday())
                                                                        <td rowspan="{{ $needRowCount }}" style="color: skyblue;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @elseif($date->isSunday())
                                                                        <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @else
                                                                        <td rowspan="{{ $needRowCount }}"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @endif
                                                                @endif
                                                                {{-- 案件名 --}}
                                                                <td class="w-project shiftActive editShift">
                                                                    @if ($spv->project)
                                                                        @php
                                                                            $suspendedValidation = '';
                                                                            if($spv->project->is_suspended == 1){
                                                                                $suspendedValidation = 'red';
                                                                            }
                                                                        @endphp
                                                                        @if ($spv->initial_project_name)
                                                                            <p class="" style="color:{{ $suspendedValidation }};">{{ $spv->initial_project_name }}</p>
                                                                        @else
                                                                            <p class="" style="color:{{ $suspendedValidation }};">{{ $spv->project->name }}</p>
                                                                        @endif
                                                                    @else
                                                                        <p class="" style="color: red;">{{ $spv->unregistered_project }}</p>
                                                                    @endif
                                                                    {{-- モーダルが取得する情報 --}}
                                                                    <input hidden type="text" value="{{ $date->format('Y-m-d') }}" class="shiftDate" data-year="{{ $date->format('Y') }}" data-month="{{ $date->format('n') }}" data-day="{{ $date->format('j') }}">
                                                                    <input hidden type="text" class="shiftValues"
                                                                         data-id="{{ $spv->id }}" data-project-id="{{ $spv->project_id }}"
                                                                         data-unproject-name="{{ $spv->unregistered_project }}" data-time-of-part="{{ $spv->time_of_day }}"
                                                                         data-vehicle-id="{{ $spv->vehicle_id }}" data-retail-price="{{ $spv->retail_price }}"
                                                                         data-driver-price="{{ $spv->driver_price }}" >
                                                                </td>
                                                                {{-- ドライバー価格 --}}
                                                                <td class="w-amount amountRow"><input type="text" value="{{ number_format($spv->driver_price) }}" name="driver_price[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 残業代 --}}
                                                                <td class="w-amount overtimeRow"><input type="text" value="{{ number_format($spv->overtime_fee) }}" name="overtime_fee[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 手当 --}}
                                                                <td class="w-amount allowance-area allowanceRow">
                                                                    <input type="text" value="{{ number_format($spv->total_allowance) }}" name="allowance[{{$spv->id}}]" class="allowance-input commaInput">
                                                                    {{-- @if ($spv->project)
                                                                        @foreach ($allowanceProject as $value)
                                                                            @if ($value->project_id == $spv->project->id)
                                                                                <input hidden class="allowanceName" type="text" value="{{$value->allowanceName}}">
                                                                                <input hidden class="amount" type="text" value="{{$value->amount}}">
                                                                            @endif
                                                                        @endforeach
                                                                    @endif --}}
                                                                </td>
                                                                {{-- 高速代 --}}
                                                                <td class="w-amount expresswayRow"><input type="text" value="{{ number_format($spv->expressway_fee) }}" name="expressway_fee[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 駐車台 --}}
                                                                <td class="w-amount parkingRow"><input type="text" value="{{ number_format($spv->parking_fee) }}" name="parking_fee[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 車両 --}}
                                                                <td class="w-amount vehicleRow">
                                                                    {{-- 自車または月リースか判定 --}}
                                                                    @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1)
                                                                        @if ($spv->rental_vehicle_id == null) {{-- nullなら自車 --}}
                                                                            @if($spv->vehicle)
                                                                                @if ($spv->vehicle->number != '自車')
                                                                                    <input type="text" value="{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                                @endif
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle && $spv->unregistered_vehicle != '自車')
                                                                                    <input style="color: red;" type="text" value="{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                                @endif
                                                                            @endif
                                                                        @elseif($spv->vehicle_id != $spv->rental_vehicle_id) {{-- 契約車両と登録車両を比較 --}}
                                                                            @if($spv->vehicle)
                                                                                <input type="text" value="{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle != '自車')
                                                                                    <input style="color: red;" type="text" value="{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif($spv->vehicle_rental_type == 3)
                                                                        @if($spv->vehicle)
                                                                            @if ($spv->vehicle->number != '自車')
                                                                                <input type="text" value="{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                            @endif
                                                                        @else
                                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                                <input style="color: red;" type="text" value="{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @php
                                                                    $rowCount++;
                                                                    // 貸出形態と貸出車両を格納
                                                                    $vehicle_rantal_type = $spv->vehicle_rental_type;
                                                                    if ($spv->rentalVehicle) {
                                                                        $vehicle_rantal_number = $spv->rentalVehicle->number;
                                                                    }
                                                                    $count++;
                                                                @endphp
                                                            </tr>
                                                        {{-- @endforeach --}}
                                                    @endif
                                                @endforeach
                                                {{-- 行数が足りない時のため --}}
                                                @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                    <tr class="shiftActive createShift @if($count == 0) dayInfirst @endif">
                                                        @if ($count == 0)
                                                            @if ($holidays->isHoliday($date))
                                                                <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @elseif ($date->isSaturday())
                                                                <td rowspan="{{ $needRowCount }}" style="color: skyblue;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @elseif($date->isSunday())
                                                                <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @else
                                                                <td rowspan="{{ $needRowCount }}"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @endif
                                                        @endif
                                                        <td class="w-project">
                                                            <p class="">
                                                                {{-- 日付 --}}
                                                                <input hidden type="text" value="{{ $date->format('Y-m-d') }}" class="shiftDate" data-year="{{ $date->format('Y') }}" data-month="{{ $date->format('n') }}" data-day="{{ $date->format('j') }}">
                                                            </p>
                                                        </td>
                                                        <td class="w-amount amountRow"><p class=""></p></td>
                                                        <td class="w-amount overtimeRow"><p class=""></p></td>
                                                        <td class="w-amount allowanceRow"><p class=""></p></td>
                                                        <td class="w-amount expresswayRow"><p class=""></p></td>
                                                        <td class="w-amount parkingRow"><p class=""></p></td>
                                                        <td class="w-amount vehicleRow"><p class=""></p></td>
                                                        @php
                                                            // $rowCount++;
                                                            $count++;
                                                        @endphp
                                                    </tr>
                                                @endfor
                                                {{-- シフトがない時のため --}}
                                                @if ($rowCount == 0)
                                                    @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                        <tr class="shiftActive createShift @if($count == 0) dayInfirst @endif">
                                                            @if ($count == 0)
                                                                @if ($holidays->isHoliday($date))
                                                                    <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @elseif ($date->isSaturday())
                                                                    <td rowspan="{{ $needRowCount }}" style="color: skyblue;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @elseif($date->isSunday())
                                                                    <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @else
                                                                    <td rowspan="{{ $needRowCount }}"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @endif
                                                            @endif
                                                            <td class="w-project">
                                                                <p class="">
                                                                    {{-- 日付 --}}
                                                                    <input hidden type="text" value="{{ $date->format('Y-m-d') }}" class="shiftDate" data-year="{{ $date->format('Y') }}" data-month="{{ $date->format('n') }}" data-day="{{ $date->format('j') }}">
                                                                </p>
                                                            </td>
                                                            <td class="w-amount amountRow"><p class=""></p></td>
                                                            <td class="w-amount overtimeRow"><p class=""></p></td>
                                                            <td class="w-amount allowanceRow"><p class=""></p></td>
                                                            <td class="w-amount expresswayRow"><p class=""></p></td>
                                                            <td class="w-amount parkingRow"><p class=""></p></td>
                                                            <td class="w-amount vehicleRow"><p class=""></p></td>
                                                            @php
                                                                $count++;
                                                            @endphp
                                                        </tr>
                                                    @endfor
                                                @endif
                                            @endif
                                        @endforeach
                                        {{-- 16行に合わせるため --}}
                                        @for ($i = 0; $i < $needRowCount; $i++)
                                            <tr @if($count == 0) class="dayInfirst" @endif>
                                                @if ($i == 0)
                                                <td rowspan="{{ $needRowCount }}" class="w-amount"></td>
                                                @endif
                                                <td class="w-project"><p class=""></p></td>
                                                <td class="w-amount amountRow"></td>
                                                <td class="w-amount overtimeRow"></td>
                                                <td class="w-amount allowanceRow"></td>
                                                <td class="w-amount expresswayRow"></td>
                                                <td class="w-amount parkingRow"></td>
                                                <td class="w-amount vehicleRow"></td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                                {{-- メインカレンダー --}}
                                <table class="driver-invoice-shift__calendar__calendar-top-wrap__table">
                                    <thead>
                                        <tr>
                                            <th>日付</th>
                                            <th>案件名</th>
                                            <th class="amountRow">金額</th>
                                            <th class="overtimeRow">残業代</th>
                                            <th class="allowanceRow">手当</th>
                                            <th class="expresswayRow">高速<br>料金</th>
                                            <th class="parkingRow">駐車<br>料金</th>
                                            <th class="vehicleRow">車両</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $dates as $date )
                                            @php
                                                // 行数カウント
                                                $rowCount = 0;
                                                $count = 0;
                                            @endphp
                                            @if ($date->format('d') >= 16)
                                                {{-- 日付　休日、祝日の分岐 --}}
                                                @foreach ( $shiftProjectVehicles as $spv )
                                                    @if ($spv->shift->date == $date->format('Y-m-d'))
                                                        {{-- @foreach ( $shift->projectsVehicles as $spv ) --}}
                                                            <tr @if($count == 0) class="dayInfirst" @endif>
                                                                @if ($count == 0)
                                                                    @if ($holidays->isHoliday($date))
                                                                        <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @elseif ($date->isSaturday())
                                                                        <td rowspan="{{ $needRowCount }}" style="color: skyblue;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @elseif($date->isSunday())
                                                                        <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @else
                                                                        <td rowspan="{{ $needRowCount }}"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                    @endif
                                                                @endif
                                                                {{-- 案件名 --}}
                                                                <td class="w-project shiftActive editShift">
                                                                    @if ($spv->project)
                                                                        @php
                                                                            $suspendedValidation = '';
                                                                            if($spv->project->is_suspended == 1){
                                                                                $suspendedValidation = 'red';
                                                                            }
                                                                        @endphp
                                                                        @if ($spv->initial_project_name)
                                                                            <p class="" style="color:{{ $suspendedValidation }};">{{ $spv->initial_project_name }}</p>
                                                                        @else
                                                                            <p class="" style="color:{{ $suspendedValidation }};">{{ $spv->project->name }}</p>
                                                                        @endif
                                                                    @else
                                                                        <p class="" style="color: red;">{{ $spv->unregistered_project }}</p>
                                                                    @endif
                                                                    {{-- モーダルが取得する情報 --}}
                                                                    <input hidden type="text" value="{{ $date->format('Y-m-d') }}" class="shiftDate" data-year="{{ $date->format('Y') }}" data-month="{{ $date->format('n') }}" data-day="{{ $date->format('j') }}">
                                                                    <input hidden type="text" class="shiftValues"
                                                                         data-id="{{ $spv->id }}" data-project-id="{{ $spv->project_id }}"
                                                                         data-unproject-name="{{ $spv->unregistered_project }}" data-time-of-part="{{ $spv->time_of_day }}"
                                                                         data-vehicle-id="{{ $spv->vehicle_id }}" data-retail-price="{{ $spv->retail_price }}"
                                                                         data-driver-price="{{ $spv->driver_price }}" >
                                                                </td>
                                                                {{-- ドライバー価格 --}}
                                                                <td class="w-amount amountRow"><input type="text" value="{{ number_format($spv->driver_price) }}" name="driver_price[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 残業代 --}}
                                                                <td class="w-amount overtimeRow"><input type="text" value="{{ number_format($spv->overtime_fee) }}" name="overtime_fee[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 手当 --}}
                                                                <td class="w-amount allowance-area allowanceRow">
                                                                    <input type="text" value="{{ number_format($spv->total_allowance) }}" name="allowance[{{$spv->id}}]" class="allowance-input commaInput">
                                                                    {{-- @if ($spv->project)
                                                                        @foreach ($allowanceProject as $value)
                                                                            @if ($value->project_id == $spv->project->id)
                                                                            <input hidden class="allowanceName" type="text"
                                                                                value="{{$value->allowanceName}}">
                                                                            <input hidden class="amount" type="text"
                                                                                value="{{$value->amount}}">
                                                                            @endif
                                                                        @endforeach
                                                                    @endif --}}
                                                                </td>
                                                                {{-- 高速代 --}}
                                                                <td class="w-amount expresswayRow"><input type="text" value="{{ number_format($spv->expressway_fee) }}" name="expressway_fee[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 駐車台 --}}
                                                                <td class="w-amount parkingRow"><input type="text" value="{{ number_format($spv->parking_fee) }}" name="parking_fee[{{$spv->id}}]" class="commaInput"></td>
                                                                {{-- 車両 --}}
                                                                <td class="w-amount vehicleRow">
                                                                    {{-- 自車または月リースか判定 --}}
                                                                    @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1)
                                                                        @if ($spv->rental_vehicle_id == null) {{-- nullなら自車 --}}
                                                                            @if($spv->vehicle)
                                                                                @if ($spv->vehicle->number != '自車')
                                                                                    <input type="text" value="{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                                @endif
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle != '自車')
                                                                                    <input style="color: red;" type="text" value="{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                                @endif
                                                                            @endif
                                                                        @elseif($spv->vehicle_id != $spv->rental_vehicle_id) {{-- 契約車両と登録車両を比較 --}}
                                                                            @if($spv->vehicle)
                                                                                <input type="text" value="{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle != '自車')
                                                                                    <input style="color: red;" type="text" value="{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif($spv->vehicle_rental_type == 3)
                                                                        @if($spv->vehicle)
                                                                            @if ($spv->vehicle->number != '自車')
                                                                                <input type="text" value="{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                            @endif
                                                                        @else
                                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                                <input style="color: red;" type="text" value="{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle" readonly>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                @php
                                                                    $rowCount++;
                                                                    // 貸出形態と貸出車両を格納
                                                                    $vehicle_rantal_type = $spv->vehicle_rental_type;
                                                                    if ($spv->rentalVehicle) {
                                                                        $vehicle_rantal_number = $spv->rentalVehicle->number;
                                                                    }
                                                                    $count++;
                                                                @endphp
                                                            </tr>
                                                        {{-- @endforeach --}}
                                                    @endif
                                                @endforeach
                                                {{-- 行数が足りない時のため --}}
                                                @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                    <tr class="shiftActive createShift @if($count == 0) dayInfirst @endif">
                                                        @if ($count == 0)
                                                            @if ($holidays->isHoliday($date))
                                                                <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @elseif ($date->isSaturday())
                                                                <td rowspan="{{ $needRowCount }}" style="color: skyblue;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @elseif($date->isSunday())
                                                                <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @else
                                                                <td rowspan="{{ $needRowCount }}"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                            @endif
                                                        @endif
                                                        <td class="w-project">
                                                            <p class="">
                                                                {{-- 日付 --}}
                                                                <input hidden type="text" value="{{ $date->format('Y-m-d') }}" class="shiftDate" data-year="{{ $date->format('Y') }}" data-month="{{ $date->format('n') }}" data-day="{{ $date->format('j') }}">
                                                            </p>
                                                        </td>
                                                        <td class="w-amount amountRow"><p class=""></p></td>
                                                        <td class="w-amount overtimeRow"><p class=""></p></td>
                                                        <td class="w-amount allowanceRow"><p class=""></p></td>
                                                        <td class="w-amount expresswayRow"><p class=""></p></td>
                                                        <td class="w-amount parkingRow"><p class=""></p></td>
                                                        <td class="w-amount vehicleRow"><p class=""></p></td>
                                                        @php
                                                            // $rowCount++;
                                                            $count++;
                                                        @endphp
                                                    </tr>
                                                @endfor
                                                {{-- シフトがない時のため --}}
                                                @if ($rowCount == 0)
                                                    @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                        <tr class="shiftActive createShift @if($count == 0) dayInfirst @endif">
                                                            @if ($count == 0)
                                                                @if ($holidays->isHoliday($date))
                                                                    <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @elseif ($date->isSaturday())
                                                                    <td rowspan="{{ $needRowCount }}" style="color: skyblue;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @elseif($date->isSunday())
                                                                    <td rowspan="{{ $needRowCount }}" style="color: red;" class="w-amount"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @else
                                                                    <td rowspan="{{ $needRowCount }}"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                                                                @endif
                                                            @endif
                                                            <td class="w-project">
                                                                <p class="">
                                                                    {{-- 日付 --}}
                                                                    <input hidden type="text" value="{{ $date->format('Y-m-d') }}" class="shiftDate" data-year="{{ $date->format('Y') }}" data-month="{{ $date->format('n') }}" data-day="{{ $date->format('j') }}">
                                                                </p>
                                                            </td>
                                                            <td class="w-amount amountRow"><p class=""></p></td>
                                                            <td class="w-amount overtimeRow"><p class=""></p></td>
                                                            <td class="w-amount allowanceRow"><p class=""></p></td>
                                                            <td class="w-amount expresswayRow"><p class=""></p></td>
                                                            <td class="w-amount parkingRow"><p class=""></p></td>
                                                            <td class="w-amount vehicleRow"><p class=""></p></td>
                                                            @php
                                                                $count++;
                                                            @endphp
                                                        </tr>
                                                    @endfor
                                                @endif
                                            @endif
                                        @endforeach
                                        @for ($i = $dates[count($dates) - 1]->format('d'); $i < 31; $i++)
                                            {{-- 16行に合わせるため --}}
                                            @for ($j = 0; $j < $needRowCount; $j++)
                                                <tr class="">
                                                    @if ($j == 0)
                                                    <td rowspan="{{ $needRowCount }}" class="w-amount"></td>
                                                    @endif
                                                    <td class="w-project"><p class=""></p></td>
                                                    <td class="w-amount overtimeRow"></td>
                                                    <td class="w-amount amountRow"></td>
                                                    <td class="w-amount allowanceRow"></td>
                                                    <td class="w-amount expresswayRow"></td>
                                                    <td class="w-amount parkingRow"></td>
                                                    <td class="w-amount vehicleRow"></td>
                                                </tr>
                                            @endfor
                                        @endfor
                                    </tbody>
                                </table>
                            </form>
                            <div class="calendar-bottom-wrap">
                                <form action="{{route('invoice.driver-edit-pdf')}}" method="POST" class="calendar-bottom-wrap__box calendar-bottom-wrap__top" id="form">
                                    @csrf
                                    <textarea name="textarea" id="setTextArea" cols="30" rows="10"></textarea>
                                    <div>
                                        {{-- 共通 --}}
                                        <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                                        <input hidden type="text" name="year" value="{{$getYear}}">
                                        <input hidden type="text" name="month" value="{{$getMonth}}">

                                        <input hidden type="text" name="invoiceAmountCheck" class="setAmountCheck">
                                        <input hidden type="text" name="invoiceAllowanceCheck" class="setAllowanceCheck">
                                        <input hidden type="text" name="invoiceExpresswayCheck" class="setExpresswayCheck">
                                        <input hidden type="text" name="invoiceParkingCheck" class="setParkingCheck">
                                        <input hidden type="text" name="invoiceVehicleCheck" class="setVehicleCheck">
                                        <input hidden type="text" name="invoiceOvertimeCheck" class="setOvertimeCheck">
                                        {{-- 絞り込み情報 --}}
                                        @foreach ($clientsId as $clientId)
                                            <input hidden type="text" name="invoiceClientsId[]" value="{{ $clientId }}">
                                        @endforeach
                                        @foreach ($projectsId as $projectId)
                                            <input hidden type="text" name="invoiceProjectsId[]" value="{{ $projectId }}">
                                        @endforeach
                                        {{-- カレンダー用 --}}
                                        <input hidden name="setRowCount" type="text" class="setRowCount">

                                        <div class="total-info-table-wrap" id="infoTableParent">
                                            <div class="total-info-table-wrap__box">
                                                <table class="total-info-table">
                                                    <tbody id="totalSalaryTableBody">
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="totalSalary[name]" value="合計金額"></th>
                                                            <td><input type="text" name="totalSalary[amount]" value="{{ number_format($totalSalary) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        @foreach ($allowanceArray as $allowanceName => $allowanceData)
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="allowanceName[]" value="{{ $allowanceName }}"></th>
                                                            <td><input type="text" name="allowanceAmount[]" value="{{ number_format($allowanceData['amount']) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                            <input hidden type="text" name="allowanceCount[]" value="{{ $allowanceData['count'] }}">
                                                        </tr>
                                                        @endforeach
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="tax[name]" value="消費税"></th>
                                                            <td><input type="text" name="tax[amount]" value="{{ number_format(round(($totalSalary + $totalAllowance) * 0.1)) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="parkingName" value="パーキング代"></th>
                                                            <td><input type="text" name="parkingAmount" value="{{ number_format($totalParking) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="expressWayName" value="高速代"></th>
                                                            <td><input type="text" name="expressWayAmount" value="{{ number_format($totalExpressWay) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="overtimeName" value="残業代"></th>
                                                            <td><input type="text" name="overtimeAmount" value="{{ number_format($totalOverTime) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="row-add-btn" id="totalSalaryAddBtn">
                                                    <i class="fa-solid fa-plus"></i>
                                                </div>
                                            </div>
                                            @php
                                                $rental_type = $shiftProjectVehicles->first()->vehicle_rental_type; //契約種類を取得
                                                if($rental_type == 1){ //月リースであれば、契約車両を取得
                                                    $rental_vehicle_number = $shiftProjectVehicles->first()->rentalVehicle->number;
                                                }
                                                // その他情報
                                                $tax_late = $InfoManagement->tax_late;
                                                $monthly_lease_fee = $InfoManagement->monthly_lease_fee;
                                                $monthly_lease_insurance_fee = $InfoManagement->monthly_lease_insurance_fee;
                                                $monthly_lease_second_fee = $InfoManagement->monthly_lease_second_fee;
                                                $monthly_lease_second_insurance_fee = $InfoManagement->monthly_lease_second_insurance_fee;
                                                $prorated_lease_fee = $InfoManagement->prorated_lease_fee;
                                                $prorated_insurance_fee = $InfoManagement->prorated_insurance_fee;
                                                $admin_commission_rate = $InfoManagement->admin_commission_rate;
                                                $admin_fee_switch = $InfoManagement->admin_fee_switch;
                                                $max_admin_fee = $InfoManagement->max_admin_fee;
                                                $min_admin_fee = $InfoManagement->min_admin_fee;
                                                $transfer_fee = $InfoManagement->transfer_fee;

                                                // 事務手数料のスイッチ
                                                if($admin_fee_switch < $totalSalary){
                                                    $admin_fee = $max_admin_fee;
                                                }else{
                                                    $admin_fee = $min_admin_fee;
                                                }
                                            @endphp
                                            <p class="txt">相殺分</p>
                                            <div class="total-info-table-wrap__box">
                                                <table class="total-info-table">
                                                    <tbody id="totalCostTableBody">
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="administrativeOutsourcing[name]" value="事務委託手数料({{ round($admin_commission_rate) }}%)"></th>
                                                            <td><input type="text" name="administrativeOutsourcing[amount]" value="{{ number_format(round($totalSalary * ($admin_commission_rate / 100))) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="administrative[name]" value="事務手数料"></th>
                                                            <td><input type="text" name="administrative[amount]" value="{{ number_format($admin_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        <tr class="info-table-row">
                                                            <th><input type="text" name="transfer[name]" value="振込手数料"></th>
                                                            <td><input type="text" name="transfer[amount]" value="{{ number_format($transfer_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                        </tr>
                                                        {{-- 月リース・なんでも --}}
                                                        @if ($rental_type == 1 || $rental_type == 2)
                                                            <tr class="info-table-row">
                                                                <th><input type="text" name="monthLease[name]" value="リース代　@if($rental_type == 1) 月契約No.{{ $rental_vehicle_number }} @else なんでも @endif"></th>
                                                                <td><input type="text" name="monthLease[amount]" value="{{ number_format($monthly_lease_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                            </tr>
                                                        @endif
                                                        {{-- 二代目以降 --}}
                                                        @if ($rental_type == 0 || $rental_type == 1) {{-- 自車・月リース --}}
                                                            @if ($secondMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="secondLease[name]" value="2台目"></th>
                                                                    <td><input type="text" name="secondLease[amount]" value="{{ number_format($secondMachineCount * $monthly_lease_second_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                            @if ($thirdMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="thirdLease[name]" value="3台目"></th>
                                                                    <td><input type="text" name="thirdLease[amount]" value="{{ number_format($thirdMachineCount * $monthly_lease_second_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                        @elseif($rental_type == 2) {{-- なんでもリース --}}
                                                            @if ($thirdMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="secondLease[name]" value="2代目以降"></th>
                                                                    <td><input type="text" name="secondLease[amount]" value="{{ number_format($thirdMachineCount * $monthly_lease_second_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                        @else                      {{-- 日割り --}}
                                                            @if ($secondMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="secondLease[name]" value="日割りリース"></th>
                                                                    <td><input type="text" name="secondLease[amount]" value="{{ number_format(($secondMachineCount + $thirdMachineCount) * $prorated_lease_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                        {{-- 保険料 --}}
                                                        @if ($rental_type == 1 || $rental_type == 2) {{-- 月リース・なんでもリース --}}
                                                            <tr class="info-table-row">
                                                                <th><input type="text" name="monthInsurance[name]" value="保険料　@if($rental_type == 1) 月契約No.{{ $rental_vehicle_number }} @endif"></th>
                                                                <td><input type="text" name="monthInsurance[amount]" value="{{ number_format(13637) }}" class="commaInput"><div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                            </tr>
                                                        @endif
                                                        {{-- 二代目以降保険料 --}}
                                                        @if ($rental_type == 0 || $rental_type == 1) {{-- 自車・月リース --}}
                                                            @if ($secondMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="secondInsurance[name]" value="保険料 2台目以降"></th>
                                                                    <td><input type="text" name="secondInsurance[amount]" value="{{ number_format(($secondMachineCount + $thirdMachineCount) * $monthly_lease_second_insurance_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                        @elseif($rental_type == 2)                   {{-- なんでも月リース --}}
                                                            @if ($thirdMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="secondInsurance[name]" value="保険料 2台目以降"></th>
                                                                    <td><input type="text" name="secondInsurance[amount]" value="{{ number_format($thirdMachineCount * $monthly_lease_second_insurance_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                        @else                                       {{-- 日割り --}}
                                                            @if ($secondMachineCount != 0)
                                                                <tr class="info-table-row">
                                                                    <th><input type="text" name="secondInsurance[name]" value="日割り保険料"></th>
                                                                    <td><input type="text" name="secondInsurance[amount]" value="{{ number_format(($secondMachineCount + $thirdMachineCount) * $prorated_insurance_fee) }}" class="commaInput"> <div class="row-delete-btn delete-btn-target"><i class="fa-solid fa-minus delete-btn-target"></i></div></td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                    </tbody>
                                                </table>
                                                <div class="row-add-btn" id="totalCostAddBtn">
                                                    <i class="fa-solid fa-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <input hidden type="submit" id="gearingBtn">
                                    </div>
                                </form>
                                <div class="calendar-bottom-wrap__box">
                                    <table class="shift-info-table">
                                        <thead>
                                            <tr>
                                                <th>案件名</th>
                                                <th>金額</th>
                                                <th>件数</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($projectInfoArray as $projectName => $data)
                                                @foreach ($data as $price => $count)
                                                    <tr>
                                                        <td>{{ $projectName }}</td>
                                                        <td>{{ number_format((float)$price) }}</td>
                                                        <td>{{ $count }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($rental_type != 2)
                                        <table class="shift-info-table --vehicle-table">
                                            <thead>
                                                <tr>
                                                    <th>2台目車両</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($secondMachineArray as $number)
                                                    @if ($number != null)
                                                        <tr>
                                                            <td>{{ $number }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <table class="shift-info-table --vehicle-table">
                                            <thead>
                                                <tr>
                                                    <th>3台目車両</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($thirdMachineArray as $number)
                                                    <tr>
                                                        <td>{{ $number }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <table class="shift-info-table --vehicle-table">
                                            <thead>
                                                <tr>
                                                    <th>2台目車両</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($thirdMachineArray as $number)
                                                    <tr>
                                                        <td>{{ $number }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="warning-txt">{{ $warning }}</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @if ($shifts !== null && !$shifts->isEmpty())
    {{-- 手当モーダル --}}
    <div class="allowance-modal-wrap" id="allowance-modal">
        <span class="allowance-modal-wrap__bg allowanceModalBg"></span>
        <div class="allowance-modal-wrap__modal">
            <div class="allowance-modal-wrap__modal__inner">
                <div class="radio-area">
                    <div class="radio-area__item">
                        <input name="swichAllowance" class="allowanceRadio" type="radio" id="allowanceS"
                            checked>
                        <label for="allowanceS">手当選択</label>
                    </div>
                    <div class="radio-area__item">
                        <input name="swichAllowance" class="allowanceRadio" type="radio" id="allowance">
                        <label for="allowance">手当入力</label>
                    </div>
                </div>
                <div class="input-area">
                    <select name="" id="allowanceSelect" class="c-select radioRalation">
                        <option value="0">選択してください</option>
                    </select>
                    <input type="text" id="allowanceInput" class="c-input radioRalation"
                        placeholder="金額を入力">
                </div>
                <div class="btn-box">
                    <div class="allowance-modal-btn modalClose">
                        <p class="">選択</p>
                    </div>
                    <div class="btn --back modalClose">
                        <p class="">戻る</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 車両モーダル --}}
    <div class="vehicle-modal-wrap" id="vehicleModal">
        <span class="vehicle-modal-wrap__bg modalClose"></span>
        <div class="vehicle-modal-wrap__modal">
            <div class="vehicle-modal-wrap__modal__inner">
                <div class="radio-area">
                    <div class="radio-area__item">
                        <input name="swichVehicle" class="vehicleRadio" type="radio" id="vehicle-01"
                            checked>
                        <label for="vehicle-01">車両選択</label>
                    </div>
                    <div class="radio-area__item">
                        <input name="swichVehicle" class="vehicleRadio" type="radio" id="vehicle-02">
                        <label for="vehicle-02">車両入力</label>
                    </div>
                </div>
                <div class="input-area">
                    <select name="" id="vehicleSelect" class="c-select vehicleRadioRalation">
                        <option value="">選択してください</option>
                        @foreach ($vehicles as $vehicle)
                        <option value="{{$vehicle->number}}">{{$vehicle->number}}</option>
                        @endforeach
                    </select>
                    <input type="text" id="vehicleInput"
                        class="input-area__input c-input vehicleRadioRalation" placeholder="ナンバー">
                </div>
                <div class="btn-box">
                    <div class="vehicle-modal-btn">
                        <p class="">選択</p>
                    </div>
                    <div class="btn --back modalClose">
                        <p class="">戻る</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/invoice-driver.js')}}"></script>
