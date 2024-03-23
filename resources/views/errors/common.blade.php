<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @php
        switch ($statusCode) {
            case 400:
                $message = 'Bad Request';
                break;
            case 401:
                $message = '認証に失敗しました';
                break;
            case 403:
                $message = 'アクセス権がありません';
                break;
            case 404:
                $message = '存在しないページです';
                break;
            case 408:
                $message = 'タイムアウトです';
                break;
            case 414:
                $message = 'リクエストURIが長すぎます';
                break;
            case 419:
                $message = '不正なリクエストです';
                break;
            case 500:
                $message = 'Internal Server Error';
                break;
            case 503:
                $message = 'Service Unavailable';
                break;
            default:
                $message = 'エラー';
                break;
        }
    @endphp

    <div class="home">
        <p class="message">{{ $statusCode }} {{ $message }}</p>
        <a href="{{ route('login') }}" class="home-back">OK</a>
    </div>
</body>
</html>
