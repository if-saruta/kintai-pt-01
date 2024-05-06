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
                        class="{{ request()->routeIs('invoice.projectShift','invoice.searchProjectShift', 'invoice.projectShiftUpdate','invoice.project-edit-pdf') ? 'active' : '' }} link">
                        <span class="">案件</span>
                    </button>
                </a>
                <a href="{{route('invoice.charterShift')}}" class="main__link-block__item --shift-link-block__item">
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
                <div class="project-invoice-shift">
                    <form action="{{ route('invoice.searchProjectShift') }}" method="POST">
                        @csrf
                        <div class="c-select-area">
                            <div class="c-select-area__block">
                                <select name="year" id="" class="c-select year-select" required>
                                    <option value="">----</option>
                                    @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                        @if ($year == $getYear)
                                            <option selected value="{{ $year }}">{{ $year }}</option>
                                        @else
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="c-select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
                                    <option value="">----</option>
                                    @for ($i = 1; $i <= 12; $i++) @if ($i==$getMonth) <option selected value="{{ $i }}">
                                        {{ $i }}</option>
                                        @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endif
                                        @endfor
                                </select>
                                <label for="">月</label>
                            </div>
                            <div class="c-select-area__block name-block">
                                <select name="client" id="" class="c-select name-select" required>
                                    <option value="">----</option>
                                    @foreach ($clients as $client)
                                    @if ($client->id == $clientId)
                                    <option selected value="{{$client->id}}">{{$client->name}}</option>
                                    @else
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <button class="c-search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>

                    @if ($ShiftProjectVehicles !== null && !$ShiftProjectVehicles->isEmpty())
                    {{-- 請求書確認 --}}
                    <div class="c-middle-head">
                        <div class="c-search-info">
                            <div class="c-search-info__date">
                                <p>{{$getYear}}年</p>
                                <p>{{$getMonth}}月</p>
                            </div>
                            <div class="c-search-info__name">
                                <p class="">{{$getClient->name}}</p>
                            </div>
                        </div>
                        <form action="{{route('invoice.project-edit-pdf')}}" method="POST"
                            class="">
                            @csrf
                            <input hidden type="text" name="client" value="{{ $getClient->id }}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">

                            @foreach ($selectedDisplayCoCheck as $company)
                            <input hidden type="text" name="companyByInvoice[]" value="{{ $company }}"
                                class="setArrowCompanyByInvoice">
                            @endforeach
                            {{-- <input hidden type="text" name="salary_check" value="1" class="setDisplayActiveInvoice">
                            <input hidden type="text" name="retail_check" value="1" class="setDisplayActiveInvoice">
                            <input hidden type="text" name="expressway_check" value="1" class="setDisplayActiveInvoice">
                            <input hidden type="text" name="parking_check" value="1" class="setDisplayActiveInvoice"> --}}
                            @foreach ( $selectedDisplayCheck as $displayClm )
                                <input hidden type="text" name="selectedDisplayCheck[]" value="{{ $displayClm }}">
                            @endforeach
                            @foreach ($narrowProjectIds as $narrowProjectId)
                            <input hidden type="text" name="narrowInvoiceProjectIds[]" value="{{ $narrowProjectId }}">
                            @endforeach
                            <button class="c-middle-head__button">
                                請求書確認
                            </button>
                        </form>
                    </div>
                    <div class="project-invoice-top-btn-area">
                        {{-- カレンダーダウンロード --}}
                        <form action="{{route('invoice.project-calendar-pdf')}}" method="POST"
                            class="project-calendar-pdf-form">
                            @csrf
                            {{-- 所属の分割 --}}
                            <input hidden type="text" class="" name="separateByCompany" value="{{ $separateByCompany }}">
                            {{-- 所属先 --}}
                            @foreach ($selectedDisplayCoCheck as $narrowCompany)
                                <input hidden type="text" name="narrowCompany[]" value="{{ $narrowCompany }}">
                            @endforeach
                            {{-- 案件 --}}
                            @foreach ($narrowProjectIds as $narrowProjectId)
                                <input hidden type="text" name="narrowProjectIds[]" value="{{ $narrowProjectId }}">
                            @endforeach
                            {{-- 表示する行 --}}
                            @foreach ( $selectedDisplayCheck as $displayClm )
                                <input hidden type="text" name="selectedDisplayCheck[]" value="{{ $displayClm }}">
                            @endforeach
                            <input hidden type="text" name="client" value="{{$getClient->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <button name="action" value="beside" class="c-pdf-download-btn">
                                ダウンロード 横画面
                            </button>
                            <button name="action" value="vertical" class="c-pdf-download-btn">
                                ダウンロード 縦画面
                            </button>
                        </form>
                        {{-- 設定ボタン --}}
                        <div class="setting-btn" id="settingOpen">
                            設定
                        </div>
                    </div>
                    {{-- 設定モーダル --}}
                    <form action="{{ route('invoice.searchProjectShift') }}" method="POST" class="setting-modal-wrap"
                        id="settingModalWrap">
                        @csrf
                        <input hidden type="text" name="client" value="{{ $getClient->id }}">
                        <input hidden type="text" name="year" value="{{$getYear}}">
                        <input hidden type="text" name="month" value="{{$getMonth}}">
                        <span class="setting-modal-wrap__bg settingCloseBtn"></span>
                        <div class="setting-modal-wrap__white-board">
                            <div class="setting-modal-wrap__white-board__inner">
                                <p class="head">設定</p>
                                <div class="modal-scroll">
                                    <p class="title">非表示項目</p>
                                    <div class="check-area">
                                        <lable class="check-item">
                                            <input type="checkbox" name="displayCheck[]" value="salaryClm"
                                                class="viewClmCheck hasDisplayValue" data-check="one"
                                                @if(!$hasNarrow) checked @elseif(in_array('salaryClm', $selectedDisplayCheck)) checked @endif>
                                            ドライバー価格
                                        </lable>
                                        <lable class="check-item">
                                            <input type="checkbox" name="displayCheck[]" value="retailClm"
                                                class="viewClmCheck hasDisplayValue" data-check="one"
                                                @if(!$hasNarrow) checked @elseif(in_array('retailClm', $selectedDisplayCheck)) checked @endif>
                                            配送料金
                                        </lable>
                                        <lable class="check-item">
                                            <input type="checkbox" name="displayCheck[]" value="expressClm"
                                                class="viewClmCheck hasDisplayValue" data-check="one"
                                                @if(!$hasNarrow) checked @elseif(in_array('expressClm', $selectedDisplayCheck)) checked @endif>
                                            高速料金
                                        </lable>
                                        <lable class="check-item">
                                            <input type="checkbox" name="displayCheck[]" value="parkingClm"
                                                class="viewClmCheck hasDisplayValue" data-check="one"
                                                @if(!$hasNarrow) checked @elseif(in_array('parkingClm', $selectedDisplayCheck)) checked @endif>
                                            駐車料金
                                        </lable>
                                        <input type="text" name="hasNarrow" value="true" hidden>
                                    </div>
                                    <p class="title">所属で分割</p>
                                        <div class="check-area">
                                            <label class="check-item" for="">
                                                <input type="radio" name="separateByCompany" value="true" class="" @if($separateByCompany == 'true') checked @endif>
                                                分割する
                                            </label>
                                            <label class="check-item" for="">
                                                <input type="radio" name="separateByCompany" value="false" class="" @if($separateByCompany == 'false') checked @endif>
                                                分割しない
                                            </label>
                                        </div>
                                    <p class="title">所属絞り込み</p>
                                    <div class="check-area">
                                        {{-- 所属先の列の表示・非表示 --}}
                                        @foreach ($getCompanies as $company)
                                        <lable class="check-item">
                                            <input type="checkbox" name="displayCoCheck[]" value="{{ $company->id }}"
                                                class="viewClmCheck hasDisplayCoValue" data-company-id="{{ $company->id }}"
                                                @if(!$hasNarrow) checked @elseif(in_array($company->id, $selectedDisplayCoCheck)) checked
                                            @endif>
                                            {{ $company->name }}
                                        </lable>
                                        @endforeach
                                    </div>
                                    <p class="title">案件絞り込み</p>
                                    <div class="check-area">
                                        @foreach ($projects as $project)
                                        <label for="" class="check-item">
                                            <input type="checkbox" name="narrowProjects[]" value="{{ $project->id }}" class="projectCheckBox"
                                            @if(empty($narrowProjectIds)) checked @elseif(in_array($project->id, $narrowProjectIds)) checked @endif class="">
                                            {{ $project->name }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="button-area">
                                    <button class="">
                                        絞り込み
                                    </button>
                                    <div class="button-area__back settingCloseBtn">
                                        戻る
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @php
                        // 案件数
                        $project_count = $narrowProjects->count();
                        // 所属先数
                        $company_count = $getCompanies->count();

                    @endphp
                    <input hidden type="text" value="{{ $project_count }}" id="projectCount">
                    <input hidden type="text" value="{{ $company_count }}" id="companyCount">
                    <div class="project-calendar-wrap">
                        <form action="{{route('invoice.projectShiftUpdate')}}" method="POST">
                            @csrf
                            <button class="project-calendar-wrap__save-btn">
                                変更内容を保存
                            </button>
                            {{-- リダイレクト先の検索用の情報 --}}
                            <input hidden type="text" name="client" value="{{$getClient->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <div class="project-calendar-wrap__scroll-wrap">
                                <div class="holiday-table">
                                    <div class="holiday-table__body">
                                        @foreach ( $narrowProjects as $project )
                                            <div class="holiday-table__body__project-item">
                                                <div class="holiday-table__body__project-item__box">{{ $project->name }}</div>
                                                <div class="holiday-table__body__project-item__box --holiday-box">
                                                    @if ($project->holiday->monday == 1)
                                                        <p class="">月</p>
                                                    @endif
                                                    @if ($project->holiday->tuesday == 1)
                                                        <p class="">火</p>
                                                    @endif
                                                    @if ($project->holiday->wednesday == 1)
                                                        <p class="">水</p>
                                                    @endif
                                                    @if ($project->holiday->thursday == 1)
                                                        <p class="">木</p>
                                                    @endif
                                                    @if ($project->holiday->friday == 1)
                                                        <p class="">金</p>
                                                    @endif
                                                    @if ($project->holiday->saturday == 1)
                                                        <p class="" style="color: blue;">土</p>
                                                    @endif
                                                    @if ($project->holiday->sunday == 1)
                                                        <p class="" style="color: red;">日</p>
                                                    @endif
                                                    @if ($project->holiday->public_holiday == 1)
                                                        <p class="" style="color: red;">祝</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- 所属で分割するカレンダー --}}
                                @if ($separateByCompany == 'true')
                                    <table class="project-calendar-wrap__table" id="calendarTable">
                                        {{-- ヘッダー --}}
                                        <thead>
                                            @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                                            <tr>
                                                {{-- 日付ヘッド --}}
                                                <th rowspan="2" class="project-table-date">----</th>
                                                {{-- 従業員名ヘッド --}}
                                                @foreach ($narrowProjects as $project)
                                                    <th colspan="{{ count($selectedDisplayCoCheck) }}" class="co-head border-right-bold" style="@if($project->is_suspended == 1) color: red; @endif">{{$project->name}}</th>
                                                @endforeach
                                                {{-- 上代合計ヘッド --}}
                                                @if (in_array('retailClm', $selectedDisplayCheck))
                                                    <th rowspan="2" class="project-table-w-amount retailClm numberBox border-right-bold">配送料金</th>
                                                @endif
                                                {{-- 案件・所属先詳細ヘッド --}}
                                                @if (!empty($selectedDisplayCheck))
                                                    @foreach ($narrowProjects as $project)
                                                        <th class="rightHead projectInfoHead border-right-bold" data-company-count="{{$company_count}}" style="@if($project->is_suspended == 1) color: red; @endif">{{$project->name}}</th>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            <tr class="head-bottom">
                                                {{-- 案件・所属先詳細項目ヘッド --}}
                                                @foreach ($narrowProjects as $project)
                                                    @php
                                                        $isCountCheck = 1;
                                                    @endphp
                                                    @foreach ($getCompanies as $company)
                                                        @if (in_array($company->id, $selectedDisplayCoCheck))
                                                            <th class="project-table-w-name company{{ $company->id }} coClmHead txtBox @if($isCountCheck == $company_count) border-right-bold @endif">{{ $company->name }}</th>
                                                        @endif
                                                        @php
                                                            $isCountCheck++;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                                @if (!empty($selectedDisplayCheck))
                                                    @foreach ($narrowProjects as $project)
                                                        <th class="border-right-bold">
                                                            <div class="thead-table-layout">
                                                                @foreach ($getCompanies as $company)
                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                        @if (in_array('salaryClm', $selectedDisplayCheck))
                                                                            <p class="thead-table-layout__cell salaryClm numberBox clmHead company{{ $company->id }}">{{ $company->name }}</p>
                                                                        @endif
                                                                        @if (in_array('retailClm', $selectedDisplayCheck))
                                                                            <p class="thead-table-layout__cell retailClm numberBox clmHead company{{ $company->id }}">配送料金</p>
                                                                        @endif
                                                                        @if (in_array('expressClm', $selectedDisplayCheck))
                                                                            <p class="thead-table-layout__cell expressClm numberBox clmHead company{{ $company->id }}">高速料金</p>
                                                                        @endif
                                                                        @if (in_array('parkingClm', $selectedDisplayCheck))
                                                                            <p class="thead-table-layout__cell parkingClm numberBox clmHead company{{ $company->id }}">駐車料金</p>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </th>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            @endif
                                        </thead>
                                        <tbody>
                                            @foreach ( $dates as $date )
                                            <tr class="tableRow">
                                                {{-- 日付 --}}
                                                <td class="project-table-date">{{ $date->format('n') }}月{{
                                                    $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                                                    @php //1日ごとの上代の合計額の格納変数
                                                        $tmp_total_retail_day = null;
                                                        $hasShift = false;
                                                    @endphp
                                                {{-- 従業員表示 --}}
                                                @foreach ($narrowProjects as $project)
                                                    @php
                                                        $isCountCheck = 1;
                                                    @endphp
                                                    @foreach ($getCompanies as $company)
                                                        @if (in_array($company->id, $selectedDisplayCoCheck))
                                                            <td class="company{{ $company->id }} employee-show @if($isCountCheck == $company_count) border-right-bold @endif">
                                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                                                        @if ($spv->shift->employee)
                                                                            @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                <p class="employee-name activeElem">
                                                                                    {{ $spv->shift->employee->name }}
                                                                                    <input hidden type="text" class="hasShiftInfo"
                                                                                        data-shiftPv-id="{{ $spv->id }}"
                                                                                        data-shiftPv-year="{{ $date->format('Y') }}"
                                                                                        data-shiftPv-month="{{ $date->format('n') }}"
                                                                                        data-shiftPv-day="{{ $date->format('j') }}"
                                                                                        data-shiftPv-employee-name="{{ $spv->shift->employee->name }}"
                                                                                        data-shiftPv-project-name="{{ $spv->project->name }}">
                                                                                </p>
                                                                                @php
                                                                                    // 上代の計算
                                                                                    $tmp_total_retail_day += $spv->retail_price;
                                                                                    $hasShift = true;
                                                                                @endphp
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                                @if (!$hasShift)
                                                                <p class="employee-name"></p>
                                                                @endif
                                                            </td>
                                                            @php
                                                                $isCountCheck++;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                                {{-- 上代 --}}
                                                @if (in_array('retailClm', $selectedDisplayCheck))
                                                    <td class="retailClm txt-right border-right-bold">{{ number_format($tmp_total_retail_day) }}</td>
                                                @endif
                                                {{-- 案件・所属先の詳細表示 --}}
                                                @if(!empty($selectedDisplayCheck))
                                                    @foreach ($narrowProjects as $project)
                                                        <td class="border-right-bold">
                                                            <div class="tbody-table-layout">
                                                                @foreach ($getCompanies as $company)
                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                        {{-- 給与 --}}
                                                                        @if (in_array('salaryClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell salaryClm company{{ $company->id }} cellHeight">
                                                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                        @if ($spv->shift->employee)
                                                                                            @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                            <input type="text" name="driver_price[{{$spv->id}}]" value="{{ number_format($spv->driver_price)}}" class="txt-right-input commaInput">
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif
                                                                        {{-- 上代 --}}
                                                                        @if (in_array('retailClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell retailClm company{{ $company->id }} cellHeight">
                                                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                        @if ($spv->shift->employee)
                                                                                            @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                            <input type="text" name="retail_price[{{$spv->id}}]"
                                                                                                value="{{ number_format($spv->retail_price) }}" class="txt-right-input commaInput">
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif

                                                                        {{-- 高速代 --}}
                                                                        @if (in_array('expressClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell expressClm company{{ $company->id }} cellHeight">
                                                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                        @if ($spv->shift->employee)
                                                                                            @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                            <input type="text" name="expressway_fee[{{$spv->id}}]"
                                                                                                value="{{ number_format($spv->expressway_fee) }}" class="txt-right-input commaInput">
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif

                                                                        {{-- 駐車料金 --}}
                                                                        @if (in_array('parkingClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell parkingClm company{{ $company->id }} cellHeight">
                                                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                        @if ($spv->shift->employee)
                                                                                            @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                            <input type="text" name="parking_fee[{{$spv->id}}]"
                                                                                                value="{{ number_format($spv->parking_fee) }}" class="txt-right-input commaInput">
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif

                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td class="project-table-date">小計</td>
                                                @php
                                                    $retailTotal = 0;
                                                @endphp
                                                @foreach ($narrowProjects as $project)
                                                    @php
                                                        $retailSubTotal = 0;
                                                    @endphp
                                                    @foreach ( $ShiftProjectVehicles as $spv )
                                                        @if ($spv->shift->employee)
                                                            @if(in_array($spv->shift->employee->company->id, $selectedDisplayCoCheck))
                                                                @if ($spv->project_id == $project->id)
                                                                    @php
                                                                        $retailSubTotal += $spv->retail_price;
                                                                        $retailTotal += $spv->retail_price;
                                                                    @endphp
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    {{-- 計算した上代の表示 --}}
                                                    <td colspan="{{ count($selectedDisplayCoCheck) }}">{{ number_format($retailSubTotal) }}</td>
                                                @endforeach
                                            </tr>
                                            <tr class="project-table-date">
                                                <td>合計</td>
                                                <td colspan="{{ count($selectedDisplayCoCheck) * $project_count }}">{{ number_format($retailTotal) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="project-calendar-wrap__table" id="calendarTable">
                                        {{-- ヘッダー --}}
                                        <thead>
                                            @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                                            <tr>
                                                {{-- 日付ヘッド --}}
                                                <th rowspan="2" class="project-table-date">----</th>
                                                {{-- 従業員名ヘッド --}}
                                                @foreach ($narrowProjects as $project)
                                                    <th rowspan="2" class="co-head border-right-bold project-table-w-name txtBox">{{$project->name}}</th>
                                                @endforeach
                                                {{-- 上代合計ヘッド --}}
                                                @if (in_array('retailClm', $selectedDisplayCheck))
                                                    <th rowspan="2" class="project-table-w-amount retailClm numberBox border-right-bold txtBox">配送料金</th>
                                                @endif
                                                {{-- 案件・所属先詳細ヘッド --}}
                                                @if (!empty($selectedDisplayCheck))
                                                    @foreach ($narrowProjects as $project)
                                                        <th class="rightHead border-right-bold">{{$project->name}}</th>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            <tr class="head-bottom">
                                                {{-- 案件・所属先詳細項目ヘッド --}}
                                                @if (!empty($selectedDisplayCheck))
                                                    @foreach ($narrowProjects as $project)
                                                        <th class="border-right-bold">
                                                            <div class="thead-table-layout">
                                                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                                                    <p class="thead-table-layout__cell salaryClm numberBox clmHead company{{ $company->id }}">ドライバー<br>価格</p>
                                                                @endif
                                                                @if (in_array('retailClm', $selectedDisplayCheck))
                                                                    <p class="thead-table-layout__cell retailClm numberBox clmHead company{{ $company->id }}">配送料金</p>
                                                                @endif
                                                                @if (in_array('expressClm', $selectedDisplayCheck))
                                                                    <p class="thead-table-layout__cell expressClm numberBox clmHead company{{ $company->id }}">高速料金</p>
                                                                @endif
                                                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                                                    <p class="thead-table-layout__cell parkingClm numberBox clmHead company{{ $company->id }}">駐車料金</p>
                                                                @endif
                                                            </div>
                                                        </th>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            @endif
                                        </thead>
                                        <tbody>
                                            @foreach ( $dates as $date )
                                            <tr class="tableRow">
                                                {{-- 日付 --}}
                                                <td class="project-table-date">{{ $date->format('n') }}月{{
                                                    $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                                                    @php //1日ごとの上代の合計額の格納変数
                                                        $tmp_total_retail_day = null;
                                                        $hasShift = false;
                                                    @endphp
                                                {{-- 従業員表示 --}}
                                                @foreach ($narrowProjects as $project)
                                                    <td class="employee-show border-right-bold">
                                                        @foreach ($getCompanies as $company)
                                                            @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                @foreach ( $ShiftProjectVehicles as $spv )
                                                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                                                        @if ($spv->shift->employee)
                                                                            @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                <p class="employee-name activeElem">
                                                                                    {{ $spv->shift->employee->name }}
                                                                                    <input hidden type="text" class="hasShiftInfo"
                                                                                        data-shiftPv-id="{{ $spv->id }}"
                                                                                        data-shiftPv-year="{{ $date->format('Y') }}"
                                                                                        data-shiftPv-month="{{ $date->format('n') }}"
                                                                                        data-shiftPv-day="{{ $date->format('j') }}"
                                                                                        data-shiftPv-employee-name="{{ $spv->shift->employee->name }}"
                                                                                        data-shiftPv-project-name="{{ $spv->project->name }}">
                                                                                </p>
                                                                                @php
                                                                                    // 上代の計算
                                                                                    $tmp_total_retail_day += $spv->retail_price;
                                                                                    $hasShift = true;
                                                                                @endphp
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                                @if (!$hasShift)
                                                                <p class="employee-name"></p>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                @endforeach
                                                {{-- 上代 --}}
                                                @if (in_array('retailClm', $selectedDisplayCheck))
                                                    <td class="retailClm txt-right border-right-bold">{{ number_format($tmp_total_retail_day) }}</td>
                                                @endif
                                                {{-- 案件・所属先の詳細表示 --}}
                                                @if(!empty($selectedDisplayCheck))
                                                    @foreach ($narrowProjects as $project)
                                                        <td class="border-right-bold">
                                                            <div class="tbody-table-layout">
                                                                {{-- @foreach ($getCompanies as $company)
                                                                    @if (in_array($company->id, $selectedDisplayCoCheck)) --}}
                                                                        {{-- 給与 --}}
                                                                        @if (in_array('salaryClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell salaryClm cellHeight">
                                                                                {{-- @foreach ($getCompanies as $company)
                                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))

                                                                                    @endif
                                                                                @endforeach --}}
                                                                                @foreach ($getCompanies as $company)
                                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                                @if ($spv->shift->employee)
                                                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                                    <input type="text" name="driver_price[{{$spv->id}}]" value="{{ number_format($spv->driver_price)}}" class="txt-right-input commaInput">
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif
                                                                        {{-- 上代 --}}
                                                                        @if (in_array('retailClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell retailClm cellHeight">
                                                                                @foreach ($getCompanies as $company)
                                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                                @if ($spv->shift->employee)
                                                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                                    <input type="text" name="retail_price[{{$spv->id}}]"
                                                                                                        value="{{ number_format($spv->retail_price) }}" class="txt-right-input commaInput">
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif

                                                                        {{-- 高速代 --}}
                                                                        @if (in_array('expressClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell expressClm cellHeight">
                                                                                @foreach ($getCompanies as $company)
                                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                                @if ($spv->shift->employee)
                                                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                                    <input type="text" name="expressway_fee[{{$spv->id}}]"
                                                                                                        value="{{ number_format($spv->expressway_fee) }}" class="txt-right-input commaInput">
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif

                                                                        {{-- 駐車料金 --}}
                                                                        @if (in_array('parkingClm', $selectedDisplayCheck))
                                                                            <p class="tbody-table-layout__cell parkingClm cellHeight">
                                                                                @foreach ($getCompanies as $company)
                                                                                    @if (in_array($company->id, $selectedDisplayCoCheck))
                                                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                                                @if ($spv->shift->employee)
                                                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                                                    <input type="text" name="parking_fee[{{$spv->id}}]"
                                                                                                        value="{{ number_format($spv->parking_fee) }}" class="txt-right-input commaInput">
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            </p>
                                                                        @endif

                                                                    {{-- @endif
                                                                @endforeach --}}
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                @endif
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td class="project-table-date">小計</td>
                                                @php
                                                    $retailTotal = 0;
                                                @endphp
                                                @foreach ($narrowProjects as $project)
                                                    @php
                                                        $retailSubTotal = 0;
                                                    @endphp
                                                    @foreach ( $ShiftProjectVehicles as $spv )
                                                        @if ($spv->shift->employee)
                                                            @if(in_array($spv->shift->employee->company->id, $selectedDisplayCoCheck))
                                                                @if ($spv->project_id == $project->id)
                                                                    @php
                                                                        $retailSubTotal += $spv->retail_price;
                                                                        $retailTotal += $spv->retail_price;
                                                                    @endphp
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    {{-- 計算した上代の表示 --}}
                                                    <td>{{ number_format($retailSubTotal) }}</td>
                                                @endforeach
                                            </tr>
                                            <tr class="project-table-date">
                                                <td>合計</td>
                                                <td colspan="{{ $project_count }}">{{ number_format($retailTotal) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        </form>
                        {{-- 削除モーダル --}}
                        <div class="shift-delete-modal shiftDeleteModal">
                            <div class="shift-delete-modal__bg shiftDeleteModalClose"></div>
                            <div class="shift-delete-modal__white-board">
                                <form action="{{ route('invoice.projectShiftDelete') }}" method="POST">
                                    @csrf
                                    {{-- リダイレクト --}}
                                    <input hidden type="text" name="client" value="{{$getClient->id}}">
                                    <input hidden type="text" name="year" value="{{$getYear}}">
                                    <input hidden type="text" name="month" value="{{$getMonth}}">

                                    <input hidden type="text" name="shiftPvId" class="setShiftPvId">

                                    <div class="shift-delete-modal__white-board__inner">
                                        <p class="title">案件情報</p>
                                        <div class="shift-info-wrap">
                                            <div class="shift-info-box">
                                                <p class="head">日付 : </p>
                                                <p class=""><span class="year">2024</span>年<span
                                                        class="month">2</span>月<span class="day">12</span>日</p>
                                            </div>
                                            <div class="shift-info-box">
                                                <p class="head">案件名 : </p>
                                                <p class="projectName">admin案件</p>
                                            </div>
                                            <div class="shift-info-box">
                                                <p class="head">ドライバー : </p>
                                                <p class="driverName">山田　太郎</p>
                                            </div>
                                        </div>
                                        <div class="button-wrap">
                                            <button class="btn --delete" onclick='return confirm("本当に削除しますか？")'>
                                                削除する
                                            </button>
                                            <div class="btn --back shiftDeleteModalClose">
                                                戻る
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="warning-txt">{{ $warning }}</p>
                    @endif
                </div>
            </div>
        </div>
    </main>



</x-app-layout>

<script src="{{asset('js/invoice-project.js')}}"></script>
