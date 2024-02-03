<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <div class="invoice">
        <div class="invoice__charter">
            <form action="{{ route('invoice.searchCharterShift') }}" method="POST">
                @csrf
                <div class="select-area">
                    <div class="invoice-date">
                        <div class="select-area__block">
                            <label for="">年</label>
                            <select name="year" id="" class="select-style">
                                <option value="">選択してください</option>
                                @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="select-area__block">
                            <label for="">月</label>
                            <select name="month" id="" class="select-style">
                                <option value="">選択してください</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                    </div>
                    <button class="search-btn">
                        <p>検索</p>
                    </button>
                </div>
            </form>

            @if($shiftArray)
                <form action="{{route('invoice.charter-shift-update')}}" method="POST">
                    @csrf
                    <div class="charter-calender-wrap">
                        <button class="save-btn">
                            <p class="">保存</p>
                        </button>
                        <div class="charter-calender-wrap__charter-calender">
                            <div class="head">
                                <div class="head__clm --date">
                                    <p class="">日付</p>
                                </div>
                                <div class="head__clm --project">
                                    <p class="">案件名</p>
                                </div>
                                <div class="head__clm --common">
                                    <p class="">上代</p>
                                </div>
                                <div class="head__clm --common">
                                    <p class="">高速代</p>
                                </div>
                                <div class="head__clm --common">
                                    <p class="">パーキング代</p>
                                </div>
                                <div class="head__clm --common">
                                    <p class="">ドライバー</p>
                                </div>
                                <div class="head__clm --common">
                                    <p class="">ドライバー価格</p>
                                </div>
                                <div class="head__clm --common">
                                    <p class="">クライアント名</p>
                                </div>
                            </div>
                            <div class="data">
                                @foreach ($shiftArray as $data)
                                    <div class="data__row">
                                        <div class="data__row__clm --date">
                                            <p class="">{{$data['shift']['date']}}</p>
                                        </div>
                                        <div class="data__row__clm --project">
                                            <input type="text" value="{{$data['project']['name']}}" class="input charter-input" readonly>
                                        </div>
                                        <div class="data__row__clm --common">
                                            <input type="text" name="retail_price[{{$data['id']}}]" value="{{ ceil($data['retail_price']) }}" class="input charter-input">
                                        </div>
                                        <div class="data__row__clm --common">
                                            <input type="text" name="expressway_fee[{{$data['id']}}]" value="{{ ceil($data['expressway_fee']) }}" class="input charter-input">
                                        </div>
                                        <div class="data__row__clm --common">
                                            <input type="text" name="parking_fee[{{$data['id']}}]" value="{{ ceil($data['parking_fee']) }}" class="input charter-input">
                                        </div>
                                        <div class="data__row__clm --common">
                                            <input type="text" value="{{$data['shift']['employee']['name']}}" class="input charter-input" readonly>
                                        </div>
                                        <div class="data__row__clm --common">
                                            <input type="text" name="driver_price[{{$data['id']}}]" value="{{ ceil($data['driver_price']) }}" class="input charter-input">
                                        </div>
                                        <div class="data__row__clm --common">
                                            <input type="text" name="" value="{{$data['project']['client']['name']}}" class="input charter-input" readonly>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="data --unregister-data">
                                @foreach ($unregisterProjectShift as $spv)
                                <div class="data__row">
                                    <div class="data__row__clm --date">
                                        <p class="">{{$spv->shift->date}}</p>
                                    </div>
                                    <div class="data__row__clm --project">
                                        <input type="text" name="unregistered_project[{{$spv->id}}]" value="{{ $spv->unregistered_project }}" class="input charter-input" readonly>
                                    </div>
                                    <div class="data__row__clm --common">
                                        <input type="text" name="retail_price[{{$spv->id}}]" value="{{ ceil($spv->retail_price) }}" class="input charter-input">
                                    </div>
                                    <div class="data__row__clm --common">
                                        <input type="text" name="expressway_fee[{{$spv->id}}]" value="{{ ceil($spv->expressway_fee) }}" class="input charter-input">
                                    </div>
                                    <div class="data__row__clm --common">
                                        <input type="text" name="parking_fee[{{$spv->id}}]" value="{{ ceil($spv->parking_fee) }}" class="input charter-input">
                                    </div>
                                    <div class="data__row__clm --common">
                                        <input type="text" name="" value="{{ $spv->shift->employee->name }}" class="input charter-input" readonly>
                                    </div>
                                    <div class="data__row__clm --common">
                                        <input type="text" name="driver_price[{{$spv->id}}]" value="{{ ceil($spv->driver_price) }}" class="input charter-input">
                                    </div>
                                    <div class="data__row__clm --common clientElem">
                                        <input hidden type="text" value="{{$spv->id}}" class="input charter-input">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <input hidden type="text" value="{{$getYear}}" name="year">
                    <input hidden type="text" value="{{$getMonth}}" name="month">
                </form>

                {{-- モーダル --}}
                <form action="{{route('invoice.charter-client-update')}}" method="POST">
                    @csrf
                    <div class="client-modal clientModal">
                        <span class="client-modal__bg closeElem"></span>
                        <div class="client-modal__main">
                            <div class="client-modal__main__inner">
                                <div class="checkbox-area">
                                    <div class="checkbox-area__block">
                                        <label for="check_01">既存</label>
                                        <input checked id="check_01" type="radio" name="client_switch" value="0" class="witchRadio">
                                    </div>
                                    <div class="checkbox-area__block">
                                        <label for="check_02">新規</label>
                                        <input id="check_02" type="radio" name="client_switch" value="1" class="witchRadio">
                                    </div>
                                </div>
                                <input hidden type="text" name="shift_id" class="setShiftIdElem">
                                <input hidden type="text" value="{{$getYear}}" name="year">
                                <input hidden type="text" value="{{$getMonth}}" name="month">
                                <div class="action-area actionElem">
                                    <select name="clientId" id="" class="select">
                                        <option value="">選択してください</option>
                                        @foreach ($clients as $client)
                                            <option value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="action-area input-area actionElem">
                                    <div class="input-area__item">
                                        <label for="">クライアント名(ID)</label>
                                        <input type="text" class="input" name="clientName">
                                    </div>
                                    <div class="input-area__item">
                                        <label for="">クライアント名(PDF使用時)</label>
                                        <input type="text" class="input" name="clientPdfName">
                                    </div>
                                </div>
                                <button class="save-btn client-modal-btn">
                                    <p class="">保存</p>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>



</x-app-layout>

{{-- script --}}
<script src="{{asset('js/invoice-charter.js')}}"></script>
