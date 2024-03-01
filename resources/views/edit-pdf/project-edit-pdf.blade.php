<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    {{-- <script>
        window.onbeforeunload = function(e) {
            e.preventDefault();
            return '';
        };

    </script> --}}

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
        <div class="main__white-board --project-pdf-edit-board">
            <form action="{{route('invoice.project-issue-pdf')}}" method="POST">
                @csrf
                {{-- 隠しフィールド --}}
                <input hidden type="text" name="clientId" value="{{ $clientId }}">
                <div class="domp-btn-wrap">
                    <button class="pdf-issue-btn project-pdf-issue-btn domp-btn">
                        <p class="">ダウンロード</p>
                    </button>
                </div>
                <a href="{{route('invoice.projectShift')}}" class="btn --back --pdf-back" onclick='return confirm("入力したデータは失われます。")'>
                    戻る
                </a>
                <div class="project-edit-pdf">
                    <div class="project-edit-pdf__inner">
                        <div class="date">
                            <p class="">{{ $today->format('Y') }}年 {{ $today->format('m') }}月 {{ $today->format('d') }}日</p>
                            <p class="date__invoice-number">請求書番号 : <input type="text" name="invoice_number" class="input" value="{{ $today->format('Y') }}{{ $today->format('m') }}{{ $today->format('d') }}"></p>
                        </div>
                        <div class="title">
                            <p class="">請求書</p>
                        </div>
                        <div class="driver">
                            <p class="driver__name"><input type="text" name="name" value="{{$getClient->pdfName}} 様" class="input"></p>
                            <p class="f-s-13 driver__subject">
    <textarea name="subject" id="" cols="30" rows="10">
    件名：{{ $today->format('n') }}月度の差引金額について
    下記の通りご請求申し上げます。
    </textarea>
                            </p>
                        </div>
                        <div class="company">
    <textarea name="company_info" id="" cols="30" rows="10">
    株式会社T.N.G

    〒124-0011
    東京都葛飾区四つ木2-3-11
    四つ木ハイム

    TEL:03-5875-7469
    FAX:03-5875-7469
    登録番号: T6011801035426
    </textarea>
    <div class="company__stanp">
        <img class="" src="{{ asset('img/signature-stamp.png') }}" alt="">
    </div>
                        </div>
                        <div class="amount">
                            <p class="amount-txt"><span class="">ご請求金額</span><span class="amount-fee">¥{{ceil($total_retail + ($total_retail * 0.1))}}</span></p>
                        </div>
                        <div class="table-wrap">
                            <div class="plus project-edit-plus-btn">
                                <span class="plus__line"></span>
                                <span class="plus__line"></span>
                            </div>
                            <table class="table">
                                <tr>
                                    <th class="table-head w-500">品番・品名</th>
                                    <th class="table-head w-100">数量</th>
                                    <th class="table-head w-100">単価</th>
                                    <th class="table-head w-110">金額</th>
                                </tr>
                                @foreach ($projectData as $projectName => $data)
                                <tr>
                                    <td class="table-item w-500"><input type="text" name="item[]" value="{{ $projectName }} ({{ $data['dates'] }})" class="input project-table-input changeElem project-edit-pdf-text-left"></td>
                                    <td class="table-data w-100"><input type="number" name="number[]" value="{{ $data['count'] }}" class="input project-table-input changeElem cntElem project-edit-pdf-text-right"></td>
                                    <td class="table-data w-100"><input type="number" name="until[]" value="{{ $data['unit_price'] }}" class="input project-table-input changeElem untilElem project-edit-pdf-text-right"></td>
                                    <td class="table-data w-110"><input type="number" name="amount[]" value="{{ $data['total_price'] }}" class="input project-table-input changeElem amountElem project-edit-pdf-text-right"></td>
                                </tr>
                                @endforeach
                                @foreach ($expresswayData as $projectName => $data )
                                    <tr>
                                        <td class="table-item w-500"><input type="text" name="item[]" value="{{ $projectName }} 高速代 ({{ $data['dates'] }})" class="input project-table-input changeElem project-edit-pdf-text-left"></td>
                                        <td class="table-data w-100"><input type="number" name="number[]" value="{{ $data['expressway_count'] }}" class="input project-table-input changeElem cntElem project-edit-pdf-text-right"></td>
                                        <td class="table-data w-100"><input type="number" name="until[]" value="{{ $data['expressway_unit_price'] }}" class="input project-table-input changeElem untilElem project-edit-pdf-text-right"></td>
                                        <td class="table-data w-110"><input type="number" name="amount[]" value="{{ $data['total_expressway_fee'] }}" class="input project-table-input changeElem amountElem project-edit-pdf-text-right"></td>
                                    </tr>
                                @endforeach
                                @foreach ($parkingData as $projectName => $data)
                                    <tr>
                                        <td class="table-item w-500"><input type="text" name="item[]" value="{{ $projectName }} パーキング代 ({{ $data['dates'] }})" class="input project-table-input changeElem project-edit-pdf-text-left"></td>
                                        <td class="table-data w-100"><input type="number" name="number[]" value="{{ $data['parking_count'] }}" class="input project-table-input changeElem cntElem project-edit-pdf-text-right"></td>
                                        <td class="table-data w-100"><input type="number" name="until[]" value="{{ $data['parking_unit_price'] }}" class="input project-table-input changeElem untilElem project-edit-pdf-text-right"></td>
                                        <td class="table-data w-110"><input type="number" name="amount[]" value="{{ $data['total_parking_fee'] }}" class="input project-table-input changeElem amountElem project-edit-pdf-text-right"></td>
                                    </tr>
                                @endforeach
                                <tr class="tableRow">
                                    <td class="table-item w-500"><input type="text" name="item[]" value="" class="input project-table-input changeElem project-edit-pdf-text-left"></td>
                                    <td class="table-data w-100"><input type="number" name="number[]" value="" class="input project-table-input changeElem cntElem"></td>
                                    <td class="table-data w-100"><input type="number" name="until[]" value="" class="input project-table-input changeElem untilElem"></td>
                                    <td class="table-data w-110"><input type="number" name="amount[]" value="" class="input project-table-input changeElem amountElem"></td>
                                </tr>
                            </table>
                        </div>
                        <table class="mini-table">
                            <tr>
                                <td class="w-500 border-none"></td>
                                <td class="mini-table-data w-201"><p class="mini-table-data-txt project-edit-pdf-text-left">小計</p></td>
                                <td class="mini-table-data w-110"><input type="text" name="sub_total_retail" value="{{$total_retail}}" class="input project-table-input amountSubTotal project-edit-pdf-text-right"></td>
                            </tr>
                            <tr>
                                <td class="w-500 border-none"></td>
                                <td class="mini-table-data w-201"><p class="mini-table-data-txt project-edit-pdf-text-left">消費税(10%)</p></td>
                                <td class="mini-table-data w-110"><input type="text" name="tax" value="{{ceil($total_retail * 0.1)}}" class="input project-table-input taxElem project-edit-pdf-text-right"></td>
                            </tr>
                            <tr>
                                <td class="w-500 border-none"></td>
                                <td class="mini-table-data w-201"><p class="mini-table-data-txt project-edit-pdf-text-left"> 合計金額(内消費税)</p></td>
                                <td class="mini-table-data w-110"><input type="text" name="total_retail" value="{{ceil($total_retail + ($total_retail * 0.1))}}" class="input project-table-input totalElem project-edit-pdf-text-right"></td>
                            </tr>
                        </table>
                        <table class="tax-table">
                            <tr class="tax-table__row">
                                <td class="tax-table__row__data"><input type="text" name="tax_table01[]" value="10%対象" class=""></td>
                                <td class="tax-table__row__data"><input type="text" name="tax_table02[]" class="targetAmount"></td>
                                <td class="tax-table__row__data"><input type="text" name="tax_table03[]" value="消費税" class=""></td>
                                <td class="tax-table__row__data"><input type="text" name="tax_table04[]" class="targetAmountTax" readonly></td>
                            </tr>
                            <tr class="tax-table__row">
                                <td class="tax-table__row__data"><input type="text" name="tax_table01[]" value="対象外" class=""></td>
                                <td class="tax-table__row__data"><input type="text" name="tax_table02[]" class="notTargetAmount" readonly></td>
                                <td class="tax-table__row__data"><input type="text" name="tax_table03[]" value="消費税" class=""></td>
                                <td class="tax-table__row__data"><input type="text" name="tax_table04[]" class="" value="" readonly></td>
                            </tr>
                        </table>
                        <div class="bank">
                            <p class="">お振込先</p>
                            <div class="bank-txt-wrap">
                                <textarea name="bank_name" id="" class="bank-textarea" cols="30" rows="10">{{$getCompanies->first()->bank_name}}         {{$getCompanies->first()->account_holder_name}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/project-pdf-edit.js')}}"></script>
