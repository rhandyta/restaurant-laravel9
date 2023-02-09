
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('assets/css/main/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/pages/auth.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.svg')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.png')}}" type="image/png">
</head>

<body>
    <div id="auth">
        
<div class="row h-100 d-flex justify-content-center">
    <div class="col-lg-8 col-12">
        <div id="auth-left">
            <h1 class="auth-title">@yield('title')</h1>
           @yield('content')
        </div>
    </div>
</div>

    </div>
    <script src="{{asset('assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
</body>

</html>
