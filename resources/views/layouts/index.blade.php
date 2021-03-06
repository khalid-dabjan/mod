<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <title>Modasti</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/boilerplate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mygrid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/developer.css') }}">
    <link rel="icon" href="/images/fav.jpg">

    <script src="{{ asset('js/respond.min.js') }}"></script>

</head>
<body>
<div id="app">
    <App></App>
</div>

@if(request()->getHost() == 'modasti.uniative.com')
    <script src="{{ asset('js/App.js') }}"></script>
@else
    <script src="{{ mix('js/App.js') }}"></script>
@endif
<!-- <script src="{{ asset('js/jquery-1.11.min.js') }}"></script> -->
<!-- <script src="{{ asset('js/myscript.js') }}"></script> -->
</body>
</html>
