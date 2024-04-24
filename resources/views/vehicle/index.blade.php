<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --info-white-board --vehicle-white-board">
            <div class="info-wrap">
                <div class="info-wrap__register-list">
                    <div class="info-wrap__register-list__head">
                        <div class="info-wrap__register-list__head__row">
                            <p class="w-number number">ナンバー</p>
                            <p class="w-name employee">使用者</p>
                            <p class="w-name date">車検満了日</p>
                            <a href="{{ route('vehicle.allShow') }}" class="all-info-btn">一覧詳細</a>
                        </div>
                    </div>
                    <div class="info-wrap__register-list__body">
                        @foreach ($vehiclesGroupedByOwner as $ownerType => $vehiclesInOwnerGroup)
                            @foreach ($vehiclesInOwnerGroup as $ownerName => $vehicles)
                                <div class="list-group-wrap">
                                    <div class="owner-row">
                                        <p class="">{{ $ownerName }}</p>
                                    </div>
                                    @foreach ($vehicles as $vehicle)
                                        <div class="info-wrap__register-list__body__row">
                                            <p class="number w-number">{{$vehicle->number}}</p>
                                            <p class="employee">
                                                @if ($vehicle->employee)
                                                    {{ $vehicle->employee->name }}
                                                @endif
                                            </p>
                                            <p class="date">
                                                @if ($vehicle->inspection_expiration_date)
                                                    {{ $vehicle->inspection_expiration_date->format('Y年n月j日') }}
                                                @endif
                                            </p>
                                            <a href="{{ route('vehicle.edit',['id' => $vehicle->id]) }}" class="edit-btn action-btn editBtn @can('admin-higher') @else user @endcan">
                                                <div class="edit-btn__inner">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    @can('admin-higher')
                                                        <p class="edit-btn-txt">編集</p>
                                                    @else
                                                        <p class="edit-btn-txt">詳細</p>
                                                    @endcan
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    <div class="info-wrap__register-list__head --foot">
                        <div class="info-wrap__register-list__head__row">
                            <p class="w-number">ナンバー</p>
                            <p class="w-name">使用者</p>
                            <p class="w-name">車検満了日</p>
                        </div>
                        @can('admin-higher')
                            <a href="{{ route('vehicle.create') }}" class="add-btn">
                                <div class="add-btn__inner">
                                    <i class="fa-solid fa-circle-plus"></i>
                                    <p class="">追加</p>
                                </div>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/vehicle.js')}}"></script>

