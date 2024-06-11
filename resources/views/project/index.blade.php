<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">
        {{-- <div class="main__link-block --info-link-block">
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
        </div> --}}
        <div class="main__white-board --client-white-board">
            <div class="info-wrap --clinet-info-wrap">
                <div class="info-wrap__register-item --client-register-item">
                    {{-- デフォルトの画面 --}}
                    <div class="info-wrap__register-item__inner client-inner" id='defaultView'>
                        <div class="scroll-y client-scroll-y" id="defaultViewInner">
                            <div class="info-row">
                                <p class="info-row__head">クライアント名</p>
                                <div class="info-row__data --top">
                                    <p class="setCompanyName setTxtClientElem">--------</p>
                                </div>
                            </div>
                            <div class="info-row --project-count-row">
                                <p class="info-row__head">登録案件数</p>
                                <p class="">/</p>
                                <div class="count-box countBox"></div>
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
                        <div class="info-wrap__register-list__head__row --client-head-row">
                            <p class="">Client Name</p>
                        </div>
                    </div>
                    <div class="info-wrap__register-list__body --client-body">
                        @foreach ($clients as $client)
                        <div class="info-wrap__register-list__body__row --client-row companyBox">
                            <p class="company">
                                {{$client->name}}
                            </p>
                            <a href="{{route('project.edit',['id' => $client->id])}}" class="edit-btn action-btn">
                                <div class="edit-btn__inner">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <p class="edit-btn-txt">編集</p>
                                </div>
                                <div class="dataHasClientName" data-client-name="{{$client->name}}"></div>
                                @foreach ($client->projects as $project)
                                <div class="dataHasProjectName"
                                    data-project-name='{{$project->name}}'>
                                </div>
                                @endforeach
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="info-wrap__register-list__head --foot">
                        <div class="info-wrap__register-list__head__row --client-head-row">
                            <p class="">Client Name</p>
                        </div>
                        <a href="{{route('project.create')}}" class="add-btn project-add-btn" >
                            <div class="add-btn__inner">
                                <i class="fa-solid fa-circle-plus"></i>
                                <p class="">追加</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/info.js')}}"></script>

