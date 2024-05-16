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
            <form action="{{route('project.update',['id' => $client->id])}}" method="POST" class="client-main">
                @csrf
                <div class="client-head-box">
                    <div class="client-head-box__inner">
                        <div class="client-name-area">
                            <p class="client-name-area__head-title">クライアント名</p>
                            <input type="text" name="clientName" class="c-input client-name-input" value="{{$client->name}}" required>
                        </div>
                        {{-- ボタン --}}
                        <div class="btn-area">
                            <button class="btn --save" type="submit" name="action" value="save">
                                入力内容を登録
                            </button>
                            <a href="{{route('project.delete', ["id" => $client->id])}}" class="btn --delete" type="submit" name="action" value="delete"
                                onclick='return confirm("案件も含め削除されます")'>
                                所属先を削除
                            </a>
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
                        <input type="text" name="clientNameByPDF" class="c-input" value="{{$client->pdfName}}" required>
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
                            @foreach ( $projects as $project )
                            {{-- 案件 --}}
                            <div class="list-item acc-wrap project-info-wrap">
                                <div class="list-item__inner">
                                    <!-- クリックする要素 -->
                                    <div class="list-item__head acc-title accordionBtn">
                                        <div class="list-item__head__inner">
                                            {{-- 案件名 --}}
                                            <div class="project-input">
                                                <p class="">案件名</p>
                                                <input type="text" name="editProjects[{{$project->id}}][name]" class="c-input" value="{{$project->name}}" required>
                                            </div>
                                            <a href="{{ route('project.info', ['id' => $project->id]) }}" class="project-info-link projectInfoLink">
                                                案件表
                                            </a>
                                            <i class="fa-solid fa-angle-up angle"></i>
                                        </div>
                                    </div>
                                    <!-- 開閉する要素 -->
                                    <div class="list-item__content acc-content accordionCt js-accordion-close">
                                        {{-- チャーター --}}
                                        <div class="charter-box">
                                            <label for="charter" class="input-head">チャーター案件</label>
                                            <div class="toggle isCharter @if($project->is_charter == 1) checked @endif">
                                                <input type="checkbox" name="editProjects[{{$project->id}}][is_charter]" @if($project->is_charter == 1) checked @endif value="1" class="toggle-input" id="charter" />
                                            </div>
                                        </div>
                                        <div class="suspended-box">
                                            <label for="charter" class="input-head">休止</label>
                                            <div class="toggle @if($project->is_suspended == 1) checked @endif">
                                                <input type="checkbox" name="editProjects[{{$project->id}}][is_suspended]" @if($project->is_suspended == 1) checked @endif value="1" class="toggle-input"/>
                                            </div>
                                        </div>
                                        {{-- ドライバー価格形式 --}}
                                        <div class="salary-type-box">
                                            <div class="head input-head">
                                                <p class="">ドライバー価格形態</p>
                                                {{-- <p class="item-type">必須</p> --}}
                                            </div>
                                            <div class="salary-type-box__input-area">
                                                <div class="input-item flex-10">
                                                    <input type="radio" name="editProjects[{{$project->id}}][payment_type]" value="0" @if($project->payment_type == 0) checked @endif class="commission" id="salary-type-1">
                                                    <label for="salary-type-1" class="label-txt">歩合</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="radio" name="editProjects[{{$project->id}}][payment_type]" value="1" @if($project->payment_type == 1) checked @endif class="commission" id="salary-type-2">
                                                    <label for="salary-type-2" class="label-txt">日給</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ドライバー価格・上代 --}}
                                        <div class="amount-box">
                                            <div class="amount-box__item">
                                                <p class="input-head">上代</p>
                                                <div class="amount-input-wrap amountInputWrap retailInput @if($project->payment_type == 0) not-input @endif @if($project->is_charter == 1) charterActive  @endif">
                                                    <input type="text" name="editProjects[{{$project->id}}][retail_price]" value="{{$project->retail_price}}" class="c-input commaInput" placeholder="1,000" @if($project->payment_type == 1) required @endif>
                                                </div>
                                            </div>
                                            <div class="amount-box__item">
                                                <p class="input-head">ドライバー価格</p>
                                                <div class="amount-input-wrap amountInputWrap salaryInput @if($project->payment_type == 0) not-input @endif @if($project->is_charter == 1) charterActive  @endif">
                                                    <input type="text" name="editProjects[{{$project->id}}][driver_price]" value="{{$project->driver_price}}" class="c-input commaInput" placeholder="1,000" @if($project->payment_type == 1) required @endif>
                                                </div>
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
                                                    <input type="checkbox" @if($project->holiday->monday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][monday]" value="1" id="day-of-week-1">
                                                    <label for="day-of-week-1" class="label-txt">月曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->tuesday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][tuesday]" value="1" id="day-of-week-2">
                                                    <label for="day-of-week-2" class="label-txt">火曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->wednesday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][wednesday]" value="1" id="day-of-week-3">
                                                    <label for="day-of-week-3" class="label-txt">水曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->thursday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][thursday]" value="1" id="day-of-week-4">
                                                    <label for="day-of-week-4" class="label-txt">木曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->friday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][friday]" value="1" id="day-of-week-5">
                                                    <label for="day-of-week-5" class="label-txt">金曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->saturday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][saturday]" value="1" id="day-of-week-6">
                                                    <label for="day-of-week-6" class="label-txt">土曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->sunday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][sunday]" value="1" id="day-of-week-7">
                                                    <label for="day-of-week-7" class="label-txt">日曜日</label>
                                                </div>
                                                <div class="input-item flex-10">
                                                    <input type="checkbox" @if($project->holiday->public_holiday ==  1) checked @endif name="editProjects[{{$project->id}}][holidays][public_holiday]" value="1" id="day-of-week-8">
                                                    <label for="day-of-week-8" class="label-txt">祝日</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 残業 --}}
                                        <div class="over-time-box">
                                            <p class="input-head">残業</p>
                                            <div class="over-time-box__input-area">
                                                <div class="input-item">
                                                    <label for="" class="label-txt">見込み残業時間</label>
                                                    <input type="text" name="editProjects[{{$project->id}}][estimated_overtime_hours]" class="c-input" value="{{$project->estimated_overtime_hours}}">
                                                </div>
                                                <div class="input-item">
                                                    <label for="" class="label-txt">残業1時間あたりの時給</label>
                                                    <input type="text" name="editProjects[{{$project->id}}][overtime_hourly_wage]" class="c-input" value="{{$project->overtime_hourly_wage}}">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 手当 --}}
                                        <div class="allowance">
                                            <div class="allowance__head">
                                                <p class="input-head">手当</p>
                                                {{-- <div class="plus-box allowanceAddBtn" id="allowanceAddBtn">
                                                    <i class="fa-solid fa-plus allowanceAddIcon"></i>
                                                </div> --}}
                                            </div>
                                            <div class="allowance__content" id="allowanceCt">
                                                @foreach ($project->allowances as $allowance)
                                                    <div class="allowance__content__item">
                                                        <div class="input-wrap required">
                                                            <p class="">必須</p>
                                                            <input type="checkbox" name="editProjects[{{$project->id}}][allowances][{{ $allowance->id }}][is_required]" value="1" @if($allowance->is_required == 1) checked @endif>
                                                        </div>
                                                        <div class="input-wrap">
                                                            <p class="">手当名</p>
                                                            <input type="text" class="c-input" name="editProjects[{{$project->id}}][allowances][{{ $allowance->id }}][allowance_name]" value="{{ $allowance->name }}" placeholder="リーダー手当">
                                                        </div>
                                                        <div class="input-wrap">
                                                            <p class="">手当上代</p>
                                                            <input type="text" class="c-input commaInput" name="editProjects[{{$project->id}}][allowances][{{ $allowance->id }}][allowance_retail_amount]" value="{{ number_format($allowance->retail_amount) }}" placeholder="1,000">
                                                        </div>
                                                        <div class="input-wrap">
                                                            <p class="">手当ドライバー価格</p>
                                                            <input type="text" class="c-input commaInput" name="editProjects[{{$project->id}}][allowances][{{ $allowance->id }}][allowance_driver_amount]" value="{{ number_format($allowance->driver_amount) }}" placeholder="1,000">
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if ($project->allowances->count() == 0)
                                                    <div class="allowance__content__item">
                                                        <div class="input-wrap required">
                                                            <p class="">必須</p>
                                                            <input type="checkbox" name="editProjects[{{$project->id}}][is_required]" value="1">
                                                        </div>
                                                        <div class="input-wrap">
                                                            <p class="">手当名</p>
                                                            <input type="text" class="c-input" name="editProjects[{{$project->id}}][allowance_name]" placeholder="リーダー手当">
                                                        </div>
                                                        <div class="input-wrap">
                                                            <p class="">手当上代</p>
                                                            <input type="text" class="c-input commaInput" name="editProjects[{{$project->id}}][allowance_retail_amount]" placeholder="1,000">
                                                        </div>
                                                        <div class="input-wrap">
                                                            <p class="">手当ドライバー価格</p>
                                                            <input type="text" class="c-input commaInput" name="editProjects[{{$project->id}}][allowance_driver_amount]" placeholder="1,000">
                                                        </div>
                                                    </div>
                                                @endif
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
                                                            @php
                                                                $isEmployee = false;
                                                            @endphp
                                                            @foreach ($employees as $employee)
                                                                @if ($employee->employment_status == '正社員')
                                                                    @foreach ( $project->payments as $record )
                                                                        @if ($record->employee_id == $employee->id)
                                                                        <div class="item">
                                                                            <p class="">{{$employee->name}}</p>
                                                                            <input type="text" name="editProjects[{{$project->id}}][employeePayments][{{$employee->id}}]" class="c-input commaInput" value="{{$record->amount}}">
                                                                        </div>
                                                                        @php
                                                                            $isEmployee = true;
                                                                        @endphp
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                            @if (!$isEmployee)
                                                                <p class="employee-list__warning-txt">正社員は登録されていません</p>
                                                            @endif
                                                        </div>
                                                        <div class="employee-list employeeList02">
                                                            @php
                                                                $isEmployee = false;
                                                            @endphp
                                                            @foreach ($employees as $employee)
                                                                @if ($employee->employment_status == '個人事業主')
                                                                    @foreach ( $project->payments as $record )
                                                                        @if ($record->employee_id == $employee->id)
                                                                        <div class="item">
                                                                            <p class="">{{$employee->name}}</p>
                                                                            <input type="text" name="editProjects[{{$project->id}}][employeePayments][{{$employee->id}}]" class="c-input commaInput" value="{{$record->amount}}">
                                                                        </div>
                                                                        @php
                                                                            $isEmployee = true;
                                                                        @endphp
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                            @if (!$isEmployee)
                                                                <p class="employee-list__warning-txt">個人事業主は登録されていません</p>
                                                            @endif
                                                        </div>
                                                        <div class="employee-list employeeList03">
                                                            @php
                                                                $isEmployee = false;
                                                            @endphp
                                                            @foreach ($employees as $employee)
                                                                @if ($employee->employment_status == 'アルバイト')
                                                                    @foreach ( $project->payments as $record )
                                                                        @if ($record->employee_id == $employee->id)
                                                                        <div class="item">
                                                                            <p class="">{{$employee->name}}</p>
                                                                            <input type="text" name="editProjects[{{$project->id}}][employeePayments][{{$employee->id}}]" class="c-input commaInput" value="{{$record->amount}}">
                                                                        </div>
                                                                        @php
                                                                            $isEmployee = true;
                                                                        @endphp
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                            @if (!$isEmployee)
                                                                <p class="employee-list__warning-txt">アルバイトは登録されていません</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ボタン --}}
                                        <div class="btn-area --project-btn-area">
                                            <a href="{{route('project.projectDelete',["id" => $project->id])}}" class="btn --delete" type="submit" name="action" value="delete"
                                                onclick='return confirm("本当に削除しますか?")'>
                                                案件を削除
                                            </a>
                                            {{-- <div class="btn --back closeBtn" onclick='return confirm("入力したデータは失われます。")'>
                                                閉じる
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
                    {{-- 休止 --}}
                    <div class="suspended-box">
                        <label for="charter" class="input-head">休止</label>
                        <div class="toggle">
                            <input type="checkbox" name="projects[${newProjectIndex}][is_suspended]" value="1" class="toggle-input"/>
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
                                <input type="radio" name="projects[${newProjectIndex}][payment_type]" value="0" class="commission" id="salary-type-1${newProjectIndex}">
                                <label for="salary-type-1${newProjectIndex}" class="label-txt">歩合</label>
                            </div>
                            <div class="input-item flex-10">
                                <input checked type="radio" name="projects[${newProjectIndex}][payment_type]" value="1" class="commission" id="salary-type-2${newProjectIndex}">
                                <label for="salary-type-2${newProjectIndex}" class="label-txt">日給</label>
                            </div>
                        </div>
                    </div>
                    {{-- ドライバー価格・上代 --}}
                    <div class="amount-box">
                        <div class="amount-box__item">
                            <p class="input-head">上代</p>
                            <div class="amount-input-wrap amountInputWrap retailInput">
                                <input type="text" name="projects[${newProjectIndex}][retail_price]" class="c-input commaInput" placeholder="1,000" required>
                            </div>
                        </div>
                        <div class="amount-box__item">
                            <p class="input-head">ドライバー価格</p>
                            <div class="amount-input-wrap amountInputWrap salaryInput">
                                <input type="text" name="projects[${newProjectIndex}][driver_price]" class="c-input commaInput" placeholder="1,000" required>
                            </div>
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
                            <div class="input-item flex-10">
                                <input type="checkbox" name="projects[${newProjectIndex}][holidays][public_holiday]" value="1" id="day-of-week-8">
                                <label for="day-of-week-8" class="label-txt">祝日</label>
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
                    {{-- 手当 --}}
                        <div class="allowance">
                            <div class="allowance__head">
                                <p class="input-head">手当</p>
                                {{--
                                    <div class="plus-box allowanceAddBtn" id="allowanceAddBtn">
                                        <i class="fa-solid fa-plus allowanceAddIcon"></i>
                                    </div>
                                    --}}
                            </div>
                            <div class="allowance__content" id="allowanceCt">
                                <div class="allowance__content__item">
                                    <div class="input-wrap required">
                                        <p class="">必須</p>
                                        <input type="checkbox" name="projects[${newProjectIndex}][is_required]" value="1">
                                    </div>
                                    <div class="input-wrap">
                                        <p class="">手当名</p>
                                        <input type="text" class="c-input" name="projects[${newProjectIndex}][allowance_name]" placeholder="リーダー手当">
                                    </div>
                                    <div class="input-wrap">
                                        <p class="">手当上代</p>
                                        <input type="text" class="c-input commaInput" name="projects[${newProjectIndex}][allowance_retail_amount]" placeholder="1,000">
                                    </div>
                                    <div class="input-wrap">
                                        <p class="">手当ドライバー価格</p>
                                        <input type="text" class="c-input commaInput" name="projects[${newProjectIndex}][allowance_driver_amount]" placeholder="1,000">
                                    </div>
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
                        <div class="btn --delete create-delete-btn"
                            onclick='return confirm("本当に削除しますか?")'>
                            案件を削除
                        </div>
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
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="c-input commaInput" placeholder="000">
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
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="c-input commaInput" placeholder="000">
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
                            <input type="text" name="projects[${newProjectIndex}][employeePayments][${employee.id}]" class="c-input commaInput" placeholder="000">
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







