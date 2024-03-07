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

        .date{
            text-align: right;
            font-size: 13px;
            line-height: 1.5;
        }
        .title{
            width: 100px;
            margin: 0 auto;
            margin-top: 40px;
            font-size: 30px;
        }
        .driver{
            margin-top: 30px;
            line-height: 2;
        }
        .company{
            position: absolute;
            top: 200px;
            right: 0;
            line-height: 1.5;
            font-size: 13px;
        }
        .company-info-txt{
            position: relative;
            z-index: 2;
        }
        .company-stanp {
            position: absolute;
            z-index: 1;
            top: -15px;
            right: -30px;
            z-index: 1;
            width: 110px;
        }

        .company-stanp img {
            width: 100%;
        }
        .amount{
            margin-top: 100px;
        }
        .amount-txt{
            display: inline-block;
            padding-bottom: 5px;
            border-bottom: 1px solid black;
        }
        .amount-fee{
            margin-left: 60px;
        }
        .f-s-13{
            font-size: 13px
        }
        .table{
            margin-top: 20px;
            border-collapse:collapse;
            font-size: 13px;
        }
        .mini-table{
            /* margin-top: 1px; */
            border-collapse:collapse;
            font-size: 13px;
        }
        .tax-table{
            width: 450px;
            margin-top: 30px;
            margin-left: auto;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
        }
        .tax-table__row{
            width: 100%;
            border-top: 1px solid black;
        }
        .tax-table__row__data{
            width: 100px;
            border-left: 1px solid black;
        }
        .tax-table__row__data:nth-child(even){
            width: 100px;
            border-left: 1px dashed black;
        }
        .tax-table__row__data input{
            width: 100%;
            padding: 0;
            text-align: center;
            border: 0px;
            font-size: 15px;
        }
        .table-head{
            padding: 5px 0;
            text-align: center;
            border: 1px solid black;
            background-color: rgba(128, 128, 128, 0.254);
        }
        .table-item{
            position: relative;
            height: 25px;
            border: 1px solid black;
        }
        .table-item-txt{
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
        }
        .table-data{
            position: relative;
            height: 25px;
            text-align: center;
            border: 1px solid black;
        }
        .table-data-txt{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .mini-table-data{
            position: relative;
            height: 25px;
            border: 1px solid black;
        }
        .mini-table-data-txt{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .bank{
            margin-top: 50px;
            font-size: 13px
        }
        .bank-txt-wrap{
            padding: 7px;
            margin-top: 7px;
            border: 1px solid black;
        }
        .--center{
            left: 50%;
            transform: translate(-50%,-50%);
        }
        .--right{
            right: 5px;
        }
        .--left{
            left: 5px;
        }
        .w-401{
            width: 401px;
        }
        .w-400{
            width: 400px;
        }
        .w-201{
            width: 201px;
        }
        .w-200{
            width: 200px;
        }
        .w-110{
            width: 110px;
        }
        .w-100{
            width: 100px;
        }
        br {
            display: block;
            content: "";
            padding: -5px 0;
        }

    </style>

    <div class="project-edit-pdf">
        <div class="date">
            <p class="">{{ $today->format('Y') }}年 {{ $today->format('m') }}月 {{ $today->format('d') }}日</p>
            <p class="">請求書番号　：　{{$invoiceNumber}}</p>
        </div>
        <div class="title">
            <p class="">請求書</p>
        </div>
        <div class="driver">
            <p class="">{{$name}}</p>
            <p class="f-s-13">
                {!! $subjectWithBreaks !!}
            </p>
        </div>
        <div class="company">
            <p class="company-info-txt">{!! $companyInfoWithBreaks !!}</p>
            <div class="company-stanp">
                <img class="" src="data:image/png;base64,{{ $image_data }}" alt="">
            </div>
        </div>
        <div class="amount">
            <p class="amount-txt"><span class="">ご請求金額</span><span class="amount-fee">¥{{number_format($totalRetail)}}</span></p>
        </div>
        <table class="table">
            <tr>
                <th class="table-head w-400">項目</th>
                <th class="table-head w-100">数量</th>
                <th class="table-head w-100">単価</th>
                <th class="table-head w-110">金額</th>
            </tr>
            @foreach ($item as $index => $value)
                <tr>
                    <td class="table-item w-400"><p class="table-item-txt">{{$value}}</p></td>
                    <td class="table-data w-100"><p class="table-data-txt --center">{{$number[$index]}}</p></td>
                    <td class="table-data w-100">
                        @if ($until[$index] != 0)
                            <p class="table-data-txt --right">{{number_format($until[$index])}}</p>
                        @endif
                    </td>
                    <td class="table-data w-110">
                        @if ($amount[$index] != 0)
                            <p class="table-data-txt --right">{{number_format($amount[$index])}}</p>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        <table class="mini-table">
            <tr>
                <td class="w-401"></td>
                <td class="mini-table-data w-201"><p class="mini-table-data-txt --left">小計</p></td>
                <td class="mini-table-data w-110"><p class="mini-table-data-txt --right">{{number_format($subTotalRetail)}}</p></td>
            </tr>
            <tr>
                <td class="w-401"></td>
                <td class="mini-table-data w-201"><p class="mini-table-data-txt --left">消費税(10%)</p></td>
                <td class="mini-table-data w-110"><p class="mini-table-data-txt --right">{{number_format($tax)}}</p></td>
            </tr>
            <tr>
                <td class="w-401"></td>
                <td class="mini-table-data w-201"><p class="mini-table-data-txt --left"> 合計金額(内消費税)</p></td>
                <td class="mini-table-data w-110"><p class="mini-table-data-txt --right">{{number_format($totalRetail)}}</p></td>
            </tr>
        </table>
        <table class="tax-table">
            @foreach ($taxTable01 as $index => $value)
            <tr class="tax-table__row">
                <td class="tax-table__row__data"><input type="text" value="{{$taxTable01[$index]}}" class=""></td>
                <td class="tax-table__row__data"><input type="text" value="{{number_format($taxTable02[$index])}}" class="targetAmount"></td>
                <td class="tax-table__row__data"><input type="text" value="{{$taxTable03[$index]}}" class=""></td>
                <td class="tax-table__row__data"><input type="text" value="{{number_format($taxTable04[$index])}}" class="targetAmountTax" readonly></td>
            </tr>
            @endforeach
        </table>
        <div class="bank">
            <p class="">お振込先</p>
            <div class="bank-txt-wrap">
                <p class="" style="white-space: pre-wrap;">{!! $bankNameInfoWithBreaks !!}</p>
            </div>
        </div>
    </div>


</body>
</html>
