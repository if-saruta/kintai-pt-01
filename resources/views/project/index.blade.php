<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件') }}
        </h2>
    </x-slot>

    <div class="main">
        <a href="{{route('project.create')}}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">案件追加</a>
            @if (session('alert'))
            <div class="alert alert-warning">
                {{ session('alert') }}
            </div>
        @endif
        <div class="project-table">
            <div class="th">
                <div class="project-th">
                    <p class="w-150">案件番号</p>
                    <p class="w-150">案件名</p>
                    <p class="w-150">上代</p>
                    <p class="w-150">賃金</p>
                </div>
            </div>
            <div class="project-tbody">
                @foreach ( $projects as $project )
                <div class="project-row">
                    <p class="w-150">{{ $project->id}}</p>
                    <p class="w-150">{{ $project->name}}</p>
                    <p class="w-150">{{ $project->retail_price}}</p>
                    <p class="w-150">{{ $project->driver_price}}</p>
                    <div class="change">
                        <a href="{{ route('project.edit',['id'=>$project->id]) }}">編集</a>
                    </div>
                    <div class="delete">
                        <a href="{{ route('project.delete',['id'=>$project->id])}}">削除</a>
                    </div>
                    <div class="employee_price">
                        <a class="text-white bg-sky-300 hover:bg-sky-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            href="{{ route('project.employeePaymentShow',['id'=>$project->id])}}">従業員別賃金詳細</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>



</x-app-layout>
