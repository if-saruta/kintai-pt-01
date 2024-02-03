<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員編集') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="employee">
            <div class="employee-create-wrap employee-create">
                <form action="{{ route('employee.update',['id'=>$employee->id])}}" method="POST">
                    @csrf
                    {{-- 登録番号 --}}
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">登録番号</label>
                        <input type="text" name="register_number" value="{{$employee->register_number}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="T838594903" required>
                    </div>
                    {{-- 従業員名 --}}
                    <div class="mb-6">
                      <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">従業員名</label>
                      <input type="text" value="{{$employee->name}}" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{$employee->name}}" required>
                    </div>
                    {{-- 住所 --}}
                    <div class="address-post">
                        <div class="address-post__input --post">
                            <label for="">郵便番号</label>
                            <input type="text" name="post_code" value="{{$employee->post_code}}" class="input">
                        </div>
                        <div class="address-post__input --address">
                            <label for="">住所</label>
                            <input type="text" name="address" value="{{$employee->address}}" class="input">
                        </div>
                    </div>
                    {{-- 銀行情報 --}}
                    <div class="bank">
                        <div class="bank__block">
                            <div class="bank__block__input">
                                <label for="">銀行名</label>
                                <input type="text" name="bank_name" value="{{$employee->bankAccounts->first()->bank_name}}" class="input">
                            </div>
                        </div>
                        <div class="bank__block">
                            <div class="bank__block__input">
                                <label for="">口座名義人</label>
                                <input type="text" name="account_holder_name" value="{{$employee->bankAccounts->first()->account_holder_name}}" class="input">
                            </div>
                        </div>
                    </div>
                    {{-- 所属先 --}}
                    <div class="mb-6">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">所属先</label>
                        <select name="company" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach ($companies as $company)
                                @if ($employee->company_id == $company->id)
                                    <option selected value="{{$company->id}}">{{$company->name}}</option>
                                @else
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{-- 雇用形態 --}}
                    <div class="flex items-center mb-4">
                        @if ($employee->employment_status == '正社員')
                            <input checked id="default-radio-1" type="radio" value="正社員" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                            <input id="default-radio-1" type="radio" value="正社員" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">正社員</label>
                    </div>
                    <div class="flex items-center mb-4">
                        @if ($employee->employment_status == 'アルバイト')
                            <input checked id="default-radio-2" type="radio" value="アルバイト" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                            <input id="default-radio-2" type="radio" value="アルバイト" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">アルバイト</label>
                    </div>
                    <div class="flex items-center mb-4">
                        @if ($employee->employment_status == '個人事業主')
                            <input checked id="default-radio-2" type="radio" value="個人事業主" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @else
                            <input id="default-radio-2" type="radio" value="個人事業主" name="status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        @endif
                        <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">個人事業主</label>
                    </div>
                    {{-- インボイス --}}
                    <div class="employee__create__invoice">
                        <p class="title">インボイス登録</p>
                        <div class="invoice-radio-wrap">
                            <div class="invoice-radio-wrap__radio">
                                <input type="radio" id="invoice-1" name="invoice" value="1" @if ($employee->is_invoice == 1) checked @endif>
                                <label for="invoice-1">登録</label>
                            </div>
                            <div class="invoice-radio-wrap__radio">
                                <input type="radio" id="invoice-2" name="invoice" value="0" @if ($employee->is_invoice == 0) checked @endif>
                                <label for="invoice-2">未登録</label>
                            </div>
                        </div>
                    </div>
                    {{-- 車両貸出形態 --}}
                    <div class="mt-4">
                        <p class="employee-create-retal-type__txt title">車両貸出形態</p>
                        <div class="employee-create-retal-type mt-3">
                            <select name="rental_type" id="" class="rental_type js-rental-type-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0" {{ $employee->vehicle_rental_type == 0 ? 'selected' : '' }}>自車</option>
                                <option value="1" {{ $employee->vehicle_rental_type == 1 ? 'selected' : '' }}>月リース</option>
                                <option value="2" {{ $employee->vehicle_rental_type == 2 ? 'selected' : '' }}>なんでも月リース</option>
                                <option value="3" {{ $employee->vehicle_rental_type == 3 ? 'selected' : '' }}>日割り</option>
                            </select>
                        </div>
                        <div class="employee-create-retal-vehicle js-rental-vehicle">
                            <label for="">貸出車両</label>
                            <select name="vehicle" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">選択してください</option>
                                @foreach ($vehicles as $vehicle)
                                    @if ($vehicle->id == $employee->vehicle_id)
                                        <option selected value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                    @else
                                        <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- 案件別給与 --}}
                    <div class="mt-4 salary-by-project">
                        <p class="salary-by-project__head title">案件別給与</p>
                        <div class="salary-by-project__project">
                            @foreach ($projectPayments as $projectPayment)
                                @foreach ($projectEmployeePayments as $projectEmployeePayment)
                                    @if ($projectPayment->id == $projectEmployeePayment->project_id)
                                        <div class="project-name">
                                            <p>{{$projectPayment->name}}</p>
                                            <input value="{{$projectEmployeePayment->amount}}" type="text" name="employeePrice[{{$projectPayment->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    {{-- 案件別手当 --}}
                    <div class="allowance-by-project">
                        <p class="allowance-by-project title">案件別手当</p>
                        <input hidden type="text" name="allowanceProjectDeleteId[]" id="tmpAllowanceProjectDeleteId">
                        @foreach ($projects as $project)
                        <?php $isCheckPorject = false ?>
                            <div class="allowance-by-project__item">
                                <div class="allowance-by-project__item__project">
                                    <p>{{$project->name}}</p>
                                </div>
                                {{-- 登録済みの手当を表示 --}}
                                @foreach ($allowanceProjectAmounts as $allowanceProjectAmount)
                                    @if ($project->id == $allowanceProjectAmount->project_id)
                                        <?php $isCheckPorject = true ?>
                                        <div class="allowance-by-project__item__allowance-block">
                                            <div class="allowance-info --allowance-name">
                                                <label for="">手当名</label>
                                                <input type="text" value="{{$allowanceProjectAmount->allowanceName}}" name="allowanceNameByEdit[{{$allowanceProjectAmount->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="手当名">
                                            </div>
                                            <div class="allowance-info">
                                                <label for="">金額</label>
                                                <input type="text" value="{{$allowanceProjectAmount->amount}}" name="allowanceAmountByEdit[{{$allowanceProjectAmount->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                            </div>
                                            <div class="allowance-project-delete-btn delete-btn" id="delete-btn-{{$allowanceProjectAmount->id}}">
                                                <span class="delete-btn__line"></span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                {{-- 未登録の手当を表示 --}}
                                @if (!$isCheckPorject)
                                    <div class="allowance-by-project__item__allowance-block">
                                        <div class="allowance-info --allowance-name">
                                            <label for="">手当名</label>
                                            <input type="text" name="allowanceName[{{$project->id}}][]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="手当名">
                                        </div>
                                        <div class="allowance-info">
                                            <label for="">金額</label>
                                            <input type="text" name="allowanceAmount[{{$project->id}}][]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                        <div class="allowance-project-delete-btn delete-btn">
                                            <span class="delete-btn__line"></span>
                                        </div>
                                    </div>
                                @endif
                                <div class="add-allowance-btn add-allowance" id="add-allowance-btn-{{$project->id}}">
                                    <p class="">手当追加</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- その他手当 --}}
                    <div class="allowance-by-other">
                        <p class="allowance-by-other__title">その他手当</p>
                        <div class="allowance-by-other__inner">
                            <input hidden type="text" name="allowanceOtherDeleteId[]" id="tmpAllowanceOtherDeleteId">
                            @if (!$allowanceOtherAmounts->isEmpty())
                                @foreach ($allowanceOtherAmounts as $allowanceOtherAmount)
                                    <div class="item">
                                        <div class="item__allowance-input allowance-name">
                                            <label for="">手当名</label>
                                            <input type="text" value="{{$allowanceOtherAmount->allowanceName}}" name="allowanceOtherNameEdit[{{$allowanceOtherAmount->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="手当名">
                                        </div>
                                        <div class="item__allowance-input allowance-amount">
                                            <label for="">金額</label>
                                            <input type="text" value="{{$allowanceOtherAmount->amount}}" name="allowanceOtherAmountEdit[{{$allowanceOtherAmount->id}}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                        </div>
                                        <div class="allowance-other-delete-btn delete-btn" id="delete-btn-{{$allowanceOtherAmount->id}}">
                                            <span class="delete-btn__line"></span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="item">
                                    <div class="item__allowance-input allowance-name">
                                        <label for="">手当名</label>
                                        <input type="text" name="allowanceOtherName[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="手当名">
                                    </div>
                                    <div class="item__allowance-input allowance-amount">
                                        <label for="">金額</label>
                                        <input type="text" name="allowanceOtherAmount[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00">
                                    </div>
                                    <div class="allowance-other-delete-btn delete-btn">
                                        <span class="delete-btn__line"></span>
                                    </div>
                                </div>
                            @endif
                            <div class="btn a default-btn" id="add-other-allowance">
                                <p class="">手当追加</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">登録する</button>
                </form>

                <form class="csv" action="{{ route('employee.csv') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="csv_file">
                    <button class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">インポート</button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/employee.js')}}"></script>
