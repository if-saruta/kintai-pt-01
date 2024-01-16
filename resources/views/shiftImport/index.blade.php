<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('csv インポート') }}
        </h2>
    </x-slot>


    <form class="csv" action="{{ route('shiftImport.Csv') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">インポート</button>
    </form>



</x-app-layout>

