<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト') }}
        </h2>
    </x-slot>

    {{-- <script>
        window.onbeforeunload = function(e) {
            e.preventDefault();
            return '';
        };

    </script> --}}

    <main class="main --shift-main">
        <div class="main__link-block --shift-link-block">
            <div class="main__link-block__tags">
                @can('admin-higher')
                <form action="{{route('shift.')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page01" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button class="{{ request()->routeIs('shift.', 'shift.selectWeek') ? 'active' : '' }} link">
                        <span class="">全表示</span>
                    </button>
                </form>
                @endcan
                <form action="{{route('shift.employeeShowShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page02" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button class="{{ request()->routeIs('shift.employeeShowShift*') ? 'active' : '' }} link">
                        <span class="">稼働表</span>
                    </button>
                </form>
                <form action="{{route('shift.employeePriceShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page03" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button class="{{ request()->routeIs('shift.employeePriceShift*') ? 'active' : '' }} link">
                        @can('admin-higher')
                            <span class="">ドライバー価格</span>
                        @else
                            <span class="">配送料金</span>
                        @endcan
                    </button>
                </form>
                @can('admin-higher')
                <form action="{{route('shift.projectPriceShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page04" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button class="{{ request()->routeIs('shift.projectPriceShift*') ? 'active' : '' }} link">
                        <span class="">上代閲覧用</span>
                    </button>
                </form>
                @endcan
                <form action="{{route('shift.projectCount')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page05" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button class="{{ request()->routeIs('shift.projectCount') ? 'active' : '' }} link">
                        <span class="">案件数用</span>
                    </button>
                </form>
            </div>
            @can('admin-higher')
                <div class="--shift-link-block__btn-area">
                    {{-- シフト編集 --}}
                    <form action="{{route('shift.edit')}}" method="POST" class="icon-block">
                        @csrf
                        <input hidden name="witch" value="page06" type="text">
                        <input hidden type="text" name="date" value="{{$startOfWeek}}">
                        <button class="{{ request()->routeIs('shift.edit*') ? 'active' : '' }} icon-block__button">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </form>
                    {{-- CSVインポート --}}
                    <a href="{{route('shift.csv')}}" class="icon-block">
                        <div class="{{ request()->routeIs('shift.csv') ? 'active' : '' }} icon-block__button">
                            <i class="fa-solid fa-file-import"></i>
                        </div>
                    </a>
                </div>
            @endcan
        </div>
        <div class="main__white-board">
            <?php $count = 0;?>
            <?php $checkCnt = true?>
            <?php
                $retailTotal = [];
                $driverTotal = [];
                $tmpEmployee = null;
            ?>
            <div class="shift-calendar">
                <div class="shift-calendar__color-nav">
                    <div class="shift-calendar__color-nav__item">
                        <div class="color-box"></div>
                        <p class="">　: 上代</p>
                    </div>
                    <div class="shift-calendar__color-nav__item">
                        <div class="color-box"></div>
                        <p class="">　：　ドライバー価格</p>
                    </div>
                </div>
                {{-- 日付の表示 --}}
                <div class="shift-calendar__date">
                    <form action="{{route('shift.editSelectWeek')}}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{$startOfWeek}}">
                        <input type="hidden" name="action" value="previous">
                        <input hidden name="witch" value="page06" type="text">
                        <button type="submit" class="">
                            <i class="fa-solid fa-angle-left date-angle"></i>
                        </button>
                    </form>
                    <div class="shift-calendar__date__show">
                        <div class="date">
                            <div class="date__txt">
                                <p class="fs-14">{{$monday->format('Y')}}<span class="fs-10">年</span></p>
                            </div>
                            <div class="date__txt">
                                <p class="fs-16">{{$monday->format('n')}}<span
                                        class="fs-10">月</span>{{$monday->format('j')}}<span class="fs-10">日</span></p>
                            </div>
                        </div>
                        <div class="date">
                            <div class="date__txt">
                                <p class="fs-14">{{$sunday->format('Y')}}<span class="fs-10">年</span></p>
                            </div>
                            <div class="date__txt">
                                <p class="fs-16">{{$sunday->format('n')}}<span
                                        class="fs-10">月</span>{{$sunday->format('j')}}<span class="fs-10">日</span></p>
                            </div>
                        </div>
                    </div>
                    <form action="{{route('shift.editSelectWeek')}}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{$endOfWeek}}">
                        <input type="hidden" name="action" value="next">
                        <input hidden name="witch" value="page06" type="text">
                        <button type="submit" class="">
                            <i class="fa-solid fa-angle-right date-angle"></i>
                        </button>
                    </form>
                </div>
                {{-- カレンダー検索 --}}
                <form action="{{route('shift.editSelectWeek')}}" class="datepicker" method="POST">
                    @csrf
                    <div class="date01">
                        <label for="" class="date01__label">
                            <input type="date" id="date" name="date" class="datepicker__input">
                        </label>
                    </div>
                    <input hidden name="witch" value="page06" type="text">
                    <button type="submit" class="datepicker__button">
                        検索
                    </button>
                </form>

                {{-- カレンダー表示 --}}
                <div class="shift-calendar__main">
                    @if(!$shiftDataByEmployee->isEmpty())
                    <div class="company-view" id="companyView">

                    </div>
                    <table class="shift-calendar-table">
                        <thead class="shift-calendar-table__head">
                            <tr class="shift-calendar-table__head__day">
                                <th rowspan="2" class="date-empty-box"></th>
                                @foreach ( $convertedDates as $date )
                                    <th colspan="2" class="txt">
                                        @if ($holidays->isHoliday($date))
                                            <p class="" style="color: red;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                                        @elseif ($date->isSaturday())
                                            <p class="" style="color: skyblue;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                                        @elseif($date->isSunday())
                                            <p class="" style="color: red;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                                        @else
                                            <p class="">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                            <tr class="shift-calendar-table__head__part">
                                @foreach ( $convertedDates as $date )
                                <th class="txt">
                                    <p class="">AM</p>
                                </th>
                                <th class="txt">
                                    <p class="">PM</p>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="shift-calendar-table__body">
                            @foreach ( $sortedShiftDataByEmployee as $employeeId => $shiftData )
                                @php
                                    // 一周目だけ従業員表示
                                    $is_employee_open = true;
                                    // 1日ごとの最大案件数
                                    $max_count = 1;
                                @endphp
                                {{-- 最大案件数の計算 --}}
                                @php
                                    foreach ($shiftData as $shift) {
                                        $am_count = 0;
                                        $pm_count = 0;
                                        foreach ($shift->projectsVehicles as $spv) {
                                            $count = 0;
                                            if($spv->time_of_day == 0){
                                                $am_count++;
                                            }
                                            if($spv->time_of_day == 1){
                                                $pm_count++;
                                            }
                                        }
                                        if($max_count < $am_count){
                                            $max_count=$am_count;
                                        }
                                        if ($max_count < $pm_count) {
                                            $max_count=$pm_count;
                                        }
                                    }
                                @endphp
                                <tr class="shift-calendar-table__body__row getRow">
                                    {{-- 左側の会社の列作成のため --}}
                                    @if ($shift->employee)
                                        <td class="td-none companyInfo" data-company-name="{{ $shift->employee->company->name }}">
                                    @endif
                                        @foreach ( $shiftData as $shift ) {{-- $shift == 1日のシフト --}}
                                            {{-- 一周目だけ従業員表示 --}}
                                            @if ($is_employee_open)
                                                <td class="table-employee-name">
                                                    <div class="table-employee-name__block">
                                                        @if ($shift->employee)
                                                            <p class="">{{$shift->employee->name}}</p>
                                                        @else
                                                            <p class="" style="color: red;">{{$shift->unregistered_employee}}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                @php
                                                    $is_employee_open = false;
                                                @endphp
                                            @endif
                                            @php

                                                $am_check_count = 0;
                                                $pm_check_count = 0;

                                                foreach ($convertedDates as $date) {
                                                    if ($shift->date == $date->format('Y-m-d')) {
                                                        $findYear = $date->format('Y');
                                                        $findMonth = $date->format('m');
                                                        $findDate = $date->format('d');
                                                    }
                                                }
                                            @endphp
                                        {{-- 午前 --}}
                                        <td class="table-cell">
                                            @foreach ( $shift->projectsVehicles as $spv )
                                                @if ($spv->time_of_day == 0)
                                                    <div class="table-cell__item hover-item targetShift">
                                                        {{-- 隠しデータ --}}
                                                        <input hidden type="text" value="{{$spv->id}}" name="" class="shiftId">
                                                        <input hidden type="text" value="{{$findYear}}" class="findYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="findMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="findDate">
                                                        <input hidden type="text" value="0" class="timeOfPart">
                                                        @if ($spv->project)
                                                            <input hidden type="text" value="{{$spv->project->name}}" name=""
                                                                class="projectName">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_project}}" name=""
                                                                class="projectName">
                                                        @endif
                                                        @if ($spv->vehicle)
                                                            <input hidden type="text" value="{{$spv->vehicle->number}}" name=""
                                                                class="vehicleNumber">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_vehicle}}" name=""
                                                                class="vehicleNumber">
                                                        @endif
                                                            <input hidden type="text" value="{{$spv->retail_price}}" name=""
                                                                class="retailPrice">
                                                            <input hidden type="text" value="{{$spv->driver_price}}" name=""
                                                                class="salaryPrice">
                                                        @if ($shift->employee)
                                                            <input hidden type="text" value="{{$shift->employee->name}}"
                                                                class="employeeName">
                                                        @endif
                                                        <input hidden type="text" value="" name="">

                                                        {{-- データ表示 --}}
                                                        @if ($spv->project)
                                                            @if ($spv->initial_project_name)
                                                                <p class="table-cell__item__row setHightElem">{{$spv->initial_project_name}}</p>
                                                            @else
                                                                <p class="table-cell__item__row setHightElem">{{$spv->project->name}}</p>
                                                            @endif
                                                        @elseif($spv->unregistered_project)
                                                            <p class="table-cell__item__row setHightElem" style="color: red;">
                                                                {{$spv->unregistered_project}}</p>
                                                        @else
                                                            <p class="table-cell__item__row setHightElem"></p>
                                                        @endif
                                                        {{-- 車両 --}}
                                                        @if ($spv->vehicle)
                                                            <p class="table-cell__item__row vehicle-row" @if(in_array($spv->vehicle->id, $MultipleDailyUsesVehiclesArray[$shift->date])) style="background-color: yellow;" @endif>No.{{$spv->vehicle->number}}</p>
                                                        @elseif($spv->unregistered_vehicle)
                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                <p class="table-cell__item__row vehicle-row" style="color: red;">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @else
                                                                <p class="table-cell__item__row vehicle-row">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @endif
                                                        @else
                                                            <p class="table-cell__item__row vehicle-row"></p>
                                                        @endif
                                                        <p class="table-cell__item__row" style="background-color: rgba(255, 0, 0, 0.087);">
                                                            @if ($spv->retail_price)
                                                                {{number_format($spv->retail_price)}}
                                                            @endif
                                                        </p>
                                                        <p class="table-cell__item__row" style="background-color: rgba(0, 0, 255, 0.087);">
                                                            @if ($spv->driver_price)
                                                                {{number_format($spv->driver_price)}}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @php $am_check_count++; @endphp
                                                @endif
                                            @endforeach
                                            @php
                                                $is_check = true;
                                            @endphp
                                            @for ($i = $am_check_count; $i <= $max_count; $i++) <div class="table-cell__item --empty-item">
                                                @if ($is_check)
                                                    <div class="create-project createBtn">
                                                        <button class="create-project__button">
                                                            新規作成
                                                        </button>
                                                        <input hidden value="{{$shift->id}}" class="createShiftId" type="text">
                                                        <input hidden type="text" value="{{$findYear}}" class="createFindYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="createFindMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="createFindDate">
                                                        <input hidden type="text" value="0" class="createTimeOfPart">
                                                        @if ($shift->employee)
                                                        <input hidden type="text" value="{{$shift->employee->name}}" data-employee-id="{{ $shift->employee->id }}" class="createEmployeeName">
                                                        @else
                                                        <input hidden type="text" value="{{$shift->unregistered_employee}}"
                                                            class="createEmployeeName">
                                                        @endif
                                                    </div>
                                                    @php
                                                        $is_check = false;
                                                    @endphp
                                                @endif
                                                    <p class="table-cell__item__row --empty-item__row setHightElem"></p>
                                                    <p class="table-cell__item__row --empty-item__row vehicle-row"></p>
                                                    <p class="table-cell__item__row --empty-item__row"></p>
                                                    <p class="table-cell__item__row --empty-item__row"></p>
                                                </div>
                                            @endfor
                                        </td>
                                        {{-- 午後 --}}
                                        <td class="table-cell --table-cell-pm">
                                            @foreach ( $shift->projectsVehicles as $spv )
                                                @if ($spv->time_of_day == 1)
                                                    <div class="table-cell__item hover-item targetShift">
                                                        {{-- 隠しデータ --}}
                                                        <input hidden type="text" value="{{$spv->id}}" name="" class="shiftId">
                                                        <input hidden type="text" value="{{$findYear}}" class="findYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="findMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="findDate">
                                                        <input hidden type="text" value="1" class="timeOfPart">
                                                        @if ($spv->project)
                                                            <input hidden type="text" value="{{$spv->project->name}}" name="" class="projectName">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_project}}" name="" class="projectName">
                                                        @endif
                                                        @if ($spv->vehicle)
                                                            <input hidden type="text" value="{{$spv->vehicle->number}}" name="" class="vehicleNumber">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_vehicle}}" name="" class="vehicleNumber">
                                                        @endif
                                                            <input hidden type="text" value="{{$spv->retail_price}}" name="" class="retailPrice">
                                                            <input hidden type="text" value="{{$spv->driver_price}}" name="" class="salaryPrice">
                                                        @if ($shift->employee)
                                                            <input hidden type="text" value="{{$shift->employee->name}}" class="employeeName">
                                                        @endif
                                                        <input hidden type="text" value="" name="">

                                                        {{-- データ表示 --}}
                                                        @if ($spv->project)
                                                            @if ($spv->initial_project_name)
                                                                <p class="table-cell__item__row setHightElem">{{$spv->initial_project_name}}</p>
                                                            @else
                                                                <p class="table-cell__item__row setHightElem">{{$spv->project->name}}</p>
                                                            @endif
                                                        @elseif($spv->unregistered_project)
                                                            <p class="table-cell__item__row setHightElem" style="color: red;">{{$spv->unregistered_project}}</p>
                                                        @else
                                                            <p class="table-cell__item__row setHightElem"></p>
                                                        @endif
                                                        {{-- 車両 --}}
                                                        @if ($spv->vehicle)
                                                            <p class="table-cell__item__row vehicle-row" @if(in_array($spv->vehicle->id, $MultipleDailyUsesVehiclesArray[$shift->date])) style="background-color: yellow;" @endif>No.{{$spv->vehicle->number}}</p>
                                                        @elseif($spv->unregistered_vehicle)
                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                <p class="table-cell__item__row vehicle-row" style="color: red;">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @else
                                                                <p class="table-cell__item__row vehicle-row">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @endif
                                                        @else
                                                            <p class="table-cell__item__row vehicle-row"></p>
                                                        @endif
                                                        <p class="table-cell__item__row" style="background-color: rgba(255, 0, 0, 0.087);">
                                                            @if ($spv->retail_price)
                                                                {{number_format($spv->retail_price)}}
                                                            @endif
                                                        </p>
                                                        <p class="table-cell__item__row" style="background-color: rgba(0, 0, 255, 0.087);">
                                                            @if ($spv->driver_price)
                                                                {{number_format($spv->driver_price)}}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @php $pm_check_count++; @endphp
                                                @endif
                                            @endforeach
                                            @php
                                                $is_check = true;
                                            @endphp
                                            @for ($i = $pm_check_count; $i <= $max_count; $i++) <div class="table-cell__item --empty-item">
                                                @if ($is_check)
                                                    <div class="create-project createBtn">
                                                        <button class="create-project__button">
                                                            新規作成
                                                        </button>
                                                        <input hidden value="{{$shift->id}}" class="createShiftId" type="text">
                                                        <input hidden type="text" value="{{$findYear}}" class="createFindYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="createFindMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="createFindDate">
                                                        <input hidden type="text" value="1" class="createTimeOfPart">
                                                        @if ($shift->employee)
                                                        <input hidden type="text" value="{{$shift->employee->name}}" data-employee-id="{{ $shift->employee->id }}" class="createEmployeeName">
                                                        @else
                                                            <input hidden type="text" value="{{$shift->unregistered_employee}}"
                                                                class="createEmployeeName">
                                                        @endif
                                                    </div>
                                                    @php
                                                        $is_check = false;
                                                    @endphp
                                                @endif
                                                <p class="table-cell__item__row --empty-item__row setHightElem"></p>
                                                <p class="table-cell__item__row --empty-item__row vehicle-row"></p>
                                                <p class="table-cell__item__row --empty-item__row"></p>
                                                <p class="table-cell__item__row --empty-item__row"></p>
                                            </div>
                                        @endfor
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- 未登録従業員 --}}
                        @foreach ( $shiftDataByUnEmployee as $employeeId => $shiftData )
                                @php
                                    // 一周目だけ従業員表示
                                    $is_employee_open = true;
                                    // 1日ごとの最大案件数
                                    $max_count = 1;
                                @endphp
                                {{-- 最大案件数の計算 --}}
                                @php
                                    foreach ($shiftData as $shift) {
                                        $am_count = 0;
                                        $pm_count = 0;
                                        foreach ($shift->projectsVehicles as $spv) {
                                            $count = 0;
                                            if($spv->time_of_day == 0){
                                                $am_count++;
                                            }
                                            if($spv->time_of_day == 1){
                                                $pm_count++;
                                            }
                                        }
                                        if($max_count < $am_count){
                                            $max_count=$am_count;
                                        }elseif ($max_count < $pm_count) {
                                            $max_count=$pm_count;
                                        }
                                    }
                                @endphp
                                <tr class="shift-calendar-table__body__row getRow">
                                    {{-- 左側の会社の列作成のため --}}
                                    @if ($shift->employee)
                                        <td class="td-none companyInfo" data-company-name="{{ $shift->employee->company->name }}">
                                    @endif
                                        @foreach ( $shiftData as $shift ) {{-- $shift == 1日のシフト --}}
                                            {{-- 一周目だけ従業員表示 --}}
                                            @if ($is_employee_open)
                                                <td class="table-employee-name">
                                                    <div class="table-employee-name__block">
                                                        @if ($shift->employee)
                                                        <p class="">{{$shift->employee->name}}</p>
                                                        @else
                                                        <p class="" style="color: red;">{{$shift->unregistered_employee}}</p>
                                                        @endif
                                                    </div>
                                                </td>
                                                @php
                                                    $is_employee_open = false;
                                                @endphp
                                            @endif
                                            @php
                                                $am_check_count = 0;
                                                $pm_check_count = 0;

                                                foreach ($convertedDates as $date) {
                                                    if ($shift->date == $date->format('Y-m-d')) {
                                                        $findYear = $date->format('Y');
                                                        $findMonth = $date->format('m');
                                                        $findDate = $date->format('d');
                                                    }
                                                }
                                            @endphp
                                        {{-- 午前 --}}
                                        <td class="table-cell">
                                            @foreach ( $shift->projectsVehicles as $spv )
                                                @if ($spv->time_of_day == 0)
                                                    <div class="table-cell__item hover-item targetShift">
                                                        {{-- 隠しデータ --}}
                                                        <input hidden type="text" value="{{$spv->id}}" name="" class="shiftId">
                                                        <input hidden type="text" value="{{$findYear}}" class="findYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="findMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="findDate">
                                                        <input hidden type="text" value="0" class="timeOfPart">
                                                        @if ($spv->project)
                                                            <input hidden type="text" value="{{$spv->project->name}}" name=""
                                                                class="projectName">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_project}}" name=""
                                                                class="projectName">
                                                        @endif
                                                        @if ($spv->vehicle)
                                                            <input hidden type="text" value="{{$spv->vehicle->number}}" name=""
                                                                class="vehicleNumber">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_vehicle}}" name=""
                                                                class="vehicleNumber">
                                                        @endif
                                                            <input hidden type="text" value="{{$spv->retail_price}}" name=""
                                                                class="retailPrice">
                                                            <input hidden type="text" value="{{$spv->driver_price}}" name=""
                                                                class="salaryPrice">
                                                        @if ($shift->employee)
                                                            <input hidden type="text" value="{{$shift->employee->name}}"
                                                                class="employeeName">
                                                        @else
                                                            <input hidden type="text" value="{{$shift->unregistered_employee}}"
                                                                    class="employeeName">
                                                        @endif
                                                        <input hidden type="text" value="" name="">

                                                        {{-- データ表示 --}}
                                                        @if ($spv->project)
                                                            <p class="table-cell__item__row setHightElem">{{$spv->project->name}}</p>
                                                        @elseif($spv->unregistered_project)
                                                            <p class="table-cell__item__row setHightElem" style="color: red;">
                                                                {{$spv->unregistered_project}}</p>
                                                        @else
                                                            <p class="table-cell__item__row setHightElem"></p>
                                                        @endif
                                                        {{-- 車両 --}}
                                                        @if ($spv->vehicle)
                                                            <p class="table-cell__item__row vehicle-row" @if(in_array($spv->vehicle->id, $MultipleDailyUsesVehiclesArray[$shift->date])) style="background-color: yellow;" @endif>No.{{$spv->vehicle->number}}</p>
                                                        @elseif($spv->unregistered_vehicle)
                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                <p class="table-cell__item__row vehicle-row" style="color: red;">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @else
                                                                <p class="table-cell__item__row vehicle-row">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @endif
                                                        @else
                                                            <p class="table-cell__item__row vehicle-row"></p>
                                                        @endif
                                                        <p class="table-cell__item__row --retail-back-ground-color">
                                                            @if ($spv->retail_price)
                                                                {{number_format($spv->retail_price)}}
                                                            @endif
                                                        </p>
                                                        <p class="table-cell__item__row --driver-back-ground-color">
                                                            @if ($spv->driver_price)
                                                                {{number_format($spv->driver_price)}}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @php $am_check_count++; @endphp
                                                @endif
                                            @endforeach
                                            @php
                                                $is_check = true;
                                            @endphp
                                            @for ($i = $am_check_count; $i <= $max_count; $i++) <div class="table-cell__item --empty-item">
                                                @if ($is_check)
                                                    <div class="create-project createBtn">
                                                        <button class="create-project__button">
                                                            新規作成
                                                        </button>
                                                        <input hidden value="{{$shift->id}}" class="createShiftId" type="text">
                                                        <input hidden type="text" value="{{$findYear}}" class="createFindYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="createFindMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="createFindDate">
                                                        <input hidden type="text" value="0" class="createTimeOfPart">
                                                        @if ($shift->employee)
                                                        <input hidden type="text" value="{{$shift->employee->name}}" data-employee-id="{{ $shift->employee->id }}" class="createEmployeeName">
                                                        @else
                                                            <input hidden type="text" value="{{$shift->unregistered_employee}}"
                                                                class="createEmployeeName">
                                                        @endif
                                                    </div>
                                                    @php
                                                        $is_check = false;
                                                    @endphp
                                                @endif
                                                    <p class="table-cell__item__row --empty-item__row setHightElem"></p>
                                                    <p class="table-cell__item__row --empty-item__row vehicle-row"></p>
                                                    <p class="table-cell__item__row --empty-item__row"></p>
                                                    <p class="table-cell__item__row --empty-item__row"></p>
                                                </div>
                                            @endfor
                                        </td>
                                        {{-- 午後 --}}
                                        <td class="table-cell --table-cell-pm">
                                            @foreach ( $shift->projectsVehicles as $spv )
                                                @if ($spv->time_of_day == 1)
                                                    <div class="table-cell__item hover-item targetShift">
                                                        {{-- 隠しデータ --}}
                                                        <input hidden type="text" value="{{$spv->id}}" name="" class="shiftId">
                                                        <input hidden type="text" value="{{$findYear}}" class="findYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="findMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="findDate">
                                                        <input hidden type="text" value="1" class="timeOfPart">
                                                        @if ($spv->project)
                                                            <input hidden type="text" value="{{$spv->project->name}}" name="" class="projectName">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_project}}" name="" class="projectName">
                                                        @endif
                                                        @if ($spv->vehicle)
                                                            <input hidden type="text" value="{{$spv->vehicle->number}}" name="" class="vehicleNumber">
                                                        @else
                                                            <input hidden type="text" value="{{$spv->unregistered_vehicle}}" name="" class="vehicleNumber">
                                                        @endif
                                                            <input hidden type="text" value="{{$spv->retail_price}}" name="" class="retailPrice">
                                                            <input hidden type="text" value="{{$spv->driver_price}}" name="" class="salaryPrice">
                                                        @if ($shift->employee)
                                                            <input hidden type="text" value="{{$shift->employee->name}}"
                                                                class="employeeName">
                                                        @else
                                                            <input hidden type="text" value="{{$shift->unregistered_employee}}"
                                                                    class="employeeName">
                                                        @endif
                                                        <input hidden type="text" value="" name="">

                                                        {{-- データ表示 --}}
                                                        @if ($spv->project)
                                                            <p class="table-cell__item__row setHightElem">{{$spv->project->name}}</p>
                                                        @elseif($spv->unregistered_project)
                                                            <p class="table-cell__item__row setHightElem" style="color: red;">{{$spv->unregistered_project}}</p>
                                                        @else
                                                            <p class="table-cell__item__row setHightElem"></p>
                                                        @endif
                                                        {{-- 車両 --}}
                                                        @if ($spv->vehicle)
                                                            <p class="table-cell__item__row vehicle-row" @if(in_array($spv->vehicle->id, $MultipleDailyUsesVehiclesArray[$shift->date])) style="background-color: yellow;" @endif>No.{{$spv->vehicle->number}}</p>
                                                        @elseif($spv->unregistered_vehicle)
                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                <p class="table-cell__item__row vehicle-row" style="color: red;">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @else
                                                                <p class="table-cell__item__row vehicle-row">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @endif
                                                        @else
                                                            <p class="table-cell__item__row vehicle-row"></p>
                                                        @endif
                                                        <p class="table-cell__item__row --retail-back-ground-color">
                                                            @if ($spv->retail_price)
                                                                {{number_format($spv->retail_price)}}
                                                            @endif
                                                        </p>
                                                        <p class="table-cell__item__row --driver-back-ground-color">
                                                            @if ($spv->driver_price)
                                                                {{number_format($spv->driver_price)}}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @php $pm_check_count++; @endphp
                                                @endif
                                            @endforeach
                                            @php
                                                $is_check = true;
                                            @endphp
                                            @for ($i = $pm_check_count; $i <= $max_count; $i++) <div class="table-cell__item --empty-item">
                                                @if ($is_check)
                                                    <div class="create-project createBtn">
                                                        <button class="create-project__button">
                                                            新規作成
                                                        </button>
                                                        <input hidden value="{{$shift->id}}" class="createShiftId" type="text">
                                                        <input hidden type="text" value="{{$findYear}}" class="createFindYear">
                                                        <input hidden type="text" value="{{$findMonth}}" class="createFindMonth">
                                                        <input hidden type="text" value="{{$findDate}}" class="createFindDate">
                                                        <input hidden type="text" value="1" class="createTimeOfPart">
                                                        @if ($shift->employee)
                                                            <input hidden type="text" value="{{$shift->employee->name}}" data-employee-id="{{ $shift->employee->id }}" class="createEmployeeName">
                                                        @else
                                                            <input hidden type="text" value="{{$shift->unregistered_employee}}"
                                                                class="createEmployeeName">
                                                        @endif
                                                    </div>
                                                    @php
                                                        $is_check = false;
                                                    @endphp
                                                @endif
                                                <p class="table-cell__item__row --empty-item__row setHightElem"></p>
                                                <p class="table-cell__item__row --empty-item__row vehicle-row"></p>
                                                <p class="table-cell__item__row --empty-item__row"></p>
                                                <p class="table-cell__item__row --empty-item__row"></p>
                                            </div>
                                        @endfor
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
            <p class="shift-warning-txt">{{$startOfWeek}}〜{{$endOfWeek}}のシフトはありません</p>
            @endif
        </div>
        </div>

        </div>


    </main>

    {{-- モーダル --}}
    <div class="shift-edit-modal" id="shiftModal">
        <span class="shift-edit-modal__bg modalClose" onclick='return confirm("入力したデータは失われます。")'></span>
        <div class="shift-edit-modal__white-board">
            {{-- シフト情報 --}}
            <div class="name-block">
                <p id="setEmployeeName">佐藤太郎</p>
            </div>
            <div class="title">
                <p class="">案件編集</p>
            </div>
            <div class="date-block">
                <p class="date-block__year"><span class="setYear">2024</span><span class="txt">年</span></p>
                <p class="date-block__month"><span class="setMonth">02</span><span class="txt">月</span><span
                        class="setDate">19</span><span class="txt">日</span></p>
                <p class="date-block__part setPart">午後の案件</p>
            </div>
            {{-- フォームエリア --}}
            <form action="{{route('shift.update')}}" method="POST" class="form-block">
                @csrf
                <input hidden value="{{$startOfWeek}}" name="startOfWeek" type="text">
                <input hidden type="text" id="setShiftId" name="setId">
                <div class="form-block__input-area">
                    {{-- プロジェクト --}}
                    <div class="form-block__item">
                        <p class="item-title">案件</p>
                        <div class="check-area">
                            <div class="check-area__item">
                                <input checked type="radio" value="0" class="projectRadio" id="02" name="projectRadio">
                                <label for="02">既存案件</label>
                            </div>
                            <div class="check-area__item">
                                <input type="radio" value="1" class="projectRadio" id="01" name="projectRadio">
                                <label for="01">新規案件</label>
                            </div>
                        </div>
                        <input name="projectInput" type="text" class="c-input modal-input" id="projectInput">
                        <select name="projectSelect" id="projectSelect" class="c-select modal-select">
                            <option value="">選択してください</option>
                            @foreach ($projects as $project)
                            <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- 車両 --}}
                    <div class="form-block__item">
                        <p class="item-title">ナンバー</p>
                        <div class="check-area">
                            <div class="check-area__item">
                                <input checked type="radio" value="0" class="vehicleRadio" id="03" name="vehicleRadio">
                                <label for="03">既存車両</label>
                            </div>
                            <div class="check-area__item">
                                <input type="radio" value="1" class="vehicleRadio" id="04" name="vehicleRadio">
                                <label for="04">新規車両</label>
                            </div>
                        </div>
                        <input type="text" class="c-input modal-input" id="vehicleInput" name="vehicleInput">
                        <select name="vehicleSelect" id="vehicleSelect" class="c-select modal-select">
                            <option value="">選択してください</option>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- 上代 --}}
                    <div class="form-block__item">
                        <p class="item-title">上代</p>
                        <input type="text" class="c-input commaInput" id="retailInput" name="retailInput" placeholder="1,000">
                    </div>
                    {{-- 給与 --}}
                    <div class="form-block__item">
                        <p class="item-title">給与</p>
                        <input type="text" class="c-input commaInput" id="salaryInput" name="salaryInput" placeholder="1,000">
                    </div>
                </div>
                {{-- ボタン --}}
                <div class="form-block__btn-area">
                    <button class="btn --save" type="submit" name="action" value="save">
                        入力内容で案件を登録
                    </button>
                    <button class="btn --delete" type="submit" name="action" value="delete"
                        onclick='return confirm("本当に削除しますか?")'>
                        この案件を削除する
                    </button>
                    <div class="btn --back modalClose" onclick='return confirm("入力したデータは失われます。")'>
                        戻る
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 新規作成用モーダル --}}
    <div class="shift-edit-modal shift-create-modal" id="createShiftModal">
        <span class="shift-edit-modal__bg createCloseModal" onclick='return confirm("入力したデータは失われます。")'></span>
        <div class="shift-edit-modal__white-board">
            {{-- シフト情報 --}}
            <div class="name-block">
                <p id="createEmployee">佐藤太郎</p>
            </div>
            <div class="title">
                <p class="">案件作成</p>
            </div>
            <div class="date-block">
                <p class="date-block__year"><span id="createYear">2024</span><span class="txt">年</span></p>
                <p class="date-block__month"><span id="createMonth">02</span><span class="txt">月</span><span
                        id="createDay">19</span><span class="txt">日</span></p>
                <p class="date-block__part" id="createSetTxtPart">午後の案件</p>
            </div>
            <select hidden name="" id="paymentSelect">
                @foreach ($payments as $payment)
                    <option data-payment-project-id="{{ $payment->project_id }}" data-payment-employee-id="{{ $payment->employee_id }}">{{ $payment->amount }}</option>
                @endforeach
            </select>
            {{-- フォームエリア --}}
            <form action="{{route('shift.store')}}" method="POST" class="form-block">
                @csrf
                <input hidden value="{{$startOfWeek}}" name="startOfWeek" type="text">
                <input hidden type="text" id="createSetId" name="setId">
                <input hidden type="text" id="createSetPart" name="part">
                <div class="form-block__input-area">
                    {{-- プロジェクト --}}
                    <div class="form-block__item">
                        <p class="item-title">案件</p>
                        <div class="check-area">
                            <div class="check-area__item">
                                <input checked type="radio" value="0" class="createProjectRadio"
                                    id="createProjectRadio01" name="createProjectRadio">
                                <label for="createProjectRadio01">既存案件</label>
                            </div>
                            <div class="check-area__item">
                                <input type="radio" value="1" class="createProjectRadio" id="createProjectRadio02"
                                    name="createProjectRadio">
                                <label for="createProjectRadio02">新規案件</label>
                            </div>
                        </div>
                        <input name="projectInput" type="text" class="c-input modal-input" id="createProjectInput"
                            placeholder="案件名">
                        <select name="projectSelect" id="createProjectSelect" class="c-select modal-select">
                            <option value="">選択してください</option>
                            @foreach ($projects as $project)
                                <option value="{{$project->id}}" data-retail-amount="{{ $project->retail_price }}" data-driver-amount="{{ $project->driver_price }}">{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- 車両 --}}
                    <div class="form-block__item">
                        <p class="item-title">ナンバー</p>
                        <div class="check-area">
                            <div class="check-area__item">
                                <input checked type="radio" value="0" class="createVehicleRadio"
                                    id="createProjectRadio03" name="createVehicleRadio">
                                <label for="createProjectRadio03">既存車両</label>
                            </div>
                            <div class="check-area__item">
                                <input type="radio" value="1" class="createVehicleRadio" id="createProjectRadio04"
                                    name="createVehicleRadio">
                                <label for="createProjectRadio04">新規車両</label>
                            </div>
                        </div>
                        <input type="text" class="c-input modal-input" id="createVehicleInput" name="vehicleInput"
                            placeholder="ナンバー">
                        <select name="vehicleSelect" id="createVehicleSelect" class="c-select modal-select">
                            <option value="">選択してください</option>
                            @foreach ($vehicles as $vehicle)
                            <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- 上代 --}}
                    <div class="form-block__item">
                        <p class="item-title">上代</p>
                        <input type="text" class="c-input commaInput" id="createRetailInput" name="retailInput" placeholder="1,000">
                    </div>
                    {{-- 給与 --}}
                    <div class="form-block__item">
                        <p class="item-title">給与</p>
                        <input type="text" class="c-input commaInput" id="createSalaryInput" name="salaryInput" placeholder="1,000">
                    </div>
                </div>
                {{-- ボタン --}}
                <div class="form-block__btn-area">
                    <button class="btn --save" type="submit" name="action" value="save">
                        入力内容で案件を登録
                    </button>
                    <div class="btn --back createCloseModal" onclick='return confirm("入力したデータは失われます。")'>
                        戻る
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>

{{-- script --}}
<script src="{{asset('js/shift.js')}}"></script>
