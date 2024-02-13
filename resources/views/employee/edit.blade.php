<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員編集') }}
        </h2>
    </x-slot>

    <main class="main">
        <form action="{{ route('employee.update',['id'=>$employee->id])}}" method="POST" class="main__white-board --employee-white-board">
            @csrf
            <div class="employee-name-box">
                <div class="employee-name-box__inner">
                    <div class="employee-area">
                        <p class="employee-area__head">従業員名</p>
                        <input type="text" name="name" value="{{$employee->name}}" class="c-input" placeholder="山田　太郎" required>
                    </div>
                    {{-- ボタン --}}
                    <div class="btn-area">
                        <button class="btn --save" type="submit" name="action" value="save">
                            入力内容を登録
                        </button>
                        <a href="{{route('employee.delete', ["id" => $employee->id])}}" class="btn --delete" type="submit" name="action" value="delete"
                            onclick='return confirm("本当に削除しますか?")'>
                            所属先を削除
                        </a>
                        <a href="{{route('employee.')}}" class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                            戻る
                        </a>
                    </div>
                </div>
            </div>
            <div class="employee-input-main">
                <div class="register-number">
                    <div class="head">
                        <p class="input-head">登録番号</p>
                        <p class="required">必須</p>
                    </div>
                    <input type="text" name="register_number" value="{{$employee->register_number}}" class="c-input" placeholder="T00000000" required>
                </div>
                <div class="company">
                    <div class="head">
                        <p class="input-head">所属先</p>
                        <p class="required">必須</p>
                    </div>
                    <select name="company" id="" class="c-select" required>
                        <option value="">選択してください</option>
                            @foreach ($companies as $company)
                                @if ($employee->company_id == $company->id)
                                    <option selected value="{{$company->id}}">{{$company->name}}</option>
                                @else
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endif
                            @endforeach
                    </select>
                </div>
                <div class="address-area">
                    <div class="address-area__input-box --post-code">
                        <div class="head">
                            <p class="input-head">郵便番号</p>
                            <p class="required">必須</p>
                        </div>
                        <input type="text" name="post_code" value="{{$employee->post_code}}" class="c-input" placeholder="000-0000" required>
                    </div>
                    <div class="address-area__input-box --address">
                        <div class="head">
                            <p class="input-head">住所</p>
                            <p class="required">必須</p>
                        </div>
                        <input type="text" name="address" value="{{$employee->address}}" class="c-input" placeholder="東京都渋谷区道玄坂0-0-0 hogehogefugafugaマンション 1F" required>
                    </div>
                </div>
                <div class="employment-status">
                    <div class="head">
                        <p class="input-head">雇用形態</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="check-area">
                        <div class="check-area__item">
                            <input type="radio" name="status" @if($employee->employment_status == '正社員') checked @endif value="正社員">
                            <label for="">正社員</label>
                        </div>
                        <div class="check-area__item">
                            <input type="radio" name="status" @if($employee->employment_status == '個人事業主') checked @endif value="個人事業主">
                            <label for="">個人事業主</label>
                        </div>
                        <div class="check-area__item">
                            <input type="radio" name="status" @if($employee->employment_status == 'アルバイト') checked @endif value="アルバイト">
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
                            <input type="text" name="bank_name" value="{{$employee->bankAccounts->first()->bank_name}}" class="c-input" placeholder="東京銀行　新宿支店　(普) 000000000" required>
                        </div>
                        <div class="item">
                            <p class="">口座名義人</p>
                            <input type="text" name="account_holder_name" value="{{$employee->bankAccounts->first()->account_holder_name}}" class="c-input" placeholder="ヤマダ タロウ" required>
                        </div>
                    </div>
                </div>
                <div class="invoice">
                    <div class="head">
                        <p class="input-head">インボイス登録</p>
                        <p class="required">必須</p>
                    </div>
                    <div class="check-area">
                        <div class="check-area__item">
                            <input type="radio" name="invoice" @if ($employee->is_invoice == 1) checked @endif value="1">
                            <label for="">登録</label>
                        </div>
                        <div class="check-area__item">
                            <input type="radio" name="invoice" @if ($employee->is_invoice == 0) checked @endif value="0">
                            <label for="">未登録</label>
                        </div>
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
                            <select name="rental_type" id="" class="c-select" required>
                                <option value="0" {{ $employee->vehicle_rental_type == 0 ? 'selected' : '' }}>自車</option>
                                <option value="1" {{ $employee->vehicle_rental_type == 1 ? 'selected' : '' }}>月リース</option>
                                <option value="2" {{ $employee->vehicle_rental_type == 2 ? 'selected' : '' }}>なんでも月リース</option>
                                <option value="3" {{ $employee->vehicle_rental_type == 3 ? 'selected' : '' }}>日割り</option>
                            </select>
                        </div>
                        <div class="select-area__item">
                            <p class="">貸出形態</p>
                            <select name="vehicle" id="" class="c-select">
                                <option value="">選択してください</option>
                                @foreach ($vehicles as $vehicle)
                                    @if ($vehicle->id == $employee->vehicle_id)
                                        <option selected value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                    @else
                                        <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
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
                                        <p class="">給与</p>
                                        @if ($project->payment_type == 1)
                                        @php
                                            $amount = 0;
                                            foreach ($projectEmployeePayments as $projectEmployeePayment) {
                                                if($projectEmployeePayment->project_id == $project->id){
                                                    $amount = $projectEmployeePayment->amount;
                                                }
                                            }
                                        @endphp
                                        <input type="text" name="employeePrice[{{$project->id}}]" value="{{$amount}}" class="c-input" placeholder="1,000">
                                        @else
                                        <div class="read-only">
                                            <input type="text" class="c-input" placeholder="1,000" readonly>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="allowance projectAllowanceContainer">
                                        @foreach ($allowanceProjects as $allowanceProject)
                                            @if($allowanceProject->project_id == $project->id)
                                                <div class="allowance-item projectAllowanceItem">
                                                    <div class="allowance__name">
                                                        <p class="">手当名</p>
                                                        <input type="text" name="allowanceNameByEdit[{{$allowanceProject->id}}]"  value="{{$allowanceProject->allowanceName}}" class="c-input" placeholder="リーダー手当">
                                                    </div>
                                                    <div class="allowance__amount">
                                                        <p class="">手当金額</p>
                                                        <input type="text" name="allowanceAmountByEdit[{{$allowanceProject->id}}]" value="{{ceil($allowanceProject->amount)}}" class="c-input" placeholder="1,000">
                                                    </div>
                                                    <i class="fa-solid fa-circle-minus delete-circle projectAllowanecDelete tmpProjectAllowance" data-project-all-id="{{$allowanceProject->id}}"></i>
                                                </div>
                                            @endif
                                        @endforeach
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
                        @foreach ($allowanceOthers as $allowanceOther)
                            <div class="input-item-box">
                                <div class="input-item allowance-name">
                                    <p class="">手当名</p>
                                    <input type="text" name="allowanceOtherNameEdit[{{$allowanceOther->id}}]" value="{{$allowanceOther->allowanceName}}" class="c-input" placeholder="リーダー手当">
                                </div>
                                <div class="input-item allowance-amount">
                                    <p class="">手当金額</p>
                                    <input type="text" name="allowanceOtherAmountEdit[{{$allowanceOther->id}}]" value="{{$allowanceOther->amount}}" class="c-input" placeholder="1,000">
                                </div>
                                <i class="fa-solid fa-circle-minus delete-circle otherAllowanceDelete tmpOtherAllowance" data-other-all-id="{{$allowanceOther->id}}"></i>
                            </div>
                        @endforeach
                        <i class="fa-solid fa-circle-plus plus-circle" id="otherAllowanceAdd"></i>
                    </div>
                </div>
            </div>
        </form>
    </main>



</x-app-layout>

{{-- script --}}
<script src="{{asset('js/employee.js')}}"></script>
