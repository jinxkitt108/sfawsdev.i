<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="../css/app.css ">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'SFAWS') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->

                @if (Route::has('login'))
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif
                    @endauth
                </ul>
                @endif
            </div>
        </div>
    </nav>

    <div class="mx-auto text-center" style="width: 500px; margin-top: 100px">
        <h1 class="display-1"><i class="fas fa-seedling text-success"></i>SFAWS</h1>
        <hr class="mx-4">
        <p class="lead"><em><q>The world where farming is<br>LIFE...</q></em></p>
    </div>

    <div class="footer fixed-bottom text-white text-center bg-secondary align-center">
        <p>Central Philippines State University. All rights reserved 2020.</p>
    </div>

    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>