<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('csv インポート') }}
        </h2>
    </x-slot>


    <form action="{{ route('shiftImport.Csv') }}" method="POST">
        @csrf
        <button type="submit" name="response" value="ok" class="btn btn-primary">OK</button>
        <button type="submit" name="response" value="cancel" class="btn btn-secondary">キャンセル</button>
    </form>


</x-app-layout>
