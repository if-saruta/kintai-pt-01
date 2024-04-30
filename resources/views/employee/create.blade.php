<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員作成') }}
        </h2>
    </x-slot>

    {{-- <script>
        window.onbeforeunload = function(e) {
            e.preventDefault();
            return '';
        };

    </script> --}}

    <main class="main">
        <form action="{{ route('employee.store')}}" method="POST" class="main__white-board --employee-white-board" id="form">
            @csrf
            <div class="employee-name-box">
                <div class="employee-name-box__inner">
                    <div class="employee-area">
                        <p class="employee-area__head">ドライバー名</p>
                        <input type="text" name="name" class="c-input" placeholder="山田　太郎">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                            tttt
                          </span>
                        @endif
                    </div>
                    {{-- ボタン --}}
                    <div class="btn-area">
                        <button class="btn --save" type="submit" name="action" value="save">
                            入力内容を登録
                        </button>
                        {{-- <button class="btn --delete" type="submit" name="action" value="delete"
                            onclick='return confirm("本当に削除しますか?")'>
                            所属先を削除
                        </button> --}}
                        <a href="{{route('employee.')}}" class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                            戻る
                        </a>
                    </div>
                </div>
            </div>
            <div class="employee-input-main">
                <div class="employee-initial">
                    <div class="head">
                        <p class="input-head">イニシャル</p>
                        <p class="required">必須</p>
                    </div>
                    <input class="c-input" name="initial" placeholder="YT" required>
                </div>
                <div class="company">
                    <div class="head">
                        <p class="input-head">所属先</p>
                        <p class="required">必須</p>
                    </div>
                    <select name="company" id="" class="c-select" required>
                        <option value="">選択してください</option>
                            @foreach ($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                    </select>
                </div>
                <div class="address-area">
                    <div class="address-area__input-box --post-code">
                        <div class="head">
                            <p class="input-head">郵便番号</p>
                            <p class="required">必須</p>
                        </div>
                        <input type="text" name="post_code" class="c-input" placeholder="000-0000" required>
                    </div>
                    <div class="address-area__input-box --address">
                        <div class="head">
                            <p class="input-head">住所</p>
                            <p class="required">必須</p>
                        </div>
                        <input type="text" name="address" class="c-input" placeholder="東京都渋谷区道玄坂0-0-0" required>
                    </div>
                    <div class="address-area__input-box --address">
                        <div class="head">
                            <p class="input-head">建物名</p>
                            <p class="required">任意</p>
                        </div>
                        <input type="text" name="building_name" class="c-input" placeholder="建物名など">
                    </div>
                </div>
                <div class="phone-area">
                    <div class="phone-area__input-box">
                        <div class="head">
                            <p class="input-head">電話番号</p>
                            <p class="required">必須</p>
                        </div>
                        <input type="text" name="phone" class="c-input" placeholder="000-0000-0000">
                    </div>
                </div>
                <div class="employment-status">
                    <div class="head">
                        <p class="input-head">雇用形態</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="check-area">
                        <div class="check-area__item">
                            <input type="radio" checked name="status" value="正社員">
                            <label for="">正社員</label>
                        </div>
                        <div class="check-area__item">
                            <input type="radio" name="status" value="個人事業主" >
                            <label for="">個人事業主</label>
                        </div>
                        <div class="check-area__item">
                            <input type="radio" name="status" value="アルバイト">
                            <label for="">アルバイト</label>
                        </div>
                    </div>
                </div>
                <div class="bank-info">
                    <div class="head">
                        <p class="input-head">口座情報</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="bank-info__input-area">
                        <div class="item">
                            <p class="">銀行名</p>
                            <input type="text" name="bank_name" class="c-input" placeholder="東京銀行　新宿支店　(普) 000000000" required>
                        </div>
                        <div class="item">
                            <p class="">口座名義人</p>
                            <input type="text" name="account_holder_name" class="c-input" placeholder="ヤマダ タロウ" required>
                        </div>
                    </div>
                </div>
                <div class="invoice-check">
                    <div class="head">
                        <p class="input-head">インボイス登録</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="check-area">
                        <div class="check-area__item">
                            <input type="radio" name="invoice" checked value="1" class="invoiceRadio">
                            <label for="">登録</label>
                        </div>
                        <div class="check-area__item">
                            <input type="radio" name="invoice" value="0" class="invoiceRadio">
                            <label for="">未登録</label>
                        </div>
                    </div>
                </div>
                <div class="register-number">
                    <div class="head">
                        <p class="input-head">登録番号</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="register-input-wrap registerInputWrap">
                        <input type="text" name="register_number" class="c-input register-input" placeholder="T00000000">
                    </div>
                </div>
                <div class="rental">
                    <div class="head">
                        <p class="input-head">車両</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="select-area">
                        <div class="select-area__item">
                            <p class="">貸出形態</p>
                            <select name="rental_type" id="observeSelect" class="c-select" required>
                                <option value="0">自車</option>
                                <option value="1">月リース</option>
                                <option value="2">なんでも月リース</option>
                                <option value="3">日割り</option>
                            </select>
                        </div>
                        <div class="select-area__item --vehicle-item">
                            <div class="select-area__item__head">
                                <p class="">貸出車両</p>
                                <p class="" id="vehicleWarningTxt" style="color:red;"></p>
                            </div>
                            <div class="" id="controlSelect">
                                <select name="vehicle" id="vehicleSelect" class="c-select">
                                    <option value="">選択してください</option>
                                    @foreach ($vehicles as $vehicle)
                                    <option value="{{$vehicle->id}}">{{ $vehicle->place_name }} {{ $vehicle->class_number }} {{ $vehicle->hiragana }} {{$vehicle->number}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="remarks-area">
                    <div class="head">
                        <p class="">備考欄</p>
                    </div>
                    <textarea name="remarks" id="" cols="30" rows="10" class="c-textarea"></textarea>
                </div>
                <div class="info-by-project">
                    <p class="input-head">案件別情報</p>
                    <div class="info-by-project__list" id="parentProjectAllowance">
                        @foreach ($projects as $project)
                            <div class="list-item">
                                <div class="list-item__project-name">
                                    <p class="">{{$project->name}}</p>
                                </div>
                                <div class="list-item__input-area">
                                    <div class="salary">
                                        <p class="">ドライバー価格</p>
                                        @if ($project->payment_type == 1)
                                        <input type="text" name="employeePrice[{{$project->id}}]" class="c-input commaInput" placeholder="1,000">
                                        @else
                                        <div class="read-only">
                                            <input type="text" class="c-input" placeholder="1,000" readonly>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="allowance projectAllowanceContainer">
                                        <div class="allowance-item projectAllowanceItem">
                                            <div class="allowance__name">
                                                <p class="">手当名</p>
                                                <input type="text" name="allowanceName[{{$project->id}}][]" class="c-input" placeholder="リーダー手当">
                                            </div>
                                            <div class="allowance__amount">
                                                <p class="">手当金額</p>
                                                <input type="text" name="allowanceAmount[{{$project->id}}][]" class="c-input commaInput" placeholder="1,000">
                                            </div>
                                            <i class="fa-solid fa-circle-minus delete-circle projectAllowanecDelete"></i>
                                        </div>
                                        <i class="fa-solid fa-circle-plus plus-circle projectAllowanceAdd" data-project-id="{{$project->id}}"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="other-allowance">
                    <p class="input-head">その他手当</p>
                    <div class="other-allowance__input-area" id="otherAllowanceContainer">
                        <div class="input-item-box">
                            <div class="input-item allowance-name">
                                <p class="">手当名</p>
                                <input type="text" name="allowanceOtherName[]" class="c-input" placeholder="リーダー手当">
                            </div>
                            <div class="input-item allowance-amount">
                                <p class="">手当金額</p>
                                <input type="text" name="allowanceOtherAmount[]" class="c-input commaInput" placeholder="1,000">
                            </div>
                            <i class="fa-solid fa-circle-minus delete-circle otherAllowanceDelete"></i>
                        </div>
                        <i class="fa-solid fa-circle-plus plus-circle" id="otherAllowanceAdd"></i>
                    </div>
                </div>
            </div>
        </form>
        {{-- <form action="{{ route('employee.csv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_file">
            <button>インポート</button>
        </form> --}}
    </main>


</x-app-layout>

<script>
    var vehicleUsedArray = @json($vehicleUsedArray);
</script>

{{-- script --}}
<script src="{{asset('js/employee.js')}}"></script>
