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
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="c-select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
                                    <option value="">----</option>
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

                            <div class="c-select-area__block name-block">
                                <select name="employeeId" id="" class="c-select name-select" required>
                                    <option value="">----</option>
                                    @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="c-search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>
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
                            <form action="{{route('invoice.driver-edit-pdf')}}" method="POST" class="c-middle-head__button">
                                @csrf
                                <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                                <input hidden type="text" name="year" value="{{$getYear}}">
                                <input hidden type="text" name="month" value="{{$getMonth}}">

                                <input hidden type="text" name="invoiceAmountCheck" id="invoiceAmountCheck">
                                <input hidden type="text" name="invoiceAllowanceCheck" id="invoiceAllowanceCheck">
                                <input hidden type="text" name="invoiceExpresswayCheck" id="invoiceExpresswayCheck">
                                <input hidden type="text" name="invoiceParkingCheck" id="invoiceParkingCheck">
                                <input hidden type="text" name="invoiceVehicleCheck" id="invoiceVehicleCheck">
                                <input hidden type="text" name="invoiceOvertimeCheck" id="invoiceOvertimeCheck">
                                <button>
                                    請求書確認
                                </button>
                            </form>
                        </div>
                        {{-- チェックエリア --}}
                        <form action="{{route('invoice.driver-calendar-pdf')}}" method="POST" class="driver-invoice-shift__check-area">
                            @csrf
                            <div class="top">
                                <div class="check-item">
                                    <input type="checkbox" name="amountCheck" value="1" class="" id="amountCheck">
                                    <label for="">金額</label>
                                </div>
                                <div class="check-item">
                                    <input type="checkbox" name="allowanceCheck" value="1" class="" id="allowanceCheck">
                                    <label for="">手当</label>
                                </div>
                                <div class="check-item">
                                    <input type="checkbox" name="expresswayCheck" value="1" class="" id="expresswayCheck">
                                    <label for="">高速代</label>
                                </div>
                                <div class="check-item">
                                    <input type="checkbox" name="parkingCheck" value="1" class="" id="parkingCheck">
                                    <label for="">パーキング代</label>
                                </div>
                                <div class="check-item">
                                    <input type="checkbox" name="vehicleCheck" value="1" class="" id="vehicleCheck">
                                    <label for="">2台目以降</label>
                                </div>
                                <div class="check-item">
                                    <input type="checkbox" name="overtimeCheck" value="1" class="" id="overtimeCheck">
                                    <label for="">残業代</label>
                                </div>
                            </div>
                            <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <textarea hidden name="textarea" id="setTextArea" cols="30" rows="10"></textarea>
                            <button class="">
                                ダウンロード
                            </button>
                        </form>
                        {{-- カレンダー --}}
                        <div class="driver-invoice-shift__calendar">
                            <form action="{{route('invoice.driverShiftUpdate')}}" method="POST" class="driver-invoice-shift__calendar__calendar-top-wrap">
                                <button class="driver-invoice-shift__calendar__calendar-top-wrap__save-btn">
                                    変更内容を保存
                                </button>
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
                                            <th class="allowanceRow">手当</th>
                                            <th class="expresswayRow">高速代</th>
                                            <th class="parkingRow">パーキング代</th>
                                            <th class="vehicleRow">2台目以降</th>
                                            <th class="overtimeRow">残業代</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // 基本の行数
                                            $needRowCount = 3;
                                            foreach ($shifts as $shift) {
                                                if ($needRowCount < $shift->projectsVehicles->count()) {
                                                    $needRowCount = $shift->projectsVehicles->count();
                                                }
                                            }
                                        @endphp
                                        {{-- <p>{{ $needRowCount }}</p> --}}
                                        @foreach ( $dates as $date )
                                            @php
                                                // 行数カウント
                                                $rowCount = 0;
                                                $count = 0;
                                            @endphp
                                            @if ($date->format('d') < 16)
                                                {{-- 日付　休日、祝日の分岐 --}}
                                                @foreach ( $shifts as $shift )
                                                    @if ($shift->date == $date->format('Y-m-d'))
                                                        @foreach ( $shift->projectsVehicles as $spv )
                                                            <tr>
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
                                                                    @if ($spv->project)
                                                                        {{-- <input type="text" value="{{ $spv->project->name }}" class=""> --}}
                                                                        <p class="">{{ $spv->project->name }}</p>
                                                                    @else
                                                                        <p class="" style="color: red;">{{ $spv->unregistered_project }}</p>
                                                                    @endif
                                                                </td>
                                                                <td class="w-amount amountRow"><input type="text" value="{{ number_format($spv->driver_price) }}" name="driver_price[{{$spv->id}}]" class=""></td>
                                                                <td class="w-amount allowance-area allowanceRow">
                                                                    <input type="text" value="{{ number_format($spv->total_allowance) }}" name="allowance[{{$spv->id}}]" class="allowance-input">
                                                                    @if ($spv->project)
                                                                        @foreach ($allowanceProject as $value)
                                                                            @if ($value->project_id == $spv->project->id)
                                                                            <input hidden class="allowanceName" type="text"
                                                                                value="{{$value->allowanceName}}">
                                                                            <input hidden class="amount" type="text"
                                                                                value="{{$value->amount}}">
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td class="w-amount expresswayRow"><input type="text" value="{{ number_format($spv->expressway_fee) }}" name="expressway_fee[{{$spv->id}}]" class=""></td>
                                                                <td class="w-amount parkingRow"><input type="text" value="{{ number_format($spv->parking_fee) }}" name="parking_fee[{{$spv->id}}]" class=""></td>
                                                                <td class="w-amount vehicleRow">
                                                                    {{-- 自車または月リースか判定 --}}
                                                                    @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1)
                                                                        @if ($spv->rental_vehicle_id == null) {{-- nullなら自車 --}}
                                                                            @if($spv->vehicle)
                                                                                @if ($spv->vehicle->number != '自車')
                                                                                    <input type="text" value="No.{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                                @endif
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle)
                                                                                    <input style="color: red;" type="text" value="No.{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                                @endif
                                                                            @endif
                                                                        @elseif($spv->vehicle_id != $spv->rental_vehicle_id) {{-- 契約車両と登録車両を比較 --}}
                                                                            @if($spv->vehicle)
                                                                                <input type="text" value="No.{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle)
                                                                                    <input style="color: red;" type="text" value="No.{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td class="w-amount overtimeRow"><input type="text" value="{{ number_format($spv->overtime_fee) }}" name="overtime_fee[{{$spv->id}}]" class="mainVehicle"></td>
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
                                                        @endforeach
                                                        {{-- 行数が足りない時のため --}}
                                                        @for ($rowCount; $rowCount <= $needRowCount; $rowCount++)
                                                            <tr>
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
                                                                <td class="w-project"><p class=""></p></td>
                                                                <td class="w-amount amountRow"><p class=""></p></td>
                                                                <td class="w-amount allowanceRow"><p class=""></p></td>
                                                                <td class="w-amount expresswayRow"><p class=""></p></td>
                                                                <td class="w-amount parkingRow"><p class=""></p></td>
                                                                <td class="w-amount vehicleRow"><p class=""></p></td>
                                                                <td class="w-amount overtimeRow"><p class=""></p></td>
                                                                @php
                                                                    $rowCount++;
                                                                    $count++;
                                                                @endphp
                                                            </tr>
                                                        @endfor
                                                    @endif
                                                @endforeach
                                                {{-- シフトがない時のため --}}
                                                @if ($rowCount == 0)
                                                    @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                        <tr>
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
                                                            <td class="w-project"><p class=""></p></td>
                                                            <td class="w-amount amountRow"><p class=""></p></td>
                                                            <td class="w-amount allowanceRow"><p class=""></p></td>
                                                            <td class="w-amount expresswayRow"><p class=""></p></td>
                                                            <td class="w-amount parkingRow"><p class=""></p></td>
                                                            <td class="w-amount vehicleRow"><p class=""></p></td>
                                                            <td class="w-amount overtimeRow"><p class=""></p></td>
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
                                            <tr class="">
                                                @if ($i == 0)
                                                <td rowspan="{{ $needRowCount }}" class="w-amount"></td>
                                                @endif
                                                <td class="w-project"><p class=""></p></td>
                                                <td class="w-amount amountRow"></td>
                                                <td class="w-amount allowanceRow"></td>
                                                <td class="w-amount expresswayRow"></td>
                                                <td class="w-amount parkingRow"></td>
                                                <td class="w-amount vehicleRow"></td>
                                                <td class="w-amount overtimeRow"></td>
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
                                            <th class="allowanceRow">手当</th>
                                            <th class="expresswayRow">高速代</th>
                                            <th class="parkingRow">パーキング代</th>
                                            <th class="vehicleRow">2台目以降</th>
                                            <th class="overtimeRow">残業代</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @php
                                            // 基本の行数
                                            $needRowCount = 3;
                                            foreach ($shifts as $shift) {
                                                if ($needRowCount < $shift->projectsVehicles->count()) {
                                                    $needRowCount = $shift->projectsVehicles->count();
                                                }
                                            }
                                        @endphp --}}
                                        @foreach ( $dates as $date )
                                            @php
                                                // 行数カウント
                                                $rowCount = 0;
                                                $count = 0;
                                            @endphp
                                            @if ($date->format('d') >= 16)
                                                {{-- 日付　休日、祝日の分岐 --}}
                                                @foreach ( $shifts as $shift )
                                                    @if ($shift->date == $date->format('Y-m-d'))
                                                        @foreach ( $shift->projectsVehicles as $spv )
                                                            <tr>
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
                                                                    @if ($spv->project)
                                                                        {{-- <input type="text" value="{{ $spv->project->name }}" class=""> --}}
                                                                        <p class="">{{ $spv->project->name }}</p>
                                                                    @else
                                                                        <p class="" style="color: red;">{{ $spv->unregistered_project }}</p>
                                                                    @endif
                                                                </td>
                                                                <td class="w-amount amountRow"><input type="text" value="{{ number_format($spv->driver_price) }}" name="driver_price[{{$spv->id}}]" class=""></td>
                                                                <td class="w-amount allowance-area allowanceRow">
                                                                    <input type="text" value="{{ number_format($spv->total_allowance) }}" name="allowance[{{$spv->id}}]" class="allowance-input">
                                                                    @if ($spv->project)
                                                                        @foreach ($allowanceProject as $value)
                                                                            @if ($value->project_id == $spv->project->id)
                                                                            <input hidden class="allowanceName" type="text"
                                                                                value="{{$value->allowanceName}}">
                                                                            <input hidden class="amount" type="text"
                                                                                value="{{$value->amount}}">
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td class="w-amount expresswayRow"><input type="text" value="{{ number_format($spv->expressway_fee) }}" name="expressway_fee[{{$spv->id}}]" class=""></td>
                                                                <td class="w-amount parkingRow"><input type="text" value="{{ number_format($spv->parking_fee) }}" name="parking_fee[{{$spv->id}}]" class=""></td>
                                                                <td class="w-amount vehicleRow">
                                                                    {{-- 自車または月リースか判定 --}}
                                                                    @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1)
                                                                        @if ($spv->rental_vehicle_id == null) {{-- nullなら自車 --}}
                                                                            @if($spv->vehicle)
                                                                                @if ($spv->vehicle->number != '自車')
                                                                                    <input type="text" value="No.{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                                @endif
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle)
                                                                                    <input style="color: red;" type="text" value="No.{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                                @endif
                                                                            @endif
                                                                        @elseif($spv->vehicle_id != $spv->rental_vehicle_id) {{-- 契約車両と登録車両を比較 --}}
                                                                            @if($spv->vehicle)
                                                                                <input type="text" value="No.{{ $spv->vehicle->number }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                            @else
                                                                                @if ($spv->unregistered_vehicle)
                                                                                    <input style="color: red;" type="text" value="No.{{ $spv->unregistered_vehicle }}" name="vehicle[{{$spv->id}}]" class="mainVehicle">
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td class="w-amount overtimeRow"><input type="text" value="{{ number_format($spv->overtime_fee) }}" name="overtime_fee[{{$spv->id}}]" class="mainVehicle"></td>
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
                                                        @endforeach
                                                        {{-- 行数が足りない時のため --}}
                                                        @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                            <tr>
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
                                                                <td class="w-project"><p class=""></p></td>
                                                                <td class="w-amount amountRow"><p class=""></p></td>
                                                                <td class="w-amount allowanceRow"><p class=""></p></td>
                                                                <td class="w-amount expresswayRow"><p class=""></p></td>
                                                                <td class="w-amount parkingRow"><p class=""></p></td>
                                                                <td class="w-amount vehicleRow"><p class=""></p></td>
                                                                <td class="w-amount overtimeRow"><p class=""></p></td>
                                                                @php
                                                                    // $rowCount++;
                                                                    $count++;
                                                                @endphp
                                                            </tr>
                                                        @endfor
                                                    @endif
                                                @endforeach
                                                {{-- シフトがない時のため --}}
                                                @if ($rowCount == 0)
                                                    @for ($rowCount; $rowCount < $needRowCount; $rowCount++)
                                                        <tr>
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
                                                            <td class="w-project"><p class=""></p></td>
                                                            <td class="w-amount amountRow"><p class=""></p></td>
                                                            <td class="w-amount allowanceRow"><p class=""></p></td>
                                                            <td class="w-amount expresswayRow"><p class=""></p></td>
                                                            <td class="w-amount parkingRow"><p class=""></p></td>
                                                            <td class="w-amount vehicleRow"><p class=""></p></td>
                                                            <td class="w-amount overtimeRow"><p class=""></p></td>
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
                                                    <td class="w-amount amountRow"></td>
                                                    <td class="w-amount allowanceRow"></td>
                                                    <td class="w-amount expresswayRow"></td>
                                                    <td class="w-amount parkingRow"></td>
                                                    <td class="w-amount vehicleRow"></td>
                                                    <td class="w-amount overtimeRow"></td>
                                                </tr>
                                            @endfor
                                        @endfor
                                    </tbody>
                                </table>
                            </form>
                            <div class="calendar-bottom-wrap">
                                <div class="calendar-bottom-wrap__box calendar-bottom-wrap__top">
                                    <textarea name="" id="getTextArea" cols="30" rows="10"></textarea>
                                    <table class="total-info-table">
                                        <tbody>
                                            <tr>
                                                <th>合計金額</th>
                                                <td>{{ number_format($totalSalary) }}</td>
                                            </tr>
                                            <tr>
                                                <th>案件別手当</th>
                                                <td>{{ number_format($totalAllowance) }}</td>
                                            </tr>
                                            <tr>
                                                <th>消費税</th>
                                                <td>{{ number_format($totalSalary * 0.1) }}</td>
                                            </tr>
                                            <tr>
                                                <th>パーキング代</th>
                                                <td>{{ number_format($totalParking) }}</td>
                                            </tr>
                                            <tr>
                                                <th>高速代</th>
                                                <td>{{ number_format($totalExpressWay) }}</td>
                                            </tr>
                                            <tr>
                                                <th>残業代</th>
                                                <td>{{ number_format($totalOverTime) }}</td>
                                            </tr>
                                            <tr>
                                                <th>事務委託手数料(15%)</th>
                                                <td>{{ number_format(ceil($totalSalary * 0.15)) }}</td>
                                            </tr>
                                            <tr>
                                                <th>事務手数料</th>
                                                <td>10,000</td>
                                            </tr>
                                            <tr>
                                                <th>振込手数料</th>
                                                <td>600</td>
                                            </tr>
                                            @if ($vehicle_rantal_type == 1 || $vehicle_rantal_type == 2)
                                                <tr>
                                                    <th>リース代　月契約No.{{ $vehicle_rantal_number }}</th>
                                                    <td>30,992</td>
                                                </tr>
                                            @endif
                                            @if ($secondMachineCount != 0)
                                                @php
                                                    $second_lease = 1000;
                                                    if ($vehicle_rantal_type == 0) {
                                                        $second_lease = 1500;
                                                    }
                                                @endphp
                                                <tr>
                                                    <th>リース2台目(日割り)</th>
                                                    <td>{{ number_format($secondMachineCount * $second_lease) }}</td>
                                                </tr>
                                            @endif
                                            @if ($thirdMachineCount != 0)
                                                <tr>
                                                    <th>リース3台目(日割り)</th>
                                                    <td>{{ number_format($thirdMachineCount * 1000) }}</td>
                                                </tr>
                                            @endif
                                            @if ($vehicle_rantal_type == 1 || $vehicle_rantal_type == 2)
                                                <tr>
                                                    <th>保険料　月契約No.{{ $vehicle_rantal_number }}</th>
                                                    <td>9,818</td>
                                                </tr>
                                            @endif
                                            @if ($secondMachineCount != 0)
                                                <tr>
                                                    <th>保険料 2台目(日割り)</th>
                                                    <td>{{ number_format($secondMachineCount * 410) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
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
                                </div>
                            </div>
                        </div>
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
                        <option value="">選択してください</option>
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
