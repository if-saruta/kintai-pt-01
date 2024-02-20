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
                    <a href="{{route('company.')}}" class="{{ request()->routeIs('company*.') ? 'active' : '' }} link">
                        <span class="">所属先</span>
                    </a>
                </div>
                <div class="main__link-block__item --info-link-block__item">
                    <a href="{{route('vehicle.')}}" class="{{ request()->routeIs('vehicle*.') ? 'active' : '' }} link">
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
                    <form action="{{route('company.update')}}" method="POST"
                        class="info-wrap__register-item__inner --edit-inner" id="editView">
                        @csrf
                        <div class="scroll-y">
                            <input hidden type="text" class="setValueElem" name="id" required>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">所属先名</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data --top --top-edit">
                                    <input type="text" class="c-input setValueElem" name="name">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">登録番号</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="register_number" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">電話番号</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="phone" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">FAX</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="fax">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">郵便番号</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="post_code" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">住所</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <textarea id="" cols="30" rows="10" class="c-textarea setValueElem"
                                        name="address" required></textarea>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">銀行口座</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="bank_name" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">口座名義人</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="account_holder_name" required>
                                </div>
                            </div>
                            {{-- ボタン --}}
                            <div class="btn-area">
                                <button class="btn --save" type="submit" name="action" value="save">
                                    入力内容を登録
                                </button>
                                <button class="btn --delete" type="submit" name="action" value="delete"
                                    onclick='return confirm("本当に削除しますか?")'>
                                    所属先を削除
                                </button>
                                <div class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                                    戻る
                                </div>
                            </div>
                        </div>

                    </form>
                    {{-- 新規作成画面 --}}
                    <form action="{{route('company.store')}}" method="POST"
                        class="info-wrap__register-item__inner --edit-inner" id="createView">
                        @csrf
                        <div class="scroll-y">
                            <input hidden type="text" class="setValueElem" name="id">
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">所属先名</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data --top --top-edit">
                                    <input type="text" class="c-input setValueElem" name="name" placeholder="株式会社⚪︎⚪︎⚪︎⚪︎" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">登録番号</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="register_number" placeholder="T0000000" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">電話番号</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="phone" placeholder="000-0000-0000" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">FAX</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="fax" placeholder="000-0000-0000">
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">郵便番号</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="post_code" placeholder="000-0000" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">住所</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <textarea id="" cols="30" rows="10" class="c-textarea setValueElem" name="address" placeholder="東京都新宿区新宿 0-00-00" required></textarea>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">銀行口座</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="bank_name" placeholder="⚪︎⚪︎銀行⚪︎⚪︎支店(普) 0000000" required>
                                </div>
                            </div>
                            <div class="info-row info-row-edit">
                                <div class="flex-10">
                                    <p class="info-row__head info-row-edit__head">口座名義人</p>
                                    <p class="required">必須</p>
                                </div>
                                <div class="info-row__data info-row-edit__data">
                                    <input type="text" class="c-input setValueElem" name="account_holder_name" placeholder="山田　太郎" required>
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
                        </div>

                    </form>
                    <form class="csv" action="{{ route('company.csv') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="csv_file">
                        <button class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">インポート</button>
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
