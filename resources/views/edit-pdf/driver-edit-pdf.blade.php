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
        <div class="main__white-board --invoice-white-board">
            <?php
            $subtotal = $administrative_commission_fee + $total_lease + $total_insurance + $administrative_fee + $transfer_fee;
            $tax = $subtotal * 0.1;
            $total = $subtotal + $tax;
            ?>

            <div class="common">
                <div class="employee-date">
                    <div class="employee-date__year-month">
                        <p>{{$year}}年</p>
                        <p>{{$month}}月</p>
                    </div>
                    <div class="employee-date__employee">
                        <p class="">{{$employeeInfo->name}}</p>
                    </div>
                </div>
                <div class="common__select-area">
                    <div class="common__block">
                        <label for="">請求書番号 DD : </label>
                        <input type="text" class="c-input invoice-number-input">
                    </div>
                    <div class="common__block">
                        <label for="">所属先</label>
                        <select name="" class="c-select companySelect" id="">
                            <option value="">選択</option>
                            @foreach ($companies as $company)
                                <option
                                    data-company-name="{{$company->name}}"
                                    data-company-post-code="{{$company->post_code}}"
                                    data-company-address="{{$company->address}}"
                                    data-company-phone = "{{$company->phone}}"
                                    data-company-fax = "{{$company->fax}}"
                                    data-company-register-number = "{{$company->register_number}}"
                                    data-bank-name = "{{$company->bank_name}}"
                                    data-account-holder-name = "{{$company->account_holder_name}}"
                                    value="">{{$company->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="common__block">
                        <label for="">請求書カラー</label>
                        <select name="" class="c-select colorSelect" id="">
                            <option value="rgb(151, 255, 151)">T.N.G color</option>
                            <option value="#9966FF">H.G.L color</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- 銀行モーダル --}}
            <div class="bank-modal" id="bankModal">
                <div class="bank-modal__bg"></div>
                <div class="bank-modal__main">
                    <div class="bank-modal__main__inner">
                        <div class="switch-radio">
                            <div class="switch-radio__item">
                                <label for="1">選択</label>
                                <input id="1" type="radio" name="bankSwitch" class="bank-switch-radio" checked>
                            </div>
                            <div class="switch-radio__item">
                                <label for="2">入力</label>
                                <input id="2" type="radio" name="bankSwitch" class="bank-switch-radio">
                            </div>
                        </div>
                        <div class="bank-select bank-switch-active">
                            <select name="" id="bankSelect" class="select">
                                <option value="">選択してください</option>
                                @foreach ($banks as $index => $bank)
                                    <option value="{{$index}}" data-bank-name="{{$bank->bank_name}}" data-account-holder="{{$bank->account_holder_name}}">{{$bank->bank_name}}{{$bank->account_holder_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bank-input bank-switch-active">
                            <div class="bank-input__inner">
                                <div class="bank-input__inner__item">
                                    <label for="">銀行名</label>
                                    <input type="text" class="input bank-name getBankNameByInput">
                                </div>
                                <div class="bank-input__inner__item">
                                    <label for="">口座名義人</label>
                                    <input type="text" class="input account-holder getAccountHolderByInput">
                                </div>
                            </div>
                        </div>
                        <div class="bank-modal-btn save-btn" id="bankModalSave">
                            <p class="">保存</p>
                        </div>
                    </div>
                </div>
            </div>

        {{-- フォーム内容 --}}
            <div class="pdf-wrap">
                <div class="pdf-wrap__inner">
                    <form action="{{route('invoice.driver-issue-pdf')}}" method="POST">
                        @csrf
                        <button class="pdf-issue-btn">
                            <p class="">ダウンロード</p>
                        </button>
                        <input hidden type="text" name="pdfColor" value="rgb(151, 255, 151)" class="sentColor">
                        <div class="pdf --driver-issue">
                            <div class="pdf-main">
                                <div class="pdf-number">
                                    <p class="">NO.DD</p>
                                    <input name="driver_invoice_number" class="invoice-number --invoice-number01 --common-data" value="{{ $today->format('Y') }}{{ $today->format('m') }}{{ $today->format('d') }}" readonly>
                                </div>
                                <div class="title">
                                    <p class="">請求書</p>
                                </div>
                                <div class="line colorChangeElem"></div>
                                <div class="date"><p class="">{{ $today->format('Y') }}年 {{ $today->format('m') }}月 {{ $today->format('d') }}日</p></div>
                                {{-- 従業員情報 --}}
                                <div class="employee-info">
                                    <input type="text" hidden name="employeeId" value="{{$employeeInfo->id}}">
                                    <p class="employee-name">{{$employeeInfo->name}}</p>
                                    <div class="">
                                        〒{{$employeeInfo->post_code}} <br>
                                        {{$employeeInfo->address}} <br>
                                        登録番号　{{$employeeInfo->register_number}} <br>
                                        <div class="employee-info__bank" id="bankArea">
                                            <div class="employee-info__bank__top">
                                                <div class=""><input type="text" name="bank_name" class="input setBankName" value="{{$employeeInfo->bankAccounts->first()->bank_name}}" readonly></div>
                                            </div>
                                            <div class="employee-info__bank__under">振込先名<input type="text" name="bank_account_holder" class="input setAccountHolder" value="{{$employeeInfo->bankAccounts->first()->account_holder_name}}" readonly></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- 会社情報 --}}
                                <div class="company">
<textarea name="salaryCompanyInfo" class="textarea salary-textarea salaryCompanyTextarea" id="" cols="30" rows="10">
株式会社 T.N.G　御中
〒124-0011
東京都葛飾区四つ木2-3-11
</textarea>
                                    <br>
                                    <br>
                                    <p class="">下記の通りご請求申し上げます。</p>
                                </div>
                                {{-- 請求金額 --}}
                                <table class="request-table">
                                    <tr>
                                        <td class="request-table-data --bg-green colorChangeElem"><p class="request-table-data-txt">ご請求金額</p></td>
                                        <td class="request-table-data"><p class="request-table-data-txt allCalcTotalView">¥{{((($total_salary + $total_allowance) * 1.1) + $etc) - $total}}</p></td>
                                    </tr>
                                </table>
                                {{-- 給与詳細テーブル --}}
                                <table class="top-table salaryTopTable">
                                    <div class="plus salaryAddBtn">
                                        <span class="plus__line"></span>
                                        <span class="plus__line"></span>
                                    </div>
                                    <tr>
                                        <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">NO</p></th>
                                        <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">月日</p></th>
                                        <th class="top-table-head w-260 --bg-green colorChangeElem"><p class="top-table-head-txt">案件名</p></th>
                                        <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">高速代他</p></th>
                                        <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">実績</p></th>
                                        <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">単価</p></th>
                                        <th class="top-table-head w-100 --bg-green colorChangeElem"><p class="top-table-head-txt">金額</p></th>
                                    </tr>
                                    <tr class="salaryBasicRow">
                                        <td class="top-table-data w-70"><input type="text" name="salaryNo[]" class="input table-input"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryMonth[]" value="{{ $today->format('m') }}月度" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-260"><input type="text" name="salaryProject[]" value="外注費" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryEtc[]" value="{{$etc}}" class="input table-input changeElement etcElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryCount[]" value="1" class="input table-input changeElement salaryNum"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryUntil[]" value="{{$total_salary}}" class="input table-input changeElement salaryUnit"></td>
                                        <td class="top-table-data w-100"><input type="number" name="salaryAmount[]" value="{{$total_salary}}" class="input table-input changeElement salaryAmount"></td>
                                    </tr>
                                    <tr class="salaryBasicRow">
                                        <td class="top-table-data w-70"><input type="text" name="salaryNo[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryMonth[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-260"><input type="text" name="salaryProject[]" value="手当" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryEtc[]" class="input table-input changeElement etcElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryCount[]" class="input table-input changeElement salaryNum"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryUntil[]" class="input table-input changeElement salaryUnit"></td>
                                        <td class="top-table-data w-100"><input type="number" name="salaryAmount[]" value="{{$total_allowance}}" class="input table-input changeElement salaryAmount"></td>
                                    </tr>
                                    <tr class="salaryBasicRow">
                                        <td class="top-table-data w-70"><input type="text" name="salaryNo[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryMonth[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-260"><input type="text" name="salaryProject[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryEtc[]" class="input table-input changeElement etcElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryCount[]" class="input table-input changeElement salaryNum"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryUntil[]" class="input table-input changeElement salaryUnit"></td>
                                        <td class="top-table-data w-70"><input type="number" name="salaryAmount[]" class="input table-input changeElement salaryAmount"></td>
                                    </tr>
                                    <tr class="salaryBasicRow">
                                        <td class="top-table-data w-70"><input type="text" name="salaryNo[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryMonth[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-260"><input type="text" name="salaryProject[]" class="input table-input changeElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryEtc[]" class="input table-input changeElement etcElement"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryCount[]" class="input table-input changeElement salaryNum"></td>
                                        <td class="top-table-data w-70"><input type="text" name="salaryUntil[]" class="input table-input changeElement salaryUnit"></td>
                                        <td class="top-table-data w-70"><input type="number" name="salaryAmount[]" class="input table-input changeElement salaryAmount"></td>
                                    </tr>
                                    <tr>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-260 --bg-green colorChangeElem"><p class="top-table-data-txt --right">小計</p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --right"></p></td>
                                        <td class="top-table-data w-100"><input type="number" name="salarySubTotal" value="{{$total_salary + $total_allowance}}" class="input table-input salarySubTotal" readonly></td>
                                    </tr>
                                    <tr>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-260 --bg-green colorChangeElem"><p class="top-table-data-txt --right">消費税(10%)</p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --right"></p></td>
                                        <td class="top-table-data w-100"><input type="number" name="salaryTax" value="{{($total_salary + $total_allowance) * 0.1}}" id="salaryTax" class="input table-input" readonly></td>
                                    </tr>
                                    <tr>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-260 --bg-green colorChangeElem"><p class="top-table-data-txt --right">高速代他</p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --right"></p></td>
                                        <td class="top-table-data w-100"><input type="text" name="etcTotal" value="{{$etc}}" class="input table-input etcTotal"></td>
                                    </tr>
                                    <tr>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70 --bg-green colorChangeElem"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-260 --bg-green colorChangeElem"><p class="top-table-data-txt --right">合計金額</p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                        <td class="top-table-data w-70"><p class="top-table-data-txt --right"></p></td>
                                        <td class="top-table-data w-100"><input type="text" name="salaryTotal" value="{{(($total_salary + $total_allowance) * 1.1) + $etc}}" class="input table-input salaryTotal" readonly></td>
                                    </tr>

                                </table>

                                {{-- 費用相殺テーブル --}}
                                <div class="parent">
                                    <div class="plus salaryCostAddBtn">
                                        <span class="plus__line"></span>
                                        <span class="plus__line"></span>
                                    </div>
                                    <table class="top-table salaryCostTable">
                                        <tr>
                                            <th class="top-table-head no-border w-70"><p class="top-table-head-txt"></p></th>
                                            <th class="top-table-head no-border w-70"><p class="top-table-head-txt"></p></th>
                                            <th class="top-table-head w-330 --bg-green colorChangeElem"><p class="top-table-head-txt">差引項目</p></th>
                                            <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">実績</p></th>
                                            <th class="top-table-head w-70 --bg-green colorChangeElem"><p class="top-table-head-txt">単価</p></th>
                                            <th class="top-table-head w-100 --bg-green colorChangeElem"><p class="top-table-head-txt">金額</p></th>
                                        </tr>
                                        <tr class="salaryCostBasicRow">
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data w-330"><p class="top-table-data-txt --center f-s-10">㈱T.N.G 請求書NO.DD<input class="invoice-number --invoice-number02 --common-data" value="{{ $today->format('Y') }}{{ $today->format('m') }}{{ $today->format('d') }}" readonly>({{ $today->format('Y') }}年 {{ $today->format('m') }}月 {{ $today->format('d') }}日発行)相殺</㈱T.N.G></p></td>
                                            <td class="top-table-data w-70"><input type="text" name="getCostNum" value="1" class="input table-input changeElement costNumByDriver"></td>
                                            <td class="top-table-data w-70"><input type="text" name="getCostUntil" value="{{$total}}" class="input table-input changeElement costUnitByDriver costUnitByDriver-C"></td>
                                            <td class="top-table-data w-100"><input type="text" name="getCostAmount" value="{{$total}}" class="input table-input changeElement costTotalByDriver" readonly></td>
                                        </tr>
                                        <tr class="salaryCostBasicRow">
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data w-330"><input type="text" name="salaryCostName[]" class="input table-input changeElement"></td>
                                            <td class="top-table-data w-70"><input type="text" name="salaryCostNum[]" class="input table-input changeElement costNumByDriver"></td>
                                            <td class="top-table-data w-70"><input type="text" name="salaryCostUntil[]" class="input table-input changeElement costUnitByDriver"></td>
                                            <td class="top-table-data w-100"><input type="text" name="salaryCostAmount[]" class="input table-input changeElement costTotalByDriver"></td>
                                        </tr>
                                        <tr class="salaryCostBasicRow">
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data w-330"><input type="text" name="salaryCostName[]" class="input table-input changeElement"></td>
                                            <td class="top-table-data w-70"><input type="text" name="salaryCostNum[]" class="input table-input changeElement costNumByDriver"></td>
                                            <td class="top-table-data w-70"><input type="text" name="salaryCostUntil[]" class="input table-input changeElement costUnitByDriver"></td>
                                            <td class="top-table-data w-100"><input type="text" name="salaryCostAmount[]" class="input table-input changeElement costTotalByDriver"></td>
                                        </tr>
                                        <tr class="salaryCostBasicRow">
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data w-330"><input type="text" name="salaryCostName[]" class="input table-input changeElement"></td>
                                            <td class="top-table-data w-70"><input type="text" name="salaryCostNum[]" class="input table-input changeElement costNumByDriver"></td>
                                            <td class="top-table-data w-70"><input type="text" name="salaryCostUntil[]" class="input table-input changeElement costUnitByDriver"></td>
                                            <td class="top-table-data w-100"><input type="text" name="salaryCostAmount[]" class="input table-input changeElement costTotalByDriver"></td>
                                        </tr>
                                    </table>
                                    <table class="top-table --no-margin">
                                        <tr>
                                            <th class="top-table-head no-border w-70"><p class="top-table-head-txt"></p></th>
                                            <th class="top-table-head no-border w-70"><p class="top-table-head-txt"></p></th>
                                            <th class="top-table-head w-330 --bg-green colorChangeElem"><p class="top-table-data-txt --right">小計</p></th>
                                            <th class="top-table-head w-70"><p class="top-table-head-txt"></p></th>
                                            <th class="top-table-head w-70"><p class="top-table-head-txt"></p></th>
                                            <th class="top-table-head w-70"><input type="text" name="salaryCostTotal" value="{{$total}}" class="input table-input changeElement costAllTotal" readonly></th>
                                        </tr>
                                        <tr>
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data no-border w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data w-330 --bg-green colorChangeElem"><p class="top-table-data-txt --center f-s-10">差引合計金額</p></td>
                                            <td class="top-table-data w-70"><p class="top-table-data-txt --center"></p></td>
                                            <td class="top-table-data w-70"><p class="top-table-data-txt --right"></p></td>
                                            <td class="top-table-data w-70"><input type="text" value="{{((($total_salary + $total_allowance) * 1.1) + $etc) - $total}}" class="inputn table-input allCalcTotal" readonly></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form action="{{route('invoice.company-issue-pdf')}}" method="POST">
                        @csrf
                        <button class="pdf-issue-btn">
                            <p class="">ダウンロード</p>
                        </button>
                        <div class="pdf --company-issue">
                            <div class="pdf-main">
                                <div class="date">
                                    <p class="">{{ $today->format('Y') }}年 {{ $today->format('m') }}月 {{ $today->format('d') }}日</p>
                                    <div class="date__invoice-number">
                                        <p class="">請求書番号　：　DD</p>
                                        <input name="invoice_number" class="invoice-number --invoice-number03 --common-data" value="{{ $today->format('Y') }}{{ $today->format('m') }}{{ $today->format('d') }}" readonly>
                                    </div>
                                </div>
                                <div class="title">
                                    <p class="">請求書</p>
                                </div>
                                <div class="driver">
                                    <p class="">{{$employeeInfo->name}}　様</p>
                                    <input type="text" hidden name="employeeId" value="{{$employeeInfo->id}}">
                                    <p class="f-s-13">
                                        件名：{{ $today->format('m') }}月度の差引金額について<br>
                                        下記の通りご請求申し上げます。
                                    </p>
                                </div>
                                <div class="company">
<textarea name="costCompanyInfo" class="textarea cost-textarea costCompanyTextarea" id="" cols="30" rows="10">
株式会社T.N.G
〒124-0011
東京都葛飾区四つ木2-3-11
四つ木ハイム
TEL:03-5875-7469
FAX:03-5875-7469
登録番号: T6011801035426
</textarea>
                                    {{-- <p class="">株式会社T.N.G</p>
                                    <p class="">
                                        〒124-0011<br>
                                        東京都葛飾区四つ木2-3-11<br>
                                        四つ木ハイム
                                    </p>
                                    <p class="">TEL:03-5875-7469</p>
                                    <p class="">FAX:03-5875-7469</p>
                                    <p class="">登録番号: T6011801035426</p> --}}
                                </div>
                                <div class="amount">
                                    <p class="amount-txt"><span class="">ご請求金額</span><span class="amount-fee costTotalView">¥{{$total}}</span></p>
                                </div>
                                <div class="parent">
                                    <div class="plus costAddBtn">
                                        <span class="plus__line"></span>
                                        <span class="plus__line"></span>
                                    </div>
                                    <table class="table costTable">
                                        <tr>
                                            <th class="table-head w-400">項目</th>
                                            <th class="table-head w-100">数量</th>
                                            <th class="table-head w-100">単価</th>
                                            <th class="table-head w-110">金額</th>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="業務委託手数料(15%)" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="1" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="{{$administrative_commission_fee}}" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="{{$administrative_commission_fee}}" class="input table-input changeElement costElem"></td>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="車両リース代" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="1" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="{{$total_lease}}" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="{{$total_lease}}" class="input table-input changeElement costElem"></td>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="保険代" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="1" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="{{$total_insurance}}" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="{{$total_insurance}}" class="input table-input changeElement costElem"></td>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="事務手数料" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="1" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="{{$administrative_fee}}" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="{{$administrative_fee}}" class="input table-input changeElement costElem"></td>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="振込手数料" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="1" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="{{$transfer_fee}}" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="{{$transfer_fee}}" class="input table-input changeElement costElem"></td>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="" class="input table-input changeElement costElem"></td>
                                        </tr>
                                        <tr class="costBasicRow">
                                            <td class="table-item w-400"><input type="text" name="costItem[]" value="" class="input table-input changeElement"></td>
                                            <td class="table-data w-100"><input type="text" name="costNum[]" value="" class="input table-input changeElement costNum"></td>
                                            <td class="table-data w-100"><input type="text" name="costUntil[]" value="" class="input table-input changeElement costUnit"></td>
                                            <td class="table-data w-110"><input type="text" name="costAmount[]" value="" class="input table-input changeElement costElem"></td>
                                        </tr>
                                    </table>
                                </div>
                                <table class="mini-table">
                                    <tr>
                                        <td class="w-401"></td>
                                        <td class="mini-table-data w-201"><p class="mini-table-data-txt --center">小計</p></td>
                                        <td class="table-data w-110"><input type="text" name="costSubTotal" value="{{$subtotal}}" class="input table-input costSubTotal" readonly></td>
                                    </tr>
                                    <tr>
                                        <td class="w-401"></td>
                                        <td class="mini-table-data w-201"><p class="mini-table-data-txt --center">消費税(10%)</p></td>
                                        <td class="table-data w-110"><input type="text" name="costTax" value="{{$tax}}" class="input table-input costTaxElem" readonly></td>
                                    </tr>
                                    <tr>
                                        <td class="w-401"></td>
                                        <td class="mini-table-data w-201"><p class="mini-table-data-txt --center"> 合計金額(内消費税)</p></td>
                                        <td class="table-data w-110"><input type="text" name="costTotal" value="{{$total}}" class="input table-input costTotalElem" readonly></td>
                                    </tr>
                                </table>
                                <div class="bank">
                                    <p class="">お振込先</p>
                                    <div class="bank-txt-wrap">
                                        <textarea class="bank-textarea bankTextarea" name="bank_info" id="" cols="30" rows="10">{{$companies->first()->bank_name}}　　　{{$companies->first()->account_holder_name}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/pdf-edit.js')}}"></script>
