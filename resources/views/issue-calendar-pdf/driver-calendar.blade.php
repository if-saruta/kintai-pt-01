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

        .top-table{
          /* width: 500px; */
        }
        table{
          font-size: 7px;
          border-collapse: collapse;
        }
        table th,
        table td{
          border: 0.5px solid black;
          /* text-align: center; */
          padding: 1.5px 0px;
        }
        .main-table{
          width: 50%;
          float: left;
          margin-top: 10px;
        }
        .other-table th{
          padding: 1.5px 5px;
        }
        .other-table td{
          padding: 1.5px 5px;
        }
        .date{
          width: 5.5%;
          vertical-align: middle;
          text-align: center;
        }
        .center-txt{
            padding: 2px 0px;
            text-align: center;
            vertical-align: middle;
        }
        .project{
          width: 39.6%;
          padding: 3px 0px;
          padding-left: 5px;
          box-sizing: border-box;
          text-align: start;
        }
        .driver-price{
          width: 7.7%;
          text-align: right;
          padding: 3px 0px;
          padding-right: 1px;
          box-sizing: border-box;
        }
        .allowance-fee{
          width: 7.7%;
          text-align: right;
          padding: 3px 0px;
          padding-right: 1px;
          box-sizing: border-box;
        }
        .expressway-fee{
          width: 8.1%;
          text-align: right;
          padding: 3px 0px;
          padding-right: 1px;
          box-sizing: border-box;
        }
        .parking-fee{
          width: 7.7%;
          text-align: right;
          padding: 3px 0px;
          padding-right: 1px;
          box-sizing: border-box;
        }
        .vehicle{
          width: 11%;
          text-align: right;
          padding: 3px 0px;
          padding-right: 1px;
          box-sizing: border-box;
        }
        .overtime{
            width: 7.7%;
          text-align: right;
          padding: 3px 0px;
          padding-right: 1px;
          box-sizing: border-box;
        }
        p{
          font-size: 7px;
        }
        .memo-box{
            width: 45%;
            height: 100px;
            /* padding: 10px; */
            box-sizing: border-box;
            border: 1px solid black;
            clear: left;
            margin-top: -40px;
        }
        .amount-total{
          width: 45%;
          float: right;
          margin-top: -110px;
          /* clear: left; */
          /* margin-top: 10px; */
          /* margin-left: 10px; */
        }
        .amount-total th{
            width: 50%;
            text-align: start;
            padding-left: 5px;
            box-sizing: border-box;
        }
        .amount-total td{
            width: 50%;
            text-align: right;
            padding-right: 5px;
            box-sizing: border-box;
        }
        .name{
            font-size: 10px;
        }
        .date-info{
            margin-top: 3px;
        }
        /* .project-count-table{
          margin-top: 20px;
          float: left;
        }
        .vehicle-table{
          margin-top: 20px;
          float: left;
          margin-left: 20px;
        }
        .bg-orange{
          background-color: rgba(255, 166, 0, 0.386);
        }
        .working-count{
          width: 90px;
          padding: 3px 5px;
          background-color: rgba(0, 128, 0, 0.445);
          clear: left;
          margin-top: 10px;
        } */

    </style>
@php
    $total_amount = 0;
    $total_allowance = 0;
    $tax_fee = 0;
    $total_parking = 0;
    $total_expressway = 0;
    $administrative_fee = 10000;
    $transfer_fee = 600;
    $rental_type = null;
    $rental_vehicle = null;
    $month_lease_fee = 30992;
    $month_insurance_fee = 9818;
    $secound_insurance_fee = 410;
    $second_machine_count = 0;
    $third_machine_count = 0;
    $secound_vehicle_array = [];
    $third_vehicle_array = [];
    $working_count = 0;
@endphp
<div class="top-table">
    <p class="name">{{ $findEmployee->name }}</p>
    <p class="date-info">{{ $getYear }}年{{ $getMonth }}月度</p>

    @php
        // シフトの数を判定し1日の行を最低3行にするコード
        $rowNeed = 3;
        foreach ($shifts as $shift) {
            if ($rowNeed < $shift->projectsVehicles->count()) {
                $rowNeed = $shift->projectsVehicles->count();
            }
        }
    @endphp
  <table class="main-table">
    <thead>
        <tr>
            <th class="center-txt">日付</th>
            <th class="center-txt">案件名</th>
            @if ($amountCheck != 1)
                <th class="center-txt">金額</th>
            @endif
            @if ($allowanceCheck != 1)
                <th class="center-txt">手当</th>
            @endif
            @if ($expresswayCheck != 1)
                <th class="center-txt">高速代</th>
            @endif
            @if ($parkingCheck != 1)
                <th class="center-txt">パーキン<br>グ代</th>
            @endif
            @if ($vehicleCheck != 1)
                <th class="center-txt">2台目</th>
            @endif
            @if ($overtimeCheck != 1)
                <th class="center-txt">残業代</th>
            @endif
        </tr>
    </thead>
    <tbody>
      @foreach ($dates as $date)
        @if ($date->format('d') < 16)
          @php
              $count = 0;
          @endphp

          @php
            //   2台目・三代目の確認変数
            $second_machine_check = true;
            // 稼働の確認
            $is_working = true;
          @endphp

          @foreach ($shifts as $shift)
            @if($shift->date == $date->format('Y-m-d'))
              @foreach ($shift->projectsVehicles as $spv)
                <tr>
                  @if ($count == 0)
                    <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                  @endif

                  @php
                    $count++;
                    // // 集計表で使用する変数
                    // $total_amount += $spv->driver_price;
                    // $total_allowance += $spv->total_allowance;
                    // $total_parking += $spv->parking_fee;
                    // $total_expressway += $spv->expressway_fee;
                    $rental_type = $spv->vehicle_rental_type;
                    if($spv->rentalVehicle){
                      $rental_vehicle = $spv->rentalVehicle->number;
                    }
                  @endphp
                    <td class="project">
                        <p class="">
                        @if ($spv->project)
                        {{$spv->project->name}}
                        @else
                            {{$spv->unregistered_project}}
                        @endif
                        </p>
                    </td>
                  @if ($amountCheck != 1)
                     <td class="driver-price"><p class="">{{number_format(ceil($spv->driver_price))}}</p></td>
                  @endif
                  @if ($allowanceCheck != 1)
                     <td class="allowance-fee"><p class="">{{number_format(ceil($spv->total_allowance))}}</p></td>
                  @endif
                  @if ($expresswayCheck != 1)
                      <td class="expressway-fee"><p class="">{{number_format(ceil($spv->expressway_fee))}}</p></td>
                  @endif
                  @if ($parkingCheck != 1)
                     <td class="parking-fee"><p class="">{{number_format(ceil($spv->parking_fee))}}</p></td>
                  @endif
                  @if ($vehicleCheck != 1)
                    <td class="vehicle">
                        {{-- 契約形態別の2台目・三代目の確認 --}}
                        <p class="">
                        {{-- 自車 --}}
                        @if (in_array($rental_type, [0 ,1]))
                            @if ($spv->vehicle)
                                @if($spv->vehicle->number != '自車')
                                    @if ($spv->vehicle->id != $spv->rental_vehicle_id)
                                        <p>No.{{ $spv->vehicle->number }}</p>
                                    @endif
                                @endif
                            @else
                                @if ($spv->unregistered_vehicle)
                                    <p>No.{{ $spv->unregistered_vehicle }}</p>
                                @endif
                            @endif
                        @endif
                        </p>
                    </td>
                  @endif
                  @if ($overtimeCheck != 1)
                    <td class="overtime"><p class="">{{ number_format($spv->overtime_fee) }}</p></td>
                  @endif
                </tr>
              @endforeach
            @endif
          @endforeach

          {{-- シフトがない場合 --}}
          @for ($count; $count < $rowNeed; $count++)
              <tr>
                @if ($count == 0)
                  <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                @endif
                <td class="project">&nbsp;</td>
                @if ($amountCheck != 1)
                    <td class="driver-price">&nbsp;</td>
                @endif
                @if ($allowanceCheck != 1)
                    <td class="allowance-fee">&nbsp;</td>
                @endif
                @if ($expresswayCheck != 1)
                    <td class="expressway-fee">&nbsp;</td>
                @endif
                @if ($parkingCheck != 1)
                    <td class="parking-fee">&nbsp;</td>
                @endif
                @if ($vehicleCheck != 1)
                    <td class="vehicle">&nbsp;</td>
                @endif
                @if ($overtimeCheck != 1)
                    <td class="overtime">&nbsp;</td>
                @endif
              </tr>
          @endfor
        @endif

      @endforeach
      @for ($count = 0; $count < $rowNeed; $count++)
        <tr>
            @if ($count == 0)
                <td rowspan="{{ $rowNeed }}" class="date"><p class=""></p></td>
            @endif
            <td class="project">&nbsp;</td>
            @if ($amountCheck != 1)
                <td class="driver-price">&nbsp;</td>
            @endif
            @if ($allowanceCheck != 1)
                <td class="allowance-fee">&nbsp;</td>
            @endif
            @if ($expresswayCheck != 1)
                <td class="expressway-fee">&nbsp;</td>
            @endif
            @if ($parkingCheck != 1)
                <td class="parking-fee">&nbsp;</td>
            @endif
            @if ($vehicleCheck != 1)
                <td class="vehicle">&nbsp;</td>
            @endif
            @if ($overtimeCheck != 1)
                <td class="overtime">&nbsp;</td>
            @endif
        </tr>
     @endfor

    </tbody>
  </table>

  {{-- 15日以降 --}}
  <table class="main-table">
    <thead>
        <tr>
            <th class="center-txt">日付</th>
            <th class="center-txt">案件名</th>
            @if ($amountCheck != 1)
                <th class="center-txt">金額</th>
            @endif
            @if ($allowanceCheck != 1)
                <th class="center-txt">手当</th>
            @endif
            @if ($expresswayCheck != 1)
                <th class="center-txt">高速代</th>
            @endif
            @if ($parkingCheck != 1)
                <th class="center-txt">パーキン<br>グ代</th>
            @endif
            @if ($vehicleCheck != 1)
                <th class="center-txt">2台目</th>
            @endif
            @if ($overtimeCheck != 1)
                <th class="center-txt">残業代</th>
            @endif
        </tr>
    </thead>
    <tbody>
      @foreach ($dates as $date)
        @if ($date->format('d') >= 16)
          @php
              $count = 0;
          @endphp

          @php
            // //   2台目・三代目の確認変数
            // $second_machine_check = true;
            // // 稼働の確認
            // $is_working = true;
          @endphp

          @foreach ($shifts as $shift)
            @if($shift->date== $date->format('Y-m-d'))
              @foreach ($shift->projectsVehicles as $spv)
                {{-- @php
                  if($is_working){
                    if($spv->project){
                      if($spv->project->name !== '休み'){
                        $working_count++;
                        $is_working = false;
                      }
                    }else{
                      $working_count++;
                      $is_working = false;
                    }
                  }
                @endphp --}}
                <tr>
                  @if ($count == 0)
                    <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                  @endif

                  @php
                    $count++;
                    // 集計表で使用する変数
                    // $total_amount += $spv->driver_price;
                    // $total_allowance += $spv->total_allowance;
                    // $total_parking += $spv->parking_fee;
                    // $total_expressway += $spv->expressway_fee;
                    $rental_type = $spv->vehicle_rental_type;
                    if($spv->rentalVehicle){
                      $rental_vehicle = $spv->rentalVehicle->number;
                    }
                  @endphp
                  <td class="project">
                    <p class="">
                      @if ($spv->project)
                      {{$spv->project->name}}
                      @else
                        {{$spv->unregistered_project}}
                      @endif
                    </p>
                  </td>
                  @if ($amountCheck != 1)
                     <td class="driver-price"><p class="">{{number_format(ceil($spv->driver_price))}}</p></td>
                  @endif
                  @if ($allowanceCheck != 1)
                     <td class="allowance-fee"><p class="">{{number_format(ceil($spv->total_allowance))}}</p></td>
                  @endif
                  @if ($expresswayCheck != 1)
                      <td class="expressway-fee"><p class="">{{number_format(ceil($spv->expressway_fee))}}</p></td>
                  @endif
                  @if ($parkingCheck != 1)
                     <td class="parking-fee"><p class="">{{number_format(ceil($spv->parking_fee))}}</p></td>
                  @endif
                  @if ($vehicleCheck != 1)
                    <td class="vehicle">
                        {{-- 契約形態別の2台目・三代目の確認 --}}
                        <p class="">
                        {{-- 自車 --}}
                        @if (in_array($rental_type, [0 ,1]))
                            @if ($spv->vehicle)
                                @if($spv->vehicle->number != '自車')
                                    @if ($spv->vehicle->id != $spv->rental_vehicle_id)
                                        <p>No.{{ $spv->vehicle->number }}</p>
                                    @endif
                                @endif
                            @else
                                @if ($spv->unregistered_vehicle)
                                    <p>No.{{ $spv->unregistered_vehicle }}</p>
                                @endif
                            @endif
                        @endif
                        {{-- @if ($rental_type == 0 && $spv->vehicle_id)
                            {{$spv->vehicle->number}}
                            @php
                                if($second_machine_check){
                                $second_machine_count++;
                                $second_machine_check = false;
                                // 2台目の車両の種類を確認
                                if(!in_array($spv->vehicle->number, $secound_vehicle_array)){
                                    $secound_vehicle_array[] = $spv->vehicle->number;
                                }
                                }else{
                                $third_machine_count++;
                                if(!in_array($spv->vehicle->number, $third_vehicle_array)){
                                    $third_vehicle_array[] = $spv->vehicle->number;
                                }
                                }
                            @endphp
                        @endif --}}
                        </p>
                    </td>
                  @endif
                  @if ($overtimeCheck != 1)
                    <td class="overtime"><p class="">{{ number_format($spv->overtime_fee) }}</p></td>
                  @endif
                </tr>
              @endforeach
            @endif
          @endforeach

          {{-- シフトがない場合 --}}
          @for ($count; $count < $rowNeed; $count++)
              <tr>
                @if ($count == 0)
                  <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{ $date->format('j') }}({{ $date->isoFormat('ddd') }})</p></td>
                @endif
                <td class="project">&nbsp;</td>
                @if ($amountCheck != 1)
                    <td class="driver-price">&nbsp;</td>
                @endif
                @if ($allowanceCheck != 1)
                    <td class="allowance-fee">&nbsp;</td>
                @endif
                @if ($expresswayCheck != 1)
                    <td class="expressway-fee">&nbsp;</td>
                @endif
                @if ($parkingCheck != 1)
                    <td class="parking-fee">&nbsp;</td>
                @endif
                @if ($vehicleCheck != 1)
                    <td class="vehicle">&nbsp;</td>
                @endif
                @if ($overtimeCheck != 1)
                    <td class="overtime">&nbsp;</td>
                @endif
              </tr>
          @endfor
        @endif

      @endforeach
      @for ($i = $dates[count($dates) - 1]->format('d'); $i < 31; $i++)
        @for ($count = 0; $count < $rowNeed; $count++)
            <tr>
                @if ($count == 0)
                    <td rowspan="{{ $rowNeed }}" class="date"><p class=""></p></td>
                @endif
                <td class="project">&nbsp;</td>
                @if ($amountCheck != 1)
                    <td class="driver-price">&nbsp;</td>
                @endif
                @if ($allowanceCheck != 1)
                    <td class="allowance-fee">&nbsp;</td>
                @endif
                @if ($expresswayCheck != 1)
                    <td class="expressway-fee">&nbsp;</td>
                @endif
                @if ($parkingCheck != 1)
                    <td class="parking-fee">&nbsp;</td>
                @endif
                @if ($vehicleCheck != 1)
                    <td class="vehicle">&nbsp;</td>
                @endif
                @if ($overtimeCheck != 1)
                    <td class="overtime">&nbsp;</td>
                @endif
            </tr>
        @endfor
      @endfor
    </tbody>
  </table>
      {{-- 備考欄 --}}
      <div class="memo-box">
        <p class="">{!! nl2br(e($textarea)) !!}</p>
    </div>
  {{-- 2台目・三代目の日割りの料金の設定 --}}
  @php
    $secound_lease_fee = 0;
    $third_lease_fee = 1000;
      if($rental_type == 0){
        $secound_lease_fee = 1500;
      }else if($rental_type == 1){
        $secound_lease_fee = 1000;
      }
  @endphp

  <table class="amount-total other-table">
    <tbody>
      <tr>
        <th>合計金額</th>
        <td>{{number_format(ceil($totalSalary))}}</td>
      </tr>
      <tr>
        <th>手当</th>
        <td>{{number_format(ceil($totalAllowance))}}</td>
      </tr>
      <tr>
        <th>消費税10%</th>
        <td>{{number_format(ceil($totalSalary * 0.1))}}</td>
      </tr>
      <tr>
        <th>パーキング代</th>
        <td>{{number_format(ceil($totalParking))}}</td>
      </tr>
      <tr>
        <th>高速代</th>
        <td>{{number_format(ceil($totalExpressWay))}}</td>
      </tr>
      <tr>
        <th>残業代</th>
        <td>{{ number_format($totalOverTime) }}</td>
      </tr>
      <tr>
        <th>事務手数料(15%)</th>
        <td>{{ number_format(ceil($totalSalary * 0.15)) }}</td>
      </tr>
      <tr>
        <th>事務手数料</th>
        <td>{{number_format(ceil($administrative_fee))}}</td>
      </tr>
      <tr>
        <th>振込手数料</th>
        <td>{{number_format(ceil($transfer_fee))}}</td>
      </tr>

      @if ($rental_type == 1 || $rental_type == 2)
      <tr>
        <th>リース代　月契約 No.{{$rental_vehicle}}</th>
        <td>{{number_format(ceil($month_lease_fee))}}</td>
      </tr>
      @endif

      @if ($rental_type == 0 || $rental_type == 1)
        @if ($secondMachineCount > 0)
          <tr>
            <th>リース　2台目(日割り)</th>
            <td>{{number_format(ceil($secondMachineCount * $secound_lease_fee))}}</td>
          </tr>
        @endif
      @endif

      @if ($thirdMachineCount != 0)
            <tr>
                <th>リース3台目(日割り)</th>
                <td>{{ number_format($thirdMachineCount * 1000) }}</td>
            </tr>
        @endif

      @if ($rental_type == 1 || $rental_type == 2)
        <tr>
          <th>保険料　月契約 No.{{$rental_vehicle}}</th>
          <td>{{number_format(ceil($month_insurance_fee))}}</td>
        </tr>
      @endif

      @if ($rental_type != 2 || $rental_type != 3)
        <tr>
          <th>保険料　2台目(日割り)</th>
          <td>{{number_format(ceil(($secondMachineCount + $third_machine_count )* $secound_insurance_fee))}}</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>



</body>
</html>
