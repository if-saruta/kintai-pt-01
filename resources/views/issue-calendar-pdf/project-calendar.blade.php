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
      html, body, textarea {font-family: ipaexm, sans-serif;}html {line-height: 1.15;-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: transparent;}body {margin: 0;}main {display: block;}p,table,blockquote,address,pre,iframe,form,figure,dl {margin: 0;}h1,h2,h3,h4,h5,h6 {font-size: inherit; font-weight: inherit; margin: 0; } ul, ol { margin: 0; padding: 0; list-style: none; } dt { font-weight: 700; } dd { margin-left: 0; } hr { box-sizing: content-box; height: 0; overflow: visible; border-top-width: 1px; margin: 0; clear: both; color: inherit; } pre { font-family: monospace, monospace; font-size: inherit; } address { font-style: inherit; } a { background-color: transparent; text-decoration: none; color: inherit; } abbr[title] { text-decoration: underline dotted; } b, strong { font-weight: bolder; } code, kbd, samp { font-family: monospace, monospace; font-size: inherit; } small { font-size: 80%; } sub, sup { font-size: 75%; line-height: 0; position: relative; vertical-align: baseline; } sub { bottom: -0.25em; } sup { top: -0.5em; } svg, img, embed, object, iframe { vertical-align: center; } button, input, optgroup, select, textarea { -webkit-appearance: none; appearance: none; vertical-align: middle; color: inherit; font: inherit; background: transparent; padding: 0; margin: 0; border-radius: 0; text-align: inherit; text-transform: inherit; } [type="checkbox"] { -webkit-appearance: checkbox; appearance: checkbox; } [type="radio"] { -webkit-appearance: radio; appearance: radio; } button, [type="button"], [type="reset"], [type="submit"] { cursor: pointer; } button:disabled, [type="button"]:disabled, [type="reset"]:disabled, [type="submit"]:disabled { cursor: default; } :-moz-focusring { outline: auto; } select:disabled { opacity: inherit; } option { padding: 0; } fieldset { margin: 0; padding: 0; min-width: 0; } legend { padding: 0; } progress { vertical-align: baseline; } textarea { overflow: auto; } [type="number"]::-webkit-inner-spin-button, [type="number"]::-webkit-outer-spin-button { height: auto; } [type="search"] { outline-offset: -2px; } [type="search"]::-webkit-search-decoration { -webkit-appearance: none; } ::-webkit-file-upload-button { -webkit-appearance: button; font: inherit; } label[for] { cursor: pointer; } details { display: block; } summary { display: list-item; } [contenteditable]:focus { outline: auto; } table { border-color: inherit; } caption { text-align: left; } td, th { vertical-align: top; padding: 0; } th { text-align: left; font-weight: 700; } th{ font-weight: normal; }

      table{
        width: 100%;
        font-size: 5px;
        border-collapse: collapse;
      }
      table th,
      table td{
        position: relative;
          border: 0.5px solid black;
          text-align: center;
          padding: 1.5px 2px;
      }
      .total-retail{
        width: fit-content;
        margin-top: 10px;
        padding: 7px 10px;
        background-color: rgba(0, 191, 0, 0.539);
      }
      .right-txt{
        margin-left: 50px;
      }


	</style>

	@php
        $project_count = $projects->count();
        $company_count = $getCompanies->count();
        $is_first_cell = 0;
        $clm_count = ($project_count * $company_count) + ($company_count * 4); //必須レコード数を計算

        $company_total_salary = [];
        $company_total_retail = [];
        $company_total_expressway = [];
        $company_total_parking = [];
	@endphp

	<table>

      {{-- ヘッド --}}
      <thead>
        @if ($project_count > 1)
          {{-- 案件が複数ある場合 --}}
          <tr>
            {{-- 日付　縦結合 --}}
            <th rowspan="2">{{$dates[0]->format('m')}}月</th>
            {{-- 案件　横結合 --}}
            @foreach ($projects as $project)
            <th colspan="{{$company_count}}">{{$project->name}}</th>
            @endforeach
            {{-- その他　縦結合 --}}
            <th rowspan="2">上代</th>
            @foreach ($getCompanies as $company)
            <th rowspan="2">{{$company->name}}<br>ドライバー</th>
            <th rowspan="2">上代</th>
            <th rowspan="2">高速代</th>
            <th rowspan="2">パーキング代</th>
            @endforeach
            {{-- @foreach ($projects as $project)
            <th colspan="{{$company_count}}">{{$project->name}}</th>
            @endforeach --}}
          </tr>
          <tr>
            @foreach ($projects as $project)
              @foreach ($getCompanies as $company)
                <th>{{$company->name}}</th>
              @endforeach
            @endforeach
            {{-- @foreach ($projects as $project)
              @foreach ($getCompanies as $company)
                <th>{{$company->name}}<br>ドライバ-</th>
                <th>上代</th>
                <th>高速代</th>
                <th>パーキング代</th>
              @endforeach
            @endforeach --}}
          </tr>
        @else
          {{-- 案件が複数ない場合 --}}
          <tr>
            <th></th>
            @foreach ($getCompanies as $company)
            <th>{{$company->name}}</th>
            <th>上代</th>
            <th>{{$company->name}}<br>ドライバー</th>
            <th>上代</th>
            <th>高速代</th>
            <th>パーキング代</th>
            @endforeach
          </tr>
        @endif

      </thead>

      <tbody>
        @foreach ($dates as $date)
          @php
              $has_shift = false;
          @endphp
          <tr>
            {{-- 日付表示 --}}
            <td>{{$date->format('d')}}</td>

            {{-- 従業員表示 --}}
            @foreach ($projects as $project) {{-- 案件数 --}}
              @foreach ($getCompanies as $company) {{-- 企業数 --}}
                <td>
                  @foreach ( $ShiftProjectVehicles as $spv )
                    @if($spv->shift->date == $date->format('Y-m-d'))
                      @if($spv->shift->employee->company_id == $company->id && $spv->project_id == $project->id)
                      {{$spv->shift->employee->name}}
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

            {{-- 企業ごとの詳細情報 --}}
            @foreach ($getCompanies as $company)
              @php
                $tmp_total_salary_day = null;
                $tmp_total_retail_day = null;
                $tmp_total_expressway_day = null;
                $tmp_total_parking_day = null;
                foreach ($ShiftProjectVehicles as $spv) {
                  if ($spv->shift->date == $date->format('Y-m-d') && $spv->shift->employee->company_id == $company->id) {
                    if($spv->driver_price){
                      $tmp_total_salary_day += $spv->driver_price;
                    }
                    if($spv->retail_price){
                      $tmp_total_retail_day += $spv->retail_price;
                    }
                    if($spv->expressway_fee){
                      $tmp_total_expressway_day += $spv->expressway_fee;
                    }
                    if($spv->parking_fee){
                      $tmp_total_parking_day += $spv->parking_fee;
                    }
                        // 会社IDごとの合計値に加算する前に、配列のキーが存在するか確認し、存在しない場合は初期値を設定
                    if (!isset($company_total_salary[$company->id])) {
                      $company_total_salary[$company->id] = 0;
                    }
                    if (!isset($company_total_retail[$company->id])) {
                      $company_total_retail[$company->id] = 0;
                    }
                    if (!isset($company_total_expressway[$company->id])) {
                      $company_total_expressway[$company->id] = 0;
                    }
                    if (!isset($company_total_parking[$company->id])) {
                      $company_total_parking[$company->id] = 0;
                    }

                    // 各種費用を会社IDごとの合計値に加算
                    $company_total_salary[$company->id] += $spv->driver_price;
                    $company_total_retail[$company->id] += $spv->retail_price;
                    $company_total_expressway[$company->id] += $spv->expressway_fee;
                    $company_total_parking[$company->id] += $spv->parking_fee;

                  }
                }
              @endphp
              <td>{{$tmp_total_salary_day}}</td>
              <td>{{$tmp_total_retail_day}}</td>
              <td>{{$tmp_total_expressway_day}}</td>
              <td>{{$tmp_total_parking_day}}</td>
            @endforeach
          </tr>
        @endforeach

        {{-- 最後の集計行 --}}
        <tr>
          <td>total<br>{{$ShiftProjectVehicles->count()}}</td>

          {{-- 各案件の合計数 --}}
          @foreach ($projects as $project)
            @php
                $projectId = $project->id;
                $filtered = $ShiftProjectVehicles->filter(function ($item) use ($projectId) {
                    return $item->project->id == $projectId;
                });
            @endphp
          <td colspan="{{$company_count}}">{{$filtered->count()}}</td>
          @endforeach

          {{-- 全案件の上代の合計 --}}
          @php
            $totalRetailPrice = $ShiftProjectVehicles->reduce(function ($carry, $item) {
              return $carry + $item->retail_price;
            }, 0); // 第二引数の0は初期値です。
          @endphp
          <td>{{ $totalRetailPrice }}</td>

          {{-- 各企業の詳細の合計 --}}
          @foreach ( $getCompanies as $company )
            <td>{{$company_total_salary[$company->id]}}</td>
            <td>{{$company_total_retail[$company->id]}}</td>
            <td>{{$company_total_expressway[$company->id]}}</td>
            <td>{{$company_total_parking[$company->id]}}</td>
          @endforeach
        </tr>
      </tbody>

	</table>
    <div class="total-retail">
      <span class="">合計(税抜き)</span>
      <span class="right-txt">{{ number_format($totalRetailPrice) }}円</span>
    </div>
</body>
</html>
