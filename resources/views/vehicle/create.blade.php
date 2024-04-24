<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('車両作成') }}
        </h2>
    </x-slot>


    <main class="main">
        <div class="main__white-board --info-white-board --vehicle-white-board">
            <form action="{{ route('vehicle.store') }}" class="vehicle-form" id="form" method="POST">
                @csrf
                <div class="vehicle-active-wrap">
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">ナンバー情報</p>
                            <p class="required">必須</p>
                        </div>
                        <div class="input-area__main">
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">地名</p>
                                <input type="" name="place_name" class="c-input" placeholder="世田谷" required>
                            </div>
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">分類番号</p>
                                <input type="" name="class_number" class="c-input" placeholder="300" required>
                            </div>
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">ひらがな</p>
                                <input type="" name="hiragana" class="c-input" placeholder="あ" required>
                            </div>
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">ナンバー</p>
                                <input type="" name="number" class="c-input" placeholder="000" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">車種</p>
                        </div>
                        <div class="input-area-item">
                            <select name="vehicle_type" id="" class="c-select">
                                <option value="">選択してください</option>
                                <option value="1トン">1トン</option>
                                <option value="1トン（クール）">1トン（クール）</option>
                                <option value="2トン">2トン</option>
                                <option value="2トン（クール）">2トン（クール）</option>
                                <option value="3トン">3トン</option>
                                <option value="3トン（クール）">3トン（クール）</option>
                                <option value="軽自動車">軽自動車</option>
                                <option value="軽クール">軽クール</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">種別</p>
                        </div>
                        <div class="input-area-item">
                            <select name="category" id="" class="c-select">
                                <option value="">選択してください</option>
                                <option value="ドライ">ドライ</option>
                                <option value="クール">クール</option>
                                <option value="チルド">チルド</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">社名</p>
                        </div>
                        <div class="input-area-item">
                            <input type="text" name="brand_name" class="c-input" placeholder="トヨタ">
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">型式</p>
                        </div>
                        <div class="input-area-item">
                            <input type="text" name="model" class="c-input" placeholder="TFJ-00000">
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">車両満了日</p>
                        </div>
                        <div class="input-area-item">
                            <div class="date01">
                                <label for="" class="date01__label">
                                    <input type="date" id="date" name="inspection_expiration_date" class="datepicker__input">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">所有者情報</p>
                            <p class="required">必須</p>
                        </div>
                        <div class="input-area__main">
                            <div class="input-area-item">
                                <p class="input-area-item__head">所有者タイプ</p>
                                <select name="ownership_type" id="ownerType" class="c-select" required>
                                    <option value="App\Models\Company" data-switch='0'>所属先</option>
                                    <option value="App\Models\Employee" data-switch='1'>従業員</option>
                                </select>
                            </div>
                            <div class="input-area-item ownerSelect">
                                <p class="input-area-item__head">所有者</p>
                                <select name="owner_company_id" id="" class="c-select owner-select" required>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area-item ownerSelect close">
                                <p class="input-area-item__head">所有者</p>
                                <select name="owner_employee_id" id="" class="c-select owner-select">
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">使用者</p>
                            <p class="" id="vehicleUserWarningTxt" style="color: red;"></p>
                        </div>
                        <div class="input-area-item">
                            <select name="employee_id" id="userVehicleSelect" class="c-select">
                                <option value="">選択してください</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="button-area">
                        <button class="c-save-btn button-area__save-btn">
                            内容を保存する
                        </button>
                        <a href="{{ route('vehicle.') }}" class="c-back-btn" onclick='return confirm("入力したデータは失われます。")'>
                            <p class="">戻る</p>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </main>

</x-app-layout>

<script>
    var vehicleUserArray = @json($vehicleUserArray);
</script>

{{-- script --}}
<script src="{{asset('js/vehicle.js')}}"></script>
