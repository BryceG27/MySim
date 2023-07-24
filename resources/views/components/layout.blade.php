<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>mySim - {{ $page ?? '' }}</title>
</head>
<body class="login bg-tim">
    <x-navbar company="{{ env('COMPANY') }}" />

    <div id="app">
        {{ $slot }}
        <div style="height: 10vh;"></div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <x-footer company="{{ env('COMPANY') }}"/>
</body>
</html>