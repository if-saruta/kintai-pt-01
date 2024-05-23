<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">

        <div class="main__white-board --client-white-board">
            <div class="fixed-shift-wrap">
                <p class="title">固定シフト</p>
                <p class="project-name">案件名 : {{ $project->name }}</p>
                <a href="{{ route('project.fixedShiftCreate', ['id' => $id]) }}" class="create-fixed-shift-btn">新規作成</a>

                <div class="fixed-shift-list">
                    @foreach ($fixedShifts as $shift)

                    @endforeach
                </div>
            </div>
        </div>

    </main>

</x-app-layout>

{{-- script --}}
{{-- <script src="{{asset('js/info.js')}}"></script> --}}

