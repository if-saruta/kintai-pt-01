<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業一覧') }}
        </h2>
    </x-slot>

    <main class="main">

        <div class="main__white-board --client-white-board">
            <div class="fixed-shift-wrap">
                <a href="{{ route('project.fixedShift', ['id' => $id]) }}" class="c-back-btn back-btn">戻る</a>
                <p class="title">固定シフト : 新規作成</p>
                <p class="project-name">案件名 : {{ $project->name }}</p>

                <form action="{{ route('project.fixedShiftStore') }}" method="POST">
                    @csrf
                    <input hidden type="text" name="project_id" value="{{ $id }}">
                    <div class="fixed-shift-create">
                        <button type="submit" class="c-save-btn">
                            登録
                        </button>
                        @if ($errors->any())
                            <div class="error-message">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>・{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="flex-box">
                            <div class="input-area">
                                <p class="">ドライバー</p>
                                <select name="employee_id" class="c-select employee-select" id="">
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <p class="input-area__title">祝日の稼働</p>
                                <input type="checkbox" name="holiday_working" value="1">
                            </div>
                        </div>
                        <table class="fixed-shift-calendar">
                            <thead>
                                <tr>
                                    <th rowspan="2"></th>
                                    <th colspan="2">月</th>
                                    <th colspan="2">火</th>
                                    <th colspan="2">水</th>
                                    <th colspan="2">木</th>
                                    <th colspan="2">金</th>
                                    <th colspan="2" style="color: rgb(0, 123, 255);">土</th>
                                    <th colspan="2" style="color: red;">日</th>
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < 7; $i++)
                                        <th class="time_of_part">AM</th>
                                        <th class="time_of_part">PM</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 6; $i++)
                                    <tr>
                                        <td>{{ $i }}週目</td>
                                        @for ($j = 0; $j < 7; $j++)
                                            {{-- 午前OR午後 --}}
                                            <td><input type="checkbox" name="shift_details[{{ $i }}][{{ $j }}][am]" value="1"></td>
                                            <td><input type="checkbox" name="shift_details[{{ $i }}][{{ $j }}][pm]" value="1"></td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>

    </main>

</x-app-layout>

{{-- script --}}
{{-- <script src="{{asset('js/info.js')}}"></script> --}}

