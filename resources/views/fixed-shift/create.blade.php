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
                <a href="{{ route('project.fixedShift', ['id' => $id]) }}" class="c-back-btn back-btn">戻る</a>

                <div class="fixed-shift-create">
                    <div class="input-area --employee">
                        <p class="input-area__title">従業員</p>
                        <select name="" class="c-select" id="">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area">
                        <p class="input-area__title">曜日</p>
                        <div class="input-area__check-box">
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                日曜日
                            </label>
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                月曜日
                            </label>
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                火曜日
                            </label>
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                水曜日
                            </label>
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                木曜日
                            </label>
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                金曜日
                            </label>
                            <label for="">
                                <input type="checkbox" name="" value="" class="">
                                土曜日
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

</x-app-layout>

{{-- script --}}
{{-- <script src="{{asset('js/info.js')}}"></script> --}}

