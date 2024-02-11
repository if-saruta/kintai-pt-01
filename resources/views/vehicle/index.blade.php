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
                    <div class="info-wrap__register-item__inner --vehicle-default-view" id='defaultView'>
                        {{-- <div class="info-row">
                            <p class="info-row__head">ナンバー</p>
                            <div class="info-row__data --top">
                                <p class="setCompanyName setTxtElem">--------</p>
                            </div>
                        </div>
                        <div class="info-row">
                            <p class="info-row__head">所属先</p>
                            <div class="info-row__data">
                                <p class="setRegisterNumber setTxtElem">--------</p>
                            </div>
                        </div> --}}
                    </div>
                    {{-- 編集画面 --}}
                    <form action="{{route('vehicle.update')}}" method="POST" class="info-wrap__register-item__inner --edit-inner" id="editView">
                        @csrf
                        <input hidden type="text" class="setValueElem" name="id">
                        <div class="info-row info-row-edit">
                            <p class="info-row__head info-row-edit__head">ナンバー</p>
                            <div class="info-row__data --top --top-edit">
                                <input type="text" class="c-input setValueElem" name="number">
                            </div>
                        </div>
                        <div class="info-row info-row-edit">
                            <p class="info-row__head info-row-edit__head">所属先</p>
                            <div class="info-row__data info-row-edit__data">
                                {{-- <input type="text" class="c-input setValueElem" name="company"> --}}
                                <select name="company" class="c-select" id="setSelectValueElem">
                                    <option value="">選択してください</option>
                                    @foreach ($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- ボタン --}}
                        <div class="btn-area edit-btn-area">
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
                    <form action="{{route('vehicle.store')}}" method="POST" class="info-wrap__register-item__inner --edit-inner" id="createView">
                        @csrf
                        <input hidden type="text" class="setValueElem" name="id">
                        <div class="info-row info-row-edit">
                            <p class="info-row__head info-row-edit__head">ナンバー</p>
                            <div class="info-row__data --top --top-edit">
                                <input type="text" class="c-input setValueElem" name="number">
                            </div>
                        </div>
                        <div class="info-row info-row-edit">
                            <p class="info-row__head info-row-edit__head">所属先</p>
                            <div class="info-row__data info-row-edit__data">
                                <select name="company" class="c-select">
                                    <option value="">選択してください</option>
                                    @foreach ($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- ボタン --}}
                        <div class="btn-area edit-btn-area">
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
                        @foreach ($vehicles as $vehicle)
                        <div class="info-wrap__register-list__body__row companyBox">
                            <p class="number w-number">{{$vehicle->number}}</p>
                            <p class="company w-name">
                                @if ($vehicle->company)
                                {{$vehicle->company->name}}
                                @endif
                            </p>
                            <button class="edit-btn action-btn editBtn">
                                <div class="edit-btn__inner">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <p class="edit-btn-txt">編集</p>
                                </div>
                                <div class="dataHasElem"
                                    data-info='["{{$vehicle->number}}","{{$vehicle->company->name}}"]'>
                                </div>
                                <div class="dataHasEditElem"
                                    data-info='["{{$vehicle->id}}","{{$vehicle->number}}","{{$vehicle->company->id}}"]'>
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

