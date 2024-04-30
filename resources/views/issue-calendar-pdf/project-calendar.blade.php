<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

	<style>
      /* dompdf日本語文字化け対策 */
      /* 基本の文字 */
      @font-face {
            font-family: ipaexm;
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/ipaexm.ttf') }}');
        }

      /* 全てのHTML要素に適用 */
      html, body, textarea {font-family: ipaexm;}html {line-height: 1.15;-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: transparent;}body {margin: 0;}main {display: block;}p,table,blockquote,address,pre,iframe,form,figure,dl {margin: 0;}h1,h2,h3,h4,h5,h6 {font-size: inherit; font-weight: inherit; margin: 0; } ul, ol { margin: 0; padding: 0; list-style: none; } dt { font-weight: 700; } dd { margin-left: 0; } hr { box-sizing: content-box; height: 0; overflow: visible; border-top-width: 1px; margin: 0; clear: both; color: inherit; } pre { font-family: monospace, monospace; font-size: inherit; } address { font-style: inherit; } a { background-color: transparent; text-decoration: none; color: inherit; } abbr[title] { text-decoration: underline dotted; } b, strong { font-weight: bolder; } code, kbd, samp { font-family: monospace, monospace; font-size: inherit; } small { font-size: 80%; } sub, sup { font-size: 75%; line-height: 0; position: relative; vertical-align: baseline; } sub { bottom: -0.25em; } sup { top: -0.5em; } svg, img, embed, object, iframe { vertical-align: center; } button, input, optgroup, select, textarea { -webkit-appearance: none; appearance: none; vertical-align: middle; color: inherit; font: inherit; background: transparent; padding: 0; margin: 0; border-radius: 0; text-align: inherit; text-transform: inherit; } [type="checkbox"] { -webkit-appearance: checkbox; appearance: checkbox; } [type="radio"] { -webkit-appearance: radio; appearance: radio; } button, [type="button"], [type="reset"], [type="submit"] { cursor: pointer; } button:disabled, [type="button"]:disabled, [type="reset"]:disabled, [type="submit"]:disabled { cursor: default; } :-moz-focusring { outline: auto; } select:disabled { opacity: inherit; } option { padding: 0; } fieldset { margin: 0; padding: 0; min-width: 0; } legend { padding: 0; } progress { vertical-align: baseline; } textarea { overflow: auto; } [type="number"]::-webkit-inner-spin-button, [type="number"]::-webkit-outer-spin-button { height: auto; } [type="search"] { outline-offset: -2px; } [type="search"]::-webkit-search-decoration { -webkit-appearance: none; } ::-webkit-file-upload-button { -webkit-appearance: button; font: inherit; } label[for] { cursor: pointer; } details { display: block; } summary { display: list-item; } [contenteditable]:focus { outline: auto; } table { border-color: inherit; } caption { text-align: left; } td, th { vertical-align: top; padding: 0; } th { text-align: left; font-weight: 700; } th{ font-weight: normal; }

      @page{
        margin: 15px;
        margin-top: 40px;
        margin-left: 40px;
      }

      table{
        margin-top: 5px;
        width: 100%;
        font-size: 10px;
        border-collapse: collapse;
        table-layout: fixed;
        overflow-wrap : break-word;
        /* IE11 にも改行させる場合 */
        word-wrap: break-word;
        /* table-layout: fixed; */
        /* transform: rotate(90deg);
        transform-origin: 27% 77%; */
      }
      table th,
      table td{
        position: relative;
          border: 0.5px solid black;
          text-align: center;
          vertical-align: middle;
          padding: 1px 2px;
      }
      table td{
        height: 2%;
      }
      .total-retail{
        width: fit-content;
        margin-top: 10px;
        padding: 7px 10px;
        background-color: rgba(0, 191, 0, 0.539);
      }
      .right-txt{
        text-align: right;
        padding-right: 2px;
        box-sizing: border-box;
      }
      p{
        font-size: 10px
      }
      .date-w-60{
        width: 60px;
      }
      .name-w-60{
        width: 60px;
      }
      .amount-w-60{
        width: 60px;
      }
      .retail-sub-total-td:last-child::after{
        content: "";
        position: absolute;
        top: 0;
        right: -0.5px;
        width: 0.5px;
        height: 100%;
        background-color: black;
      }
      .border-right{
        position: relative;
      }
      .border-right::after{
        content: "";
        position: absolute;
        top: 0;
        right: -0.5px;
        width: 0.5px;
        height: 100%;
        background-color: black;
      }
      .empty-clm{
        border: 0px;
        border-bottom: 0.5px solid black;
      }
      .holiday-table{
        table-layout: fixed;
        overflow-wrap : break-word;
        /* IE11 にも改行させる場合 */
        word-wrap: break-word;
        margin-top: 10px;
        margin-bottom: 10px;
      }
      .holiday-table th{
        width: 60px;
        white-space: wrap;
      }
      .holiday-table td{
        width: 60px;
      }

	</style>

	@php
        $project_count = $projects->count();
        $company_count = $getCompanies->count();
        if($separateByCompany == 'false'){
            $company_count = 1;
        }
        $item_count = count($selectedDisplayCheck);
        // テーブルの横幅を計算 1は日付の固定分
        $clmCount = 1 + ($project_count * $company_count) + $retailCheck + ($project_count * ($company_count * $item_count));
        $clmCountByOnly = 1 + $company_count + $retailCheck + ($company_count * $item_count);
        $clmWidth = 52;
        $tableWidth = $clmCount * $clmWidth;
        $onlyTableWidth = $clmCountByOnly * $clmWidth;
        $dataWidth = (70 / $tableWidth) * 100;
        $dataWidthByOnly = (70 / $onlyTableWidth) * 100;

        // 休日テーブルの横幅
        $holidayTableWidth = $clmWidth * $project_count;
	@endphp

<p class="">{{ $client->name }}</p>
<p class="">{{ $getYear }}年{{ $getMonth }}月度</p>

@if ($clmCount <= 21)
    @if ($separateByCompany == 'true')
        <table style="width: {{ $tableWidth }}px;">
            {{-- ヘッダー --}}
            <thead>
                @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                    <tr>
                        <th rowspan="3" style="width: {{ round($dataWidth) }}%;">----</th>
                        @foreach ($projects as $project)
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{$company_count}}">{{$project->name}}</th>
                            @endif
                        @endforeach
                        @if ($retailCheck == 1)
                            <th rowspan="3">配送料金</th>
                        @endif
                        @foreach ($projects as $project)
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{$company_count * $item_count }}" rowspan="2">{{$project->name}}</th>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($projects as $project)
                            @php
                                $hasHoliday = false;
                            @endphp
                            <th colspan="{{$company_count}}">
                                @if ($project->holiday->monday == 1)
                                    月
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->tuesday == 1)
                                    火
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->wednesday == 1)
                                    水
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->thursday == 1)
                                    木
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->friday == 1)
                                    金
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->saturday == 1)
                                    土
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->sunday == 1)
                                    日
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->public_holiday == 1)
                                    祝
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if(!$hasHoliday)
                                    なし
                                @endif
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($projects as $project)
                            @foreach ($getCompanies as $company)
                                <th>{{ $company->name }}</th>
                            @endforeach
                        @endforeach
                        @foreach ($projects as $project)
                            @foreach ($getCompanies as $company)
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <th class="name-w-60">{{ $company->name }}</th>
                                @endif
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <th>配送料金</th>
                                @endif
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <th>高速料金</th>
                                @endif
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <th>駐車料金</th>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endif
            </thead>
            <tbody>
                @foreach ( $dates as $date )
                    {{-- 案件数をカウント --}}
                    @php
                        $projectEmployeeCount = [];
                        foreach ($projects as $project) {
                            foreach ($getCompanies as $company) {
                                $count = 0; // 従業員のカウンタを初期化

                                foreach ($ShiftProjectVehicles as $spv) {
                                    // 日付、会社ID、プロジェクトIDが一致するレコードの数を数える
                                    if ($spv->shift->date == $date->format('Y-m-d') &&
                                        $spv->shift->employee &&
                                        $spv->shift->employee->company_id == $company->id &&
                                        $spv->project_id == $project->id) {
                                        $count++;
                                    }
                                }

                                // 従業員が一人以上いる場合にのみ結果を格納
                                if ($count > 0) {
                                    $projectEmployeeCount[$project->id][$company->id] = $count;
                                }
                            }
                        }
                    @endphp

                    <tr class="tr">
                        <td style="width: {{ round($dataWidth) }}%;">{{ $date->format('n') }}月{{ $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                        @foreach ($projects as $project)
                            @foreach ($getCompanies as $company)
                                <td class="name-w-60">
                                    @foreach ( $ShiftProjectVehicles as $spv )
                                        @if($spv->shift->date == $date->format('Y-m-d'))
                                            @if ($spv->shift->employee)
                                                @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                    <span @if(isset($projectEmployeeCount[$project->id][$company->id]) && $projectEmployeeCount[$project->id][$company->id] >= 2) style="font-size: 8px;" @endif>{{ $spv->shift->employee->name }}</span><br>
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
                                if($spv->shift->employee){
                                    if(in_array($spv->shift->employee->company->id, $selectedCompanies)){
                                        if ($spv->shift->date == $date->format('Y-m-d')) {
                                            if($spv->project_id == $project->id){
                                                if($spv->retail_price){
                                                    $tmp_total_retail_day += $spv->retail_price;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        @if ($retailCheck == 1)
                            @php
                                $amount = $tmp_total_retail_day ? number_format($tmp_total_retail_day) : '';
                            @endphp
                            <td class="right-txt amount-w-60 retail-clm">{{$amount}}</td>
                        @endif
                        @foreach ($projects as $project)
                            @foreach ($getCompanies as $company)
                                {{-- 給与 --}}
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->driver_price ? number_format($spv->driver_price) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 上代 --}}
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->retail_price ? number_format($spv->retail_price) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 高速代 --}}
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->expressway_fee ? number_format($spv->expressway_fee) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- パーキング代 --}}
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->parking_fee ? number_format($spv->parking_fee) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
                @for ($i = $dates[count($dates) - 1]->format('d'); $i < 31; $i++)
                    <tr>
                        <td></td>
                        @foreach ($projects as $project)
                            @foreach ($getCompanies as $company)
                                <td></td>
                            @endforeach
                        @endforeach
                        @if (in_array('retailClm', $selectedDisplayCheck))
                            <td></td>
                        @endif
                        @foreach ($projects as $project)
                            @foreach ($getCompanies as $company)
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                @endfor
                <tr>
                    <td>小計</td>
                    @php
                        $retailTotal = 0;
                    @endphp
                    @foreach ($projects as $project)
                        @php
                            $retailSubTotal = 0;
                        @endphp
                        @foreach ( $ShiftProjectVehicles as $spv )
                            @if ($spv->shift->employee)
                                @if (in_array($spv->shift->employee->company->id, $selectedCompanies))
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
                        <td colspan="{{ $company_count }}" class="retail-sub-total-td">{{ number_format($retailSubTotal) }}</td>
                    @endforeach
                    @if (in_array('retailClm', $selectedDisplayCheck))
                        <td class="empty-clm"></td>
                    @endif
                    @foreach ($projects as $project)
                        @foreach ($getCompanies as $company)
                            {{-- 給与 --}}
                            @if (in_array('salaryClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                            {{-- 上代 --}}
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                            {{-- 高速代 --}}
                            @if (in_array('expressClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                            {{-- パーキング代 --}}
                            @if (in_array('parkingClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
                <tr>
                    <td>合計</td>
                    <td colspan="{{ $company_count * $project_count }}" class="border-right">{{ number_format($retailTotal) }}</td>
                </tr>
            </tbody>
        </table>
    @else

    {{-- 分割しない --}}
        <table style="width: {{ $tableWidth }}px;">
            {{-- ヘッダー --}}
            <thead>
                @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                    <tr>
                        <th rowspan="2" style="width: {{ round($dataWidth) }}%;">----</th>
                        @foreach ($projects as $project)
                            @if (!$getCompanies->isEmpty())
                                <th rowspan="1">{{$project->name}}</th>
                            @endif
                        @endforeach
                        @if ($retailCheck == 1)
                            <th rowspan="2">配送料金</th>
                        @endif
                        @foreach ($projects as $project)
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{ $item_count }}">{{$project->name}}</th>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($projects as $project)
                            @php
                                $hasHoliday = false;
                            @endphp
                            <th colspan="{{$company_count}}">
                                @if ($project->holiday->monday == 1)
                                    月
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->tuesday == 1)
                                    火
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->wednesday == 1)
                                    水
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->thursday == 1)
                                    木
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->friday == 1)
                                    金
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->saturday == 1)
                                    土
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->sunday == 1)
                                    日
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->public_holiday == 1)
                                    祝
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if(!$hasHoliday)
                                    なし
                                @endif
                            </th>
                        @endforeach
                        @foreach ($projects as $project)
                            @if (in_array('salaryClm', $selectedDisplayCheck))
                                <th class="name-w-60" style="font-size: 9px;">ドライバー価格</th>
                            @endif
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <th>配送料金</th>
                            @endif
                            @if (in_array('expressClm', $selectedDisplayCheck))
                                <th>高速料金</th>
                            @endif
                            @if (in_array('parkingClm', $selectedDisplayCheck))
                                <th>駐車料金</th>
                            @endif
                        @endforeach
                    </tr>
                @endif
            </thead>
            <tbody>
                @foreach ( $dates as $date )
                    {{-- 案件数をカウント --}}
                    @php
                        $projectEmployeeCount = [];
                        foreach ($projects as $project) {
                            foreach ($getCompanies as $company) {
                                $count = 0; // 従業員のカウンタを初期化

                                foreach ($ShiftProjectVehicles as $spv) {
                                    // 日付、会社ID、プロジェクトIDが一致するレコードの数を数える
                                    if ($spv->shift->date == $date->format('Y-m-d') &&
                                        $spv->shift->employee &&
                                        $spv->shift->employee->company_id == $company->id &&
                                        $spv->project_id == $project->id) {
                                        $count++;
                                    }
                                }

                                // 従業員が一人以上いる場合にのみ結果を格納
                                if ($count > 0) {
                                    $projectEmployeeCount[$project->id][$company->id] = $count;
                                }
                            }
                        }
                    @endphp

                    <tr class="tr">
                        {{-- 日付 --}}
                        <td style="width: {{ round($dataWidth) }}%;">{{ $date->format('n') }}月{{ $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                        {{-- 従業員名 --}}
                        @foreach ($projects as $project)
                            <td class="name-w-60">
                                @foreach ($getCompanies as $company)
                                    @foreach ( $ShiftProjectVehicles as $spv )
                                        @if($spv->shift->date == $date->format('Y-m-d'))
                                            @if ($spv->shift->employee)
                                                @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                    <span @if(isset($projectEmployeeCount[$project->id][$company->id]) && $projectEmployeeCount[$project->id][$company->id] >= 2) style="font-size: 8px;" @endif>{{ $spv->shift->employee->name }}</span><br>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                        @endforeach
                        {{-- 上代 --}}
                        @php
                            //   上代の計算
                            $tmp_total_retail_day = null;
                            foreach ($ShiftProjectVehicles as $spv) {
                                if($spv->shift->employee){
                                    if(in_array($spv->shift->employee->company->id, $selectedCompanies)){
                                        if ($spv->shift->date == $date->format('Y-m-d')) {
                                            if($spv->retail_price){
                                                $tmp_total_retail_day += $spv->retail_price;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        @if ($retailCheck == 1)
                            @php
                                $amount = $tmp_total_retail_day ? number_format($tmp_total_retail_day) : '';
                            @endphp
                            <td class="right-txt amount-w-60 retail-clm">{{$amount}}</td>
                        @endif
                        @foreach ($projects as $project)
                            {{-- @foreach ($getCompanies as $company) --}}
                                {{-- 給与 --}}
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $getCompanies as $company )
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                            @php
                                                                $amount = $spv->driver_price ? number_format($spv->driver_price) : '';
                                                            @endphp
                                                            {{ $amount }}<br>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 上代 --}}
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $getCompanies as $company )
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                            @php
                                                                $amount = $spv->retail_price ? number_format($spv->retail_price) : '';
                                                            @endphp
                                                            {{ $amount }}<br>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 高速代 --}}
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $getCompanies as $company )
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                            @php
                                                                $amount = $spv->expressway_fee ? number_format($spv->expressway_fee) : '';
                                                            @endphp
                                                            {{ $amount }}<br>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                @endif
                                {{-- パーキング代 --}}
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $getCompanies as $company )
                                            @foreach ( $ShiftProjectVehicles as $spv )
                                                @if($spv->shift->date == $date->format('Y-m-d'))
                                                    @if ($spv->shift->employee)
                                                        @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                            @php
                                                                $amount = $spv->parking_fee ? number_format($spv->parking_fee) : '';
                                                            @endphp
                                                            {{ $amount }}<br>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                @endif
                            {{-- @endforeach --}}
                        @endforeach
                    </tr>
                @endforeach
                @for ($i = $dates[count($dates) - 1]->format('d'); $i < 31; $i++)
                    <tr>
                        <td></td>
                        @foreach ($projects as $project)
                            <td></td>
                        @endforeach
                        @if (in_array('retailClm', $selectedDisplayCheck))
                            <td></td>
                        @endif
                        @foreach ($projects as $project)
                            @if (in_array('salaryClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('expressClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('parkingClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endfor
                <tr>
                    <td>小計</td>
                    @php
                        $retailTotal = 0;
                    @endphp
                    @foreach ($projects as $project)
                        @php
                            $retailSubTotal = 0;
                        @endphp
                        @foreach ( $ShiftProjectVehicles as $spv )
                            @if ($spv->shift->employee)
                                @if (in_array($spv->shift->employee->company->id, $selectedCompanies))
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
                        <td colspan="{{ $company_count }}" class="retail-sub-total-td">{{ number_format($retailSubTotal) }}</td>
                    @endforeach
                    @if (in_array('retailClm', $selectedDisplayCheck))
                        <td class="empty-clm"></td>
                    @endif
                    @foreach ($projects as $project)
                        {{-- 給与 --}}
                        @if (in_array('salaryClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- 上代 --}}
                        @if (in_array('retailClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- 高速代 --}}
                        @if (in_array('expressClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- パーキング代 --}}
                        @if (in_array('parkingClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    <td>合計</td>
                    <td colspan="{{ $project_count }}" class="border-right">{{ number_format($retailTotal) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
@else


    {{--

        ---------------------------------------------------------------------------------
        ---------------------------------------------------------------------------------

        --}}
    @if ($separateByCompany == 'true')
        @php
            $countIndex = 0
        @endphp
        @foreach ( $projects as $index => $project )
            @if ($countIndex == $index)
            <table style="width: {{ $onlyTableWidth }}px; @if($index < ($project_count - 1)) page-break-after: always @endif">
                {{-- ヘッダー --}}
                <thead>
                    @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                        <tr>
                            <th rowspan="3" style="width: {{ round($dataWidthByOnly) }}%;">----</th>
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{$company_count}}">{{$project->name}}</th>
                            @endif
                            @if ($retailCheck == 1)
                                <th rowspan="3">配送料金</th>
                            @endif
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{$company_count * $item_count }}" rowspan="2">{{$project->name}}</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan="{{$company_count}}">
                                @php
                                    $hasHoliday = false;
                                @endphp
                                @if ($project->holiday->monday == 1)
                                    月
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->tuesday == 1)
                                    火
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->wednesday == 1)
                                    水
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->thursday == 1)
                                    木
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->friday == 1)
                                    金
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->saturday == 1)
                                    土
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->sunday == 1)
                                    日
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->public_holiday == 1)
                                    祝
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if(!$hasHoliday)
                                    なし
                                @endif
                            </th>
                        </tr>
                        <tr>
                            @foreach ($getCompanies as $company)
                                <th>{{ $company->name }}</th>
                            @endforeach
                            @foreach ($getCompanies as $company)
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <th class="name-w-60">{{ $company->name }}</th>
                                @endif
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <th>配送料金</th>
                                @endif
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <th>高速料金</th>
                                @endif
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <th>駐車料金</th>
                                @endif
                            @endforeach
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @foreach ( $dates as $date )
                        {{-- 案件数をカウント --}}
                        @php
                            $projectEmployeeCount = [];
                                foreach ($getCompanies as $company) {
                                    $count = 0; // 従業員のカウンタを初期化

                                    foreach ($ShiftProjectVehicles as $spv) {
                                        // 日付、会社ID、プロジェクトIDが一致するレコードの数を数える
                                        if ($spv->shift->date == $date->format('Y-m-d') &&
                                            $spv->shift->employee &&
                                            $spv->shift->employee->company_id == $company->id &&
                                            $spv->project_id == $project->id) {
                                            $count++;
                                        }
                                    }

                                    // 従業員が一人以上いる場合にのみ結果を格納
                                    if ($count > 0) {
                                        $projectEmployeeCount[$project->id][$company->id] = $count;
                                    }
                                }
                        @endphp

                        <tr class="tr">
                            <td style="width: {{ round($dataWidth) }}%;">{{ $date->format('n') }}月{{ $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                                @foreach ($getCompanies as $company)
                                    <td class="name-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        <span @if(isset($projectEmployeeCount[$project->id][$company->id]) && $projectEmployeeCount[$project->id][$company->id] >= 2) style="font-size: 8px;" @endif>{{ $spv->shift->employee->name }}</span><br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            {{-- 上代 --}}
                            @php
                                //   上代の計算
                                $tmp_total_retail_day = null;
                                foreach ($ShiftProjectVehicles as $spv) {
                                    if($spv->shift->employee){
                                        if(in_array($spv->shift->employee->company->id, $selectedCompanies)){
                                            if ($spv->shift->date == $date->format('Y-m-d')) {
                                                if($spv->project_id == $project->id){
                                                    if($spv->retail_price){
                                                        $tmp_total_retail_day += $spv->retail_price;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp
                            @if ($retailCheck == 1)
                                @php
                                    $amount = $tmp_total_retail_day ? number_format($tmp_total_retail_day) : '';
                                @endphp
                                <td class="right-txt amount-w-60 retail-clm">{{$amount}}</td>
                            @endif
                            @foreach ($getCompanies as $company)
                                {{-- 給与 --}}
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->driver_price ? number_format($spv->driver_price) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 上代 --}}
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->retail_price ? number_format($spv->retail_price) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 高速代 --}}
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->expressway_fee ? number_format($spv->expressway_fee) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- パーキング代 --}}
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->parking_fee ? number_format($spv->parking_fee) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    @for ($i = $dates[count($dates) - 1]->format('d'); $i < 31; $i++)
                        <tr>
                            <td></td>
                            @foreach ($getCompanies as $company)
                                <td></td>
                            @endforeach
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @foreach ($getCompanies as $company)
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endfor
                    <tr>
                        <td>小計</td>
                        @php
                            $retailTotal = 0;
                        @endphp
                        @php
                            $retailSubTotal = 0;
                        @endphp
                        @foreach ( $ShiftProjectVehicles as $spv )
                            @if ($spv->shift->employee)
                                @if (in_array($spv->shift->employee->company->id, $selectedCompanies))
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
                        <td colspan="{{ $company_count }}" class="retail-sub-total-td">{{ number_format($retailSubTotal) }}</td>
                        @if (in_array('retailClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        @foreach ($getCompanies as $company)
                            {{-- 給与 --}}
                            @if (in_array('salaryClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                            {{-- 上代 --}}
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                            {{-- 高速代 --}}
                            @if (in_array('expressClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                            {{-- パーキング代 --}}
                            @if (in_array('parkingClm', $selectedDisplayCheck))
                                <td class="empty-clm"></td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td>合計</td>
                        <td colspan="{{ $company_count }}" class="border-right">{{ number_format($retailTotal) }}</td>
                    </tr>
                </tbody>
            </table>
            @endif
            @php
                $countIndex++;
            @endphp
        @endforeach
    @else

        {{-- 分割しない --}}
        @php
            $countIndex = 0
        @endphp
        @foreach ( $projects as $index => $project )
            @if ($countIndex == $index)
            <table style="width: {{ $onlyTableWidth }}px; @if($index < ($project_count - 1)) page-break-after: always @endif">
                {{-- ヘッダー --}}
                <thead>
                    @if ($project_count >= 1 || $company_count >= 1) {{-- どちらか複数あれば --}}
                        <tr>
                            <th rowspan="2" style="width: {{ round($dataWidthByOnly) }}%;">----</th>
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{$company_count}}">{{$project->name}}</th>
                            @endif
                            @if ($retailCheck == 1)
                                <th rowspan="2">配送料金</th>
                            @endif
                            @if (!$getCompanies->isEmpty())
                                <th colspan="{{ $item_count }}">{{$project->name}}</th>
                            @endif
                        </tr>
                        <tr>
                            <th colspan="{{$company_count}}">
                                @php
                                    $hasHoliday = false;
                                @endphp
                                @if ($project->holiday->monday == 1)
                                    月
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->tuesday == 1)
                                    火
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->wednesday == 1)
                                    水
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->thursday == 1)
                                    木
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->friday == 1)
                                    金
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->saturday == 1)
                                    土
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->sunday == 1)
                                    日
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if ($project->holiday->public_holiday == 1)
                                    祝
                                    @php
                                        $hasHoliday = true;
                                    @endphp
                                @endif
                                @if(!$hasHoliday)
                                    なし
                                @endif
                            </th>
                            @if (in_array('salaryClm', $selectedDisplayCheck))
                                <th class="name-w-60" style="font-size: 9px;">ドライバー価格</th>
                            @endif
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <th>配送料金</th>
                            @endif
                            @if (in_array('expressClm', $selectedDisplayCheck))
                                <th>高速料金</th>
                            @endif
                            @if (in_array('parkingClm', $selectedDisplayCheck))
                                <th>駐車料金</th>
                            @endif
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @foreach ( $dates as $date )
                        {{-- 案件数をカウント --}}
                        @php
                            $projectEmployeeCount = [];
                                foreach ($getCompanies as $company) {
                                    $count = 0; // 従業員のカウンタを初期化

                                    foreach ($ShiftProjectVehicles as $spv) {
                                        // 日付、会社ID、プロジェクトIDが一致するレコードの数を数える
                                        if ($spv->shift->date == $date->format('Y-m-d') &&
                                            $spv->shift->employee &&
                                            $spv->shift->employee->company_id == $company->id &&
                                            $spv->project_id == $project->id) {
                                            $count++;
                                        }
                                    }

                                    // 従業員が一人以上いる場合にのみ結果を格納
                                    if ($count > 0) {
                                        $projectEmployeeCount[$project->id][$company->id] = $count;
                                    }
                                }
                        @endphp

                        <tr class="tr">
                            {{-- 日付 --}}
                            <td style="width: {{ round($dataWidth) }}%;">{{ $date->format('n') }}月{{ $date->format('j') }}日({{ $date->isoFormat('ddd') }})</td>
                            {{-- 従業員名 --}}
                            <td class="name-w-60">
                                @foreach ( $ShiftProjectVehicles as $spv )
                                    @if($spv->shift->date == $date->format('Y-m-d'))
                                        @if ($spv->shift->employee)
                                            @foreach ($getCompanies as $company)
                                                @if ($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                                                    <span @if(isset($projectEmployeeCount[$project->id][$company->id]) && $projectEmployeeCount[$project->id][$company->id] >= 2) style="font-size: 8px;" @endif>{{ $spv->shift->employee->name }}</span><br>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            {{-- 上代 --}}
                            @php
                                //   上代の計算
                                $tmp_total_retail_day = null;
                                foreach ($ShiftProjectVehicles as $spv) {
                                    if($spv->shift->employee){
                                        if(in_array($spv->shift->employee->company->id, $selectedCompanies)){
                                            if ($spv->shift->date == $date->format('Y-m-d')) {
                                                if($spv->project_id == $project->id){
                                                    if($spv->retail_price){
                                                        $tmp_total_retail_day += $spv->retail_price;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp
                            @if ($retailCheck == 1)
                                @php
                                    $amount = $tmp_total_retail_day ? number_format($tmp_total_retail_day) : '';
                                @endphp
                                <td class="right-txt amount-w-60 retail-clm">{{$amount}}</td>
                            @endif
                            {{-- @foreach ($getCompanies as $company) --}}
                                {{-- 給与 --}}
                                @if (in_array('salaryClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if (in_array($spv->shift->employee->company->id, $selectedCompanies) && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->driver_price ? number_format($spv->driver_price) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 上代 --}}
                                @if (in_array('retailClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if (in_array($spv->shift->employee->company->id, $selectedCompanies) && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->retail_price ? number_format($spv->retail_price) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- 高速代 --}}
                                @if (in_array('expressClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if (in_array($spv->shift->employee->company->id, $selectedCompanies) && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->expressway_fee ? number_format($spv->expressway_fee) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                {{-- パーキング代 --}}
                                @if (in_array('parkingClm', $selectedDisplayCheck))
                                    <td class="right-txt amount-w-60">
                                        @foreach ( $ShiftProjectVehicles as $spv )
                                            @if($spv->shift->date == $date->format('Y-m-d'))
                                                @if ($spv->shift->employee)
                                                    @if (in_array($spv->shift->employee->company->id, $selectedCompanies) && $spv->project_id == $project->id)
                                                        @php
                                                            $amount = $spv->parking_fee ? number_format($spv->parking_fee) : '';
                                                        @endphp
                                                        {{ $amount }}<br>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            {{-- @endforeach --}}
                        </tr>
                    @endforeach
                    @for ($i = $dates[count($dates) - 1]->format('d'); $i < 31; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('salaryClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('retailClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('expressClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                            @if (in_array('parkingClm', $selectedDisplayCheck))
                                <td></td>
                            @endif
                        </tr>
                    @endfor
                    <tr>
                        <td>小計</td>
                        @php
                            $retailTotal = 0;
                        @endphp
                        @php
                            $retailSubTotal = 0;
                        @endphp
                        @foreach ( $ShiftProjectVehicles as $spv )
                            @if ($spv->shift->employee)
                                @if (in_array($spv->shift->employee->company->id, $selectedCompanies))
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
                        <td colspan="{{ $company_count }}" class="retail-sub-total-td">{{ number_format($retailSubTotal) }}</td>
                        @if (in_array('retailClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- 給与 --}}
                        @if (in_array('salaryClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- 上代 --}}
                        @if (in_array('retailClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- 高速代 --}}
                        @if (in_array('expressClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                        {{-- パーキング代 --}}
                        @if (in_array('parkingClm', $selectedDisplayCheck))
                            <td class="empty-clm"></td>
                        @endif
                    </tr>
                    <tr>
                        <td>合計</td>
                        <td colspan="{{ $company_count }}" class="border-right">{{ number_format($retailTotal) }}</td>
                    </tr>
                </tbody>
            </table>
            @endif
            @php
                $countIndex++;
            @endphp
        @endforeach
    @endif
@endif
</body>
</html>
