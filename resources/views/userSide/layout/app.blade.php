<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    @include('userSide.include.user_top')
    @yield('extraHeader')
</head>
<body>

{{--Navbar Start--}}
@include('userSide.include.user_nav')
{{--Navbar End--}}

{{--Content Start--}}
@yield('content')
{{--Content End--}}


<!-- Footer Area Start -->
@include('userSide.include.user_footer')
<!-- Footer Area End -->


@include('userSide.include.user_bottom')


</body>
</html>
