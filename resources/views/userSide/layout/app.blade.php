<!DOCTYPE html>
<html lang="en">

<head>
   @include('userSide.include.user_top')
</head>

<body>
<!-- navbar Strat -->
@include('userSide.include.user_nav')
<!-- navbar End -->

<!-- content Strat -->
@yield('content')

<!-- content End -->

<!-- Footer Start -->
@include('userSide.include.user_footer')
<!-- Footer End -->


@include('userSide.include.user_bottom')
</body>
</html>
