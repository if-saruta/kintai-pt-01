<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --employee-white-board">
            <div class="employee-list-wrap">
                <div class="sand-head --top">
                    <div class="sand-head__inner">
                        <p class="w-name txt">Name</p>
                        <p class="w-status txt">Employment status</p>
                        <p class="w-Affiliation txt">Affiliation</p>
                    </div>
                </div>
                <div class="list-body">
                    @foreach ($employeesByCompany as $company => $employees)
                        <div class="list-box">
                            <div class="list-body__company-row"><p class="">{{$company}}</p></div>
                            @foreach ($employees as $employee)
                                <div class="list-body__employee-row">
                                    <p class="w-name txt">{{$employee->name}}</p>
                                    <p class="w-status txt">{{$employee->employment_status}}</p>
                                    <p class="w-Affiliation txt">{{$employee->company->name}}</p>
                                    @can('admin-higher')
                                        <a href="{{route('employee.edit', ["id" => $employee->id])}}" class="edit-btn action-btn">
                                            <div class="edit-btn__inner">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <p class="edit-btn-txt">編集</p>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{route('employee.show', ["id" => $employee->id])}}" class="edit-btn action-btn">
                                            <div class="edit-btn__inner">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <p class="edit-btn-txt">詳細</p>
                                            </div>
                                        </a>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="sand-head --under">
                    <div class="sand-head__inner">
                        <p class="w-name txt">Name</p>
                        <p class="w-status txt">Employment status</p>
                        <p class="w-Affiliation txt">Affiliation</p>
                        @can('admin-higher')
                        <a href="{{route('employee.create')}}" class="add-btn" >
                            <div class="add-btn__inner">
                                <i class="fa-solid fa-circle-plus"></i>
                                <p class="">追加</p>
                            </div>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/employee.js')}}"></script>
