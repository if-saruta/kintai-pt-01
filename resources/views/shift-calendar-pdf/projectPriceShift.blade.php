<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    {{-- スタイル --}}
    {{-- スタイル --}}
    <style>
        /* dompdf日本語文字化け対策 */
        /* 基本の文字 */
        @font-face {
            font-family: ipaexm;
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path(' fonts/ipaexm.ttf') }}');
        }

        @font-face {
            font-family: Noto Sans JP;
            font-style: normal;
            font-weight: bold;
            src:url('{{ storage_path(' fonts/NotoSansJP-Bold.ttf')}}');
        }

        /* 全てのHTML要素に適用 */
        html,
        body,
        textarea {
            font-family: ipaexm;
        }

        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            margin: 0;
        }

        main {
            display: block;
        }

        p,
        table,
        blockquote,
        address,
        pre,
        iframe,
        form,
        figure,
        dl {
            margin: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-size: inherit;
            font-weight: inherit;
            margin: 0;
        }

        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        dt {
            font-weight: 700;
        }

        dd {
            margin-left: 0;
        }

        hr {
            box-sizing: content-box;
            height: 0;
            overflow: visible;
            border-top-width: 1px;
            margin: 0;
            clear: both;
            color: inherit;
        }

        pre {
            font-family: monospace, monospace;
            font-size: inherit;
        }

        address {
            font-style: inherit;
        }

        a {
            background-color: transparent;
            text-decoration: none;
            color: inherit;
        }

        abbr[title] {
            text-decoration: underline dotted;
        }

        b,
        strong {
            font-weight: bolder;
        }

        code,
        kbd,
        samp {
            font-family: monospace, monospace;
            font-size: inherit;
        }

        small {
            font-size: 80%;
        }

        sub,
        sup {
            font-size: 75%;
            line-height: 0;
            position: relative;
            vertical-align: baseline;
        }

        sub {
            bottom: -0.25em;
        }

        sup {
            top: -0.5em;
        }

        svg,
        img,
        embed,
        object,
        iframe {
            vertical-align: center;
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            -webkit-appearance: none;
            appearance: none;
            vertical-align: middle;
            color: inherit;
            font: inherit;
            background: transparent;
            padding: 0;
            margin: 0;
            border-radius: 0;
            text-align: inherit;
            text-transform: inherit;
        }

        [type="checkbox"] {
            -webkit-appearance: checkbox;
            appearance: checkbox;
        }

        [type="radio"] {
            -webkit-appearance: radio;
            appearance: radio;
        }

        button,
        [type="button"],
        [type="reset"],
        [type="submit"] {
            cursor: pointer;
        }

        button:disabled,
        [type="button"]:disabled,
        [type="reset"]:disabled,
        [type="submit"]:disabled {
            cursor: default;
        }

        :-moz-focusring {
            outline: auto;
        }

        select:disabled {
            opacity: inherit;
        }

        option {
            padding: 0;
        }

        fieldset {
            margin: 0;
            padding: 0;
            min-width: 0;
        }

        legend {
            padding: 0;
        }

        progress {
            vertical-align: baseline;
        }

        textarea {
            overflow: auto;
        }

        [type="number"]::-webkit-inner-spin-button,
        [type="number"]::-webkit-outer-spin-button {
            height: auto;
        }

        [type="search"] {
            outline-offset: -2px;
        }

        [type="search"]::-webkit-search-decoration {
            -webkit-appearance: none;
        }

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            font: inherit;
        }

        label[for] {
            cursor: pointer;
        }

        details {
            display: block;
        }

        summary {
            display: list-item;
        }

        [contenteditable]:focus {
            outline: auto;
        }

        table {
            border-color: inherit;
        }

        caption {
            text-align: left;
        }

        td,
        th {
            vertical-align: top;
            padding: 0;
        }

        th {
            text-align: left;
            font-weight: 700;
        }

        th {
            font-weight: normal;
        }

        @page {
            margin-top: 25px;
            margin-bottom: 5px;
            margin-left: 40px;
            margin-right: 5px;
        }

        table {
            width: 1070px;
            font-size: 10px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 0.5px solid black;
            word-break: break-word;
        }

        .w {
            width: 1070px;
            height: 10px;
            background-color: red;
        }

        .employee-clm {
            width: 4.59%;
            font-size: 9px;
        }

        .empty-employee-clm {
            border-bottom: 0.5px solid black;
        }

        .employee-name-view {
            position: relative;
            border-bottom: 1px solid black;
        }

        .date-clm {
            width: 13.34%;
            height: 20px;
        }

        .date-part-clm {
            width: 6.67%;
            height: 20px;
        }

        .total-amount-clm {
            width: 4.59%;
        }

        tbody .total-amount-clm {
            border-bottom: 1px solid black;
        }

        .date-part-clm:nth-child(2n) {
            border-right: 1px dashed rgb(214, 214, 214);
        }

        .date-part-clm:nth-child(2n+1) {
            border-left: 1px dashed rgb(214, 214, 214);
        }

        .head-date-part-clm:nth-child(2n) {
            border-left: 1px dashed rgb(214, 214, 214);
            border-right: 0.5px solid black;
        }

        .head-date-part-clm:nth-child(2n+1) {
            border-right: 1px dashed rgb(214, 214, 214);
            border-left: 0.5px solid black;
        }

        .txt-position-center {
            text-align: center;
            vertical-align: middle;
        }

        .item-cell {
            border-top: 1px dashed rgb(214, 214, 214);
            border-bottom: 1px dashed rgb(214, 214, 214);
            text-align: center;
            display: table;
            table-layout: fixed;
            width: 100%;
        }

        .txt-position-center .item-cell:first-child {
            border-top: 0px;
        }

        .txt-position-center .item-cell:last-child {
            border-bottom: 0.5px solid black;
        }

        .date-part-clm .txt-position-center:last-child .item-cell {
            border-bottom: 0px;
        }

        tbody .date-part-clm {
            border-bottom: 1px solid black;
        }

        .item-cell p {
            display: table-cell;
            vertical-align: middle;
            height: 20px;
        }
    </style>

    <table>
        {{-- ヘッド --}}
        <thead>
            {{-- 日付 --}}
            <tr>
                <th rowspan="2" class="employee-clm empty-employee-clm"></th>
                @foreach ( $convertedDates as $date )
                    <th colspan="2" class="date-clm txt-position-center">
                        @if ($holidays->isHoliday($date))
                            <p class="" style="color: red;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                        @elseif ($date->isSaturday())
                            <p class="" style="color: rgb(0, 123, 255);">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                        @elseif($date->isSunday())
                            <p class="" style="color: red;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                        @else
                            <p class="">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                        @endif
                    </th>
                @endforeach
                <th rowspan="2" class="total-amount-clm txt-position-center">
                    <p class="">合計金額</p>
                </th>
            </tr>
            {{-- AM・PM --}}
            <tr>
                @foreach ( $convertedDates as $date )
                    <th class="date-part-clm head-date-part-clm txt-position-center">
                        <p class="">AM</p>
                    </th>
                    <th class="date-part-clm head-date-part-clm txt-position-center">
                        <p class="">PM</p>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($shiftDataByEmployee as $employeeId => $shiftData)
                @php
                    // 一周目だけ従業員を表示
                    $is_employee_open = true;
                    // 1日ごとの最大案件数
                    $max_count = 1;
                    // シフトがあるか
                    $hasShift = false;
                @endphp
                {{--  最大案件数の計算 --}}
                @php
                    foreach ($shiftData as $shift) {
                        $am_count = 0;
                        $pm_count = 0;
                        foreach ($shift->projectsVehicles as $spv) {
                            $count = 0;
                            $hasShift = true;
                            if($spv->time_of_day == 0){
                                $am_count++;
                            }
                            if($spv->time_of_day == 1){
                                $pm_count++;
                            }
                        }
                        if($max_count < $am_count){
                            $max_count = $am_count;
                        }
                        if ($max_count < $pm_count) {
                            $max_count = $pm_count;
                        }
                    }
                @endphp

                @if ($hasShift)
                    @php
                        $total_amount = 0;
                    @endphp
                    <tr>
                        @foreach ($shiftData as $shift) {{-- 従業員ごとの1日ごとのシフト --}}

                            {{-- 従業員表示 --}}
                            @if ($is_employee_open)
                                <td class="employee-clm employee-name-view txt-position-center">
                                    <p class="">{{ $shift->employee->name }}</p>
                                </td>
                                {{-- 一周目だけ従業員を表示 --}}
                                @php
                                    $is_employee_open = false;
                                @endphp
                            @endif
                            {{-- 午前・午後の案件数を格納 --}}
                            @php
                                $am_check_count = 0;
                                $pm_check_count = 0;
                            @endphp

                            {{-- 午前 --}}
                            <td class="date-part-clm">
                                @foreach ($shift->projectsVehicles as $spv)
                                    @if ($spv->time_of_day == 0)
                                        <div class="txt-position-center">
                                            {{-- 案件名 --}}
                                            <div class="item-cell">
                                                @if ($spv->project)
                                                    @if ($spv->initial_project_name)
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->initial_project_name }}</p>
                                                    @else
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->project->name }}</p>
                                                    @endif
                                                @elseif($spv->unregistered_project)
                                                    <p class="" style="color: red; height: {{ $projectHeight / 1.5 }}px;">{{ $spv->unregistered_project }}</p>
                                                @else
                                                    <p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p>
                                                @endif
                                            </div>
                                            {{-- 配送料金 --}}
                                            <div class="item-cell">
                                                <p class="">
                                                    @if ($spv->retail_price)
                                                        {{ number_format($spv->retail_price) }}
                                                        {{-- 合計金額の計算 --}}
                                                        @php
                                                            $total_amount += $spv->retail_price;
                                                        @endphp
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @php $am_check_count++; @endphp
                                    @endif
                                @endforeach
                                @for ($i = $am_check_count; $i < $max_count; $i++)
                                    <div class="txt-position-center">
                                        <div class="item-cell"><p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p></div>
                                        <div class="item-cell"><p class=""></p></div>
                                    </div>
                                @endfor
                            </td>
                            {{-- 午後 --}}
                            <td class="date-part-clm">
                                @foreach ($shift->projectsVehicles as $spv)
                                    @if ($spv->time_of_day == 1)
                                        <div class="txt-position-center">
                                            {{-- 案件名 --}}
                                            <div class="item-cell">
                                                @if ($spv->project)
                                                    @if ($spv->initial_project_name)
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->initial_project_name }}</p>
                                                    @else
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->project->name }}</p>
                                                    @endif
                                                @elseif($spv->unregistered_project)
                                                    <p class="" style="color: red; height: {{ $projectHeight / 1.5 }}px;">{{ $spv->unregistered_project }}</p>
                                                @else
                                                    <p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p>
                                                @endif
                                            </div>
                                            {{-- 配送料金 --}}
                                            <div class="item-cell">
                                                <p class="">
                                                    @if ($spv->retail_price)
                                                        {{ number_format($spv->retail_price) }}
                                                        {{-- 合計金額の計算 --}}
                                                        @php
                                                            $total_amount += $spv->retail_price;
                                                        @endphp
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @php $pm_check_count++; @endphp
                                    @endif
                                @endforeach
                                @for ($i = $pm_check_count; $i < $max_count; $i++)
                                    <div class="txt-position-center">
                                        <div class="item-cell"><p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p></div>
                                        <div class="item-cell"><p class=""></p></div>
                                    </div>
                                @endfor
                            </td>
                        @endforeach
                        {{-- 合計金額 --}}
                        <td class="total-amount-clm txt-position-center">
                            <p class="">{{ number_format($total_amount) }}</p>
                        </td>
                    </tr>
                @endif
            @endforeach

            {{-- 未登録従業員 --}}
                @foreach ($shiftDataByUnEmployee as $unEmployee => $shiftData)
                @php
                    // 一周目だけ従業員を表示
                    $is_employee_open = true;
                    // 1日ごとの最大案件数
                    $max_count = 1;
                    // シフトがあるか
                    $hasShift = false;
                @endphp
                {{--  最大案件数の計算 --}}
                @php
                    foreach ($shiftData as $shift) {
                        $am_count = 0;
                        $pm_count = 0;
                        foreach ($shift->projectsVehicles as $spv) {
                            $count = 0;
                            $hasShift = true;
                            if($spv->time_of_day == 0){
                                $am_count++;
                            }
                            if($spv->time_of_day == 1){
                                $pm_count++;
                            }
                        }
                        if($max_count < $am_count){
                            $max_count = $am_count;
                        }
                        if ($max_count < $pm_count) {
                            $max_count = $pm_count;
                        }
                    }
                @endphp

                @if ($hasShift)
                    <tr>
                        @php
                            $total_amount = 0;
                        @endphp
                        @foreach ($shiftData as $shift) {{-- 従業員ごとの1日ごとのシフト --}}

                            {{-- 従業員表示 --}}
                            @if ($is_employee_open)
                                <td class="employee-clm employee-name-view txt-position-center">
                                    <p class="" style="color: red;">{{ $unEmployee }}</p>
                                </td>
                                {{-- 一周目だけ従業員を表示 --}}
                                @php
                                    $is_employee_open = false;
                                @endphp
                            @endif
                            {{-- 午前・午後の案件数を格納 --}}
                            @php
                                $am_check_count = 0;
                                $pm_check_count = 0;
                            @endphp

                            {{-- 午前 --}}
                            <td class="date-part-clm">
                                @foreach ($shift->projectsVehicles as $spv)
                                    @if ($spv->time_of_day == 0)
                                        <div class="txt-position-center">
                                            {{-- 案件名 --}}
                                            <div class="item-cell">
                                                @if ($spv->project)
                                                    @if ($spv->initial_project_name)
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->initial_project_name }}</p>
                                                    @else
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->project->name }}</p>
                                                    @endif
                                                @elseif($spv->unregistered_project)
                                                    <p class="" style="color: red; height: {{ $projectHeight / 1.5 }}px;">{{ $spv->unregistered_project }}</p>
                                                @else
                                                    <p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p>
                                                @endif
                                            </div>
                                            {{-- 配送料金 --}}
                                            <div class="item-cell">
                                                <p class="">
                                                    @if ($spv->retail_price)
                                                        {{ number_format($spv->retail_price) }}
                                                        {{-- 合計金額の計算 --}}
                                                        @php
                                                            $total_amount += $spv->retail_price;
                                                        @endphp
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @php $am_check_count++; @endphp
                                    @endif
                                @endforeach
                                @for ($i = $am_check_count; $i < $max_count; $i++)
                                    <div class="txt-position-center">
                                        <div class="item-cell"><p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p></div>
                                        <div class="item-cell"><p class=""></p></div>
                                    </div>
                                @endfor
                            </td>
                            {{-- 午後 --}}
                            <td class="date-part-clm">
                                @foreach ($shift->projectsVehicles as $spv)
                                    @if ($spv->time_of_day == 1)
                                        <div class="txt-position-center">
                                            {{-- 案件名 --}}
                                            <div class="item-cell">
                                                @if ($spv->project)
                                                    @if ($spv->initial_project_name)
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->initial_project_name }}</p>
                                                    @else
                                                        <p class="" style="height: {{ $projectHeight / 1.5 }}px;">{{ $spv->project->name }}</p>
                                                    @endif
                                                @elseif($spv->unregistered_project)
                                                    <p class="" style="color: red; height: {{ $projectHeight / 1.5 }}px;">{{ $spv->unregistered_project }}</p>
                                                @else
                                                    <p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p>
                                                @endif
                                            </div>
                                            {{-- 配送料金 --}}
                                            <div class="item-cell">
                                                <p class="">
                                                    @if ($spv->retail_price)
                                                        {{ number_format($spv->retail_price) }}
                                                        {{-- 合計金額の計算 --}}
                                                        @php
                                                            $total_amount += $spv->retail_price;
                                                        @endphp
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @php $pm_check_count++; @endphp
                                    @endif
                                @endforeach
                                @for ($i = $pm_check_count; $i < $max_count; $i++)
                                    <div class="txt-position-center">
                                        <div class="item-cell"><p class="" style="height: {{ $projectHeight / 1.5 }}px;"></p></div>
                                        <div class="item-cell"><p class=""></p></div>
                                    </div>
                                @endfor
                            </td>
                        @endforeach
                        {{-- 合計金額 --}}
                        <td class="total-amount-clm txt-position-center">
                            <p class="">{{ number_format($total_amount) }}</p>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

</body>
</html>
