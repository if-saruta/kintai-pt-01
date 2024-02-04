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
          text-align: center;
          padding: 1.5px 0px;
        }
        .main-table{
          width: 50%;
          float: left;
        }
        .other-table th{
          padding: 1.5px 5px;
        }
        .other-table td{
          padding: 1.5px 5px;
        }
        .date{
          width: 5.5%;
        }
        .project{
          width: 46.6%;
        }
        .driver-price{
          width: 7.7%;
        }
        .allowance-fee{
          width: 7.7%;
        }
        .expressway-fee{
          width: 8.1%;
        }
        .parking-fee{
          width: 16%;
        }
        .vehicle{
          width: 8.1%;
        }
        p{
          font-size: 7px;
        }
        .amount-total{
          width: 25%;
          clear: left;
          margin-top: 10px;
        }
        .project-count-table{
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
        }

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
  <table class="main-table">
    <thead>
        <tr>
            <th>日付</th>
            <th>案件名</th>
            <th>金額</th>
            <th>手当</th>
            <th>高速代</th>
            <th>パーキング代</th>
            <th>二代目</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($dates as $date)
        @if ($date->format('d') < 16)
          @php
              // シフトの数を判定し1日の行を最低3行にするコード
              $rowNeed = 3;
              $count = 0;
              foreach ($shifts as $shift) {
                if($shift->date == $date->format('Y-m-d')){
                  $rowNeed = max(3, $shift->projectsVehicles->count());
                }
              }
          @endphp

          @php
            //   二代目・三代目の確認変数
            $second_machine_check = true;
            // 稼働の確認
            $is_working = true;
          @endphp

          @foreach ($shifts as $shift)
            @if($shift->date == $date->format('Y-m-d'))
              @foreach ($shift->projectsVehicles as $spv)
                @php
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
                @endphp
                <tr>
                  @if ($count == 0)
                    <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{$date->format('d')}}</p></td>
                  @endif

                  @php
                    $count++;
                    // 集計表で使用する変数
                    $total_amount += $spv->driver_price;
                    $total_allowance += $spv->total_allowance;
                    $total_parking += $spv->parking_fee;
                    $total_expressway += $spv->expressway_fee;
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
                  <td class="driver-price"><p class="">{{number_format(ceil($spv->driver_price))}}</p></td>
                  <td class="allowance-fee"><p class="">{{number_format(ceil($spv->total_allowance))}}</p></td>
                  <td class="expressway-fee"><p class="">{{number_format(ceil($spv->expressway_fee))}}</p></td>
                  <td class="parking-fee"><p class="">{{number_format(ceil($spv->parking_fee))}}</p></td>
                  <td class="vehicle">
                    {{-- 契約形態別の二代目・三代目の確認 --}}
                    <p class="">
                      {{-- 自車 --}}
                      @if ($rental_type == 0 && $spv->vehicle_id)
                        {{$spv->vehicle->number}}
                        @php
                            if($second_machine_check){
                              $second_machine_count++;
                              $second_machine_check = false;
                            // 二代目の車両の種類を確認
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
                      @endif
                      {{-- 月リース --}}
                      @if ($rental_type == 1)
                        @if ($spv->vehicle_id != $spv->rental_vehicle_id)
                          {{$spv->vehicle_id}}
                          @php
                            if($second_machine_check){
                              $second_machine_count++;
                              $second_machine_check = false;
                            }else{
                              $third_machine_count++;
                            }
                          @endphp
                        @endif
                      @endif
                    </p>
                  </td>
                </tr>
              @endforeach
            @endif
          @endforeach

          {{-- シフトがない場合 --}}
          @for ($count; $count < $rowNeed; $count++)
              <tr>
                @if ($count == 0)
                  <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{$date->format('d')}}</p></td>
                @endif
                <td class="project">&nbsp;</td>
                <td class="driver-price">&nbsp;</td>
                <td class="allowance-fee">&nbsp;</td>
                <td class="expressway-fee">&nbsp;</td>
                <td class="parking-fee">&nbsp;</td>
                <td class="vehicle">&nbsp;</td>
              </tr>
          @endfor
        @endif

      @endforeach

    </tbody>
  </table>

  {{-- 15日以降 --}}
  <table class="main-table">
    <thead>
        <tr>
            <th>日付</th>
            <th>案件名</th>
            <th>金額</th>
            <th>手当</th>
            <th>高速代</th>
            <th>パーキング代</th>
            <th>二代目</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($dates as $date)
        @if ($date->format('d') >= 16)
          @php
              $rowNeed = 3;
              $count = 0;
              foreach ($shifts as $shift) {
                if($shift->date == $date->format('Y-m-d')){
                  $rowNeed = max(3, $shift->projectsVehicles->count());
                }
              }
          @endphp

          @php
            //   二代目・三代目の確認変数
            $second_machine_check = true;
            // 稼働の確認
            $is_working = true;
          @endphp

          @foreach ($shifts as $shift)
            @if($shift->date== $date->format('Y-m-d'))
              @foreach ($shift->projectsVehicles as $spv)
                @php
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
                @endphp
                <tr>
                  @if ($count == 0)
                    <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{$date->format('d')}}</p></td>
                  @endif

                  @php
                    $count++;
                    // 集計表で使用する変数
                    $total_amount += $spv->driver_price;
                    $total_allowance += $spv->total_allowance;
                    $total_parking += $spv->parking_fee;
                    $total_expressway += $spv->expressway_fee;
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
                  <td class="driver-price"><p class="">{{number_format(ceil($spv->driver_price))}}</p></td>
                  <td class="allowance-fee"><p class="">{{number_format(ceil($spv->total_allowance))}}</p></td>
                  <td class="expressway-fee"><p class="">{{number_format(ceil($spv->expressway_fee))}}</p></td>
                  <td class="parking-fee"><p class="">{{number_format(ceil($spv->parking_fee))}}</p></td>
                  <td class="vehicle">
                    {{-- 契約形態別の二代目・三代目の確認 --}}
                    <p class="">
                      {{-- 自車 --}}
                      @if ($rental_type == 0 && $spv->vehicle_id)
                        {{$spv->vehicle->number}}
                        @php
                            if($second_machine_check){
                              $second_machine_count++;
                              $second_machine_check = false;
                            // 二代目の車両の種類を確認
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
                      @endif
                      {{-- 月リース --}}
                      @if ($rental_type == 1)
                        @if ($spv->vehicle_id != $spv->rental_vehicle_id)
                          {{$spv->vehicle_id}}
                          @php
                            if($second_machine_check){
                              $second_machine_count++;
                              $second_machine_check = false;
                            }else{
                              $third_machine_count++;
                            }
                          @endphp
                        @endif
                      @endif
                    </p>
                  </td>
                </tr>
              @endforeach
            @endif
          @endforeach

          {{-- シフトがない場合 --}}
          @for ($count; $count < $rowNeed; $count++)
              <tr>
                @if ($count == 0)
                  <td rowspan="{{ $rowNeed }}" class="date"><p class="">{{$date->format('d')}}</p></td>
                @endif
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
          @endfor
        @endif

      @endforeach
    </tbody>
  </table>
</div>

  {{-- 二代目・三代目の日割りの料金の設定 --}}
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
        <td>合計金額</td>
        <td>{{number_format(ceil($total_amount))}}</td>
      </tr>
      <tr>
        <td>手当</td>
        <td>{{number_format(ceil($total_allowance))}}</td>
      </tr>
      <tr>
        <td>消費税10%</td>
        <td>{{number_format(ceil($total_amount * 0.1))}}</td>
      </tr>
      <tr>
        <td>パーキング代</td>
        <td>{{number_format(ceil($total_parking))}}</td>
      </tr>
      <tr>
        <td>高速代</td>
        <td>{{number_format(ceil($total_expressway))}}</td>
      </tr>
      <tr>
        <td>事務手数料(15%)</td>
        <td>{{number_format(ceil($total_amount * 0.15))}}</td>
      </tr>
      <tr>
        <td>事務手数料</td>
        <td>{{number_format(ceil($administrative_fee))}}</td>
      </tr>
      <tr>
        <td>振込手数料</td>
        <td>{{number_format(ceil($transfer_fee))}}</td>
      </tr>

      @if ($rental_type == 1 || $rental_type == 2)  {{-- 0:自車　1:月リース　2:なんでも 3:日割り --}}
      <tr>
        <td>リース代　月契約 No.{{$rental_vehicle}}</td>
        <td>{{number_format(ceil($month_lease_fee))}}</td>
      </tr>
      @endif

      @if ($rental_type == 0 || $rental_type == 1)
        @if ($second_machine_count > 0)
          <tr>
            <td>リース　二代目(日割り)</td>
            <td>{{number_format(ceil($second_machine_count * $secound_lease_fee))}}</td>
          </tr>
        @endif
      @endif

      @if ($third_machine_count > 0)
        <tr>
          <td>リース　三代目(日割り)</td>
          <td>{{number_format(ceil($third_machine_count * $third_lease_fee))}}</td>
        </tr>
      @endif

      @if ($rental_type == 1 || $rental_type == 2)
        <tr>
          <td>保険料　月契約 No.{{$rental_vehicle}}</td>
          <td>{{number_format(ceil($month_insurance_fee))}}</td>
        </tr>
      @endif

      @if ($rental_type != 2 || $rental_type != 3)
        <tr>
          <td>保険料　二代目(日割り)</td>
          <td>{{number_format(ceil(($second_machine_count + $third_machine_count )* $secound_insurance_fee))}}</td>
        </tr>
      @endif
    </tbody>
  </table>

  @php
      $project_total_count = 0;
  @endphp
  <table class="project-count-table other-table">
    <thead>
      <tr>
        <th>案件名</th>
        <th>金額</th>
        <th>件数</th>
      </tr>
    </thead>
    <tbody>
      @foreach ( $projectCount as $projectName => $amountKey )
        @foreach ( $amountKey as $amount => $count )
          <tr>
            <td>{{$projectName}}</td>
            <td>{{number_format(ceil((float)$amount))}}</td>
            <td>{{number_format(ceil($count))}}</td>
          </tr>
          @php
              $project_total_count += $count
          @endphp
        @endforeach
      @endforeach
      <tr>
        <td></td>
        <td></td>
        <td class="bg-orange">{{$project_total_count}}</td>
      </tr>
    </tbody>
  </table>

  <table class="vehicle-table other-table">
    <tbody>
      <tr>
        <td>二代目車両</td>
      </tr>
      @foreach ($secound_vehicle_array as $number)
      <tr>
        <td>{{$number}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table class="vehicle-table other-table">
    <tbody>
      <tr>
        <td>三代目車両</td>
      </tr>
      @foreach ($third_vehicle_array as $number)
      <tr>
        <td>{{$number}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="working-count">
    <p class="">稼働日数　　　　　{{$working_count}}</p>
  </div>


</body>
</html>
