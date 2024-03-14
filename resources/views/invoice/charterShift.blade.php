<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <main class="main --shift-main">
        <div class="main__link-block --shift-link-block">
            <div class="main__link-block__tags">
                <a href="{{route('invoice.driverShift')}}" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.driverShift','invoice.searchShift','invoice.driver-edit-pdf') ? 'active' : '' }} link">
                        <span class="">ドライバー</span>
                    </button>
                </a>
                <a href="{{route('invoice.projectShift')}}" class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button
                        class="{{ request()->routeIs('invoice.projectShift','invoice.searchProjectShift', 'invoice.project-edit-pdf') ? 'active' : '' }} link">
                        <span class="">案件</span>
                    </button>
                </a>
                <a href="{{route('invoice.charterShift')}}"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <button class="{{ request()->routeIs('invoice.charterShift','invoice.findCharterShift') ? 'active' : '' }} link">
                        <span class="">チャーター</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="main__white-board --charter-board">
            <div class="invoice">
                <div class="invoice__charter">
                    <form action="{{ route('invoice.searchCharterShift') }}" method="POST">
                        @csrf
                        <div class="select-area">
                            <div class="c-select-area__block">
                                <select name="year" id="" class="c-select year-select" required>
                                    <option value="">----</option>
                                    @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                        @if ($year == $getYear)
                                            <option selected value="{{ $year }}">{{ $year }}</option>
                                        @else
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <label for="">年</label>
                            </div>
                            <div class="c-select-area__block month-block">
                                <select name="month" id="" class="c-select month-select" required>
                                    <option value="">----</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        @if ($i == $getMonth)
                                            <option selected value="{{ $i }}">{{ $i }}</option>
                                        @else
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <label for="">月</label>
                            </div>
                            <button class="search-btn">
                                <p>表示する</p>
                            </button>
                        </div>
                    </form>

                    <div class="">
                        @if(!empty($shiftArray) || $unregisterProjectShift)
                        <form action="{{route('invoice.charter-calendar-pdf')}}" method="POST">
                            @csrf
                            <input hidden type="text" value="{{$getYear}}" name="year">
                            <input hidden type="text" value="{{$getMonth}}" name="month">

                            <button class="calendar-pdf-button">
                                <p>ダウンロード</p>
                            </button>
                        </form>
                        <form action="{{route('invoice.charter-shift-update')}}" method="POST">
                            @csrf
                            <input hidden type="text" value="{{$getYear}}" name="year">
                            <input hidden type="text" value="{{$getMonth}}" name="month">

                            <div class="charter-calender-wrap">
                                <button class="save-btn">
                                    <p class="">保存</p>
                                </button>
                                <div class="charter-calender-wrap__scroll">
                                    <div class="charter-calender-wrap__charter-calender">
                                        <div class="head">
                                            <div class="head__clm --date">
                                                <p class="">日付</p>
                                            </div>
                                            <div class="head__clm --project">
                                                <p class="">案件名</p>
                                            </div>
                                            <div class="head__clm --common-amount">
                                                <p class="">配送料金</p>
                                            </div>
                                            <div class="head__clm --common-amount">
                                                <p class="">高速料金</p>
                                            </div>
                                            <div class="head__clm --common-amount">
                                                <p class="">駐車料金</p>
                                            </div>
                                            <div class="head__clm --common">
                                                <p class="">ドライバー</p>
                                            </div>
                                            <div class="head__clm --common-amount">
                                                <p class="">ドライバー<br>価格</p>
                                            </div>
                                            <div class="head__clm --common">
                                                <p class="">クライアント名</p>
                                            </div>
                                        </div>
                                        <div class="data">
                                            @foreach ($shiftArray as $data)
                                                <div class="data__row shiftRow">
                                                    <div class="data__row__clm --date">
                                                        @foreach ($dates as $date)
                                                            @if ($date->format('Y-m-d') == $data['shift']['date'])
                                                                <p class="">{{ $date->format('n月j日') }}({{ $date->isoFormat('ddd') }})</p>
                                                            @endif
                                                        @endforeach
                                                        {{-- <p class="">{{$data['shift']['date']}}</p> --}}
                                                    </div>
                                                    <div class="data__row__clm --project projectNameBox register">
                                                        @if ($data['initial_project_name'] != null)
                                                            <input type="text" value="{{$data['initial_project_name']}}" class="input charter-input" readonly>
                                                        @else
                                                            <input type="text" value="{{$data['project']['name']}}" class="input charter-input" readonly>
                                                        @endif
                                                        {{-- モーダルに値を渡す --}}
                                                        <input hidden type="text" value="{{$data['id']}}" class="setId">
                                                        @foreach ($dates as $date)
                                                            @if ($date->format('Y-m-d') == $data['shift']['date'])
                                                                <input hidden type="text" value="{{ $date->format('Y') }}" class="setYear">
                                                                <input hidden type="text" value="{{ $date->format('n') }}" class="setMonth">
                                                                <input hidden type="text" value="{{ $date->format('j') }}" class="setDay">
                                                            @endif
                                                        @endforeach
                                                        <input hidden type="text" value="{{ $data['project']['name'] }}" class="setProjectName">
                                                        @if (isset($data['shift']['employee']['name']))
                                                            <input type="text" hidden value="{{$data['shift']['employee']['name']}}"  class="setEmployeeName">
                                                        @else
                                                            <input type="text" hidden value="{{$data['shift']['unregistered_employee']}}" class="setEmployeeName">
                                                        @endif

                                                    </div>
                                                    {{-- 上代 --}}
                                                    <div class="data__row__clm --common-amount --text-right">
                                                        <input type="text" name="retail_price[{{$data['id']}}]" value="{{ number_format($data['retail_price']) }}" class="input charter-input commaInput">
                                                    </div>
                                                    {{-- 高速代 --}}
                                                    <div class="data__row__clm --common-amount --text-right">
                                                        <input type="text" name="expressway_fee[{{$data['id']}}]" value="{{ number_format($data['expressway_fee']) }}" class="input charter-input commaInput">
                                                    </div>
                                                    {{-- 駐車台 --}}
                                                    <div class="data__row__clm --common-amount --text-right">
                                                        <input type="text" name="parking_fee[{{$data['id']}}]" value="{{ number_format($data['parking_fee']) }}" class="input charter-input commaInput">
                                                    </div>
                                                    {{-- 従業員 --}}
                                                    <div class="data__row__clm --common --text-center driverNameBox">
                                                        @if (isset($data['shift']['employee']['name']))
                                                            <input type="text" value="{{$data['shift']['employee']['name']}}" class="input charter-input" readonly>
                                                        @else
                                                            <input type="text" value="{{$data['shift']['unregistered_employee']}}" class="input charter-input" readonly>
                                                        @endif

                                                        {{-- モーダルに値を渡す --}}
                                                        <input hidden type="text" value="{{$data['id']}}" class="setId">
                                                        @foreach ($dates as $date)
                                                            @if ($date->format('Y-m-d') == $data['shift']['date'])
                                                                <input hidden type="text" value="{{ $date->format('Y') }}" class="setYear">
                                                                <input hidden type="text" value="{{ $date->format('n') }}" class="setMonth">
                                                                <input hidden type="text" value="{{ $date->format('j') }}" class="setDay">
                                                            @endif
                                                        @endforeach
                                                        @if (isset($data['shift']['employee']['name']))
                                                            <input type="text" hidden value="{{$data['shift']['employee']['name']}}"  class="setEmployeeName">
                                                        @else
                                                            <input type="text" hidden value="{{$data['shift']['unregistered_employee']}}" class="setEmployeeName">
                                                        @endif

                                                    </div>
                                                    {{-- ドライバー価格 --}}
                                                    <div class="data__row__clm --common-amount --text-right">
                                                        <input type="text" name="driver_price[{{$data['id']}}]" value="{{ number_format($data['driver_price']) }}" class="input charter-input commaInput">
                                                    </div>
                                                    {{-- クライアント名 --}}
                                                    <div class="data__row__clm --common --text-center">
                                                        <input type="text" value="{{$data['project']['client']['name']}}" class="input charter-input" readonly>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="data --unregister-data">
                                            <div class="unregister-message">
                                                <p class="">以下は未登録案件です　※以下のリストはPDFに反映されません</p>
                                            </div>
                                            @foreach ($unregisterProjectShift as $spv)
                                                @if ($spv->unregistered_project != '休み')
                                                    <div class="data__row">
                                                        <div class="data__row__clm --date">
                                                            @foreach ($dates as $date)
                                                                @if ($date->format('Y-m-d') == $spv->shift->date)
                                                                    <p class="">{{ $date->format('n月j日') }}({{ $date->isoFormat('ddd') }})</p>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="data__row__clm projectNameBox unregister --project">
                                                            <input type="text" name="unregistered_project[{{$spv->id}}]" value="{{ $spv->unregistered_project }}" class="input charter-input" readonly>

                                                            {{-- モーダルに値を渡す --}}
                                                            <input hidden type="text" value="{{$spv->id}}" class="setId">
                                                            @foreach ($dates as $date)
                                                                @if ($date->format('Y-m-d') == $spv->shift->date)
                                                                    <input hidden type="text" value="{{ $date->format('Y') }}" class="setYear">
                                                                    <input hidden type="text" value="{{ $date->format('n') }}" class="setMonth">
                                                                    <input hidden type="text" value="{{ $date->format('j') }}" class="setDay">
                                                                @endif
                                                            @endforeach
                                                            <input hidden type="text" value="{{ $spv->unregistered_project }}" class="setProjectName">
                                                            @if ($spv->shift->employee)
                                                                <input type="text" hidden value="{{$spv->shift->employee->name}}"  class="setEmployeeName">
                                                            @else
                                                                <input type="text" hidden value="{{$spv->shift->unregistered_employee}}" class="setEmployeeName">
                                                            @endif

                                                        </div>
                                                        {{-- 上代 --}}
                                                        <div class="data__row__clm --common-amount --text-right">
                                                            <input type="text" name="retail_price[{{$spv->id}}]" value="{{ number_format($spv->retail_price) }}" class="input charter-input commaInput">
                                                        </div>
                                                        {{-- 高速代 --}}
                                                        <div class="data__row__clm --common-amount --text-right">
                                                            <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{ number_format($spv->expressway_fee) }}" class="input charter-input commaInput">
                                                        </div>
                                                        {{-- 駐車台 --}}
                                                        <div class="data__row__clm --common-amount --text-right">
                                                            <input type="text" name="parking_fee[{{$spv->id}}]" value="{{ number_format($spv->parking_fee) }}" class="input charter-input commaInput">
                                                        </div>
                                                        {{-- 従業員 --}}
                                                        <div class="data__row__clm --common --text-center driverNameBox">
                                                            @if ($spv->shift->employee)
                                                            <input type="text" value="{{ $spv->shift->employee->name }}" class="input charter-input" readonly>
                                                            @else
                                                            <input type="text" value="{{ $spv->shift->unregistered_employee }}" class="input charter-input" readonly>
                                                            @endif

                                                            {{-- モーダルに値を渡す --}}
                                                            <input hidden type="text" value="{{$spv->id}}" class="setId">
                                                            @foreach ($dates as $date)
                                                                @if ($date->format('Y-m-d') == $spv->shift->date)
                                                                    <input hidden type="text" value="{{ $date->format('Y') }}" class="setYear">
                                                                    <input hidden type="text" value="{{ $date->format('n') }}" class="setMonth">
                                                                    <input hidden type="text" value="{{ $date->format('j') }}" class="setDay">
                                                                @endif
                                                            @endforeach
                                                            @if ($spv->shift->employee)
                                                                <input type="text" hidden value="{{ $spv->shift->employee->name }}"  class="setEmployeeName">
                                                            @else
                                                                <input type="text" hidden value="{{$spv->shift->unregistered_employee}}" class="setEmployeeName">
                                                            @endif

                                                        </div>
                                                        {{-- ドライバー価格 --}}
                                                        <div class="data__row__clm --common-amount --text-right">
                                                            <input type="text" name="driver_price[{{$spv->id}}]" value="{{ number_format($spv->driver_price) }}" class="input charter-input commaInput">
                                                        </div>
                                                        <div class="data__row__clm --common clientElem --text-center">
                                                            <input hidden type="text" value="{{$spv->id}}" class="input charter-input">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- モーダル --}}
                        <form action="{{route('invoice.charter-client-update')}}" method="POST">
                            @csrf
                            <div class="client-modal clientModal">
                                <span class="client-modal__bg closeElem"></span>
                                <div class="client-modal__main">
                                    <div class="client-modal__main__inner">
                                        <div class="checkbox-area">
                                            <div class="checkbox-area__block">
                                                <label for="check_01">既存</label>
                                                <input checked id="check_01" type="radio" name="client_switch" value="0" class="witchRadio">
                                            </div>
                                            <div class="checkbox-area__block">
                                                <label for="check_02">新規</label>
                                                <input id="check_02" type="radio" name="client_switch" value="1" class="witchRadio">
                                            </div>
                                        </div>
                                        <input hidden type="text" name="shift_id" class="setShiftIdElem">
                                        <input hidden type="text" value="{{$getYear}}" name="year">
                                        <input hidden type="text" value="{{$getMonth}}" name="month">
                                        <div class="action-area actionElem">
                                            <select name="clientId" id="" class="select">
                                                <option value="">選択してください</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="action-area input-area actionElem">
                                            <div class="input-area__item">
                                                <label for="">クライアント名(ID)</label>
                                                <input type="text" class="input" name="clientName">
                                            </div>
                                            <div class="input-area__item">
                                                <label for="">クライアント名(PDF使用時)</label>
                                                <input type="text" class="input" name="clientPdfName">
                                            </div>
                                        </div>
                                        <button class="save-btn client-modal-btn" onclick='return confirm("本当に保存しますか？")'>
                                            <p class="">保存</p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- 案件モーダル --}}
                        <div class="shift-delete-modal shiftProjectModal">
                            <div class="shift-delete-modal__bg shiftProjectModalClose"></div>
                            <div class="shift-delete-modal__white-board">
                                <form action="{{ route('invoice.charter-project-update') }}" method="POST">
                                    @csrf
                                    {{-- リダイレクト --}}
                                    <input hidden type="text" value="{{$getYear}}" name="year">
                                    <input hidden type="text" value="{{$getMonth}}" name="month">

                                    <input hidden type="text" name="shiftPvId" class="setShiftPvId">

                                    <div class="shift-delete-modal__white-board__inner">
                                        <p class="title">案件情報</p>
                                        <div class="shift-info-wrap">
                                            <div class="shift-info-box">
                                                <p class="head">日付 : </p>
                                                <p class=""><span class="year">2024</span>年<span class="month">2</span>月<span class="day">12</span>日</p>
                                            </div>
                                            <div class="shift-info-box modalProjectShow">
                                                <p class="head">案件名 : </p>
                                                <p class="projectName">admin案件</p>
                                            </div>
                                            <div class="select-project modalSelectProject">
                                                <p class="head">案件 : </p>
                                                <div class="select-project__right-box">
                                                    <div class="select-project__radio-wrap">
                                                        <div class="item">
                                                            <input type="radio" name="projectRadio" value="0" class="radio projectRadio">
                                                            <label for="">既存案件</label>
                                                        </div>
                                                        <div class="item">
                                                            <input checked type="radio" name="projectRadio" value="1" class="radio projectRadio">
                                                            <label for="">案件入力</label>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="unProject" class="c-input inputProject">
                                                    <select name="projectId" id="" class="c-select selectProject">
                                                        @foreach ($projectsByCharter as $project)
                                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="shift-info-box">
                                                <p class="head">ドライバー : </p>
                                                <p class="driverName">山田　太郎</p>
                                            </div>
                                        </div>
                                        <div class="button-wrap">
                                            <button name="action" value="update" class="btn --save saveBtn" onclick='return confirm("本当に登録しますか？")'>
                                                変更する
                                            </button>
                                            <button name="action" value="delete" class="btn --delete" onclick='return confirm("本当に削除しますか？")'>
                                                削除する
                                            </button>
                                            <div class="btn --back shiftProjectModalClose">
                                                戻る
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- ドライバーモーダル --}}
                        <div class="shift-driver-modal shiftDriverModal">
                            <div class="shift-driver-modal__bg shiftDriverModalClose"></div>
                            <div class="shift-driver-modal__white-board">
                                <form action="{{ route('invoice.charter-driver-update') }}" method="POST">
                                    @csrf
                                    {{-- リダイレクト --}}
                                    <input hidden type="text" value="{{$getYear}}" name="year">
                                    <input hidden type="text" value="{{$getMonth}}" name="month">

                                    <input hidden type="text" name="shiftPvId" class="setShiftPvId">

                                    <div class="shift-driver-modal__white-board__inner">
                                        <p class="title">ドライバー情報</p>
                                        <div class="shift-info-wrap">
                                            <div class="shift-info-box">
                                                <p class="head">日付 : </p>
                                                <p class=""><span class="year">2024</span>年<span class="month">2</span>月<span class="day">12</span>日</p>
                                            </div>
                                            <div class="shift-info-box">
                                                <p class="head">ドライバー : </p>
                                                <select name="employeeId" id="" class="c-select employeeSelect">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="button-wrap">
                                            <button name="action" value="update" class="btn --save saveBtn" onclick='return confirm("本当に登録しますか？")'>
                                                変更する
                                            </button>
                                            <div class="btn --back shiftDriverModalClose">
                                                戻る
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if ($warning !== null)
                        <p class="warning-txt">{{$warning}}</p>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </main>



</x-app-layout>

{{-- script --}}
<script src="{{asset('js/invoice-charter.js')}}"></script>
