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
                            {{-- <form action="{{route('invoice.driver-edit-pdf')}}" method="POST" class="c-middle-head__button">
                                @csrf
                                <input hidden type="text" name="employeeId" value="{{$findEmployee->id}}">
                                <input hidden type="text" name="year" value="{{$getYear}}">
                                <input hidden type="text" name="month" value="{{$getMonth}}">

                                <input hidden type="text" name="invoiceAmountCheck" id="invoiceAmountCheck">
                                <input hidden type="text" name="invoiceAllowanceCheck" id="invoiceAllowanceCheck">
                                <input hidden type="text" name="invoiceExpresswayCheck" id="invoiceExpresswayCheck">
                                <input hidden type="text" name="invoiceParkingCheck" id="invoiceParkingCheck">
                                <input hidden type="text" name="invoiceVehicleCheck" id="invoiceVehicleCheck">
                                <input hidden type="text" name="invoiceOvertimeCheck" id="invoiceOvertimeCheck">
                                <button>
                                    請求書確認
                                </button>
                            </form> --}}
                        </div>
                        @php
                            // 案件数
                            $project_count = $projects->count();
                            // 所属先数
                            $company_count = $getCompanies->count();

                        @endphp
                        <div class="project-calendar-wrap">
                            <table class="project-calendar-wrap__table">
                                {{-- ヘッダー --}}
                                <thead>
                                    @if ($project_count > 1 || $company_count > 1) {{-- どちらか複数あれば --}}
                                        <tr>
                                            {{-- 日付ヘッド --}}
                                            <th rowspan="2" class="project-table-date">----</th>
                                            {{-- 従業員名ヘッド --}}
                                            @foreach ($projects as $project)
                                                <th colspan="{{$company_count}}">{{$project->name}}</th>
                                            @endforeach
                                            {{-- 上代合計ヘッド --}}
                                            <th rowspan="2" class="project-table-w-amount">上代</th>
                                            {{-- 案件・所属先詳細ヘッド --}}
                                            @foreach ($projects as $project)
                                                <th colspan="{{$company_count * 4 }}">{{$project->name}}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            {{-- 案件・所属先詳細項目ヘッド --}}
                                            @foreach ($projects as $project)
                                                @foreach ($getCompanies as $company)
                                                    <th class="project-table-w-name">{{ $company->name }}</th>
                                                @endforeach
                                            @endforeach
                                            @foreach ($projects as $project)
                                                @foreach ($getCompanies as $company)
                                                    <th class="project-table-w-amount">{{ $company->name }}</th>
                                                    <th class="project-table-w-amount">上代</th>
                                                    <th class="project-table-w-amount">高速代</th>
                                                    <th class="project-table-w-amount">パーキング代</th>
                                                @endforeach
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
                                                    <td>
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
                                            <td>{{$tmp_total_retail_day}}</td>
                                            {{-- 案件・所属先の詳細表示 --}}
                                            @foreach ($projects as $project)
                                                @foreach ($getCompanies as $company)
                                                    {{-- 給与 --}}
                                                    <td>
                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                @if ($spv->shift->employee)
                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                        {{ $spv->driver_price }}
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    {{-- 上代 --}}
                                                    <td>
                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                @if ($spv->shift->employee)
                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                        {{ $spv->retail_price }}
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    {{-- 高速代 --}}
                                                    <td>
                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                @if ($spv->shift->employee)
                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                        {{ $spv->expressway_fee }}
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    {{-- パーキング代 --}}
                                                    <td>
                                                        @foreach ( $ShiftProjectVehicles as $spv )
                                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                                @if ($spv->shift->employee)
                                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                                        {{ $spv->parking_fee }}
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>



</x-app-layout>

<script src="{{asset('js/invoice-project.js')}}"></script>
