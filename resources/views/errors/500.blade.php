<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>error</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="home">
        <p class="message">予期せぬ問題が発生しました</p>
        <p class="message02">何度も発生する場合は管理者までご連絡ください。</p>
        <a href="{{ route('shift.') }}" class="home-back">OK</a>
    </div>
</body>
</html>
