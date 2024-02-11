<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__link-block --info-link-block">
            <div class="main__link-block__tags">
                <div class="main__link-block__item --info-link-block__item">
                    <a href="{{route('company.')}}"
                        class="{{ request()->routeIs('company*.') ? 'active' : '' }} link">
                        <span class="">所属先</span>
                    </a>
                </div>
                <div class="main__link-block__item --info-link-block__item">
                    <a href="{{route('vehicle.')}}"
                        class="{{ request()->routeIs('vehicle*.') ? 'active' : '' }} link">
                        <span class="">車両</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="main__white-board">
            <div class="info-wrap">
                <div class="info-wrap__register-item">
                    {{-- デフォルトの画面 --}}
                    <div class="info-wrap__register-item__inner" id='defaultView'>
                        <div class="scroll-y">
                            <div class="info-row">
                                <p class="info-row__head">所属先名</p>
                                <div class="info-row__data --top">
                                    <p class="setCompanyName setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">登録番号</p>
                                <div class="info-row__data">
                                    <p class="setRegisterNumber setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">電話番号</p>
                                <div class="info-row__data">
                                    <p class="setPhone setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">FAX</p>
                                <div class="info-row__data">
                                    <p class="setFax setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">郵便番号</p>
                                <div class="info-row__data">
                                    <p class="setPostCode setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">住所</p>
                                <div class="info-row__data">
                                    <p class="setAddress setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">銀行口座</p>
                                <div class="info-row__data">
                                    <p class="setBankName setTxtElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <p class="info-row__head">口座名義人</p>
                                <div class="info-row__data">
                                    <p class="setAccount setTxtElem">--------</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- 編集画面 --}}
                    <form action="{{route('company.update')}}" method="POST" class="info-wrap__register-item__inner --edit-inner" id="editView">
                        @csrf
                        <div class="scroll-y">
                            <input hidden type="text" class="setValueElem" name="id">
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">所属先名</p>
                                <div class="info-row__data --top --top-edit">
                                    <input type="text" class="c-input setValueElem" name="name">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">登録番号</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="register_number">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">電話番号</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="phone">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">FAX</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="fax">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">郵便番号</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="post_code">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">住所</p>
                                <div class="info-row__data info-row-edit__data">
                                    <textarea id="" cols="30" rows="10" class="c-textarea setValueElem" name="address"></textarea>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">銀行口座</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="bank_name">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">口座名義人</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="account_holder_name">
                                </div>
                            </div>
                        </div>

                        {{-- ボタン --}}
                        <div class="btn-area">
                            <button class="btn --save" type="submit" name="action" value="save">
                                入力内容を登録
                            </button>
                            <button class="btn --delete" type="submit" name="action" value="delete" onclick='return confirm("本当に削除しますか?")'>
                                所属先を削除
                            </button>
                            <div class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                                戻る
                            </div>
                        </div>
                    </form>
                    {{-- 新規作成画面 --}}
                    <form action="{{route('company.store')}}" method="POST" class="info-wrap__register-item__inner --edit-inner" id="createView">
                        @csrf
                        <div class="scroll-y">
                            <input hidden type="text" class="setValueElem" name="id">
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">所属先名</p>
                                <div class="info-row__data --top --top-edit">
                                    <input type="text" class="c-input setValueElem" name="name">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">登録番号</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="register_number">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">電話番号</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="phone">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">FAX</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="fax">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">郵便番号</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="post_code">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">住所</p>
                                <div class="info-row__data info-row-edit__data">
                                    <textarea id="" cols="30" rows="10" class="c-textarea setValueElem" name="address"></textarea>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">銀行口座</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="bank_name">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <p class="info-row__head info-row-edit__head">口座名義人</p>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="account_holder_name">
                                </div>
                            </div>
                        </div>

                        {{-- ボタン --}}
                        <div class="btn-area">
                            <button class="btn --save" type="submit" name="action" value="save">
                                入力内容を登録
                            </button>
                            <div class="btn --back createCloseBtn" onclick='return confirm("入力したデータは失われます。")'>
                                戻る
                            </div>
                        </div>
                    </form>
                </div>
                <div class="info-wrap__register-list">
                    <div class="info-wrap__register-list__head">
                        <div class="info-wrap__register-list__head__row">
                            <p class="w-number">Number</p>
                            <p class="w-name">Company Name</p>
                        </div>
                    </div>
                    <div class="info-wrap__register-list__body">
                        @foreach ($companies as $company)
                        <div class="info-wrap__register-list__body__row companyBox">
                            <p class="number w-number">{{$company->register_number}}</p>
                            <p class="company w-name">
                                @if ($company->name)
                                {{$company->name}}
                                @endif
                            </p>
                            <button class="edit-btn action-btn editBtn">
                                <div class="edit-btn__inner">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <p class="edit-btn-txt">編集</p>
                                </div>
                                <div class="dataHasElem"
                                    data-info='["{{$company->name}}","{{$company->register_number}}","{{$company->phone}}","{{$company->fax}}","{{$company->post_code}}","{{$company->address}}","{{$company->bank_name}}","{{$company->account_holder_name}}"]'>
                                </div>
                                <div class="dataHasEditElem"
                                    data-info='["{{$company->id}}","{{$company->name}}","{{$company->register_number}}","{{$company->phone}}","{{$company->fax}}","{{$company->post_code}}","{{$company->address}}","{{$company->bank_name}}","{{$company->account_holder_name}}"]'>
                                </div>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="info-wrap__register-list__head --foot">
                        <div class="info-wrap__register-list__head__row">
                            <p class="w-number">Number</p>
                            <p class="w-name">Company Name</p>
                        </div>
                        <button class="add-btn" id="addBtn">
                            <div class="add-btn__inner">
                                <i class="fa-solid fa-circle-plus"></i>
                                <p class="">追加</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/info.js')}}"></script>

