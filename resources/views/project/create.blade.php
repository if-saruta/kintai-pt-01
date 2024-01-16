<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件作成') }}
        </h2>
    </x-slot>


    <div class="main">
        <div class="project-create-wrap">
            <form action="{{ route('project.store')}}" method="POST">
                @csrf
                <div class="mb-6">
                    <button type="submit" class="mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">登録する</button>

                    {{-- クライアント名 --}}
                    <div class="client-wrap">
                        <div class="client-input-area">
                            <label for="">クライアント名（ID）</label>
                            <input type="text" name="clientName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                        </div>
                        <div class="client-input-area">
                            <label for="">クライアント名（PDF使用時）</label>
                            <input type="text" name="clientNameByPDF" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                        </div>
                    </div>
                    {{-- 案件名 --}}
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">案件名</label>
                  <input type="text" name="name" class="mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="⚪︎⚪︎案件" required>

                    <div class="flex items-center mb-4">
                        <input id="default-radio-1" type="radio" value="0" name="type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">歩合</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input checked id="default-radio-2" type="radio" value="1" name="type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">日給</label>
                    </div>

                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">上代</label>
                  <input type="text" name="retail_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00" required>
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">賃金</label>
                  <input type="text" name="driver_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00" required>

                  {{-- 休日 --}}
                <div class="project-holiday-wrap">
                    <p class="">休日</p>
                    <div class="input-area">
                        <label><input type="checkbox" name="monday" value="1"> 月曜日</label><br>
                        <label><input type="checkbox" name="tuesday" value="1"> 火曜日</label><br>
                        <label><input type="checkbox" name="wednesday" value="1"> 水曜日</label><br>
                        <label><input type="checkbox" name="thursday" value="1"> 木曜日</label><br>
                        <label><input type="checkbox" name="friday" value="1"> 金曜日</label><br>
                        <label><input type="checkbox" name="saturday" value="1"> 土曜日</label><br>
                        <label><input type="checkbox" name="sunday" value="1"> 日曜日</label><br>
                    </div>
                </div>

                {{-- 残業 --}}
                <div class="project-overtime-wrap">
                    <p class="">残業</p>
                    <div class="input-area">
                        <label for="">見込み残業時間</label>
                        <input type="text" name="overtime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="input-area">
                        <label for="">一時間あたりの時給</label>
                        <input type="text" name="overtime_hourly_wage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                </div>

                  {{-- @foreach ($employees as $employee)
                    <div class="flex">
                        <p class="mt-4">{{$employee->name}}</p>
                        <input type="text" name="employeePrice[{{$employee->id}}]" class="mt-4 mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                    </div>
                  @endforeach --}}

                  <div class="employee-wrap">
                    <p class="">従業員別日給</p>
                    <div class="employees-block">
                        <div class="employee-status-block">
                            <p class="">正社員</p>
                            <div class="employee-input-area">
                                @foreach ($employees as $employee)
                                    @if ($employee->employment_status == "正社員")

                                        <div class="input-area">
                                            <label for="">{{$employee->name}}</label>
                                            <input type="text" name="employeePrice[{{$employee->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="employee-status-block">
                            <p class="">アルバイト</p>
                            <div class="employee-input-area">
                                @foreach ($employees as $employee)
                                    @if ($employee->employment_status == "アルバイト")
                                        <div class="input-area">
                                            <label for="">{{$employee->name}}</label>
                                            <input type="text" name="employeePrice[{{$employee->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="employee-status-block">
                            <p class="">個人事業主</p>
                            <div class="employee-input-area">
                                @foreach ($employees as $employee)
                                    @if ($employee->employment_status == "個人事業主")
                                        <div class="input-area">
                                            <label for="">{{$employee->name}}</label>
                                            <input type="text" name="employeePrice[{{$employee->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="employee-status-block">
                            <p class="">未登録</p>
                            <div class="employee-input-area">
                                @foreach ($employees as $employee)
                                    @if ($employee->employment_status == null)
                                        <div class="input-area">
                                            <label for="">{{$employee->name}}</label>
                                            <input type="text" name="employeePrice[{{$employee->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                  </div>

                </div>
            </form>

            <form class="csv " action="{{ route('project.csv') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <button class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">インポート</button>
            </form>
        </div>
    </div>

</x-app-layout>
