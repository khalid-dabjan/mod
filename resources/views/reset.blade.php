<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <title>Rest Password</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/boilerplate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/mygrid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/developer.css') }}">
    <script src="{{ asset('js/respond.min.js') }}"></script>

</head>
<body style="background: none">
<div class="popupPage">
    <div class="verticalCentered">
        <div class="theCell">

            <div class="outerClose"></div>

            <div class="thePage">
                <div class="head">
                    <span>Reset password</span>
                    <a href="/"><span class="icon closePopup"><i class="fa fa-close"></i></span></a>
                </div>
                <div class="content">
                    <div class="theForm">
                        <form action="{{route('_password.reset',['token'=>$token])}}" method="post">
                            {!!  csrf_field() !!}
                            <input type="password" class="formEle" name="password" placeholder="Enter new password">
                            <input type="password" class="formEle" name="password_confirmation"
                                   placeholder="Enter you password again">
                            @foreach($errors->all() as $error)
                                <p class="error">{{$error}}</p>
                            @endforeach
                            <input type="submit" class="formEle btn" value="send">
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    .popupPage .thePage .head .icon {
        color: #f8f8f8;
    }
    .error{
        color:red;
    }
</style>
<script src="{{ asset('js/jquery-1.11.min.js') }}"></script>
<script src="{{ asset('js/myscript.js') }}"></script>

</body>
</html>
