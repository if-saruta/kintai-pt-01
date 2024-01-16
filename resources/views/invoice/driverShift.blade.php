<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <div class="invoice">
        <div class="invoice__driver-shift">
            <form action="{{ route('invoice.searchShift') }}" method="POST">
                @csrf
                <div class="select-area">
                    <div class="invoice-date">
                        <div class="select-area__block">
                            <label for="">年</label>
                            <select name="year" id="" class="select-style">
                                <option value="">選択してください</option>
                                @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="select-area__block">
                            <label for="">月</label>
                            <select name="month" id="" class="select-style">
                                <option value="">選択してください</option>
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
                        </div>
                    </div>
                    <div class="select-area__block">
                        <label for="">ドライバー</label>
                        <select name="employeeId" id="" class="select-style">
                            <option value="">選択してください</option>
                            @foreach ($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="search-btn">
                        <p>検索</p>
                    </button>
                </div>
            </form>
            @if ($shifts)
                <div class="">
                    <div class="when-invoice">
                        <p class="">{{$employeeName->name}}</p>
                        <div class="year-month">
                            <p>{{$getYear}}年{{$getMonth}}月</p>
                        </div>
                    </div>
                    <div class="scroll-x">
                        <form action="{{route('invoice.driverShiftUpdate')}}" method="POST">
                            @csrf
                            <div class="driver-shift">
                                <input hidden type="text" name="employee" value="{{$employeeName->id}}">
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
                                    <div class="driver-shift__head__item --common">
                                        <p class="">金額</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">手当</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">高速代</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">パーキング代</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">二代目</p>
                                    </div>
                                    <div class="driver-shift__head__item --date">
                                        <p class="">日付</p>
                                    </div>
                                    <div class="driver-shift__head__item --project">
                                        <p class="">案件名</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">金額</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">手当</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">高速代</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">パーキング代</p>
                                    </div>
                                    <div class="driver-shift__head__item --common">
                                        <p class="">二代目</p>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="driver-shift__calender-shift">
                                        <?php
                                            $total_salary = 0;//合計給与
                                            $total_allowance = 0;//合計手当
                                            $total_parking = 0;//合計パーキング代
                                            $total_expressway = 0;//合計高速代
                                            $projectCount = [];//合計案件数
                                            $total_working_days = 0;//稼働日数
                                            $vehicle_use = [];//二代目に使用した車両
                                            $vehicle_second_count = 0;//二代目に使用した車両の数
                                            $vehicle_rental_type = null;//その月の貸出形態
                                            $rental_vehicle_number = null;//その月の貸出車両
                                        ?>
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
                                                    @foreach ($shifts as $shift)
                                                        @if($shift->date == $date->format('Y-m-d'))
                                                            <?php $isCheck = true; ?>
                                                            <div class="driver-shift__calender-shift__day__info">
                                                                <?php $hasShift = false; ?>
                                                                @foreach ( $shift->projectsVehicles as $spv )
                                                                    <?php $hasShift = true; ?>
                                                                    <div class="driver-shift__calender-shift__day__info__row">
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
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <p>{{$spv->driver_price}}</p>
                                                                            <?php $total_salary += $spv->driver_price?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <p>{{$spv->total_allowance}}</p>
                                                                            <?php $total_allowance += $spv->total_allowance?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{$spv->expressway_fee}}">
                                                                            <?php $total_expressway += $spv->expressway_fee?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <input type="text" name="parking_fee[{{$spv->id}}]" value="{{$spv->parking_fee}}">
                                                                            <?php $total_parking += $spv->parking_fee?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            @if ($spv->vehicle_rental_type)
                                                                                @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1)
                                                                                    <?php $vehicle_rental_type = $spv->vehicle_rental_type;?>
                                                                                    <?php $rental_vehicle_number = $spv->rentalVehicle->number?>
                                                                                    @if($spv->vehicle_id)
                                                                                        @if($spv->vehicle_id != $spv->rental_vehicle_id)
                                                                                            <p>No.{{$spv->vehicle->number}}</p>
                                                                                            @if (!in_array($spv->vehicle->number, $vehicle_use))
                                                                                                <?php $vehicle_use[] = $spv->vehicle->number; ?>
                                                                                            @endif
                                                                                            <?php $vehicle_second_count++?>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                            @if ($spv->unregistered_vehicle)
                                                                                <p style="color:red;">No.{{$spv->unregistered_vehicle}}</p>
                                                                                @if (!in_array($spv->unregistered_vehicle, $vehicle_use))
                                                                                    <?php $vehicle_use[] = $spv->unregistered_vehicle; ?>
                                                                                @endif
                                                                                <?php $vehicle_second_count++?>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <?php $count++; ?>
                                                                @endforeach
                                                                @if ($hasShift)
                                                                    <?php $total_working_days++?>
                                                                @endif
                                                                @for ($i = 0; $i < ($mustCnt - $count); $i++)
                                                                    <div class="driver-shift__calender-shift__day__info__row">
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --project">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

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
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

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
                                                    @foreach ($shifts as $shift)
                                                        @if($shift->date == $date->format('Y-m-d'))
                                                            <?php $isCheck = true; ?>
                                                            <?php $hasShift = false; ?>
                                                            <div class="driver-shift__calender-shift__day__info">
                                                                @foreach ( $shift->projectsVehicles as $spv )
                                                                    <?php $hasShift = true; ?>
                                                                    <div class="driver-shift__calender-shift__day__info__row">
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
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <p>{{$spv->driver_price}}</p>
                                                                            <?php $total_salary += $spv->driver_price?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <p>{{$spv->total_allowance}}</p>
                                                                            <?php $total_allowance += $spv->total_allowance?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{$spv->expressway_fee}}">
                                                                            <?php $total_expressway += $spv->expressway_fee?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            <input type="text" name="parking_fee[{{$spv->id}}]" value="{{$spv->parking_fee}}">
                                                                            <?php $total_parking += $spv->parking_fee?>
                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">
                                                                            @if ($spv->vehicle_rental_type)
                                                                                @if ($spv->vehicle_rental_type == 0 || $spv->vehicle_rental_type == 1)
                                                                                    <?php $vehicle_rental_type = $spv->vehicle_rental_type;?>
                                                                                    <?php $rental_vehicle_number = $spv->rentalVehicle->number?>
                                                                                    @if($spv->vehicle_id)
                                                                                        @if($spv->vehicle_id != $spv->rental_vehicle_id)
                                                                                            <p>No.{{$spv->vehicle->number}}</p>
                                                                                            @if (!in_array($spv->vehicle->number, $vehicle_use))
                                                                                                <?php $vehicle_use[] = $spv->vehicle->number; ?>
                                                                                            @endif
                                                                                            <?php $vehicle_second_count++?>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                            @if ($spv->unregistered_vehicle)
                                                                                <p style="color:red;">No.{{$spv->unregistered_vehicle}}</p>
                                                                                @if (!in_array($spv->unregistered_vehicle, $vehicle_use))
                                                                                    <?php $vehicle_use[] = $spv->unregistered_vehicle; ?>
                                                                                @endif
                                                                                <?php $vehicle_second_count++?>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <?php $count++; ?>
                                                                @endforeach
                                                                @if ($hasShift)
                                                                    <?php $total_working_days++?>
                                                                @endif
                                                                @for ($i = 0; $i < ($mustCnt - $count); $i++)
                                                                    <div class="driver-shift__calender-shift__day__info__row">
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --project">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                        </div>
                                                                        <div class="driver-shift__calender-shift__day__info__row__data --common">

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
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

                                                                    </div>
                                                                    <div class="driver-shift__calender-shift__day__info__row__data --common">

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
                                                <p class="">二代目車両</p>
                                            </div>
                                            @foreach ($vehicle_use as $number)
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
                                    $second_lease = 1000;//二代目リース料　リース契約時
                                    $second_lease_myCar = 1500;//二代目リース料　自車契約時
                                    $three_lease = 1000;//三代目リース料
                                    $lease_insurance = 9818;//月額リース保険料
                                    $month_lease_insurance = 410;//二代目以降保険料
                                    $daily_lease_rate = 1364;//日割りリース料
                                    $daily_insurance_rate = 455;//日割り保険料
                                ?>
                                <div class="total-amount-table">
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">合計金額</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$total_salary}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">手当</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$total_allowance}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">消費税10%</p></div>
                                        <?php $tax = ($total_salary + $total_allowance) * $tax?>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$tax}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">パーキング代</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$total_parking}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">高速代</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$total_expressway}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">事務委託手数料(15%)</p></div>
                                        <?php $administrative_commission_fee = $total_salary * $administrative_rate?>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$administrative_commission_fee}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">事務手数料</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$administrative_fee}}</p></div>
                                    </div>
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">振込手数料</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$transfer_fee}}</p></div>
                                    </div>
                                    @if ($vehicle_rental_type == 1)
                                        <div class="total-amount-table__row">
                                            <div class="total-amount-table__row__item"><p class="">リース代　月契約 No.{{$rental_vehicle_number}}</p></div>
                                            <div class="total-amount-table__row__item --amount"><p class="">{{$month_lease_fee}}</p></div>
                                        </div>
                                    @endif
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">リース　二代目(日割り)</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$second_lease * $vehicle_second_count}}</p></div>
                                    </div>
                                    @if ($vehicle_rental_type == 1)
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">保険料　月契約 No.{{$rental_vehicle_number}}</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$lease_insurance}}</p></div>
                                    </div>
                                    @endif
                                    <div class="total-amount-table__row">
                                        <div class="total-amount-table__row__item"><p class="">保険料　二代目(日割り)</p></div>
                                        <div class="total-amount-table__row__item --amount"><p class="">{{$month_lease_insurance * $vehicle_second_count}}</p></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if ($shifts)
            <div class="PDF-dump">
                <form action="{{route('invoice.driver-issue-pdf')}}" method="POST">
                    @csrf
                    {{-- 業務委託手数料 --}}
                    <input hidden type="text" name="administrative_commission_fee" value="{{$administrative_commission_fee}}">
                    {{-- 合計リース代 --}}
                    <input hidden type="text" name="total_lease" value="{{$month_lease_fee + ($second_lease * $vehicle_second_count)}}">
                    {{-- 合計保険代 --}}
                    <input hidden type="text" name="total_insurance" value="{{$lease_insurance + ($month_lease_insurance * $vehicle_second_count)}}">
                    {{-- 事務手数料 --}}
                    <input hidden type="text" name="administrative_fee" value="{{$administrative_fee}}">
                    {{-- 振込手数料 --}}
                    <input hidden type="text" name="transfer_fee" value="{{$transfer_fee}}">
                    {{-- 給与合計 --}}
                    <input hidden type="text" name="total_salary" value="{{$total_salary}}">
                    {{-- 手当 --}}
                    <input hidden type="text" name="total_allowance" value="{{$total_allowance}}">
                    {{-- 高速代・パーキング代 --}}
                    <input hidden type="text" name="etc" value="{{$total_expressway + $total_parking}}">
                    {{-- 従業員ID --}}
                    <input hidden type="text" name="employee" value="{{$employeeName->id}}">
                    {{-- <input hidden type="text" name="" value=""> --}}
                    <button class="PDF-dump-btn">
                        <p>ドライバー発行PDF請求書</p>
                    </button>
                </form>
            </div>
            <div class="PDF-dump --to-company">
                <form action="{{route('invoice.company-issue-pdf')}}" method="POST">
                    @csrf
                    {{-- 業務委託手数料 --}}
                    <input hidden type="text" name="administrative_commission_fee" value="{{$administrative_commission_fee}}">
                    {{-- 合計リース代 --}}
                    <input hidden type="text" name="total_lease" value="{{$month_lease_fee + ($second_lease * $vehicle_second_count)}}">
                    {{-- 合計保険代 --}}
                    <input hidden type="text" name="total_insurance" value="{{$lease_insurance + ($month_lease_insurance * $vehicle_second_count)}}">
                    {{-- 事務手数料 --}}
                    <input hidden type="text" name="administrative_fee" value="{{$administrative_fee}}">
                    {{-- 振込手数料 --}}
                    <input hidden type="text" name="transfer_fee" value="{{$transfer_fee}}">
                    {{-- 給与合計 --}}
                    <input hidden type="text" name="total_salary" value="{{$total_salary}}">
                    {{-- 手当 --}}
                    <input hidden type="text" name="total_allowance" value="{{$total_allowance}}">
                    {{-- 高速代・パーキング代 --}}
                    <input hidden type="text" name="etc" value="{{$total_expressway + $total_parking}}">
                    {{-- 従業員ID --}}
                    <input hidden type="text" name="employee" value="{{$employeeName->id}}">
                    {{-- <input hidden type="text" name="" value=""> --}}
                    <button class="PDF-dump-btn">
                        <p>所属先発行PDF請求書</p>
                    </button>
                </form>
            </div>

            @endif

        </div>
    </div>



</x-app-layout>
