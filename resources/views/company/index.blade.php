<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <div class="main">
        <a href="{{route('company.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">所属先追加</a>

        <div class="employee-table">
            <div>
              <div class="employee-th">
                <p class="w-100">所属先番号</p>
                <p class="w-200">名前</p>
              </div>
            </div>
            <div class="employee-tbody">
                @foreach ( $companies as $company )
                  <div class="employee-row">
                    <p class="w-100">{{ $company->id}}</p>
                    <p class="w-200">{{ $company->name}}</p>
                    <div class="change w-100">
                        <a href="{{ route('company.edit',['id'=>$company->id]) }}">編集</a>
                    </div>
                    <div class="delete w-100">
                        <a href="{{ route('company.delete',['id'=>$company->id])}}" onclick='return confirm("従業員と車両の所属先も削除されます")'>削除</a>
                    </div>
                  </div>
                @endforeach
            </div>
          </div>
    </div>

</x-app-layout>
