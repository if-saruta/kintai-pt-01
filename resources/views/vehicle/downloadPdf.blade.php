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
            margin-top: 30px;
            margin-bottom: 5px;
            margin-left: 40px;
            margin-right: 5px;
        }
        table {
            width: 745px;
            font-size: 7px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 0.5px solid black;
            word-break: break-word;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
              padding: 5px 0;
        }
        table th{
            padding: 8px 0;
        }
    </style>

    <p class="" style="margin-bottom: 10px;">車両一覧</p>

    <table class="vehicle-list-table">
        <thead>
            <th class="number"><p class="">車両番号</p></th>
            <th class="vehicle-type"><p class="">車種</p></th>
            <th class="category"><p class="">種別</p></th>
            <th class="brand"><p class="">社名</p></th>
            <th class="model"><p class="">型式</p></th>
            <th class="owner"><p class="">所有</p></th>
            <th class="user"><p class="">使用</p></th>
            <th class="vehicle-date"><p class="">車検満了日</p></th>
        </thead>
        <tbody>
            @foreach ($vehiclesGroupedByOwner as $ownerType => $vehiclesInOwnerGroup)
                @foreach ($vehiclesInOwnerGroup as $ownerName => $vehicles)
                    @if (in_array($ownerName, $narrowOwnerArray))
                        @foreach ($vehicles as $vehicle)
                            <tr>
                                <td class="number"><p class="">{{ $vehicle->place_name }} {{ $vehicle->class_number }} {{ $vehicle->hiragana }} {{ $vehicle->number }}</p></td>
                                <td class="vehicle-type"><p class="">{{ $vehicle->vehicle_type }}</p></td>
                                <td class="category"><p class="">{{ $vehicle->category }}</p></td>
                                <td class="brand"><p class="">{{ $vehicle->brand_name }}</p></td>
                                <td class="model"><p class="">{{ $vehicle->model }}</p></td>
                                <td class="owner"><p class="">{{ $vehicle->ownership ? $vehicle->ownership->name : null }}</p></td>
                                <td class="user"><p class="">{{ $vehicle->employee ? $vehicle->employee->name : null }}</p></td>
                                <td class="vehicle-date"><p class="">{{ $vehicle->inspection_expiration_date ? $vehicle->inspection_expiration_date->format('Y月n月j日') : null }}</p></td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
