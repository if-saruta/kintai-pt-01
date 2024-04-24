<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('車両作成') }}
        </h2>
    </x-slot>


    <main class="main">
        <div class="main__white-board --info-white-board --vehicle-white-board">
            <form action="{{ route('vehicle.update', ['id' => $findVehicle->id]) }}" class="vehicle-form" id="form" method="POST">
                @csrf
                <div class="vehicle-active-wrap">
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">ナンバー情報</p>
                        </div>
                        <div class="input-area__main">
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">地名</p>
                                <input type="" name="place_name" value="{{ $findVehicle->place_name }}" class="c-input" placeholder="世田谷">
                            </div>
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">分類番号</p>
                                <input type="" name="class_number" value="{{ $findVehicle->class_number }}" class="c-input" placeholder="300">
                            </div>
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">ひらがな</p>
                                <input type="" name="hiragana" value="{{ $findVehicle->hiragana }}" class="c-input" placeholder="あ">
                            </div>
                            <div class="input-area-item --number-info-width">
                                <p class="input-area-item__head">ナンバー</p>
                                <input type="" name="number" value="{{ $findVehicle->number }}" class="c-input" placeholder="000">
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
                                <option value="1トン" @if($findVehicle->vehicle_type == '1トン') selected @endif>1トン</option>
                                <option value="1トン（クール）" @if($findVehicle->vehicle_type == '1トン（クール）') selected @endif>1トン（クール）</option>
                                <option value="2トン" @if($findVehicle->vehicle_type == '2トン') selected @endif>2トン</option>
                                <option value="2トン（クール）" @if($findVehicle->vehicle_type == '2トン（クール）') selected @endif>2トン（クール）</option>
                                <option value="3トン" @if($findVehicle->vehicle_type == '3トン') selected @endif>3トン</option>
                                <option value="3トン（クール）" @if($findVehicle->vehicle_type == '3トン（クール）') selected @endif>3トン（クール）</option>
                                <option value="軽自動車" @if($findVehicle->vehicle_type == '軽自動車') selected @endif>軽自動車</option>
                                <option value="軽クール" @if($findVehicle->vehicle_type == '軽クール') selected @endif>軽クール</option>
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
                                <option value="ドライ" @if($findVehicle->category == 'ドライ') selected @endif>ドライ</option>
                                <option value="クール" @if($findVehicle->category == 'クール') selected @endif>クール</option>
                                <option value="チルド" @if($findVehicle->category == 'チルド') selected @endif>チルド</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">社名</p>
                        </div>
                        <div class="input-area-item">
                            <input type="text" name="brand_name" value="{{ $findVehicle->brand_name }}" class="c-input" placeholder="トヨタ">
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">型式</p>
                        </div>
                        <div class="input-area-item">
                            <input type="text" name="model" value="{{ $findVehicle->model }}" class="c-input" placeholder="TFJ-00000">
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">車両満了日</p>
                        </div>
                        <div class="input-area-item">
                            <div class="date01">
                                <label for="" class="date01__label">
                                    <input type="date" id="date" name="inspection_expiration_date" value="{{ $findVehicle->inspection_expiration_date ? $findVehicle->inspection_expiration_date->format('Y-m-d') : null }}" class="datepicker__input">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="input-area__head">
                            <p class="">所有者情報</p>
                        </div>
                        <div class="input-area__main">
                            <div class="input-area-item">
                                <p class="input-area-item__head">所有者タイプ</p>
                                <select name="ownership_type" id="ownerType" class="c-select">
                                    <option value="App\Models\Company" @if($findVehicle->ownership_type == 'App\Models\Company') selected @endif data-switch='0'>所属先</option>
                                    <option value="App\Models\Employee" @if($findVehicle->ownership_type == 'App\Models\Employee') selected @endif data-switch='1'>従業員</option>
                                </select>
                            </div>
                            <div class="input-area-item ownerSelect @if($findVehicle->ownership_type != 'App\Models\Company') close @endif">
                                <p class="input-area-item__head">所有者</p>
                                <select name="owner_company_id" id="" class="owner-select c-select">
                                    @foreach ($companies as $company)
                                        @if ($company->id == $findVehicle->ownership_id)
                                            <option value="{{ $company->id }}" selected>{{ $company->name }}</option>
                                        @else
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area-item ownerSelect @if($findVehicle->ownership_type != 'App\Models\Employee') close @endif">
                                <p class="input-area-item__head">所有者</p>
                                <select name="owner_employee_id" id="" class="owner-select c-select">
                                    @foreach ($employees as $employee)
                                        @if ($employee->id == $findVehicle->ownership_id)
                                            <option value="{{ $employee->id }}" selected>{{ $employee->name }}</option>
                                        @else
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endif
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
                                    @if ($employee->id == $findVehicle->employee_id)
                                        <option value="{{ $employee->id }}" selected>{{ $employee->name }}</option>
                                    @else
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="button-area">
                        <button class="c-save-btn button-area__save-btn">
                            内容を保存する
                        </button>
                        <a href="{{ route('vehicle.delete', ['id' => $findVehicle->id]) }}" class="c-delete-btn" onclick='return confirm("本当に削除しますか。")'>
                            <p class="">削除する</p>
                        </a>
                        <a href="{{ route('vehicle.') }}" class="c-back-btn" onclick='return confirm("変更したデータは失われます。")'>
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
