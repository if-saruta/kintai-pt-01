<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>PDF出力</title>
    </head>
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
        html, body, textarea {
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

        th{
            font-weight: normal;
        }

.csv{
    margin-top: 1rem;
}
.csv-head{
    position: relative;
    width: fit-content;
    display: flex;
    border-top: 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;
}

.csv-head-txt{
    /* padding: 15px 0; */
}
.csv-tbody-wrap{
    width: fit-content;
    display: flex;
    flex-direction: column;
    /* gap: 30px; */
    /* margin-left: -30px; */
    border-top: 1px solid black;
}
.csv-tbody{
    position: relative;
    display: flex;
    border: 1px solid black;
    border-top: 0px solid black;
}

.csv-txt-width{
    width: 150px;
    text-align: center;
    /* padding: 15px 0; */
    /* border: 1px solid black; */
}
.b-r{
    border-right: 1px solid black;
}
.alert{
    margin-top: 30px;
    color: red;
}
    </style>
    <body>


        {{-- <div class="main">
            <div class="">
                <p>{{$getProject->name}}</p>
                <div class="csv-head">
                    <p class="csv-txt-width csv-head-txt b-r">日付</p>
                    <p class="am csv-txt-width csv-head-txt b-r">午前</p>
                    <p class="pm csv-txt-width csv-head-txt b-r">午後</p>
                    <p class="pm csv-txt-width csv-head-txt">上代</p>
                </div>
                <div class="csv-tbody-wrap">
                    @foreach ($dates as $date)
                    <?php
                        $count = 0;
                    ?>
                    <div class="csv-tbody">
                        <p class="csv-txt-width b-r">{{$date['display']}}</p>
                        <div class="csv-txt-width b-r">
                            @foreach ($shifts as $shift)
                                @if ($shift->date == $date['compare'])
                                    @foreach ($shift->projects as $project)
                                        @if ($project->id == $getProject->id && $project->pivot->time_of_day == 0)
                                            <p class="">
                                                {{$shift->employee->name}}
                                            </p>
                                            <?php $count++;?>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        <div class="csv-txt-width b-r">
                            @foreach ($shifts as $shift)
                            @if ($shift->date == $date['compare'])
                            @foreach ($shift->projects as $project)
                            @if ($project->id == $getProject->id && $project->pivot->time_of_day == 1)
                            <p class="">
                                {{$shift->employee->name}}
                            </p>
                            <?php $count++;?>
                            @endif
                            @endforeach
                            @endif
                            @endforeach
                        </div>
                        <div class="csv-txt-width b-l">
                            <?php echo $count * $getProject->retail_price;?>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div> --}}


        <p>{{$getProject->name}}</p>
        <table border="1">
            <thead>
                <tr>
                    <th class="">日付</th>
                    <th class="">午前</th>
                    <th class="">午後</th>
                    <th class="">上代</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dates as $date)
                <?php
                    $count = 0;
                ?>
                    <tr>
                        <td>
                            <p class="">{{$date['display']}}</p>
                        </td>
                        <td>
                            @foreach ($shifts as $shift)
                                @if ($shift->date == $date['compare'])
                                    @foreach ($shift->projects as $project)
                                        @if ($project->id == $getProject->id && $project->pivot->time_of_day == 0)
                                            <p class="">
                                                {{$shift->employee->name}}
                                            </p>
                                            <?php $count++;?>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($shifts as $shift)
                            @if ($shift->date == $date['compare'])
                            @foreach ($shift->projects as $project)
                            @if ($project->id == $getProject->id && $project->pivot->time_of_day == 1)
                            <p class="">
                                {{$shift->employee->name}}
                            </p>
                            <?php $count++;?>
                            @endif
                            @endforeach
                            @endif
                            @endforeach
                        </td>
                        <td>
                            <?php echo $count * $getProject->retail_price;?>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </body>

</html>

