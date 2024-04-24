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

        @page {
            margin-top: 25px;
            margin-bottom: 5px;
            margin-left: 40px;
            margin-right: 5px;
        }
        table {
            width: 745px;
            font-size: 10px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 0.5px solid black;
            word-break: break-word;
            text-align: center;
            vertical-align: middle;
        }
        table td{
            height: 30px;
        }
        .project-clm{
            width: 300px;
        }
        .date-clm{
            width: 63.57px;
            height: 20px;
        }
        .total-row td{
            border-top: 1px solid black;
        }

    </style>

    <table>
        {{-- ヘッド --}}
        <thead>
            {{-- 日付 --}}
            <th class="project-clm"></th>
            @foreach ( $convertedDates as $date )
                <th class="date-clm">
                    @if ($holidays->isHoliday($date))
                        <p class="" style="color: red;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                    @elseif ($date->isSaturday())
                        <p class="" style="color: skyblue;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                    @elseif($date->isSunday())
                        <p class="" style="color: red;">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                    @else
                        <p class="">{{$date->format('j')}}日({{ $date->isoFormat('ddd') }})</p>
                    @endif
                </th>
            @endforeach
        </thead>
        <tbody>
            @foreach ($projects as $project)
                @if($project->client->id != 1)
                    <tr>
                        <td class="project-clm">
                            <p class="">{{ $project->name }}</p>
                        </td>
                        @foreach ( $convertedDates as $date )
                            @php
                                $project_count = 0;
                                foreach ($shifts as $shift) {
                                    if ($shift->date == $date->format('Y-m-d')) {
                                        foreach ($shift->projectsVehicles as $spv) {
                                            if($spv->project){
                                                if($spv->project->id == $project->id){
                                                    $project_count++;
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <td class="date-clm">
                                @if ($project_count != 0)
                                    {{$project_count}}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endif
            @endforeach

            @foreach ( $unregistered_project as $unProject )
                <tr>
                    <td class="project-clm">
                        <p class="" style="color: red;">{{ $unProject }}</p>
                    </td>
                    @foreach ( $convertedDates as $date )
                        @php
                            $unProject_count = 0;
                            foreach ($shifts as $shift) {
                                if ($shift->date == $date->format('Y-m-d')) {
                                    foreach ($shift->projectsVehicles as $spv) {
                                        if($spv->unregistered_project){
                                            if($spv->unregistered_project == $unProject){
                                                $unProject_count++;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        <td class="date-clm">
                            @if ($unProject_count != 0)
                                {{$unProject_count}}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach

            {{-- 合計 --}}
            <tr class="total-row">
                <td class="project-clm">
                    <p class="">合計</p>
                </td>
                @foreach ( $convertedDates as $date )
                    @php
                        $day_count = 0;
                        foreach ($shifts as $shift) {
                                if ($shift->date == $date->format('Y-m-d')) {
                                    foreach ($shift->projectsVehicles as $spv) {
                                        if($spv->project){
                                            if($spv->project->client->id == 1){
                                                continue;
                                            }
                                        }
                                        $day_count++;
                                    }
                                }
                            }
                    @endphp
                    <td class="date-clm">
                        {{$day_count}}
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
</html>
