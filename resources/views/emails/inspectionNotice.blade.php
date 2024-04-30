<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <p>件名：車検の期日について</p>

    <p class="">以下の車両の車検日が{{ $timeFrame }}前に迫っているのでお知らせします。</p>

    <p>------------------------------------------------------------------------------------</p>

    @foreach ($vehicles as $vehicle)
        <p class="">{{ $vehicle->place_name }} {{ $vehicle->class_number }} {{ $vehicle->hiragana }} {{ $vehicle->number }}</p>
    @endforeach

    <p>------------------------------------------------------------------------------------</p>

    <p class="">車検が完了しましたら、車両画面から次の車検日を設定してください。</p>
</body>
</html>
