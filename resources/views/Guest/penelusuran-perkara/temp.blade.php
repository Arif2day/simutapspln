<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hasil Survei</title>
</head>
<body>
    <div class="center" style="border: 0.3px solid gray">
        @if($data)
        <div id="chart_div">
            <div id="chart_title_div" style="text-align: center;color:#5e5f62">
            {!! $title !!}
            </div>
            <img src="{{ $data }}" alt="" style="width: 100%;">
        </div>

        {{-- {!! $data !!} --}}
        @endif
    </div>
</body>
</html>