<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('情報管理') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --definitive-white-board">
            <a href="{{ route('definitive.listMonth', ['year' => $year, 'month' => $month]) }}" class="c-back-btn definitive-back-btn">戻る</a>
            <p class="title">稼働表確定版一覧</p>
            <p class="now-page">{{ $year }}年{{ $month }}月</p>

            <div class="year-list --pdf-list">
                @foreach ($pdfs as $pdf)
                    <a href="{{ route('definitive.pdfDownload', ['year' => $year, 'month' => $month, 'fileName' => $pdf['full_name']]) }}" class="year-list__item --pdf-item">
                        <p>{{ $pdf['display_name'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
{{-- <script src="{{asset('js/info-management.js')}}"></script> --}}