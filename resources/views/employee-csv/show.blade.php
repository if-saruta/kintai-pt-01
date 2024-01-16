<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <a href="{{route('csv-issue.')}}" style="display: block;width:fit-content"
            class="mb-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">検索に戻る</a>

        @if ($shifts->isEmpty())
        <p>条件に一致するシフトがありません</p>
        @else
        <a href="{{route('csv-employee.export',['employeeId'=>$employeeId,'year'=>$year,'month'=>$month])}}"
            style="display: block;width:fit-content"
            class="mb-10 text-white bg-green-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">csvファイルにエクスポート</a>
        <div class="print-out">
            {{-- 従業員名取得 --}}
            @foreach ($shifts as $shift)
                <?php $employeeName = $shift->employee->name?>
            @endforeach
            <p>{{$employeeName}}</p>
            <div class="print-out__calendar">
                @foreach ($dates as $date)
                    <div class="print-out__calendar__day">
                        {{-- 日付を表示 --}}
                        <div class="">
                            <p>{{$date}}</p>
                        </div>
                        {{-- 午前案件 --}}
                        <div class="print-out__calendar__day__project">
                            @foreach ($shifts as $shift)
                                @if ($shift->date == $date)
                                    @foreach ($shift->projectsVehicles as $spv)
                                        @if($spv->time_of_day == 0)
                                            @if ($spv->project)
                                                <p>{{$spv->project->name}}</p>
                                            @elseif(!empty($spv->unregistered_project))
                                                <p style="color:red;">{{$spv->unregistered_project}}</p>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        {{-- 午後案件 --}}
                        <div class="print-out__calendar__day__project">
                            @foreach ($shifts as $shift)
                                @if ($shift->date == $date)
                                    @foreach ($shift->projectsVehicles as $spv)
                                        @if($spv->time_of_day == 1)
                                            @if ($spv->project)
                                                <p>{{$spv->project->name}}</p>
                                            @elseif(!empty($spv->unregistered_project))
                                                <p style="color:red;">{{$spv->unregistered_project}}</p>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        {{-- 給与合計 --}}
                        <div class="div">
                            <?php $priceTotal = 0;?>
                            @foreach ($shifts as $shift)
                                @if ($shift->date == $date)
                                    @foreach ($shift->projectsVehicles as $spv)
                                        <?php $isDirverPriceCheck = false;?>
                                        @if ($spv->driver_price)
                                            <?php $priceTotal += $spv->driver_price?>
                                            <?php $isDirverPriceCheck = true;?>
                                        @elseif($spv->project)
                                            @foreach ($payments as $payment)
                                                @if ($payment->employee_id == $shift->employee_id && $payment->project_id == $spv->project_id)
                                                    @if(!empty($payment->amount))
                                                        <?php $priceTotal += $payment->amount?>
                                                        <?php $isDirverPriceCheck = true;?>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        @if (!$isDirverPriceCheck)
                                            @if ($spv->project)
                                                <?php $priceTotal += $spv->project->driver_price?>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            <p>{{$priceTotal}}円</p>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</x-app-layout>
