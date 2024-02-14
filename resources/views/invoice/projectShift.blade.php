<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>
    <main class="main --main-shift --shift-main">
        <div class="main__link-block --shift-link-block">
            <div class="main__link-block__tags">
                <a href="{{route('invoice.driverShift')}}" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.driverShift','invoice.searchShift','invoice.driver-edit-pdf') ? 'active' : '' }} link">
                        <span class="">ドライバー</span>
                    </button>
                </a>
                <a href="{{route('invoice.projectShift')}}" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.projectShift','invoice.searchProjectShift', 'invoice.project-edit-pdf') ? 'active' : '' }} link">
                        <span class="">案件</span>
                    </button>
                </a>
                <a href="{{route('invoice.charterShift')}}"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button class="{{ request()->routeIs('invoice.charterShift','invoice.findCharterShift') ? 'active' : '' }} link">
                        <span class="">チャーター</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="main__white-board --invoice-project-board">
            <div class="invoice">
                <div class="invoice__project-shift">
                    <form action="{{ route('invoice.searchProjectShift') }}" method="POST">
                        @csrf
                        <div class="select-area">
                            <div class="select-area__block">
                                <select name="year" id="" class="c-select year-select" required>
                                    {{-- <option value="">選択してください</option> --}}
                                    @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
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
                            <div class="select-area__block name-block">
                                <select name="client" id="" class="c-select name-select" required>
                                    <option value="">選択してください</option>
                                    @foreach ($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>

                    <?php
                        $which_part = null;//0 : 案件 1 : 所属先
                        $part_count = [];
                        $total_retail = 0;
                        $company_total_salary = [];
                        $company_total_expressway = [];
                        $company_total_parking = [];
                        $pdf_retail = 0;
                    ?>

                    @if ($ShiftProjectVehicles !== null && !$ShiftProjectVehicles->isEmpty())
                    <div class="project-shfit-wrap x-scroll --x-scroll-project-shift">
                        {{-- クライアント・案件情報 --}}
                        {{-- <div class="project-info-table">
                            <div class="project-info-table__row">
                                <div class="project-info-table__row__txt --head">
                                    <p class="">クライアント名</p>
                                </div>
                                <div class="project-info-table__row__txt --data">
                                    <p class="">{{$client->pdfName}}</p>
                                </div>
                            </div>
                            <div class="project-info-table__row">
                                <div class="project-info-table__row__txt --head">
                                    <p class="">案件名</p>
                                </div>
                                <div class="project-info-table__row__txt --project --data">
                                    @foreach ($projects as $project)
                                    <p>{{$project->name}}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                        <div class="employee-date">
                            <div class="employee-date__year-month">
                                <p>{{$getYear}}年</p>
                                <p>{{$getMonth}}月</p>
                            </div>
                            <div class="employee-date__employee">
                                <p class="">{{$getClient->name}}</p>
                            </div>
                        </div>
                        <form action="{{route('invoice.projectShiftUpdate')}}" method="POST" class="parent-scroll">
                            @csrf
                            {{-- リダイレクト先の検索用の情報 --}}
                            <input hidden type="text" name="client" value="{{$client->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">

                            <button class="save-btn mt-10 mt-free">
                                <p class="">保存</p>
                            </button>

                            {{-- カレンダー --}}
                            <div class="project-shift-calender">
                                {{-- ヘッド --}}
                                <div class="project-shift-calender__head">
                                    {{-- 日付空欄 --}}
                                    <div class="project-shift-calender__head__item">
                                        <p class=""></p>
                                    </div>
                                    {{-- 表示形式の判定 --}}
                                    @if ($projects->count() > 1) {{-- 案件形式で表示 --}}
                                    @foreach ($projects as $project)
                                    <div class="project-shift-calender__head__item --andCompany">
                                        <p class="project-txt">{{$project->name}}</p>
                                        <div class="--andCompany__wrap">
                                            @foreach ($getCompanies as $getCompany)
                                            <div class="--andCompany__wrap__block">
                                                <p class="">{{$getCompany->name}}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if (!isset($part_count[$project->name]))
                                    <?php $part_count[$project->name] = 0?>
                                    @endif
                                    @endforeach
                                    <?php $which_part = 0?>
                                    @else {{-- 会社形式で表示 --}}
                                    @foreach ($getCompanies as $getCompany)
                                    <div class="project-shift-calender__head__item">
                                        <p class="">{{$getCompany->name}}</p>
                                    </div>
                                    @if ( !isset($part_count[$getCompany->name]) )
                                    <?php $part_count[$getCompany->name] = 0?>
                                    @endif
                                    @endforeach
                                    <?php $which_part = 1?>
                                    @endif

                                    {{-- 上代 --}}
                                    <div class="project-shift-calender__head__item">
                                        <p class="">上代</p>
                                    </div>

                                    {{-- --}}
                                    <div class="project-shift-calender__head__project">
                                        @foreach ($projects as $project)
                                        <div class="project-block">
                                            @if($which_part == 0)
                                            <div class="project-block__project-name">
                                                <p class="">{{$project->name}}</p>
                                            </div>
                                            @endif
                                            <div class="company-block">
                                                @foreach ($getCompanies as $getCompany)
                                                <div class="company-item project{{$getCompany->id}}">
                                                    <div class="company-item__clm salaryClm">
                                                        <p class="">ドライバー</p>
                                                        <p class="">{{$getCompany->name}}</p>
                                                    </div>
                                                    <div class="company-item__clm retailClm">
                                                        <p class="">上代</p>
                                                    </div>
                                                    <div class="company-item__clm expressClm">
                                                        <p class="">高速代</p>
                                                    </div>
                                                    <div class="company-item__clm parkingClm">
                                                        <p class="">パーキング代</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                </div>

                                {{-- カレンダーBODY --}}
                                <div class="project-shift-calender__data">
                                    @foreach ($dates as $date)
                                    {{-- 行 --}}
                                    <div class="project-shift-calender__data__row">

                                        {{-- 日付 --}}
                                        <div class="project-shift-calender__data__row__item">
                                            <p class="">{{$date->format('m-d')}}</p>
                                        </div>
                                        {{-- ドライバー --}}
                                        @if ( $which_part == 0 )
                                        @foreach ( $projects as $project )
                                        <div class="project-shift-calender__data__row__item">
                                            @foreach ( $getCompanies as $getCompany )
                                            <div
                                                class="project-shift-calender__data__row__item__company project{{$getCompany->id}}">
                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                    @if ( $spv->shift->date == $date->format('Y-m-d') )
                                                        @if ( $spv->project->id == $project->id )
                                                            @if ($spv->shift->employee)
                                                                @if ( $spv->shift->employee->company_id == $getCompany->id )
                                                                    <div class="">
                                                                        <p>{{$spv->shift->employee->name}}</p>
                                                                    </div>
                                                                    @if ( isset($part_count[$project->name]) )
                                                                        <?php $part_count[$project->name]++?>
                                                                    @else
                                                                        <?php $part_count[$project->name] = 1?>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                        @else
                                        @foreach ( $getCompanies as $getCompany )
                                        <div class="project-shift-calender__data__row__item --employee-wrap project{{$getCompany->id}}">
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if ($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company->id == $getCompany->id)
                                                            <p>{{$spv->shift->employee->name}}</p>
                                                            @if (isset($part_count[$getCompany->name]))
                                                                <?php $part_count[$getCompany->name]++?>
                                                            @else
                                                                <?php $part_count[$getCompany->name] = 1?>
                                                            @endif
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
                                            {{-- 複数入ってる場合の上代の計算 --}}
                                            <?php $tmp_retail += $spv->retail_price?>
                                            {{-- 上代の月の合計の計算 --}}
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

                                        @foreach ($projects as $project)
                                        @foreach ($getCompanies as $getCompany)
                                        {{-- 所属先ごとのシフトの情報 --}}
                                        <?php
                                                            $tmp_salary = 0;
                                                            $is_salary_check = false;
                                                        ?>
                                        {{-- 給与 --}}
                                        <div
                                            class="project-shift-calender__data__row__item project{{$getCompany->id}} salaryClm">
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if ($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company->id == $getCompany->id && $project->id == $spv->project_id)
                                                            <input type="text" name="driver_price[{{$spv->id}}]" value="{{$spv->driver_price}}">
                                                            <?php $is_salary_check = true; ?>
                                                            <?php $tmp_salary += $spv->driver_price?>
                                                            @if (isset($company_total_salary[$getCompany->id]))
                                                                <?php $company_total_salary[$getCompany->id] += $spv->driver_price?>
                                                            @else
                                                                <?php $company_total_salary[$getCompany->id] = $spv->driver_price?>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                            {{-- @if ($is_salary_check)
                                            <p>{{$tmp_salary}}</p>
                                            @endif --}}
                                        </div>

                                        {{-- 上代 --}}
                                        <div
                                            class="project-shift-calender__data__row__item --clm project{{$getCompany->id}} retailClm">
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if ($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company->id == $getCompany->id && $project->id == $spv->project_id)
                                                            <input type="text" name="retail_price[{{$spv->id}}]" value="{{$spv->retail_price}}">
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>

                                        {{-- 高速代 --}}
                                        <div
                                            class="project-shift-calender__data__row__item --clm project{{$getCompany->id}} expressClm">
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if ($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company->id == $getCompany->id && $project->id == $spv->project_id)
                                                            <input type="text" name="expressway_fee[{{$spv->id}}]"
                                                                value="{{$spv->expressway_fee}}">
                                                            @if (isset($company_total_expressway[$getCompany->id]))
                                                                <?php $company_total_expressway[$getCompany->id] += $spv->expressway_fee?>
                                                            @else
                                                                <?php $company_total_expressway[$getCompany->id] = $spv->expressway_fee?>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>

                                        {{-- パーキング代 --}}
                                        <div
                                            class="project-shift-calender__data__row__item --clm project{{$getCompany->id}} parkingClm">
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                            @if ($spv->shift->date == $date->format('Y-m-d'))
                                            @if ($spv->shift->employee->company->id == $getCompany->id && $project->id
                                            == $spv->project_id)
                                            <input type="text" name="parking_fee[{{$spv->id}}]"
                                                value="{{$spv->parking_fee}}">
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
                                        <div
                                            class="project-shift-calender__data__row__item --projectTotalCount --column">
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
                    @if ($warning !== null)
                    <p class="warning-txt">{{$warning}}</p>
                    @endif
                    @if ($ShiftProjectVehicles !== null && !$ShiftProjectVehicles->isEmpty())
                    <div class="PDF-dump --project-pdf">
                        <form action="{{route('invoice.project-edit-pdf')}}" method="POST">
                            @csrf

                            {{-- カレンダーvalue --}}
                            <input hidden type="text" name="total_retail" value="{{$total_retail}}">
                            <input hidden type="text" name="total_count" value="{{$ShiftProjectVehicles->count()}}">
                            <input hidden type="text" name="pdf_retail" value="{{$pdf_retail}}">
                            <input hidden type="text" name="projectClientNameByPdf" value="{{$getClient->pdfName}}">

                            {{-- その他情報 --}}
                            <input hidden type="text" name="client" value="{{$getClient->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">

                            {{-- パーキング代・高速代 --}}
                            <?php $total_express_way_fee = 0?>
                            @foreach ($company_total_expressway as $companyId => $amount)
                            <?php $total_express_way_fee += $amount; ?>
                            @endforeach

                            <?php $total_parking_fee = 0?>
                            @foreach ($company_total_parking as $companyId => $amount)
                            <?php $total_parking_fee += $amount; ?>
                            @endforeach

                            <input hidden type="text" name="total_express_way__fee" value="{{$total_express_way_fee}}">
                            <input hidden type="text" name="total_parking_fee" value="{{$total_parking_fee}}">

                            <button class="calendar-pdf-button ab-invoice-elem">
                                <p>請求書確認画面</p>
                            </button>

                            {{-- 表示機能のチェックボックス --}}
                            <div class="view-clm ab-invoice-check-elem">
                                @foreach ($getCompanies as $getCompany)
                                <div class="view-clm__item">
                                    <label for="">{{$getCompany->name}}</label>
                                    <input type="checkbox" name="company_check[]" value="project{{$getCompany->id}}"
                                        class="viewClmCheck" checked>
                                </div>
                                @endforeach
                                <div class="view-clm__item">
                                    <label for="">上代</label>
                                    <input type="checkbox" name="retail_check" value="retailClm" class="viewClmCheck"
                                        checked>
                                </div>
                                <div class="view-clm__item">
                                    <label for="">ドライバー</label>
                                    <input type="checkbox" name="slary_check" value="salaryClm" class="viewClmCheck"
                                        checked>
                                </div>
                                <div class="view-clm__item">
                                    <label for="">高速代</label>
                                    <input type="checkbox" name="expressway_check" value="expressClm"
                                        class="viewClmCheck" checked>
                                </div>
                                <div class="view-clm__item">
                                    <label for="">パーキング代</label>
                                    <input type="checkbox" name="parking_check" value="parkingClm" class="viewClmCheck"
                                        checked>
                                </div>
                            </div>
                        </form>
                        <form action="{{route('invoice.project-calendar-pdf')}}" class="" method="POST">
                            @csrf
                            {{-- その他情報 --}}
                            <input hidden type="text" name="client" value="{{$client->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">

                            <button class="calendar-pdf-button ab-elem">
                                <p>カレンダーPDF</p>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>



</x-app-layout>

<script src="{{asset('js/invoice-project.js')}}"></script>
