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
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="c-select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
                                    <option value="">----</option>
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
                            <div class="c-select-area__block name-block">
                                <select name="client" id="" class="c-select name-select" required>
                                    <option value="">----</option>
                                    @foreach ($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="c-search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>

                    @if ($ShiftProjectVehicles !== null && !$ShiftProjectVehicles->isEmpty())
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
                            <form action="{{route('invoice.project-edit-pdf')}}" method="POST" class="c-middle-head__button">
                                @csrf
                                <input hidden type="text" name="client" value="{{ $getClient->id }}">
                                <input hidden type="text" name="year" value="{{$getYear}}">
                                <input hidden type="text" name="month" value="{{$getMonth}}">

                                <input hidden type="text" name="retail_check" id="">
                                <input hidden type="text" name="salary_check" id="">
                                <input hidden type="text" name="expressway_check" id="">
                                <input hidden type="text" name="parking_check" id="">
                                <button>
                                    請求書確認
                                </button>
                            </form>
                        </div>
                        <form action="{{route('invoice.project-calendar-pdf')}}" method="POST" class="project-calendar-pdf-form">
                            @csrf
                            <div class="check-area">
                                @foreach ($getCompanies as $company)
                                    <div class="check-area__item">
                                        <input checked type="checkbox" name="company[{{ $company->id }}]" value="company{{ $company->id }}" class="viewClmCheck" id="" data-check="company">
                                        <label for="">{{ $company->name }}</label>
                                    </div>
                                @endforeach
                                <div class="check-area__item">
                                    <input checked type="checkbox" name="retailCheck" value="retailClm" class="viewClmCheck" id="" data-check="one">
                                    <label for="">上代</label>
                                </div>
                                <div class="check-area__item">
                                    <input checked type="checkbox" name="salaryCheck" value="salaryClm" class="viewClmCheck" id="" data-check="one">
                                    <label for="">ドライバー</label>
                                </div>
                                <div class="check-area__item">
                                    <input checked type="checkbox" name="expresswayCheck" value="expressClm" class="viewClmCheck" id="" data-check="one">
                                    <label for="">高速代</label>
                                </div>
                                <div class="check-area__item">
                                    <input checked type="checkbox" name="parkingCheck" value="parkingClm" class="viewClmCheck" id="" data-check="one">
                                    <label for="">パーキング代</label>
                                </div>
                            </div>
                            <input hidden type="text" name="client" value="{{$getClient->id}}">
                            <input hidden type="text" name="year" value="{{$getYear}}">
                            <input hidden type="text" name="month" value="{{$getMonth}}">
                            <button>
                                ダウンロード
                            </button>
                        </form>
                        @php
                            // 案件数
                            $project_count = $projects->count();
                            // 所属先数
                            $company_count = $getCompanies->count();

                        @endphp
                        <input hidden type="text" value="{{ $project_count }}" id="projectCount">
                        <input hidden type="text" value="{{ $company_count }}" id="companyCount">
                        <div class="project-calendar-wrap">
                            <form action="{{route('invoice.projectShiftUpdate')}}" method="POST">
                                @csrf
                                <button class="">
                                    変更内容を保存
                                </button>
                                {{-- リダイレクト先の検索用の情報 --}}
                                <input hidden type="text" name="client" value="{{$getClient->id}}">
                                <input hidden type="text" name="year" value="{{$getYear}}">
                                <input hidden type="text" name="month" value="{{$getMonth}}">
                                <table class="project-calendar-wrap__table">
                                    {{-- ヘッダー --}}
                                    <thead>
                                        @if ($project_count > 1 || $company_count > 1) {{-- どちらか複数あれば --}}
                                            <tr>
                                                {{-- 日付ヘッド --}}
                                                <th rowspan="2" class="project-table-date">----</th>
                                                {{-- 従業員名ヘッド --}}
                                                @foreach ($projects as $project)
                                                    <th colspan="{{$company_count}}" class="co-head">{{$project->name}}</th>
                                                @endforeach
                                                {{-- 上代合計ヘッド --}}
                                                <th rowspan="2" class="project-table-w-amount retailClm">上代</th>
                                                {{-- 案件・所属先詳細ヘッド --}}
                                                @foreach ($projects as $project)
                                                    <th colspan="{{$company_count * 4 }}" class="rightHead">{{$project->name}}</th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                {{-- 案件・所属先詳細項目ヘッド --}}
                                                @foreach ($projects as $project)
                                                    @foreach ($getCompanies as $company)
                                                        <th class="project-table-w-name company{{ $company->id }} coClmHead">{{ $company->name }}</th>
                                                    @endforeach
                                                @endforeach
                                                @foreach ($projects as $project)
                                                    @foreach ($getCompanies as $company)
                                                        <th class="project-table-w-amount salaryClm company{{ $company->id }} clmHead">{{ $company->name }}</th>
                                                        <th class="project-table-w-amount retailClm company{{ $company->id }} clmHead">上代</th>
                                                        <th class="project-table-w-amount expressClm company{{ $company->id }} clmHead">高速代</th>
                                                        <th class="project-table-w-amount parkingClm company{{ $company->id }} clmHead">パーキング代</th>
                                                    @endforeach
                                                @endforeach
                                            </tr>
                                        @endif
                                        @if ($project_count == 1 && $company_count == 1)
                                            <tr>
                                                {{-- 日付ヘッド --}}
                                                <th rowspan="2" class="project-table-date">----</th>
                                                @foreach ($getCompanies as $company)
                                                <th class="company{{ $company->id }}">{{ $company->name }}</th>
                                                @endforeach
                                                <th class="retailClm">上代</th>
                                                @foreach ($getCompanies as $company)
                                                <th class="company{{ $company->id }} salaryClm">{{ $company->name }}</th>
                                                <th class="retailClm company{{ $company->id }} retailClm">上代</th>
                                                <th class="company{{ $company->id }} expressClm">高速代</th>
                                                <th class="company{{ $company->id }} parkingClm">パーキング代</th>
                                                @endforeach
                                            </tr>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @foreach ( $dates as $date )
                                            <tr>
                                                {{-- 日付 --}}
                                                <td class="project-table-date">{{ $date->format('n') }}月{{ $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                                                {{-- 従業員表示 --}}
                                                @foreach ($projects as $project)
                                                    @foreach ($getCompanies as $company)
                                                        <td class="company{{ $company->id }}">
                                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                                    @if ($spv->shift->employee)
                                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                            {{ $spv->shift->employee->name }}<br>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                @endforeach
                                                {{-- 上代 --}}
                                                @php
                                                    //   上代の計算
                                                    $tmp_total_retail_day = null;
                                                    foreach ($ShiftProjectVehicles as $spv) {
                                                        if ($spv->shift->date == $date->format('Y-m-d')) {
                                                            if($spv->retail_price){
                                                                $tmp_total_retail_day += $spv->retail_price;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <td class="retailClm txt-right">{{$tmp_total_retail_day}}</td>
                                                {{-- 案件・所属先の詳細表示 --}}
                                                @foreach ($projects as $project)
                                                    @foreach ($getCompanies as $company)
                                                        {{-- 給与 --}}
                                                        <td class="salaryClm company{{ $company->id }}">
                                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                                    @if ($spv->shift->employee)
                                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                        <input type="text" name="driver_price[{{$spv->id}}]"value="{{$spv->driver_price}}" class="txt-right-input">
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
                                                                        <input type="text" name="retail_price[{{$spv->id}}]" value="{{$spv->retail_price}}" class="txt-right-input">
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
                                                                        <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{$spv->expressway_fee}}" class="txt-right-input">
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        {{-- パーキング代 --}}
                                                        <td class="parkingClm company{{ $company->id }}">
                                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                                    @if ($spv->shift->employee)
                                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                        <input type="text" name="parking_fee[{{$spv->id}}]" value="{{$spv->parking_fee}}" class="txt-right-input">
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endforeach
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>



</x-app-layout>

<script src="{{asset('js/invoice-project.js')}}"></script>
