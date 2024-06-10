<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員') }}
        </h2>
    </x-slot>

    <main class="main">
        <div class="main__white-board --employee-white-board">
            <a href="{{ route('employee.') }}" class="c-back-btn register-back-btn">戻る</a>

            <div class="register-wrap">
                <p class="title">ログイン情報</p>
                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <form action="{{ route('employee.registerStore') }}" method="POST">
                    @csrf
                    @if ($user)
                        <input hidden type="text" name="id" value="{{ $user->id }}">
                    @endif
                    <input hidden name="employee_id" value="{{ $employee->id }}" type="text">
                    <input hidden type="text" class="c-input" name="name" value="{{ $employee->name }}">
                    <div class="register-list">
                        @if ($user)
                            <p class="update-password">メールアドレス更新</p>
                        @endif
                        <div class="register-list__item">
                            <p class="">メールアドレス</p>
                            <input type="email" class="c-input" name="email" value="{{ $user->email ?? '' }}" required>
                        </div>
                        @if ($user)
                            <p class="update-password">パスワード更新</p>
                        @endif
                        <div class="register-list__item">
                            <p class="">パスワード</p>
                            <input type="password" class="c-input" name="password" value="" required>
                        </div>
                        <div class="register-list__item">
                            <p class="">パスワード確認用</p>
                            <input type="password" class="c-input" name="password_confirmation" value="" required>
                        </div>
                        <button class="c-save-btn register-btn">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</x-app-layout>

{{-- script --}}
<script src="{{asset('js/employee.js')}}"></script>
