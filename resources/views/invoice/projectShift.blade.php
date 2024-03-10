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

                            @foreach ($getCompanies as $company)
                            <input hidden type="text" name="companyByInvoice[]" value=""
                                class="setArrowCompanyByInvoice">
                            @endforeach
                            <input hidden type="text" name="salary_check" value="1" class="setDisplayActiveInvoice">
                            <input hidden type="text" name="retail_check" value="1" class="setDisplayActiveInvoice">
                            <input hidden type="text" name="expressway_check" value="1" class="setDisplayActiveInvoice">
                            <input hidden type="text" name="parking_check" value="1" class="setDisplayActiveInvoice">
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
                            {{-- @foreach ($getCompanies as $company)
                            <input hidden type="text" name="company[{{ $company->id }}]" value=""
                                class="setDisplayCompanyByCalendar" id="" data-check="company">
                            @endforeach --}}
                            @foreach ($getCompanies as $company)
                                <input hidden type="text" name="company[]" class="setArrowCompanyByCalendar">
                            @endforeach
                            <input hidden type="text" name="salaryCheck" value="1" class="setDisplayActiveByCalendar">
                            <input hidden type="text" name="retailCheck" value="1" class="setDisplayActiveByCalendar">
                            <input hidden type="text" name="expresswayCheck" value="1"
                                class="setDisplayActiveByCalendar">
                            <input hidden type="text" name="parkingCheck" value="1" class="setDisplayActiveByCalendar">
                            @foreach ($narrowProjectIds as $narrowProjectId)
                                <input hidden type="text" name="narrowProjectIds[]" value="{{ $narrowProjectId }}">
                            @endforeach
                            <input hidden type="text" name="client" value="{{$getClient->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <button name="action" value="beside">
                                ダウンロード 横画面
                            </button>
                            <button name="action" value="vertical">
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
                                <p class="title">非表示項目</p>
                                <div class="check-area">
                                    {{-- 所属先の列の表示・非表示 --}}
                                    @foreach ($getCompanies as $company)
                                    <lable class="check-item">
                                        <input type="checkbox" name="displayCoCheck[]" value="company{{ $company->id }}"
                                            class="viewClmCheck hasDisplayCoValue" data-company-id="{{ $company->id }}"
                                            @if(in_array('company' . $company->id, $selectedDisplayCoCheck)) checked
                                        @endif>
                                        {{ $company->name }}
                                    </lable>
                                    @endforeach
                                    {{-- 所属先の絞り込み --}}
                                    {{-- @foreach ($getCompanies as $company)
                                    <input hidden type="text" name="company[]" value=""
                                        class="setDisplayCoActiveByCalendar">
                                    @endforeach --}}
                                    <lable class="check-item">
                                        <input type="checkbox" name="displayCheck[]" value="salaryClm"
                                            class="viewClmCheck hasDisplayValue" data-check="one"
                                            @if(in_array('salaryClm', $selectedDisplayCheck)) checked @endif>
                                        ドライバー価格
                                    </lable>
                                    <lable class="check-item">
                                        <input type="checkbox" name="displayCheck[]" value="retailClm"
                                            class="viewClmCheck hasDisplayValue" data-check="one"
                                            @if(in_array('retailClm', $selectedDisplayCheck)) checked @endif>
                                        配送料金
                                    </lable>
                                    <lable class="check-item">
                                        <input type="checkbox" name="displayCheck[]" value="expressClm"
                                            class="viewClmCheck hasDisplayValue" data-check="one"
                                            @if(in_array('expressClm', $selectedDisplayCheck)) checked @endif>
                                        高速料金
                                    </lable>
                                    <lable class="check-item">
                                        <input type="checkbox" name="displayCheck[]" value="parkingClm"
                                            class="viewClmCheck hasDisplayValue" data-check="one"
                                            @if(in_array('parkingClm', $selectedDisplayCheck)) checked @endif>
                                        駐車料金
                                    </lable>
                                </div>
                                <p class="title">案件絞り込み</p>
                                <div class="check-area">
                                    @foreach ($projects as $project)
                                    <label for="" class="check-item">
                                        <input type="checkbox" name="narrowProjects[]" value="{{ $project->id }}"
                                            @if(in_array($project->id, $narrowProjectIds)) checked @endif class="">
                                        {{ $project->name }}
                                    </label>
                                    @endforeach
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
                                <table class="project-calendar-wrap__table" id="calendarTable">
                                    {{-- ヘッダー --}}
                                    <thead>
                                        @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                                        <tr>
                                            {{-- 日付ヘッド --}}
                                            <th rowspan="2" class="project-table-date">----</th>
                                            {{-- 従業員名ヘッド --}}
                                            @foreach ($narrowProjects as $project)
                                            <th colspan="{{$company_count}}" class="co-head">{{$project->name}}</th>
                                            @endforeach
                                            {{-- 上代合計ヘッド --}}
                                            <th rowspan="2" class="project-table-w-amount retailClm numberBox border-right-bold">配送料金</th>
                                            {{-- 案件・所属先詳細ヘッド --}}
                                            @foreach ($narrowProjects as $project)
                                            <th colspan="{{$company_count * 4 }}" class="rightHead">{{$project->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            {{-- 案件・所属先詳細項目ヘッド --}}
                                            @foreach ($narrowProjects as $project)
                                                @foreach ($getCompanies as $company)
                                                    <th class="project-table-w-name company{{ $company->id }} coClmHead txtBox">
                                                    {{ $company->name }}</th>
                                                @endforeach
                                            @endforeach
                                            @foreach ($narrowProjects as $project)
                                                @foreach ($getCompanies as $company)
                                                <th
                                                    class="project-table-w-amount salaryClm company{{ $company->id }} clmHead numberBox">
                                                    {{ $company->name }}</th>
                                                <th
                                                    class="project-table-w-amount retailClm company{{ $company->id }} clmHead numberBox">
                                                    配送料金</th>
                                                <th
                                                    class="project-table-w-amount expressClm company{{ $company->id }} clmHead numberBox">
                                                    高速料金</th>
                                                <th
                                                    class="project-table-w-amount parkingClm company{{ $company->id }} clmHead">
                                                    駐車料金</th>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @foreach ( $dates as $date )
                                        <tr>
                                            {{-- 日付 --}}
                                            <td class="project-table-date">{{ $date->format('n') }}月{{
                                                $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                                                @php //1日ごとの上代の合計額の格納変数
                                                    $tmp_total_retail_day = null;
                                                    $hasShift = false;
                                                @endphp
                                            {{-- 従業員表示 --}}
                                            @foreach ($narrowProjects as $project)
                                                @foreach ($getCompanies as $company)
                                                    <td class="company{{ $company->id }} employee-show">
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
                                                @endforeach
                                            @endforeach
                                            {{-- 上代 --}}
                                            <td class="retailClm txt-right border-right-bold">{{ number_format($tmp_total_retail_day) }}</td>
                                            {{-- 案件・所属先の詳細表示 --}}
                                            @foreach ($narrowProjects as $project)
                                                @foreach ($getCompanies as $company)
                                                {{-- 給与 --}}
                                                <td class="salaryClm company{{ $company->id }}">
                                                    @foreach ( $ShiftProjectVehicles as $spv )
                                                        @if($spv->shift->date == $date->format('Y-m-d'))
                                                            @if ($spv->shift->employee)
                                                                @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                <input type="text" name="driver_price[{{$spv->id}}]"
                                                                    value="{{ number_format($spv->driver_price)}}" class="txt-right-input commaInput">
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                                {{-- 上代 --}}
                                                <td class="retailClm company{{ $company->id }}">
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
                                                </td>
                                                {{-- 高速代 --}}
                                                <td class="expressClm company{{ $company->id }}">
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
                                                </td>
                                                {{-- 駐車料金 --}}
                                                <td class="parkingClm company{{ $company->id }}">
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
                                                </td>
                                                @endforeach
                                            @endforeach
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
                                                        @if ($spv->project_id == $project->id)
                                                            @php
                                                                $retailSubTotal += $spv->retail_price;
                                                                $retailTotal += $spv->retail_price;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endforeach
                                                {{-- 計算した上代の表示 --}}
                                                <td colspan="{{ $company_count }}">{{ number_format($retailSubTotal) }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="project-table-date">
                                            <td>合計</td>
                                            <td colspan="{{ $company_count * $project_count }}">{{ number_format($retailTotal) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
