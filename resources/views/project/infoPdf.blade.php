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

        @font-face {
            font-family: Noto Sans JP;
            font-style: normal;
            font-weight: bold;
            src:url('{{ storage_path(' fonts/NotoSansJP-Bold.ttf')}}');
        }

        /* 全てのHTML要素に適用 */
        html, body, textarea {font-family: ipaexm;} html {line-height: 1.15; -webkit-text-size-adjust: 100%; -webkit-tap-highlight-color: transparent;} body {margin: 0;} main {display: block;} p, table, blockquote, address, pre, iframe, form, figure, dl {margin: 0;} h1, h2, h3, h4, h5, h6 {font-size: inherit; font-weight: inherit; margin: 0;} ul, ol {margin: 0; padding: 0; list-style: none;} dt {font-weight: 700;} dd {margin-left: 0;} hr {box-sizing: content-box; height: 0; overflow: visible; border-top-width: 1px; margin: 0; clear: both; color: inherit;} pre {font-family: monospace, monospace; font-size: inherit;} address {font-style: inherit;} a {background-color: transparent; text-decoration: none; color: inherit;} abbr[title] {text-decoration: underline dotted;} b, strong {font-weight: bolder;} code, kbd, samp {font-family: monospace, monospace; font-size: inherit;} small {font-size: 80%;} sub, sup {font-size: 75%; line-height: 0; position: relative; vertical-align: baseline;} sub {bottom: -0.25em;} sup {top: -0.5em;} svg, img, embed, object, iframe {vertical-align: center;} button, input, optgroup, select, textarea {-webkit-appearance: none; appearance: none; vertical-align: middle; color: inherit; font: inherit; background: transparent; padding: 0; margin: 0; border-radius: 0; text-align: inherit; text-transform: inherit;} [type="checkbox"] {-webkit-appearance: checkbox; appearance: checkbox;} [type="radio"] {-webkit-appearance: radio; appearance: radio;} button, [type="button"], [type="reset"], [type="submit"] {cursor: pointer;} button:disabled, [type="button"]:disabled, [type="reset"]:disabled, [type="submit"]:disabled {cursor: default;} :-moz-focusring {outline: auto;} select:disabled {opacity: inherit;} option {padding: 0;} fieldset {margin: 0; padding: 0; min-width: 0;} legend {padding: 0;} progress {vertical-align: baseline;} textarea {overflow: auto;} [type="number"]::-webkit-inner-spin-button, [type="number"]::-webkit-outer-spin-button {height: auto;} [type="search"] {outline-offset: -2px;} [type="search"]::-webkit-search-decoration {-webkit-appearance: none;} ::-webkit-file-upload-button {-webkit-appearance: button; font: inherit;} label[for] {cursor: pointer;} details {display: block;} summary {display: list-item;} [contenteditable]:focus {outline: auto;} table {border-color: inherit;} caption {text-align: left;} td, th {vertical-align: top; padding: 0;} th {text-align: left; font-weight: 700;} th {font-weight: normal;}

        @page {
            margin-top: 60px;
            margin-bottom: 5px;
            margin-left: 80px;
            margin-right: 80px;
        }

        table {
            position: relative;
            width: 650px;
            margin: 0 auto;
            font-size: 13px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 0.5px solid black;
            word-break: break-word;
            height: 40px;
            text-align: center;
            vertical-align: middle;
        }
        .title{
            width: fit-content;
            text-align: center;
            font-size: 20px;
        }
        .project-head{
            width: 10%;
        }
        .project-name{
            width: 40%;
        }
        .client-head{
            width: 13%;
        }
        .client-name{
            width: 37%;
        }
        .blue-bg{
            background-color: rgb(163, 199, 239);
        }
        .middle-table{
            margin-top: 10px;
        }
        .middle-table .head{
            width: 20%;
        }
        .amount-table{
            margin-top: 10px;
        }
        .amount-table .head{
            width: 15%;
        }
        .table-layout{
            width: 100%;
            display: table;
            table-layout: fixed;
        }
        .project-table-cell{
            height: 40px;
            width: 33%;
            display: table-cell;
            border: 1px solid black;
            vertical-align: middle;
        }

        .remarks-box{
            width: 650px;
            height: 170px;
            margin-top: 10px;
            border: 1px solid black;
        }
        textarea{
                width: 100%;
                height: 100%;
                padding-top: 10px;
                padding-left: 10px;
                box-sizing: content-box;
                font-size: 13px;
                line-height: 1.5;
                text-align: center;
                border: 0px;
            }

    </style>

    <p class="title">案件表(社内用)</p>

    <table>
        <tr>
            <td class="project-head blue-bg">案件名</td>
            <td class="project-name">{{ $project->name }}</td>
            <td class="client-head blue-bg" style="font-size: 11px;">クライアント名</td>
            <td class="client-name">{{ $project->client->name }}</td>
        </tr>
    </table>

    <table class="middle-table">
        <tr>
            <td class="head blue-bg">配達種類</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->delivery_type : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">着車場所</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->arrival_location : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">配達エリア</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->delivery_area : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">納品先</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->delivery_address : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">荷物の種類</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->cargo_type : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">着車時間</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->arrival_time : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">終了時間</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->finish_time : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">件数</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->count : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">稼働日</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->operation_date : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">車両</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->vehicle : null }}</td>
        </tr>
        <tr>
            <td class="head blue-bg">代引き</td>
            <td class="dd">{{ $project->projectDetail ? $project->projectDetail->cash_on_delivery : null }}</td>
        </tr>
    </table>

    <table class="amount-table">
        <colgroup>
            <col style="width: 6%;">
            <col style="width: 40%;">
        </colgroup>
        <thead>
            <tr>
                <th class="blue-bg" style="height: 30px" colspan="2">T.N.G(税別)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="head">上代</td>
                <td class="dd">{{ $financialMetrics['retail_price'] != 0 ? number_format($financialMetrics['retail_price']) : null }}</td>
            </tr>
            <tr>
                <td class="head">T.N.G Dr</td>
                <td class="dd">{{ $financialMetrics['driver_price'] != 0 ? number_format($financialMetrics['driver_price']) : null }}</td>
            </tr>
            <tr>
                <td class="head">(株)H.G.L</td>
                <td class="dd">{{ $financialMetrics['retail_price_for_hgl'] != 0 ? number_format($financialMetrics['retail_price_for_hgl']) : null }}</td>
            </tr>
            <tr>
                <td rowspan="2" class="head">頭抜き</td>
                <td>
                    <div class="table-layout">
                        <div class="project-table-cell blue-bg" style="width: 20%;font-size: 10px;">(株)T.N.G Dr</div>
                        <div class="project-table-cell">{{ $financialMetrics['tng_head'] != 0 ? number_format($financialMetrics['tng_head']) : null }}</div>
                        <div class="project-table-cell blue-bg" style="width: 20%;font-size: 10px;">利益率</div>
                        <div class="project-table-cell">{{ $financialMetrics['profit_rate_tng'] != 0 ? round($financialMetrics['profit_rate_tng'] * 100).'%' : null }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="table-layout">
                        <div class="project-table-cell blue-bg" style="width: 20%;font-size: 10px;">(株)H.G.L</div>
                        <div class="project-table-cell">{{ $financialMetrics['hgl_head'] != 0 ? number_format($financialMetrics['hgl_head']) : null }}</div>
                        <div class="project-table-cell blue-bg" style="width: 20%;font-size: 10px;">利益率</div>
                        <div class="project-table-cell">{{ $financialMetrics['profit_rate_hgl'] != 0 ? round($financialMetrics['profit_rate_hgl'] * 100).'%' : null }}</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="remarks-box">
        <textarea name="" id="" cols="30" rows="10" readonly>{{ $project->projectDetail ? $project->projectDetail->notes : null }}</textarea>
    </div>

</body>
</html>
