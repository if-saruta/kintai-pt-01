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
          font-size: 10px;
          border-collapse: collapse;
        }
        table th,
        table td{
          border: 0.5px solid black;
          text-align: center;
          padding: 1.5px 5px;
        }
        .project{
            text-align: left;
            padding-left: 5px;
            box-sizing: border-box;
        }
        .text-right{
            text-align: right;
            padding-right: 5px;
            box-sizing: border-box;
        }
    </style>

    <table>
        <thead>
            <tr>
                <th>日付</th>
                <th>案件名</th>
                <th>上代</th>
                <th>高速代</th>
                <th>パーキング代</th>
                <th>ドライバー</th>
                <th>ドライバー価格</th>
                <th>クライアント名</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $shiftArray as $data )
            <tr>
                <td>
                    @foreach ($dates as $date)
                        @if ($date->format('Y-m-d') == $data['shift']['date'])
                            <p class="">{{ $date->format('n月j日') }}({{ $date->isoFormat('ddd') }})</p>
                        @endif
                    @endforeach
                </td>
                <td class="project">{{$data['project']['name']}}</td>
                <td class="text-right">{{ number_format($data['retail_price']) }}</td>
                <td class="text-right">{{ number_format($data['expressway_fee']) }}</td>
                <td class="text-right">{{ number_format($data['parking_fee']) }}</td>
                <td class="">
                    @if (isset($data['shift']['employee']['name']))
                        <p>{{$data['shift']['employee']['name']}}</p>
                    @else
                        <p class="">{{ $data['shift']['unregistered_employee'] }}</p>
                    @endif
                </td>
                <td class="text-right">{{ number_format($data['driver_price']) }}</td>
                <td>{{$data['project']['client']['name']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
