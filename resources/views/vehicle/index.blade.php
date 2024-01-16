<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('車両') }}
        </h2>
    </x-slot>

    <div class="main">
        <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" href="{{route('vehicle.create')}}">車両追加</a>
        @if (session('alert'))
            <div class="alert alert-warning">
                {{ session('alert') }}
            </div>
        @endif
        <div class="vehicle-table">
            <div>
              <div class="vehicle-th">
                <p class="w-100">登録番号</p>
                <p class="w-100">車両番号</p>
                <p class="w-200">所属先</p>
              </div>
            </div>
            <div class="vehicle-tbody">
                @foreach ( $vehicles as $vehicle )
                  <div class="vehicle-item-wrap">
                        <p class="w-100">{{ $vehicle->id}}</p>
                        <p class="w-100">{{ $vehicle->number}}</p>
                        @if ($vehicle->company)
                            <p class="w-200">{{$vehicle->company->name}}</p>
                        @else
                            <p class="w-200">未登録</p>
                        @endif
                        <div class="change w-100">
                            <a href="{{ route('vehicle.edit',['id'=>$vehicle->id]) }}">編集</a>
                        </div>
                        <div class="delete w-100">
                            <a href="{{ route('vehicle.delete',['id'=>$vehicle->id])}}">削除</a>
                        </div>
                    </div>
                @endforeach
            </div>
          </div>
    </div>

</x-app-layout>
