<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員給与シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="button-under">
            <a href="{{route('shift.')}}" class="mb-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">トップに戻る</a>
            <a href="{{route('shift.projectPriceShift')}}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">上代閲覧用シフト</a>
            <a href="{{route('shift.employeeShowShift')}}" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員閲覧用シフト</a>
            <a href="{{route('shift.projectCount')}}" class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">案件数用シフト</a>
        </div>

        <div class="caleder">
            <form action="{{route('shift.employeePriceShiftSelectWeek')}}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{$startOfWeek}}">
                <input type="hidden" name="action" value="previous">
                <button type="submit" class="prev-week action-weekBtn">
                    前の週
                </button>
            </form>
            <form action="{{route('shift.employeePriceShiftSelectWeek')}}" class="calender-view" method="POST">
                @csrf
                <input type="date" id="date" name="date">
                <button type="submit" class="calender-view__button">
                    検索
                </button>
            </form>
            <form action="{{route('shift.employeePriceShiftSelectWeek')}}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{$endOfWeek}}">
                <input type="hidden" name="action" value="next">
                <button type="submit" class="prev-week action-weekBtn">
                    次の週
                </button>

            </form>
        </div>

        <div class="shift">
            @if(!$shiftDataByEmployee->isEmpty())
                @foreach ($shiftDataByEmployee as $employeeId => $shiftData)
                {{-- 給与合計格納変数 --}}
                <?php $priceTotal = 0?>
                    {{-- 従業員のシフト --}}
                    <div class="shift__employee-shift">
                        <?php $employeeCnt = true; ?>
                        {{-- 1日ごとのシフトのループ --}}
                        @foreach ($shiftData as $shift)
                            <div class="shift__day">
                                {{-- 日付表示 --}}
                                <p class="">{{$shift->date}}</p>
                                <div class="shift__day__info">
                                    {{-- 従業員表示 --}}
                                    @if ($employeeCnt)
                                        <div class="shift__employeeName">
                                            @if ($shift->employee)
                                                <p>{{$shift->employee->name}}</p>
                                            @else
                                                <p style="color:red;">{{$shift->unregistered_employee}}</p>
                                            @endif
                                        </div>
                                    @endif
                                    <?php $employeeCnt = false; ?>
                                    {{-- 1日のシフトの表示 --}}
                                    <div class="shift-day__detail">
                                        {{-- 午前のシフト --}}
                                        <div class="shift-day__detail__part">
                                            <p class="">午前</p>
                                            {{-- 案件表示 --}}
                                            <div class="shift-project">
                                                @foreach ($shift->projectsVehicles as $spv)
                                                    @if ($spv->time_of_day == 0)
                                                        @if ($spv->project)
                                                            <p>{{$spv->project->name}}</p>
                                                            <?php $isProject = true?>
                                                        @elseif(!empty($spv->unregistered_project))
                                                            <p style="color:red;">{{$spv->unregistered_project}}</p>
                                                            <?php $isProject = true?>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                            {{-- 給与表示 --}}
                                            <div class="shift-driverPrice">
                                                @foreach ($shift->projectsVehicles as $spv)
                                                    @if ($spv->time_of_day == 0)
                                                        <?php $isDirverPriceCheck = false;?>
                                                        @if ($spv->driver_price)
                                                            <p>D : {{$spv->driver_price}}円</p>
                                                            <?php $priceTotal += $spv->driver_price?>
                                                            <?php $isDirverPriceCheck = true;?>
                                                        @endif
                                                        @if (!$isDirverPriceCheck)
                                                                <p>D : 円</p>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        {{-- 午後のシフト --}}
                                        <div class="shift-day__detail__part">
                                            <p class="">午後</p>
                                            {{-- 案件表示 --}}
                                            <div class="shift-project">
                                                @foreach ($shift->projectsVehicles as $spv)
                                                    @if ($spv->time_of_day == 1)
                                                        @if ($spv->project)
                                                            <p>{{$spv->project->name}}</p>
                                                            <?php $isProject = true?>
                                                        @elseif(!empty($spv->unregistered_project))
                                                            <p style="color:red;">{{$spv->unregistered_project}}</p>
                                                            <?php $isProject = true?>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                            {{-- 給与表示 --}}
                                            <div class="shift-driverPrice">
                                                @foreach ($shift->projectsVehicles as $spv)
                                                    @if ($spv->time_of_day == 1)
                                                        <?php $isDirverPriceCheck = false;?>
                                                        @if ($spv->driver_price)
                                                            <p>D : {{$spv->driver_price}}円</p>
                                                            <?php $priceTotal += $spv->driver_price?>
                                                            <?php $isDirverPriceCheck = true;?>
                                                        @endif
                                                        @if (!$isDirverPriceCheck)
                                                        <p>D : 円</p>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <p class="totalPrice">{{$priceTotal}}円</p>
                    </div>
                @endforeach
            @else
                <p>{{$startOfWeek}}〜{{$endOfWeek}}のシフトはありません</p>
            @endif
        </div>

    </div>



</x-app-layout>
