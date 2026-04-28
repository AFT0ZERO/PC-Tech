<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{asset('../assets/img/favicon/favicon.ico')}}" />
    <link rel="preconnect" href="{{asset('https://fonts.googleapis.com')}}" />
    <link rel="preconnect" href="{{asset('https://fonts.gstatic.com')}}" crossorigin />
    <link href="{{asset('https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('../assets/vendor/fonts/boxicons.css')}}" />

    <link rel="stylesheet" href="{{asset('../assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('../assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('../assets/css/demo.css')}}" />
    <link rel="stylesheet" href="{{asset('../assets/vendor/css/pages/page-auth.css')}}" />

    <script src="{{asset('../assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('../assets/js/config.js')}}"></script>

    @yield('extraHeader')
</head>
<body>
@yield('content')

<script src="{{asset('../assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('../assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('../assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('../assets/vendor/js/menu.js')}}"></script>
<script src="{{asset('../assets/js/main.js')}}"></script>
</body>
</html>
