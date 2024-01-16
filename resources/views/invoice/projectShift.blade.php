<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <div class="invoice">
        <div class="invoice__project-shift">
            <form action="{{ route('invoice.searchProjectShift') }}" method="POST">
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
                        <label for="">クライアント名</label>
                        <select name="projectClient" id="" class="select-style">
                            <option value="">選択してください</option>
                            @foreach ($projectClients as $projectClient)
                                <option value="{{$projectClient}}">{{$projectClient}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="search-btn">
                        <p>検索</p>
                    </button>
                </div>
            </form>

            @if ($ShiftProjectVehicles)
                <div class="project-shfit-wrap">
                    <div class="project-info-table">
                        <div class="project-info-table__row">
                            <div class="project-info-table__row__txt --head"><p class="">クライアント名</p></div>
                            <div class="project-info-table__row__txt --data"><p class="">{{$projectClientNameByPdf}}</p></div>
                        </div>
                        <div class="project-info-table__row">
                            <div class="project-info-table__row__txt --head"><p class="">案件名</p></div>
                            <div class="project-info-table__row__txt --data">
                                @foreach ($getProjects as $getProject)
                                    <p>{{$getProject->name}}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <?php
                        $which_part = null;//0 : 案件 1 : 所属先
                        $part_count = [];
                        $total_retail = 0;
                        $company_total_salary = [];
                        $company_total_expressway = [];
                        $company_total_parking = [];
                        $pdf_retail = 0;
                    ?>
                    <form action="{{route('invoice.projectShiftUpdate')}}" method="POST">
                        @csrf
                        <input hidden type="text" name="projectClient" value="{{$projectClientName}}">
                        <input hidden type="text" name="year" value="{{$getYear}}">
                        <input hidden type="text" name="month" value="{{$getMonth}}">
                        <button class="save-btn mt-10">
                            <p class="">保存</p>
                        </button>
                        <div class="project-shift-calender">
                            <div class="project-shift-calender__head">
                                <div class="project-shift-calender__head__item"><p class=""></p></div>
                                @if (count($getProjects) > 1)
                                    @foreach ($getProjects as $getProject)
                                        <div class="project-shift-calender__head__item"><p class="">{{$getProject->name}}</p></div>
                                        @if (!isset($part_count[$getProject->name]))
                                            <?php $part_count[$getProject->name] = 0?>
                                        @endif
                                    @endforeach
                                    <?php $which_part = 0?>
                                @else
                                    @foreach ($getCompanies as $getComapny)
                                        <div class="project-shift-calender__head__item"><p class="">{{$getComapny->name}}</p></div>
                                        @if (!isset($part_count[$getComapny->name]))
                                            <?php $part_count[$getComapny->name] = 0?>
                                        @endif
                                    @endforeach
                                    <?php $which_part = 1?>
                                @endif
                                <div class="project-shift-calender__head__item"><p class="">上代</p></div>
                                @foreach ($getCompanies as $getComapny)
                                    <div class="project-shift-calender__head__item --column">
                                        <p class="">ドライバー</p>
                                        <p class="">{{$getComapny->name}}</p>
                                    </div>
                                    <div class="project-shift-calender__head__item"><p class="">高速代</p></div>
                                    <div class="project-shift-calender__head__item"><p class="">パーキング代</p></div>
                                @endforeach
                            </div>
                            <div class="project-shift-calender__data">
                                @foreach ($dates as $date)
                                    <div class="project-shift-calender__data__row">
                                        {{-- 日付 --}}
                                        <div class="project-shift-calender__data__row__item">
                                            <p class="">{{$date->format('m-d')}}</p>
                                        </div>
                                        {{-- ドライバー --}}
                                        @if ($which_part == 0)
                                            @foreach ($getProjects as $getProject)
                                                <div class="project-shift-calender__data__row__item">
                                                    @foreach ( $ShiftProjectVehicles as $spv )
                                                        @if ($spv->shift->date == $date->format('Y-m-d'))
                                                            @if ($spv->project->id == $getProject->id)
                                                                <p>{{$spv->shift->employee->name}}</p>
                                                                @if (isset($part_count[$getProject->name]))
                                                                    <?php $part_count[$getProject->name]++?>
                                                                @else
                                                                    <?php $part_count[$getProject->name] = 1?>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ( $getCompanies as $getComapny )
                                                <div class="project-shift-calender__data__row__item --employee-wrap">
                                                    @foreach ( $ShiftProjectVehicles as $spv )
                                                        @if ($spv->shift->date == $date->format('Y-m-d'))
                                                            @if ($spv->shift->employee->company->id == $getComapny->id)
                                                                <p>{{$spv->shift->employee->name}}</p>
                                                                @if (isset($part_count[$getComapny->name]))
                                                                    <?php $part_count[$getComapny->name]++?>
                                                                @else
                                                                    <?php $part_count[$getComapny->name] = 1?>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endif
                                        {{-- 上代 --}}
                                        <?php
                                            $tmp_retail = 0;
                                            $is_retail_check = false;
                                        ?>
                                        <div class="project-shift-calender__data__row__item">
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if ($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->retail_price)
                                                        <?php $tmp_retail += $spv->retail_price?>
                                                        <?php $total_retail += $spv->retail_price?>
                                                        <?php $is_retail_check = true; ?>
                                                        <?php $pdf_retail = $spv->retail_price;?>
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if ($is_retail_check)
                                                <p>{{$tmp_retail}}</p>
                                                <?php $tmp_retail = 0?>
                                            @endif
                                        </div>
                                        @foreach ($getCompanies as $getCompany)
                                            {{-- 所属先ごとのシフトの情報 --}}
                                            <?php
                                                $tmp_salary = 0;
                                                $is_salary_check = false;
                                            ?>
                                            {{-- 給与 --}}
                                            <div class="project-shift-calender__data__row__item">
                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                    @if ($spv->shift->date == $date->format('Y-m-d'))
                                                        @if ($spv->shift->employee->company->id == $getCompany->id)
                                                            <?php $is_salary_check = true; ?>
                                                            <?php $tmp_salary += $spv->driver_price?>
                                                            @if (isset($company_total_salary[$getCompany->id]))
                                                                <?php $company_total_salary[$getCompany->id] += $spv->driver_price?>
                                                            @else
                                                                <?php $company_total_salary[$getCompany->id] = $spv->driver_price?>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @if ($is_salary_check)
                                                    <p>{{$tmp_salary}}</p>
                                                @endif
                                            </div>
                                            {{-- 高速代 --}}
                                            <div class="project-shift-calender__data__row__item">
                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                    @if ($spv->shift->date == $date->format('Y-m-d'))
                                                        @if ($spv->shift->employee->company->id == $getCompany->id)
                                                            <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{$spv->expressway_fee}}">
                                                            @if (isset($company_total_expressway[$getCompany->id]))
                                                                <?php $company_total_expressway[$getCompany->id] += $spv->expressway_fee?>
                                                            @else
                                                                <?php $company_total_expressway[$getCompany->id] = $spv->expressway_fee?>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                            {{-- パーキング代 --}}
                                            <div class="project-shift-calender__data__row__item">
                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                    @if ($spv->shift->date == $date->format('Y-m-d'))
                                                        @if ($spv->shift->employee->company->id == $getCompany->id)
                                                        <input type="text" name="parking_fee[{{$spv->id}}]" value="{{$spv->parking_fee}}">
                                                            @if (isset($company_total_parking[$getCompany->id]))
                                                                <?php $company_total_parking[$getCompany->id] += $spv->parking_fee?>
                                                            @else
                                                                <?php $company_total_parking[$getCompany->id] = $spv->parking_fee?>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                                <div class="project-shift-calender__data__row">
                                    {{-- 全案件数 --}}
                                    <div class="project-shift-calender__data__row__item --column">
                                        <p class="">total</p>
                                        <p class="">{{$ShiftProjectVehicles->count()}}</p>
                                    </div>
                                    {{-- 個々の案件数 --}}
                                    @foreach ($part_count as $name => $count)
                                        <div class="project-shift-calender__data__row__item --column">
                                            <p class="">{{$name}}</p>
                                            <p class="">{{$count}}</p>
                                        </div>
                                    @endforeach
                                    {{-- 上代合計 --}}
                                    <div class="project-shift-calender__data__row__item --column">
                                        <p class="">{{$total_retail}}円</p>
                                    </div>
                                    {{-- 所属先ごとの項目の合計 --}}
                                    @foreach ($getCompanies as $getCompany)
                                        <div class="project-shift-calender__data__row__item">
                                            @foreach ($company_total_salary as $companyId => $amount)
                                                @if ($getCompany->id == $companyId)
                                                    <p class="">{{$amount}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="project-shift-calender__data__row__item">
                                            @foreach ($company_total_expressway as $companyId => $amount)
                                                @if ($getCompany->id == $companyId)
                                                    <p class="">{{$amount}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="project-shift-calender__data__row__item">
                                            @foreach ($company_total_parking as $companyId => $amount)
                                                @if ($getCompany->id == $companyId)
                                                    <p class="">{{$amount}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                <div class="total">
                                    <p class="">合計(税抜き)</p>
                                    <p class="">{{$total_retail}}円</p>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            @endif
            @if ($ShiftProjectVehicles)
            <div class="PDF-dump --project-pdf">
                <form action="{{route('invoice.project-issue-pdf')}}" method="POST">
                    @csrf
                    <input hidden type="text" name="total_retail" value="{{$total_retail}}">
                    <input hidden type="text" name="total_count" value="{{$ShiftProjectVehicles->count()}}">
                    <input hidden type="text" name="pdf_retail" value="{{$pdf_retail}}">
                    <input hidden type="text" name="projectClientNameByPdf" value="{{$projectClientNameByPdf}}">
                    <button class="PDF-dump-btn">
                        <p>ドライバー発行PDF請求書</p>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>



</x-app-layout>
