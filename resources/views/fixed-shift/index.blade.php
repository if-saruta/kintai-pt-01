<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">

        <div class="main__white-board --client-white-board">
            <div class="fixed-shift-wrap">
                <a href="{{ route('project.edit', ['id' => $project->client_id]) }}" class="c-back-btn back-btn">戻る</a>
                <p class="title">固定シフト : 一覧</p>
                <p class="project-name">案件名 : {{ $project->name }}</p>
                <a href="{{ route('project.fixedShiftCreate', ['id' => $id]) }}" class="create-fixed-shift-btn">新規作成</a>

                <div class="fixed-shift-wrap__scroll-y">
                    <table class="fixed-shift-list">
                        @foreach ($fixedShifts as $fixedShift)
                        <tr>
                            <td class="employee">{{$fixedShift->employee->name}}</td>
                            <td><a href="{{ route('project.fixedShiftShow', ['id' => $fixedShift->id]) }}" class="link show">詳細</a></td>
                            <td><a href="{{ route('project.fixedShiftEdit', ['id' => $fixedShift->id]) }}" class="link edit">編集</a></td>
                            <td><a href="{{ route('project.fixedShiftDelete', ['id' => $fixedShift->id]) }}" class="link delete" onclick='return confirm("本当に削除しますか?")'>削除</a></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </main>

</x-app-layout>

{{-- script --}}
{{-- <script src="{{asset('js/info.js')}}"></script> --}}

