<!-- Header Section Start From Here  for desktop-->
<header class="header-wrapper">

    <!-- Header Top  Nav Start -->
    <div class="header-nav">
        <div class="container">
            <div class="header-nav-wrapper d-md-flex d-sm-flex d-xl-flex d-lg-flex justify-content-between">
                <div class="header-static-nav">
                    <a href="mailto:yourname@email.com">yourname@email.com</a>
                </div>
                <div class="header-menu-nav">
                    <ul class="menu-nav">
                        <li>
                            <div class="dropdown">
                                <button type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account <i class="ion-ios-arrow-down"></i></button>

                                <ul class="dropdown-menu animation slideDownIn" aria-labelledby="dropdownMenuButton">
                                    <li><a href="my-account.html">My account</a></li>
                                    <li><a href="checkout.html">Checkout</a></li>
                                    <li><a href="login.html">Sign in</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown">
                                <button type="button" id="dropdownMenuButton-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">USD $ <i class="ion-ios-arrow-down"></i></button>

                                <ul class="dropdown-menu animation slideDownIn" aria-labelledby="dropdownMenuButton-2">
                                    <li><a href="#">EUR €</a></li>
                                    <li><a href="#">USD $</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="pr-0">
                            <div class="dropdown">
                                <button type="button" id="dropdownMenuButton-3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="assets/images/flag/1.jpg" alt="" /> English <i class="ion-ios-arrow-down"></i>
                                </button>

                                <ul class="dropdown-menu animation slideDownIn" aria-labelledby="dropdownMenuButton-3">
                                    <li>
                                        <a href="#"><img src="assets/images/flag/1.jpg" alt="" /> English</a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="assets/images/flag/2.jpg" alt="" /> Français</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Top Nav End -->

    <!-- Header middle Nav Start -->
    <div class="header-top bg-white ptb-30px d-lg-block d-none">
        <div class="container">
            <div class="row">
                <div class="col-md-3 d-flex">
                    <div class="logo align-self-center">
                        <a href="{{route('landing')}}"><img class="img-responsive w-50" src="{{asset('assets/asset/images/main-image/logo.png')}}" alt="logo.jpg" /></a>
                    </div>
                </div>
                <div class="col-md-9 align-self-center">
                    <div class="header-right-element d-flex">
                        <div class="search-element media-body mr-20px">
                            <form class="d-flex" action="{{route('categoryNull')}}">
                                <input type="text" name="search" placeholder="Search ... " />
                                <button>Search</button>
                            </form>
                        </div>
                        <!--Cart info Start -->
                        <div class="header-tools d-flex">
                            <div class="cart-info d-flex align-self-center">
                                <a href="#offcanvas-wishlist" class="heart offcanvas-toggle"><i class="lnr lnr-heart"></i><span>Wishlist</span></a>
                            </div>
                        </div>
                    </div>
                    <!--Cart info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Header middle Nav End -->

    <!-- Header bottom Nav Start -->
    <div class="header-menu bg-white sticky-nav d-lg-block d-none padding-0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header-menu-vertical">
                        <h4 class="menu-title">Browse Categories </h4>
                        <ul class="menu-content display-none">
                            @foreach($categories as $category)
                                <li class="menu-item"><a href="{{route('category',$category->id)}}">{{$category->name}}</a></li>
                            @endforeach
                        </ul>
                        <!-- menu content -->
                    </div>
                    <!-- header menu vertical -->
                </div>
                <div class="col-lg-9">
                    <div class="header-horizontal-menu">
                        <ul class="menu-content">

                            <li class="active "><a href="{{route('landing')}}"> Home </a></li>

                            <li class="active "><a href="{{route('categoryNull')}}"> Components </a></li>

                            <li class="active "><a href="{{route('about')}}"> About </a></li>

                            <li class="active "><a href="{{route('faqs')}}"> FAQs </a></li>

                            <li><a href="{{route('contact')}}">contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- Header bottom Nav End -->

</header>


<!-- Mobile Header Section Start -->
<div class="mobile-header d-lg-none sticky-nav bg-white ptb-20px">
    <div class="container">
        <div class="row align-items-center">

            <!-- Header Logo Start -->
            <div class="col">
                <div class="header-logo">
                    <a href="{{route('landing')}}"><img class="img-responsive w-50" src="{{asset('assets/asset/images/main-image/logo.png')}}" alt="logo.jpg" /></a>
                </div>
            </div>
            <!-- Header Logo End -->

            <!-- Header Tools Start -->
            <div class="col-auto">
                <div class="header-tools justify-content-end">
                    <div class="cart-info d-flex align-self-center">
                        <a href="#offcanvas-wishlist" class="heart offcanvas-toggle"><i class="lnr lnr-heart"></i><span>Wishlist</span></a>

                    </div>
                    <div class="mobile-menu-toggle">
                        <a href="#offcanvas-mobile-menu" class="offcanvas-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Header Tools End -->

        </div>
    </div>
</div>

<!-- Search Category Start -->
<div class="mobile-search-area d-lg-none mb-15px">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-element media-body">
                    <form class="d-flex" action="{{route('categoryNull')}}">
                        <input type="text" name="search" placeholder="Search ... " />
                        <button>Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Search Category End -->

<div class="mobile-category-nav d-lg-none mb-15px">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <!--=======  category menu  =======-->
                <div class="hero-side-category">
                    <!-- Category Toggle Wrap -->
                    <div class="category-toggle-wrap">
                        <!-- Category Toggle -->
                        <button class="category-toggle"><i class="fa fa-bars"></i> All Categories</button>
                    </div>

                    <!-- Category Menu -->
                    <nav class="category-menu">
                        <ul>
                                @foreach($categories as $category)
                                     <li class="menu-item"><a href="{{route('category',$category->id)}}">{{$category->name}}</a></li>
                                @endforeach
                        </ul>
                    </nav>
                </div>

                <!--=======  End of category menu =======-->
            </div>
        </div>
    </div>
</div>
<!-- Mobile Header Section End -->

<!-- OffCanvas Wishlist Start -->
<div id="offcanvas-wishlist" class="offcanvas offcanvas-wishlist">
    <div class="inner">
        <div class="head">
            <span class="title">Wishlist</span>
            <button class="offcanvas-close">×</button>
        </div>
        <div class="body customScroll">
            <ul class="minicart-product-list">
                <li>
                    <a href="single-product.html" class="image"><img src="assets/images/product-image/1.jpg" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="single-product.html" class="title">Walnut Cutting Board</a>
                        <span class="quantity-price">1 x <span class="amount">$100.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
                <li>
                    <a href="single-product.html" class="image"><img src="assets/images/product-image/2.jpg" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="single-product.html" class="title">Lucky Wooden Elephant</a>
                        <span class="quantity-price">1 x <span class="amount">$35.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
                <li>
                    <a href="single-product.html" class="image"><img src="assets/images/product-image/3.jpg" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="single-product.html" class="title">Fish Cut Out Set</a>
                        <span class="quantity-price">1 x <span class="amount">$9.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="foot">
            <div class="buttons">
                <a href="wishlist.html" class="btn btn-dark btn-hover-primary mt-30px">view wishlist</a>
            </div>
        </div>
    </div>
</div>
<!-- OffCanvas Wishlist End -->

<!-- OffCanvas Search Start -->
<div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
    <div class="inner customScroll">
        <div class="head">
            <span class="title">&nbsp;</span>
            <button class="offcanvas-close">×</button>
        </div>
        <div class="offcanvas-menu-search-form">
            <form class="d-flex" action="{{route('categoryNull')}}">
                <input type="text" name="search" placeholder="Search ... " />
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="offcanvas-menu">
            <ul>
                <li><a href="{{route('landing')}}"><span class="menu-text">Home</span></a></li>

                <li><a href="{{route('categoryNull')}}"><span class="menu-text">Components</span></a></li>

                <li><a href="{{route('about')}}"><span class="menu-text">About</span></a></li>

                <li><a href="{{route('faqs')}}"><span class="menu-text">FAQs</span></a></li>

                <li><a href="{{route('contact')}}">Contact Us</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- OffCanvas Search End -->

<div class="offcanvas-overlay"></div>
