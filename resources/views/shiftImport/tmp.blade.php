<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('csv インポート') }}
        </h2>
    </x-slot>


    <!-- shiftImport/tmp.blade.php -->
<table>
    <thead>
        <tr>
            @foreach ($records[0] as $key => $value)
                <th class="tmp">{{ $key }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
            <tr>
                @foreach ($record as $value)
                    <td class="tmp">{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>



</x-app-layout>
