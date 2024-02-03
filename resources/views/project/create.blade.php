<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件作成') }}
        </h2>
    </x-slot>


    <div class="main">
        <div class="project">
            <div class="project-create">
                <form action="{{ route('project.store')}}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <button type="submit" class="mb-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">登録する</button>

                        {{-- クライアント名 --}}
                        <div class="client-wrap">
                            <div class="client-input-area">
                                <label for="">クライアント名（ID）</label>
                                <input type="text" name="clientName" class="input" placeholder="">
                            </div>
                            <div class="client-input-area">
                                <label for="">クライアント名（PDF使用時）</label>
                                <input type="text" name="clientNameByPDF" class="input" placeholder="">
                            </div>
                        </div>

                        <div class="" id="projectsContainer">
                        {{-- 案件情報 --}}
                        <div class="project-info-wrap sec-margin" id="project0">
                            {{-- 案件名 --}}
                            <div class="project-info-wrap__project">
                                <label class="">案件名</label>
                                <input type="text" name="projects[0][name]" class="input" placeholder="⚪︎⚪︎案件" required>
                            </div>

                            {{-- チャーター --}}
                            <div class="project-info-wrap__charter block-margin">
                                <p class="">チャーター</p>
                                <input type="checkbox" name="projects[0][is_charter]" value="1">
                            </div>

                            {{-- 歩合・日給 --}}
                            <div class="project-info-wrap__payment block-margin">
                                <p class="">給与形態</p>
                                <div class="project-info-wrap__payment__radio">
                                    <div class="radio">
                                        <input id="default-radio-1" type="radio" value="0" name="projects[0][payment_type]" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-1" class="">歩合</label>
                                    </div>
                                    <div class="radio">
                                        <input checked id="default-radio-2" type="radio" value="1" name="projects[0][payment_type]" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-2" class="">日給</label>
                                    </div>
                                </div>
                            </div>

                            {{-- 金額 --}}
                            <div class="project-info-wrap__amount block-margin">
                                <div class="project-info-wrap__amount__input">
                                    <label class="">上代</label>
                                    <input type="text" name="projects[0][retail_price]" class="input" placeholder="00" required>
                                </div>
                                <div class="project-info-wrap__amount__input">
                                    <label class="">給与</label>
                                    <input type="text" name="projects[0][driver_price]" class="input" placeholder="00" required>
                                </div>
                            </div>

                            {{-- 休日 --}}
                            <div class="project-info-wrap__holiday block-margin">
                                <p class="">休日</p>
                                <div class="project-info-wrap__holiday__checkbox">
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][monday]" value="1"> 月曜日</label><br>
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][tuesday]" value="1"> 火曜日</label><br>
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][wednesday]" value="1"> 水曜日</label><br>
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][thursday]" value="1"> 木曜日</label><br>
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][friday]" value="1"> 金曜日</label><br>
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][saturday]" value="1"> 土曜日</label><br>
                                    <label class="checkbox-label"><input type="checkbox" name="projects[0][holidays][sunday]" value="1"> 日曜日</label><br>
                                </div>
                            </div>

                            {{-- 残業 --}}
                            <div class="project-info-wrap__overtime-wrap block-margin">
                                <p class="">残業</p>
                                <div class="input-area">
                                    <label for="">見込み残業時間</label>
                                    <input type="text" name="projects[0][estimated_overtime_hours]" class="input">
                                </div>
                                <div class="input-area">
                                    <label for="">一時間あたりの時給</label>
                                    <input type="text" name="projects[0][overtime_hourly_wage]" class="input">
                                </div>
                            </div>

                            <div class="employee-wrap sec-margin">
                                <p class="">従業員別日給</p>
                                <div class="employees-block">
                                    <div class="employee-status-block block-margin">
                                        <p class="">正社員</p>
                                        <div class="employee-input-area">
                                            @foreach ($employees as $employee)
                                                @if ($employee->employment_status == "正社員")
                                                    <div class="input-area">
                                                        <label for="">{{$employee->name}}</label>
                                                        <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="input" placeholder="00">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="employee-status-block block-margin">
                                        <p class="">アルバイト</p>
                                        <div class="employee-input-area">
                                            @foreach ($employees as $employee)
                                                @if ($employee->employment_status == "アルバイト")
                                                    <div class="input-area">
                                                        <label for="">{{$employee->name}}</label>
                                                        <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="input" placeholder="00">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="employee-status-block block-margin">
                                        <p class="">個人事業主</p>
                                        <div class="employee-input-area">
                                            @foreach ($employees as $employee)
                                                @if ($employee->employment_status == "個人事業主")
                                                    <div class="input-area">
                                                        <label for="">{{$employee->name}}</label>
                                                        <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="input" placeholder="00">
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
                                                        <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="input" placeholder="00">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="" id="addProject">追加</div>
                    </div>
                </form>

                <form class="csv " action="{{ route('project.csv') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="csv_file">
                    <button class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">インポート</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var employees = @json($employees);

        window.addEventListener('load', () => {
            document.getElementById('addProject').addEventListener('click', function() {
            var container = document.getElementById('projectsContainer');
            var newProjectIndex = container.getElementsByClassName('project-info-wrap').length;
            var newProject = document.createElement('div');
            newProject.className = 'project-info-wrap sec-margin';
            newProject.innerHTML = `
                <div class="create-delete-button">削除</div>
                <!-- 案件名 -->
                <div class="project-info-wrap__project">
                    <label>案件名</label>
                    <input type="text" name="projects[${newProjectIndex}][name]" class="input" placeholder="案件名" required>
                </div>
                <div class="project-info-wrap__charter block-margin">
                    <p class="">チャーター</p>
                    <input type="checkbox" name="projects[${newProjectIndex}][is_charter]" value="1">
                </div>
                <!-- 歩合・日給 -->
                <div class="project-info-wrap__payment block-margin">
                    <p>給与形態</p>
                    <div class="project-info-wrap__payment__radio">
                        <div class="radio">
                            <input type="radio" value="0" name="projects[${newProjectIndex}][payment_type]" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label>歩合</label>
                        </div>
                        <div class="radio">
                            <input type="radio" value="1" name="projects[${newProjectIndex}][payment_type]" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                            <label>日給</label>
                        </div>
                    </div>
                </div>
                <!-- 金額 -->
                <div class="project-info-wrap__amount block-margin">
                    <div class="project-info-wrap__amount__input">
                        <label>上代</label>
                        <input type="text" name="projects[${newProjectIndex}][retail_price]" class="input" placeholder="00" required>
                    </div>
                    <div class="project-info-wrap__amount__input">
                        <label>給与</label>
                        <input type="text" name="projects[${newProjectIndex}][driver_price]" class="input" placeholder="00" required>
                    </div>
                </div>
                <!-- 休日 -->
                <div class="project-info-wrap__holiday block-margin">
                    <p>休日</p>
                    <div class="project-info-wrap__holiday__checkbox">
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][monday]" value="1"> 月曜日</label><br>
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][tuesday]" value="1"> 火曜日</label><br>
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][wednesday]" value="1"> 水曜日</label><br>
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][thursday]" value="1"> 木曜日</label><br>
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][friday]" value="1"> 金曜日</label><br>
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][saturday]" value="1"> 土曜日</label><br>
                        <label class="checkbox-label"><input type="checkbox" name="projects[${newProjectIndex}][holidays][sunday]" value="1"> 日曜日</label><br>
                    </div>
                </div>
                <!-- 残業 -->
                <div class="project-info-wrap__overtime-wrap block-margin">
                    <p>残業</p>
                    <div class="input-area">
                        <label for="">見込み残業時間</label>
                        <input type="text" name="projects[${newProjectIndex}][estimated_overtime_hours]" class="input">
                    </div>
                    <div class="input-area">
                        <label for="">一時間あたりの時給</label>
                        <input type="text" name="projects[${newProjectIndex}][overtime_hourly_wage]" class="input">
                    </div>
                </div>
            `;

            // 従業員別日給情報の追加
            var employees = @json($employees); // Laravel Bladeで従業員データをJSON形式で取得
            var employeeGroups = { '正社員': [], 'アルバイト': [], '個人事業主': [], '未登録': [] };

            // 従業員をグループ化
            employees.forEach(function(employee) {
                var status = employee.employment_status || '未登録';
                if (!employeeGroups[status]) {
                    employeeGroups[status] = [];
                }
                employeeGroups[status].push(employee);
            });

            var employeePaymentsHtml = '<div class="employee-wrap sec-margin">';

            // 各グループごとにHTMLを生成
            Object.keys(employeeGroups).forEach(function(status) {
                employeePaymentsHtml += `<div class="employees-block"><div class="employee-status-block block-margin">
                    <p>${status}</p>
                    <div class="employee-input-area">`;

                employeeGroups[status].forEach(function(employee) {
                    employeePaymentsHtml += `
                        <div class="input-area">
                            <label for="">${employee.name}</label>
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="input" placeholder="00">
                        </div>`;
                });

                employeePaymentsHtml += '</div></div></div>';
            });

            employeePaymentsHtml += '</div>';
            newProject.innerHTML += employeePaymentsHtml;

            container.appendChild(newProject);
        });
        })

    </script>
</x-app-layout>


{{-- script --}}
<script src="{{asset('js/project.js')}}"></script>
