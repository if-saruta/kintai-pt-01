<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員閲覧用シフト') }}
        </h2>
    </x-slot>

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
                {{-- 日付の表示 --}}
                <div class="shift-calendar__date">
                    <form action="{{route('shift.employeeShowShiftSelectWeek')}}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{$startOfWeek}}">
                        <input type="hidden" name="action" value="previous">
                        <input hidden name="witch" value="page02" type="text">
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
                    <form action="{{route('shift.employeeShowShiftSelectWeek')}}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{$endOfWeek}}">
                        <input type="hidden" name="action" value="next">
                        <input hidden name="witch" value="page02" type="text">
                        <button type="submit" class="">
                            <i class="fa-solid fa-angle-right date-angle"></i>
                        </button>
                    </form>
                </div>
                {{-- カレンダー検索 --}}
                <form action="{{route('shift.employeeShowShiftSelectWeek')}}" class="datepicker" method="POST">
                    @csrf
                    <div class="date01">
                        <label for="" class="date01__label">
                            <input type="date" id="date" name="date" class="datepicker__input">
                        </label>
                    </div>
                    <input hidden name="witch" value="page02" type="text">
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
                                <th class="txt"><p class="">AM</p></th>
                                <th class="txt"><p class="">PM</p></th>
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
                                            $max_count = $am_count;
                                        }
                                        if ($max_count < $pm_count) {
                                            $max_count = $pm_count;
                                        }
                                    }
                                @endphp
                                    <tr class="shift-calendar-table__body__row getRow">
                                        @if ($shift->employee)
                                            <td class="td-none companyInfo" data-company-name="{{ $shift->employee->company->name }}">
                                        @endif
                                        @foreach ( $shiftData as $shift )  {{-- $shift == 1日のシフト --}}
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
                                            {{-- 最大案件数の計算 --}}
                                            @php
                                                $am_check_count = 0;
                                                $pm_check_count = 0;
                                            @endphp
                                            {{-- 午前 --}}
                                            <td class="table-cell">
                                                @foreach ( $shift->projectsVehicles as $spv )
                                                    @if ($spv->time_of_day == 0)
                                                    <div class="table-cell__item">
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
                                                            <p class="table-cell__item__row">No.{{$spv->vehicle->number}}</p>
                                                        @elseif($spv->unregistered_vehicle)
                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                <p class="table-cell__item__row" style="color: red;">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @else
                                                                <p class="table-cell__item__row">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @endif
                                                        @else
                                                            <p class="table-cell__item__row"></p>
                                                        @endif
                                                    </div>
                                                    @php $am_check_count++; @endphp
                                                    @endif
                                                @endforeach

                                                @for ($i = $am_check_count; $i < $max_count; $i++)
                                                    <div class="table-cell__item">
                                                        <p class="table-cell__item__row setHightElem"></p>
                                                        <p class="table-cell__item__row"></p>
                                                    </div>
                                                @endfor
                                            </td>
                                            {{-- 午後 --}}
                                            <td class="table-cell --table-cell-pm">
                                                @foreach ( $shift->projectsVehicles as $spv )
                                                    @if ($spv->time_of_day == 1)
                                                    <div class="table-cell__item">
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
                                                            <p class="table-cell__item__row">No.{{$spv->vehicle->number}}</p>
                                                        @elseif($spv->unregistered_vehicle)
                                                            @if ($spv->unregistered_vehicle != '自車')
                                                                <p class="table-cell__item__row" style="color: red;">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @else
                                                                <p class="table-cell__item__row">
                                                                    No.{{$spv->unregistered_vehicle}}</p>
                                                            @endif
                                                        @else
                                                            <p class="table-cell__item__row"></p>
                                                        @endif
                                                    </div>
                                                    @php $pm_check_count++; @endphp
                                                    @endif
                                                @endforeach

                                                @for ($i = $pm_check_count; $i < $max_count; $i++)
                                                    <div class="table-cell__item">
                                                        <p class="table-cell__item__row setHightElem"></p>
                                                        <p class="table-cell__item__row"></p>
                                                    </div>
                                                @endfor
                                            </td>
                                        @endforeach
                                    </tr>
                            @endforeach

                            {{-- 未登録従業員 --}}
                            @foreach ( $shiftDataByUnEmployee as $unEmployee => $shiftData )
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
                                            $max_count = $am_count;
                                        }elseif ($max_count < $pm_count) {
                                            $max_count = $pm_count;
                                        }
                                    }
                                @endphp
                                <tr class="shift-calendar-table__body__row getRow">
                                    @if ($shift->employee)
                                        <td class="td-none companyInfo" data-company-name="{{ $shift->employee->company->name }}">
                                    @endif
                                    @foreach ( $shiftData as $shift )  {{-- $shift == 1日のシフト --}}
                                        {{-- 一周目だけ従業員表示 --}}
                                        @if ($is_employee_open)
                                            <td class="table-employee-name">
                                                <div class="table-employee-name__block">

                                                    <p class="" style="color: red;">{{$unEmployee}}</p>

                                                </div>
                                            </td>
                                            @php
                                                $is_employee_open = false;
                                            @endphp
                                        @endif
                                        {{-- 最大案件数の計算 --}}
                                        @php
                                            $am_check_count = 0;
                                            $pm_check_count = 0;
                                        @endphp
                                        {{-- 午前 --}}
                                        <td class="table-cell">
                                            @foreach ( $shift->projectsVehicles as $spv )
                                                @if ($spv->time_of_day == 0)
                                                <div class="table-cell__item">
                                                    @if ($spv->project)
                                                    <p class="table-cell__item__row setHightElem">{{$spv->project->name}}</p>
                                                    @elseif($spv->unregistered_project)
                                                    <p class="table-cell__item__row setHightElem" style="color: red;">{{$spv->unregistered_project}}</p>
                                                    @else
                                                    <p class="table-cell__item__row setHightElem"></p>
                                                    @endif
                                                    {{-- 車両 --}}
                                                    @if ($spv->vehicle)
                                                        <p class="table-cell__item__row">{{$spv->vehicle->number}}</p>
                                                    @elseif($spv->unregistered_vehicle)
                                                        @if ($spv->unregistered_vehicle != '自車')
                                                            <p class="table-cell__item__row" style="color: red;">
                                                                No.{{$spv->unregistered_vehicle}}</p>
                                                        @else
                                                            <p class="table-cell__item__row">
                                                                No.{{$spv->unregistered_vehicle}}</p>
                                                        @endif
                                                    @else
                                                        <p class="table-cell__item__row"></p>
                                                    @endif
                                                </div>
                                                @php $am_check_count++; @endphp
                                                @endif
                                            @endforeach

                                            @for ($i = $am_check_count; $i < $max_count; $i++)
                                                <div class="table-cell__item">
                                                    <p class="table-cell__item__row setHightElem"></p>
                                                    <p class="table-cell__item__row"></p>
                                                </div>
                                            @endfor
                                        </td>
                                        {{-- 午後 --}}
                                        <td class="table-cell --table-cell-pm">
                                            @foreach ( $shift->projectsVehicles as $spv )
                                                @if ($spv->time_of_day == 1)
                                                <div class="table-cell__item">
                                                    @if ($spv->project)
                                                    <p class="table-cell__item__row setHightElem">{{$spv->project->name}}</p>
                                                    @elseif($spv->unregistered_project)
                                                    <p class="table-cell__item__row setHightElem" style="color: red;">{{$spv->unregistered_project}}</p>
                                                    @else
                                                    <p class="table-cell__item__row setHightElem"></p>
                                                    @endif
                                                    {{-- 車両 --}}
                                                    @if ($spv->vehicle)
                                                        <p class="table-cell__item__row">{{$spv->vehicle->number}}</p>
                                                    @elseif($spv->unregistered_vehicle)
                                                        @if ($spv->unregistered_vehicle != '自車')
                                                            <p class="table-cell__item__row" style="color: red;">
                                                                No.{{$spv->unregistered_vehicle}}</p>
                                                        @else
                                                            <p class="table-cell__item__row">
                                                                No.{{$spv->unregistered_vehicle}}</p>
                                                        @endif
                                                    @else
                                                        <p class="table-cell__item__row"></p>
                                                    @endif
                                                </div>
                                                @php $pm_check_count++; @endphp
                                                @endif
                                            @endforeach

                                            @for ($i = $pm_check_count; $i < $max_count; $i++)
                                            <div class="table-cell__item">
                                                <p class="table-cell__item__row setHightElem"></p>
                                                <p class="table-cell__item__row"></p>
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



</x-app-layout>

{{-- script --}}
<script src="{{asset('js/shift.js')}}"></script>
