<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('請求書') }}
        </h2>
    </x-slot>

    <div class="invoice">
        <div class="invoice-nav">
            <a href="{{ route('invoice.driverShift') }}" class="invoice-nav__button">
                <p class="">ドライバー</p>
            </a>
            <a href="{{ route('invoice.projectShift') }}" class="invoice-nav__button">
                <p class="">案件</p>
            </a>
        </div>
    </div>



</x-app-layout>
