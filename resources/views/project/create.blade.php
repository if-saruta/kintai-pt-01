<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件作成') }}
        </h2>
    </x-slot>

    {{-- <script>
        window.onbeforeunload = function(e) {
            e.preventDefault();
            return '';
        };

    </script> --}}

    <main class="main">
        <div class="main__white-board --client-white-board">
            <form action="{{route('project.store')}}" method="POST" class="client-main">
                @csrf
                <div class="client-head-box">
                    <div class="client-head-box__inner">
                        <div class="client-name-area">
                            <p class="client-name-area__head-title">クライアント名</p>
                            <input type="text" name="clientName" class="c-input client-name-input" placeholder="株式会社example" required>
                        </div>
                        {{-- ボタン --}}
                        <div class="btn-area">
                            <button class="btn --save" type="submit" name="action" value="save">
                                入力内容を登録
                            </button>
                            {{-- <button class="btn --delete" type="submit" name="action" value="delete"
                                onclick='return confirm("本当に削除しますか?")'>
                                所属先を削除
                            </button> --}}
                            <a href="{{route('project.')}}" class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                                戻る
                            </a>
                        </div>
                    </div>
                </div>
                <div class="client-main__body">
                    {{-- クライアントPDF --}}
                    <div class="client-pdf-box">
                        <p class="client-pdf-box__txt">クライアント名（PDF使用時）</p>
                        <input type="text" name="clientNameByPDF" class="c-input" placeholder="株式会社example" required>
                    </div>
                    {{-- 案件リスト --}}
                    <div class="project-list">
                        <div class="project-list__head">
                            <div class="project-list__head__inner">
                                <p class="txt">案件一覧</p>
                                <div class="add-btn" id="addProject">
                                    <div class="add-btn__inner">
                                        <i class="fa-solid fa-circle-plus"></i>
                                        <p class="">追加</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="project-list__body accordionContainer" id="projectsContainer">
                            {{-- 案件 --}}
                            <div class="list-item acc-wrap project-info-wrap">
                                <div class="list-item__inner">
                                    <!-- クリックする要素 -->
                                    <div class="list-item__head acc-title accordionBtn">
                                        <div class="list-item__head__inner">
                                            {{-- 案件名 --}}
                                            <div class="project-input">
                                                <p class="">案件名</p>
                                                <input type="text" name="projects[0][name]" class="c-input" placeholder="example案件" required>
                                            </div>
                                            <i class="fa-solid fa-angle-up angle"></i>
                                        </div>
                                    </div>
                                    <!-- 開閉する要素 -->
                                    <div class="list-item__content acc-content accordionCt">
                                        {{-- チャーター --}}
                                        <div class="charter-box">
                                            <label for="charter" class="input-head">チャーター案件</label>
                                            <div class="toggle">
                                                <input type="checkbox" name="projects[0][is_charter]" value="1" class="toggle-input" id="charter" />
                                            </div>
                                        </div>
                                        {{-- ドライバー価格形式 --}}
                                        <div class="salary-type-box">
                                            <div class="head input-head">
                                                <p class="">ドライバー価格形態</p>
                                                <p class="item-type">必須</p>
                                            </div>
                                            <div class="salary-type-box__input-area">
                                                <div class="input-item flex-10">
                                                    <input type="radio" name="projects[0][payment_type]" value="0" id="salary-type-1">
                                                    <label for="salary-type-1" class="label-txt">歩合</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input checked type="radio" name="projects[0][payment_type]" value="1" id="salary-type-2">
                                                    <label for="salary-type-2" class="label-txt">日給</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ドライバー価格・上代 --}}
                                        <div class="amount-box">
                                            <div class="amount-box__item">
                                                <p class="input-head">上代</p>
                                                <input type="text" name="projects[0][retail_price]" class="c-input" placeholder="1,000" required>
                                            </div>
                                            <div class="amount-box__item">
                                                <p class="input-head">ドライバー価格</p>
                                                <input type="text" name="projects[0][driver_price]" class="c-input" placeholder="1,000" required>
                                            </div>
                                        </div>
                                        {{-- 休日 --}}
                                        <div class="holiday-box">
                                            <div class="head input-head">
                                                <p class="">休日</p>
                                                {{-- <p class="item-type any">任意</p> --}}
                                            </div>
                                            <div class="holiday-box__check-area">
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][monday]" value="1" id="day-of-week-1">
                                                    <label for="day-of-week-1" class="label-txt">月曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][tuesday]" value="1" id="day-of-week-2">
                                                    <label for="day-of-week-2" class="label-txt">火曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][wednesday]" value="1" id="day-of-week-3">
                                                    <label for="day-of-week-3" class="label-txt">水曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][thursday]" value="1" id="day-of-week-4">
                                                    <label for="day-of-week-4" class="label-txt">木曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][friday]" value="1" id="day-of-week-5">
                                                    <label for="day-of-week-5" class="label-txt">金曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][saturday]" value="1" id="day-of-week-6">
                                                    <label for="day-of-week-6" class="label-txt">土曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" name="projects[0][holidays][sunday]" value="1" id="day-of-week-7">
                                                    <label for="day-of-week-7" class="label-txt">日曜日</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 残業 --}}
                                        <div class="over-time-box">
                                            <p class="input-head">残業</p>
                                            <div class="over-time-box__input-area">
                                                <div class="input-item">
                                                    <label for="" class="label-txt">見込み残業時間</label>
                                                    <input type="text" name="projects[0][estimated_overtime_hours]" class="c-input" placeholder="000">
                                                </div>
                                                <div class="input-item">
                                                    <label for="" class="label-txt">残業1時間あたりの時給</label>
                                                    <input type="text" name="projects[0][overtime_hourly_wage]" class="c-input" placeholder="000">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 従業員別ドライバー価格 --}}
                                        <div class="employee-salary">
                                            <p class="input-head">ドライバー別支払い</p>
                                            <div class="employee-salary__list">
                                                <div class="employee-salary__list__tags-area">
                                                    <div class="tag open employeeTag01"><p class="">正社員</p></div>
                                                    <div class="tag employeeTag02"><p class="">個人事業主</p></div>
                                                    <div class="tag employeeTag03"><p class="">アルバイト</p></div>
                                                </div>
                                                <div class="employee-salary__list__body">
                                                    <div class="inner">
                                                        <div class="employee-list employee-list-open employeeList01">
                                                            @foreach ($employees as $employee)
                                                                @if ($employee->employment_status == '正社員')
                                                                <div class="item">
                                                                    <p class="">{{$employee->name}}</p>
                                                                    <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="c-input" placeholder="000">
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="employee-list employeeList02">
                                                            @foreach ($employees as $employee)
                                                                @if ($employee->employment_status == '個人事業主')
                                                                <div class="item">
                                                                    <p class="">{{$employee->name}}</p>
                                                                    <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="c-input" placeholder="000">
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="employee-list employeeList03">
                                                            @foreach ($employees as $employee)
                                                                @if ($employee->employment_status == 'アルバイト')
                                                                <div class="item">
                                                                    <p class="">{{$employee->name}}</p>
                                                                    <input type="text" name="projects[0][employeePayments][{{$employee->id}}]" class="c-input" placeholder="000">
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ボタン --}}
                                        {{-- <div class="btn-area --project-btn-area">
                                            <button class="btn --delete" type="submit" name="action" value="delete"
                                                onclick='return confirm("本当に削除しますか?")'>
                                                案件を削除
                                            </button>
                                            <div class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                                                閉じる
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <form action="{{ route('project.csv') }}" method="POST">
            @csrf
            <input type="file" name="csv_file">
            <button>インポート</button>
        </form>
    </main>

    <script>
        var employees = @json($employees);

        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('addProject').addEventListener('click', function() {
            var container = document.getElementById('projectsContainer');
            var newProjectIndex = container.getElementsByClassName('project-info-wrap').length;
            var newProject = document.createElement('div');
            newProject.className = 'list-item acc-wrap project-info-wrap';
            newProject.innerHTML = `
            <div class="list-item__inner">
                <!-- クリックする要素 -->
                <div class="list-item__head acc-title accordionBtn">
                    <div class="list-item__head__inner">
                        {{-- 案件名 --}}
                        <div class="project-input">
                            <p class="">案件名</p>
                            <input type="text" name="projects[${newProjectIndex}][name]" class="c-input" placeholder="example案件" required>
                        </div>
                        <i class="fa-solid fa-angle-up angle"></i>
                    </div>
                </div>
                <!-- 開閉する要素 -->
                <div class="list-item__content acc-content accordionCt">
                    {{-- チャーター --}}
                    <div class="charter-box">
                        <label for="charter" class="input-head">チャーター案件</label>
                        <div class="toggle">
                            <input type="checkbox" name="projects[${newProjectIndex}][is_charter]" class="toggle-input" value="1" id="charter${newProjectIndex}" />
                        </div>
                    </div>
                    {{-- ドライバー価格形式 --}}
                    <div class="salary-type-box">
                        <div class="head input-head">
                            <p class="">ドライバー価格形態</p>
                            <p class="item-type">必須</p>
                        </div>
                        <div class="salary-type-box__input-area">
                            <div class="input-item flex-10">
                                <input type="radio" name="projects[${newProjectIndex}][payment_type]" value="0" id="salary-type-1${newProjectIndex}">
                                <label for="salary-type-1${newProjectIndex}" class="label-txt">歩合</label>
                            </div>
                            <div class="input-item flex-10">
                                <input checked type="radio" name="projects[${newProjectIndex}][payment_type]" value="1" id="salary-type-2${newProjectIndex}">
                                <label for="salary-type-2${newProjectIndex}" class="label-txt">日給</label>
                            </div>
                        </div>
                    </div>
                    {{-- ドライバー価格・上代 --}}
                    <div class="amount-box">
                        <div class="amount-box__item">
                            <p class="input-head">上代</p>
                            <input type="text" name="projects[${newProjectIndex}][retail_price]" class="c-input" placeholder="1,000" required>
                        </div>
                        <div class="amount-box__item">
                            <p class="input-head">ドライバー価格</p>
                            <input type="text" name="projects[${newProjectIndex}][driver_price]" class="c-input" placeholder="1,000" required>
                        </div>
                    </div>
                    {{-- 休日 --}}
                    <div class="holiday-box">
                        <div class="head input-head">
                            <p class="">休日</p>
                            <p class="item-type any">任意</p>
                        </div>
                        <div class="holiday-box__check-area">
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][monday]" value="1" id="day-of-week-1">
                                <label for="day-of-week-1" class="label-txt">月曜日</label>
                            </div>
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][tuesday]" value="1" id="day-of-week-2">
                                <label for="day-of-week-2" class="label-txt">火曜日</label>
                            </div>
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][wednesday]" value="1" id="day-of-week-3">
                                <label for="day-of-week-3" class="label-txt">水曜日</label>
                            </div>
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][thursday]" value="1" id="day-of-week-4">
                                <label for="day-of-week-4" class="label-txt">木曜日</label>
                            </div>
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][friday]" value="1" id="day-of-week-5">
                                <label for="day-of-week-5" class="label-txt">金曜日</label>
                            </div>
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][saturday]" value="1" id="day-of-week-6">
                                <label for="day-of-week-6" class="label-txt">土曜日</label>
                            </div>
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][sunday]" value="1" id="day-of-week-7">
                                <label for="day-of-week-7" class="label-txt">日曜日</label>
                            </div>
                        </div>
                    </div>
                    {{-- 残業 --}}
                    <div class="over-time-box">
                        <p class="input-head">残業</p>
                        <div class="over-time-box__input-area">
                            <div class="input-item">
                                <label for="" class="label-txt">見込み残業時間</label>
                                <input type="text" name="projects[${newProjectIndex}][estimated_overtime_hours]" class="c-input" placeholder="000">
                            </div>
                            <div class="input-item">
                                <label for="" class="label-txt">残業1時間あたりの時給</label>
                                <input type="text" name="projects[${newProjectIndex}][overtime_hourly_wage]" class="c-input" placeholder="000">
                            </div>
                        </div>
                    </div>
                    {{-- 従業員別ドライバー価格 --}}
                    <div class="employee-salary">
                        <p class="input-head">従業員別ドライバー価格</p>
                        <div class="employee-salary__list">
                            <div class="employee-salary__list__tags-area">
                                <div class="tag open employeeTag01"><p class="">正社員</p></div>
                                <div class="tag employeeTag02"><p class="">個人事業主</p></div>
                                <div class="tag employeeTag03"><p class="">アルバイト</p></div>
                            </div>
                            <div class="employee-salary__list__body">
                                <div class="inner" id="inner${newProjectIndex}">

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ボタン --}}
                    <div class="btn-area --project-btn-area">
                        <button class="btn --delete" type="submit" name="action" value="delete"
                            onclick='return confirm("本当に削除しますか?")'>
                            案件を削除
                        </button>
                    </div>
                </div>
            </div>
            `;

            container.appendChild(newProject);
            var inner1 = document.getElementById('inner'+newProjectIndex);

            var employeePaymentsHtml01 = `<div class="employee-list employee-list-open employeeList01">`;
            employees.forEach(employee => {
                var employeeItem01 = '';
                if(employee.employment_status == '正社員'){
                    employeeItem01 = `
                        <div class="item">
                            <p class="">${employee.name}</p>
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="c-input" placeholder="000">
                        </div>
                    `;
                }
                employeePaymentsHtml01 += employeeItem01;
            });
            employeePaymentsHtml01 += `</div>`
            inner1.innerHTML += employeePaymentsHtml01;

            var employeePaymentsHtml02 = `<div class="employee-list employeeList02">`;
            employees.forEach(employee => {
                var employeeItem02 = '';
                if(employee.employment_status == '個人事業主'){
                    employeeItem02 = `
                        <div class="item">
                            <p class="">${employee.name}</p>
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="c-input" placeholder="000">
                        </div>
                    `;
                }
                employeePaymentsHtml02 += employeeItem02;
            });
            employeePaymentsHtml02 += `</div>`
            inner1.innerHTML += employeePaymentsHtml02;

            var employeePaymentsHtml03 = `<div class="employee-list employeeList03">`;
            employees.forEach(employee => {
                var employeeItem03 = '';
                if(employee.employment_status == 'アルバイト'){
                    employeeItem03 = `
                        <div class="item">
                            <p class="">${employee.name}</p>
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="c-input" placeholder="000">
                        </div>
                    `;
                }
                employeePaymentsHtml03 += employeeItem03;
            });
            employeePaymentsHtml03 += `</div>`
            inner1.innerHTML += employeePaymentsHtml03;

        });
        })

    </script>

</x-app-layout>


{{-- script --}}
<script src="{{asset('js/project.js')}}"></script>
