<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件カウント') }}
        </h2>
    </x-slot>

    <main class="main --shift-main">
        <div class="main__link-block --shift-link-block">
            <div class="main__link-block__tags">
                <form action="{{route('shift.')}}" method="POST" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page01" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button
                        class="{{ request()->routeIs('shift.', 'shift.selectWeek') ? 'active' : '' }} link">
                        <span class="">全表示</span>
                    </button>
                </form>
                <form action="{{route('shift.employeeShowShift')}}" method="POST" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page02" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button
                        class="{{ request()->routeIs('shift.employeeShowShift*') ? 'active' : '' }} link">
                        <span class="">従業員閲覧用</span>
                    </button>
                </form>
                <form action="{{route('shift.employeePriceShift')}}" method="POST" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page03" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button
                        class="{{ request()->routeIs('shift.employeePriceShift*') ? 'active' : '' }} link">
                        <span class="">従業員給与</span>
                    </button>
                </form>
                <form action="{{route('shift.projectPriceShift')}}" method="POST" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page04" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button
                        class="{{ request()->routeIs('shift.projectPriceShift*') ? 'active' : '' }} link">
                        <span class="">上代閲覧用</span>
                    </button>
                </form>
                <form action="{{route('shift.projectCount')}}" method="POST" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page05" type="text">
                    <input hidden type="text" name="date" value="{{$startOfWeek}}">
                    <button
                        class="{{ request()->routeIs('shift.projectCount*') ? 'active' : '' }} link">
                        <span class="">案件数用</span>
                    </button>
                </form>
            </div>
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
                        <div class="date">
                            <div class="date__txt">
                                <p class="fs-14">{{$monday->format('Y')}}<span class="fs-10">年</span></p>
                            </div>
                            <div class="date__txt">
                                <p class="fs-16">{{$monday->format('m')}}<span
                                        class="fs-10">月</span>{{$monday->format('d')}}<span class="fs-10">日</span></p>
                            </div>
                        </div>
                        <div class="date">
                            <div class="date__txt">
                                <p class="fs-14">{{$sunday->format('Y')}}<span class="fs-10">年</span></p>
                            </div>
                            <div class="date__txt">
                                <p class="fs-16">{{$sunday->format('m')}}<span
                                        class="fs-10">月</span>{{$sunday->format('d')}}<span class="fs-10">日</span></p>
                            </div>
                        </div>
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
                {{-- カレンダー表示 --}}
                <div class="shift-calendar__main">
                    @if(!$shifts->isEmpty())
                    <table class="shift-calendar-table">
                        <thead class="shift-calendar-table__head">
                            <tr class="shift-calendar-table__head__day">
                                <th></th>
                                @foreach ( $convertedDates as $date )
                                <th class="txt"><p class="">{{$date->format('d')}}日</p></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="shift-calendar-table__body">
                            @foreach ($projects as $project)
                                <tr class="shift-calendar-table__body__row --count-row">
                                    <td class="--count-row__project"><p class="">{{$project->name}}</p></td>
                                    @foreach ( $convertedDates as $date )
                                        @php
                                            $project_count = 0;
                                            foreach ($shifts as $shift) {
                                                if ($shift->date == $date->format('Y-m-d')) {
                                                    foreach ($shift->projectsVehicles as $spv) {
                                                        if($spv->project){
                                                            if($spv->project->id == $project->id){
                                                                $project_count++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <td class="--count-row__count">
                                            {{$project_count}}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            @foreach ($unregistered_project as $unProject)
                                <tr class="shift-calendar-table__body__row --count-row">
                                    <td class="--count-row__project" style="color: red;">{{$unProject}}</td>
                                    @foreach ( $convertedDates as $date )
                                        @php
                                            $unProject_count = 0;
                                            foreach ($shifts as $shift) {
                                                if ($shift->date == $date->format('Y-m-d')) {
                                                    foreach ($shift->projectsVehicles as $spv) {
                                                        if($spv->unregistered_project){
                                                            if($spv->unregistered_project == $unProject){
                                                                $unProject_count++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <td>{{$unProject_count}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr class="shift-calendar-table__body__row --count-row">
                                <td class="">合計</td>
                                @foreach ( $convertedDates as $date )
                                    @php
                                        $day_count = 0;
                                        foreach ($shifts as $shift) {
                                                if ($shift->date == $date->format('Y-m-d')) {
                                                    foreach ($shift->projectsVehicles as $spv) {
                                                        $day_count++;
                                                    }
                                                }
                                            }
                                    @endphp
                                    <td class="--count-row__project">
                                        {{$day_count}}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <p>{{$startOfWeek}}〜{{$endOfWeek}}のシフトはありません</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
