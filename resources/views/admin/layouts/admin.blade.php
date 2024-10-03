<!doctype html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
      data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">


<!-- top and links start -->
@include('admin.layouts.include.admin_top')
<!-- top and links end -->


<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <!-- Aside Start -->
        @include('admin.layouts.include.admin_aside')
        <!-- Aside End -->

        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

            <!-- Navbar Start -->
            @include('admin.layouts.include.admin_navbar')

            <!-- Navbar End -->


            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">

                    <!-- content here ................. -->
                    @yield('content')
                </div>
                <!-- / Content -->

                <!-- Footer -->
                @include('admin.layouts.include.admin_footer')


                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->


<!-- bottom and links start -->
@include('admin.layouts.include.admin_bottom')
<!-- bottom and links end -->

</body>

</html>
