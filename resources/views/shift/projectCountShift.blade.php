<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件カウント') }}
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
                    @foreach ($narrowEmployeeId as $EmployeeId)
                        <input hidden type="text" name="narrowEmployeeId[]" value="{{ $EmployeeId }}">
                    @endforeach
                    <input hidden type="text" name="narrowUnregisterEmployee" name="narrowUnregisterEmployee" value="{{ $narrowUnregisterEmployee }}">
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
                    @foreach ($narrowEmployeeId as $EmployeeId)
                        <input hidden type="text" name="narrowEmployeeId[]" value="{{ $EmployeeId }}">
                    @endforeach
                    <input hidden type="text" name="narrowUnregisterEmployee" name="narrowUnregisterEmployee" value="{{ $narrowUnregisterEmployee }}">
                    <button class="{{ request()->routeIs('shift.employeeShowShift*') ? 'active' : '' }} link">
                        <span class="">稼働表</span>
                    </button>
                </form>
                <form action="{{route('shift.employeePriceShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page03" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    @foreach ($narrowEmployeeId as $EmployeeId)
                        <input hidden type="text" name="narrowEmployeeId[]" value="{{ $EmployeeId }}">
                    @endforeach
                    <input hidden type="text" name="narrowUnregisterEmployee" name="narrowUnregisterEmployee" value="{{ $narrowUnregisterEmployee }}">
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
                    @foreach ($narrowEmployeeId as $EmployeeId)
                        <input hidden type="text" name="narrowEmployeeId[]" value="{{ $EmployeeId }}">
                    @endforeach
                    <input hidden type="text" name="narrowUnregisterEmployee" name="narrowUnregisterEmployee" value="{{ $narrowUnregisterEmployee }}">
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
                    @foreach ($narrowEmployeeId as $EmployeeId)
                        <input hidden type="text" name="narrowEmployeeId[]" value="{{ $EmployeeId }}">
                    @endforeach
                    <input hidden type="text" name="narrowUnregisterEmployee" name="narrowUnregisterEmployee" value="{{ $narrowUnregisterEmployee }}">
                    <button class="{{ request()->routeIs('shift.projectCount*') ? 'active' : '' }} link">
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
                        @foreach ($narrowEmployeeId as $EmployeeId)
                            <input hidden type="text" name="narrowEmployeeId[]" value="{{ $EmployeeId }}">
                        @endforeach
                        <input hidden type="text" name="narrowUnregisterEmployee" name="narrowUnregisterEmployee" value="{{ $narrowUnregisterEmployee }}">
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
                {{-- シフトPDFダウンロード --}}
                <div class="calendar-download">
                    <form action="{{ route('shift.allViewPdf') }}" method="POST">
                        @csrf
                        {{-- 日付 --}}
                        <input hidden name="startOfWeek" value="{{ $startOfWeek }}" type="text">
                        <input hidden name="endOfWeek" value="{{ $endOfWeek }}" type="text">
                        {{-- 案件のhegiht --}}
                        <input hidden name="projectHeight" value="" id="projectHeight" type="text">
                        {{-- シフトの種類 --}}
                        <input hidden name="shiftType" value="projectCount" type="text">
                        <button class="calendar-download-btn">ダウンロード</button>
                    </form>
                </div>
                {{-- 日付の表示 --}}
                <div class="shift-calendar__date">
                    <form action="{{route('shift.projectCountSelectWeek')}}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{$startOfWeek}}">
                        <input type="hidden" name="action" value="previous">
                        <input hidden name="witch" value="page05" type="text">
                        <button type="submit" class="">
                            <i class="fa-solid fa-angle-left date-angle"></i>
                        </button>
                    </form>
                    <div class="shift-calendar__date__show">
                        <p class="week-of-month">{{$monday->format('Y')}}年{{$monday->format('n')}}月第{{ $weekOfMonth }}週目 案件数用</p>
                    </div>
                    <form action="{{route('shift.projectCountSelectWeek')}}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{$endOfWeek}}">
                        <input type="hidden" name="action" value="next">
                        <input hidden name="witch" value="page05" type="text">
                        <button type="submit" class="">
                            <i class="fa-solid fa-angle-right date-angle"></i>
                        </button>
                    </form>
                </div>
                {{-- カレンダー検索 --}}
                <form action="{{route('shift.projectCountSelectWeek')}}" class="datepicker" method="POST">
                    @csrf
                    <div class="date01">
                        <label for="" class="date01__label">
                            <input type="date" id="date" name="date" class="datepicker__input">
                        </label>
                    </div>
                    <input hidden name="witch" value="page05" type="text">
                    <button type="submit" class="datepicker__button">
                        検索
                    </button>
                </form>
                {{-- シフトが一件でもあるか判定 --}}
                @php
                    $hasAllShift = false;
                    foreach ($shifts as $shift) {
                        foreach($shift->projectsVehicles as $spv){
                            $hasAllShift = true;
                        }
                    }
                @endphp
                {{-- カレンダー表示 --}}
                <div class="shift-calendar__main">
                    @if($hasAllShift)
                    <table class="shift-calendar-table shift-count-table">
                        <thead class="shift-calendar-table__head">
                            <tr class="shift-calendar-table__head__day">
                                <th></th>
                                @foreach ( $convertedDates as $date )
                                    <th class="txt">
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
                                <th class="amount-th">合計</th>
                            </tr>
                        </thead>
                        <tbody class="shift-calendar-table__body">
                            @foreach ($projectsGroupByClient as $clientId => $projects)
                                @foreach ($projects as $project)
                                    @if($project->client->id != 1)
                                    <tr class="shift-calendar-table__body__row --count-row">
                                        <td class="--count-row__project"><p class="">{{$project->name}}</p></td>
                                        @php
                                            $projectTotalCount = 0;
                                        @endphp
                                        @foreach ( $convertedDates as $date )
                                            @php
                                                $project_count = 0;
                                                foreach ($shifts as $shift) {
                                                    if ($shift->date == $date->format('Y-m-d')) {
                                                        foreach ($shift->projectsVehicles as $spv) {
                                                            if($spv->project){
                                                                if($spv->project->id == $project->id){
                                                                    $project_count++;
                                                                    $projectTotalCount++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <td class="--count-row__count">
                                                @if ($project_count != 0)
                                                    {{$project_count}}
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="--count-row__count amount-td">{{ $projectTotalCount != 0 ? $projectTotalCount : '' }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            @foreach ($unregistered_project as $unProject)
                                <tr class="shift-calendar-table__body__row --count-row">
                                    <td class="--count-row__project" style="color: red;">{{$unProject}}</td>
                                    @php
                                        $unProjectTotalCount = 0;
                                    @endphp
                                    @foreach ( $convertedDates as $date )
                                        @php
                                            $unProject_count = 0;
                                            foreach ($shifts as $shift) {
                                                if ($shift->date == $date->format('Y-m-d')) {
                                                    foreach ($shift->projectsVehicles as $spv) {
                                                        if($spv->unregistered_project){
                                                            if($spv->unregistered_project == $unProject){
                                                                $unProject_count++;
                                                                $unProjectTotalCount++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <td class="--count-f-s">
                                            @if ($unProject_count != 0)
                                                {{$unProject_count}}
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="--count-f-s amount-td">{{ $unProjectTotalCount != 0 ? $unProjectTotalCount : '' }}</td>
                                </tr>
                            @endforeach
                            {{-- <tr class="shift-calendar-table__body__row --count-row --total-row-count">
                                <td class="">合計</td>
                                @foreach ( $convertedDates as $date )
                                    @php
                                        $day_count = 0;
                                        foreach ($shifts as $shift) {
                                                if ($shift->date == $date->format('Y-m-d')) {
                                                    foreach ($shift->projectsVehicles as $spv) {
                                                        if($spv->project){
                                                            if($spv->project->client->id == 1){
                                                                continue;
                                                            }
                                                        }
                                                        $day_count++;
                                                    }
                                                }
                                            }
                                    @endphp
                                    <td class="--count-row__project --count-f-s">
                                        {{$day_count}}
                                    </td>
                                @endforeach
                                <td></td>
                            </tr> --}}
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
