<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --info-white-board --vehicle-white-board">
            <div class="vehicle-action-area">
                <div class="setting-btn" id="modalOpenBtn">
                    <p class="">設定</p>
                </div>
                <form action="{{ route('vehicle.downloadPdf') }}" class="down-load-form" method="POST">
                    @csrf
                    @foreach ($narrowOwnerArray as $narrowOwnerName)
                        <input hidden type="text" name="narrowOwnerName[]" value="{{ $narrowOwnerName }}">
                    @endforeach
                    <button class="download-btn">
                        ダウンロード
                    </button>
                </form>
                <a href="{{ route('vehicle.') }}" class="c-back-btn vehicle-action-area__back-btn">
                    <p class="">戻る</p>
                </a>
            </div>
            {{-- モーダル --}}
            <div class="vehicle-modal" id="vehicleModal">
                <div class="vehicle-modal__bg modalCloseBtn"></div>
                <div class="vehicle-modal__white-board">
                    <div class="vehicle-modal__white-board__inner">
                        <p class="head">絞り込み</p>
                        <form action="{{ route('vehicle.allShow') }}" method="POST">
                            @csrf
                            <div class="owner-list">
                                @foreach ($ownerArray as $ownerArrayName)
                                    <label for="">
                                        <input type="checkbox" value="{{ $ownerArrayName }}" name="narrowOwner[]" @if(in_array($ownerArrayName, $narrowOwnerArray)) checked @endif>
                                        {{ $ownerArrayName }}
                                    </label>
                                @endforeach
                            </div>
                            <div class="vehicle-modal-btn-area">
                                <button class="vehicle-narrow-btn c-save-btn">
                                    絞り込む
                                </button>
                                <div class="c-back-btn modalCloseBtn">
                                    <p class="">戻る</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="vehicle-list-wrap">
                <table class="vehicle-list-table">
                    <thead>
                        <th class="number"><p class="">車両番号</p></th>
                        <th class="vehicle-type"><p class="">車種</p></th>
                        <th class="category"><p class="">種別</p></th>
                        <th class="brand"><p class="">社名</p></th>
                        <th class="model"><p class="">型式</p></th>
                        <th class="owner"><p class="">所有</p></th>
                        <th class="user"><p class="">使用</p></th>
                        <th class="vehicle-date"><p class="">車検満了日</p></th>
                    </thead>
                    <tbody>
                        @foreach ($vehiclesGroupedByOwner as $ownerType => $vehiclesInOwnerGroup)
                            @foreach ($vehiclesInOwnerGroup as $ownerName => $vehicles)
                                {{-- 絞り込みの条件で判定 --}}
                                @if (in_array($ownerName, $narrowOwnerArray))
                                    @foreach ($vehicles as $vehicle)
                                        <tr>
                                            <td class="number"><p class="">{{ $vehicle->place_name }} {{ $vehicle->class_number }} {{ $vehicle->hiragana }} {{ $vehicle->number }}</p></td>
                                            <td class="vehicle-type"><p class="">{{ $vehicle->vehicle_type }}</p></td>
                                            <td class="category"><p class="">{{ $vehicle->category }}</p></td>
                                            <td class="brand"><p class="">{{ $vehicle->brand_name }}</p></td>
                                            <td class="model"><p class="">{{ $vehicle->model }}</p></td>
                                            <td class="owner"><p class="">{{ $vehicle->ownership ? $vehicle->ownership->name : null }}</p></td>
                                            <td class="user"><p class="">{{ $vehicle->employee ? $vehicle->employee->name : null }}</p></td>
                                            <td class="vehicle-date"><p class="">{{ $vehicle->inspection_expiration_date ? $vehicle->inspection_expiration_date->format('Y月n月j日') : null }}</p></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/vehicle.js')}}"></script>

