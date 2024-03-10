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
            src: url('{{ storage_path(' fonts/ipaexm.ttf') }}');
        }

        /* 全てのHTML要素に適用 */
        html,
        body,
        textarea {
            font-family: ipaexm, sans-serif;
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

        table {
            border-collapse: collapse;
        }

        .pdf-number {
            text-align: right;
            font-size: 13px;
            line-height: 1.5;
        }

        .date {
            position: absolute;
            top: 100px;
            right: 0;
        }

        .employee-info {
            position: absolute;
            top: 190px;
            right: 0px;
            font-size: 13px;
            line-height: 1.5;
        }

        .employee-name {
            font-size: 16px;
        }

        .title {
            font-size: 30px;
            text-align: right;
            margin-top: 10px;
            margin-right: 30px;
        }

        .line {
            width: 100vw;
            height: 5px;
            background-color: rgb(44, 212, 44);
        }

        .company {
            margin-top: 20px;
            font-size: 13px;
            line-height: 1.5;
        }

        .company-txt {
            font-size: 16px
        }

        .request-table {
            margin-top: 10px;
        }

        .request-table-data {
            position: relative;
            width: 200px;
            height: 100px;
            border: 1px solid black;
        }

        .request-table-data-txt {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .top-table {
            margin-top: 25px;
            font-size: 13px;
        }

        .top-table-head {
            position: relative;
            height: 30px;
            border: 1px solid black;
        }

        .top-table-head-txt {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            white-space: nowrap;
        }

        .top-table-data {
            position: relative;
            height: 30px;
            border: 1px solid black;
        }

        .top-table-data-txt {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            white-space: nowrap;
        }

        .--center {
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .--right {
            right: 5px;
        }

        .--bg-green {
            background-color: {{ $color }};
        }

        .no-border {
            border: 0px;
        }

        .--no-margin {
            margin: 0;
        }

        .w-330 {
            width: 330px;
        }

        .w-260 {
            width: 260px;
        }

        .w-190 {
            width: 190px;
        }

        .w-100 {
            width: 100px;
        }

        .w-70 {
            width: 70px;
        }

        .f-s-13 {
            font-size: 13px
        }

        .f-s-10 {
            font-size: 10px
        }
    </style>

    <div class="">
        <div class="pdf-number">
            <p class="">NO.{{ $employee->initials }}{{$invoiceNumber}}</p>
        </div>
        <div class="title">
            <p class="">請求書</p>
        </div>
        <div class="line" style="background-color:{{$color}};"></div>
        <div class="date">
            <p class="">{{ $today->format('Y') }}年 {{ $today->format('m') }}月 20日</p>
        </div>
        <div class="employee-info">
            <p class="employee-name">{{$employee->name}}</p>
            <p class="">
                〒{{$employee->post_code}} <br>
                {{$employee->address}} <br>
                @if ($employee->is_invoice == 1)
                登録番号　{{$employee->register_number}} <br>
                @endif
                {{$bankName}} <br>
                振込先名 {{$bankAccountHolder}}
            </p>
        </div>
        <div class="company">
            <div class="company">
                <p class="">{!! $textWithBreaks !!}</p>
            </div>
            <br>
            <br>
            <p class="">下記の通りご請求申し上げます。</p>
        </div>
        <table class="request-table">
            <tr>
                <td class="request-table-data --bg-green">
                    <p class="request-table-data-txt">ご請求金額</p>
                </td>
                <td class="request-table-data">
                    <p class="request-table-data-txt">¥{{$allTotal}}</p>
                </td>
            </tr>
        </table>
        <table class="top-table">
            <tr>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">NO</p>
                </th>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">月日</p>
                </th>
                <th class="top-table-head w-260 --bg-green">
                    <p class="top-table-head-txt">案件名</p>
                </th>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">高速代他</p>
                </th>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">実績</p>
                </th>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">単価</p>
                </th>
                <th class="top-table-head w-100 --bg-green">
                    <p class="top-table-head-txt">金額</p>
                </th>
            </tr>
            @foreach ($salaryNo as $index => $value)
            <tr>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center">{{$salaryNo[$index]}}</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center">{{$salaryMonth[$index]}}</p>
                </td>
                <td class="top-table-data w-260">
                    <p class="top-table-data-txt --center">{{$salaryProject[$index]}}</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center">{{$salaryEtc[$index]}}</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center">{{$salaryCount[$index]}}</p>
                </td>
                {{-- @php
                $until = ($salaryUntil[$index] != 0) ? number_format($salaryUntil[$index]) : '';
                $salary = ($salaryAmount[$index] != 0) ? number_format($salaryAmount[$index]) : '';
                @endphp --}}
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right">{{ $salaryUntil[$index] }}</p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{ $salaryAmount[$index] }}</p>
                </td>
            </tr>
            @endforeach
            <tr>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-260 --bg-green">
                    <p class="top-table-data-txt --right">小計</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right"></p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{$salarySubTotal}}</p>
                </td>
            </tr>
            @if ($salaryTax != null)
            <tr>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-260 --bg-green">
                    <p class="top-table-data-txt --right">消費税(10%)</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right"></p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{$salaryTax}}</p>
                </td>
            </tr>
            @endif
            <tr>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-260 --bg-green">
                    <p class="top-table-data-txt --right">高速代他</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right"></p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{$etcTotal}}</p>
                </td>
            </tr>
            <tr>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70 --bg-green">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-260 --bg-green">
                    <p class="top-table-data-txt --right">合計金額</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right"></p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{$salaryTotal}}</p>
                </td>
            </tr>

        </table>

        <table class="top-table">
            <tr>
                <th class="top-table-head no-border w-70">
                    <p class="top-table-head-txt"></p>
                </th>
                <th class="top-table-head no-border w-70">
                    <p class="top-table-head-txt"></p>
                </th>
                <th class="top-table-head w-330 --bg-green">
                    <p class="top-table-head-txt">差引項目</p>
                </th>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">実績</p>
                </th>
                <th class="top-table-head w-70 --bg-green">
                    <p class="top-table-head-txt">単価</p>
                </th>
                <th class="top-table-head w-100 --bg-green">
                    <p class="top-table-head-txt">金額</p>
                </th>
            </tr>
            <tr>
                <td class="top-table-data no-border w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data no-border w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-330">
                    <p class="top-table-data-txt --center f-s-10">㈱T.N.G 請求書NO.{{ $employee->initials
                        }}{{$invoiceNumber}}({{ $today->format('Y') }}年 {{ $today->format('m') }}月 15日発行)相殺</㈱T.N.G>
                    </p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center">{{$getCostNum}}</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right">{{$getCostAmount}}</p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{$getCostAmount}}</p>
                </td>
            </tr>
            @foreach ($salaryCostName as $index => $value)
            <tr>
                <td class="top-table-data no-border w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data no-border w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-330">
                    <p class="top-table-data-txt --center f-s-10">{{$salaryCostName[$index]}}</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center">{{$salaryCostNum[$index]}}</p>
                </td>
                {{-- @php
                $costUntil = ($salaryCostUntil[$index] != 0) ? number_format($salaryCostUntil[$index]) : '';
                $costAmount = ($salaryCostAmount[$index] != 0) ? number_format($salaryCostAmount[$index]) : '';
                @endphp --}}
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right">{{$salaryCostUntil[$index]}}</p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{$salaryCostAmount[$index]}}</p>
                </td>
            </tr>
            @endforeach
        </table>
        <table class="top-table --no-margin">
            <tr>
                <th class="top-table-head no-border w-70">
                    <p class="top-table-head-txt"></p>
                </th>
                <th class="top-table-head no-border w-70">
                    <p class="top-table-head-txt"></p>
                </th>
                <th class="top-table-head w-330 --bg-green">
                    <p class="top-table-data-txt --right">小計</p>
                </th>
                <th class="top-table-head w-70">
                    <p class="top-table-head-txt"></p>
                </th>
                <th class="top-table-head w-70">
                    <p class="top-table-head-txt"></p>
                </th>
                <th class="top-table-head w-100">
                    <p class="top-table-data-txt --right">{{$salaryCostTotal}}</p>
                </th>
            </tr>
            <tr>
                <td class="top-table-data no-border w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data no-border w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-330 --bg-green">
                    <p class="top-table-data-txt --center f-s-13">差引合計金額</p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --center"></p>
                </td>
                <td class="top-table-data w-70">
                    <p class="top-table-data-txt --right"></p>
                </td>
                <td class="top-table-data w-100">
                    <p class="top-table-data-txt --right">{{ $allTotal }}</p>
                </td>
            </tr>
        </table>
    </div>


</body>

</html>
