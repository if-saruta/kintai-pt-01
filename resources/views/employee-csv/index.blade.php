<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('csv選択') }}
        </h2>
    </x-slot>

    <div class="main">
        <form action="{{route('csv-employee.show')}}" method="POST">
            @csrf

            <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">年を選択</label>
            <select id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-200 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @for ($year = now()->year; $year >= now()->year - 10; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>

            <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">月を選択</label>
            <select id="month" name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-200 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>

            <label for="project" class="block mt-10 mb-2 text-sm font-medium text-gray-900 dark:text-white">従業員を選択</label>
            <select id="project" name="employee" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-200 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach ($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                @endforeach
            </select>
            <button type="submit" class="mt-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">検索する</button>
        </form>
    </div>

</x-app-layout>

