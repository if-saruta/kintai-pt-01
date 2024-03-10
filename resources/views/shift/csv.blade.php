<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト') }}
        </h2>
    </x-slot>

    {{-- <script>
        window.onbeforeunload = function(e) {
            e.preventDefault();
            return '';
        };

    </script> --}}

    <main class="main --shift-main">
        <div class="main__link-block --shift-link-block">
            <div class="main__link-block__tags">
                <div class="main__link-block__item --shift-link-block__item">
                    <a href="{{route('shift.')}}"
                        class="{{ request()->routeIs('shift.', 'shift.selectWeek') ? 'active' : '' }} link">
                        <span class="">全表示</span>
                    </a>
                </div>
                <form action="{{route('shift.employeeShowShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page02" type="text">
                    <button class="{{ request()->routeIs('shift.employeeShowShift*') ? 'active' : '' }} link">
                        <span class="">稼働表</span>
                    </button>
                </form>
                <form action="{{route('shift.employeePriceShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page03" type="text">
                    <button class="{{ request()->routeIs('shift.employeePriceShift*') ? 'active' : '' }} link">
                        <span class="">ドライバー価格</span>
                    </button>
                </form>
                <form action="{{route('shift.projectPriceShift')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page04" type="text">
                    <button class="{{ request()->routeIs('shift.projectPriceShift*') ? 'active' : '' }} link">
                        <span class="">上代閲覧用</span>
                    </button>
                </form>
                <form action="{{route('shift.projectCount')}}" method="POST"
                    class="main__link-block__item --shift-link-block__item">
                    @csrf
                    <input hidden name="witch" value="page05" type="text">
                    <button class="{{ request()->routeIs('shift.projectCount') ? 'active' : '' }} link">
                        <span class="">案件数用</span>
                    </button>
                </form>
            </div>
            <div class="--shift-link-block__btn-area">
                {{-- シフト編集 --}}
                <form action="{{route('shift.edit')}}" method="POST" class="icon-block">
                    @csrf
                    <input hidden name="witch" value="page06" type="text">
                    <button class="{{ request()->routeIs('shift.edit*') ? 'active' : '' }} icon-block__button">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </form>
                {{-- CSVインポート --}}
                <a href="{{route('shift.csv')}}" class="icon-block">
                    <div class="{{ request()->routeIs('shift.csv') ? 'active' : '' }} icon-block__button">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="main__white-board">
            <form action="{{route('shift.csvImport')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="csv-wrap">
                    <label class="file-up-wrap">
                        <input class="csvInput" type="file" name="csv_file" required>
                        <div class="csv-icon">
                            <i class="fa-solid fa-file-csv default-csv"></i>
                            <i class="fa-solid fa-file-circle-check check-csv"></i>
                            <i class="fa-solid fa-file-circle-exclamation warning-csv"></i>
                        </div>
                        <p class="file-txt">ファイルを選択</p>
                    </label>
                    <div class="active-file-name-wrap active-file-box">
                        <div class="active-file-name-wrap__inner">
                            <div class="head">
                                <div class="head__icon-wrap active-icon-box">
                                    <i class="fa-solid fa-file-lines active-icon"></i>
                                </div>
                                <p class="head__txt">選択中のファイル : </p>
                            </div>
                            <p class="active-file-txt"></p>
                        </div>
                    </div>
                    <button class="import-btn" disabled onclick='return confirm("すでに登録してある日付が含まれる場合、上書き保存されます")'>
                        インポート
                    </button>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>

{{-- script --}}
<script src="{{asset('js/shift.js')}}"></script>
