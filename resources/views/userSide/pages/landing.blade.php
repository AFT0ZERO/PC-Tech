@extends('userSide.layout.app')
@section('content')

    <!-- Slider Start -->
    <div class="slider-area">
        <div class="hero-slider-wrapper">
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/slider-image/test.jpg')}}">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-1 text-left pl-60px">
                        <h1 class="animated color-black">
                            Xbox One Pro <br />
                            Wireless Controller
                        </h1>
                        <p class="animated color-gray">Revolution Pro Controller.</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOW NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/slider-image/test.jpg')}}">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-2 text-left pl-60px">
                        <h1 class="animated color-black">
                            Bobovr Z4 Virtual <br />
                            Reality 3D Glasses
                        </h1>
                        <p class="animated color-gray">Virtual reality through a new lens</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/slider-image/test.jpg')}}">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-3 text-left pl-60px">
                        <h1 class="animated color-black">
                            Portable Wireless <br />
                            Bluetooth Speaker
                        </h1>
                        <p class="animated color-gray">With Colorful LED Light</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
        </div>
    </div>
    <!-- Slider End -->

    <!-- Static Area Start -->
    <div class="static-area mtb-50px">
        <div class="container">
            <div class="static-area-wrap">
                <div class="row">
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <img src="{{asset('assets/asset/images/icons/static-icons-1.png')}}" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>Free Shipping</h4>
                                <p>On all orders over $75.00</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <img src="assets/images/icons/static-icons-2.png" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>Free Returns</h4>
                                <p>Returns are free within 9 days</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-sm-30px">
                        <div class="single-static">
                            <img src="assets/images/icons/static-icons-3.png" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>100% Payment Secure</h4>
                                <p>Your payment are safe with us.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 ">
                        <div class="single-static">
                            <img src="assets/images/icons/static-icons-4.png" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>Support 24/7</h4>
                                <p>Contact us 24 hours a day</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Static Area End -->


    <!-- Arrivals Area Start -->
    <div class="arrival-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>New Arrivals</h2>
                    </div>
                </div>
            </div>
            <!-- tab content -->
            <div class="tab-content">
                <!-- First-Tab -->
                <div id="tab-1" class="tab-pane active fade">
                    <!-- Arrivel slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">

                        @foreach($lastProducts as $lastProduct)
                            <div class="slider-single-item">
                                <!-- Single Item -->
                                <article class="list-product text-center">
                                    <div class="product-inner">
                                        <div class="img-block">
                                            <a href="single-product.html" class="thumbnail">
                                                <img class="first-img" src="{{asset($lastProduct->images[0]->image)}}" alt="fix" />
                                            </a>
                                            {{-- model start--}}
                                            <div class="add-to-link">
                                                <ul>
                                                    <li>
                                                        <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            <i class="lnr lnr-magnifier"></i>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                            {{-- model end--}}

                                        </div>
                                        <div class="product-decs">
                                            <a class="inner-link" href="shop-4-column.html"><span>{{$lastProduct->category->name}}</span></a>
                                            <h2><a href="single-product.html" class="product-link">{{$lastProduct->name}}</a></h2>
                                            <div class="pricing-meta">
{{--                                                <ul>--}}
{{--                                                    <li class="old-price">$23.90</li>--}}
{{--                                                    <li class="current-price">$21.51</li>--}}
{{--                                                </ul>--}}
                                            </div>
                                        </div>
                                        <div class="cart-btn">
                                            <a href="#" class="add-to-curt" title="Add to cart">View</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    <!-- Arrivel slider end -->
                </div>
                <!-- First-Tab -->
                <!-- Second-Tab -->
                <div id="tab-2" class="tab-pane fade">
                    <!-- Arrivel slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>

                    </div>
                    <!-- Arrivel slider end -->
                </div>
                <!-- Second-Tab -->
                <!-- Third-Tab -->
                <div id="tab-3" class="tab-pane fade">
                    <!-- Arrivel slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <!-- Arrivel slider end -->
                </div>
                <!-- Third-Tab -->
            </div>
            <!-- tab content end-->
        </div>
    </div>
    <!-- Arrivals Area End -->

    <!-- Arrivals Area Start -->
    <div class="arrival-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>CPU</h2>

                    </div>
                </div>
            </div>
            <!-- tab content -->
            <div class="tab-content">
                <!-- First-Tab -->
                <div id="tab-1" class="tab-pane active fade">
                    <!-- Arrivel slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">
                        @foreach($CategoryProducts as $CategoryProduct)
                            <div class="slider-single-item">
                                <!-- Single Item -->
                                <article class="list-product text-center">
                                    <div class="product-inner">
                                        <div class="img-block">
                                            <a href="single-product.html" class="thumbnail">
                                                <img class="first-img" src="{{asset($CategoryProduct->images[0]->image)}}" alt="fix" />
                                            </a>
                                            {{-- model start--}}
                                            <div class="add-to-link">
                                                <ul>
                                                    <li>
                                                        <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            <i class="lnr lnr-magnifier"></i>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                            {{-- model end--}}

                                        </div>
                                        <div class="product-decs">
                                            <a class="inner-link" href="shop-4-column.html"><span>{{$CategoryProduct->category->name}}</span></a>
                                            <h2><a href="single-product.html" class="product-link">{{$CategoryProduct->name}}</a></h2>
                                            <div class="pricing-meta">
                                                {{--                                                <ul>--}}
                                                {{--                                                    <li class="old-price">$23.90</li>--}}
                                                {{--                                                    <li class="current-price">$21.51</li>--}}
                                                {{--                                                </ul>--}}
                                            </div>
                                        </div>
                                        <div class="cart-btn">
                                            <a href="#" class="add-to-curt" title="Add to cart">View</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Naham WiFi HD 1080P</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#"  class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/19.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/20.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <!-- Arrivel slider end -->
                </div>
                <!-- First-Tab -->
                <!-- Second-Tab -->
                <div id="tab-2" class="tab-pane fade">
                    <!-- Arrivel slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Naham WiFi HD 1080P</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#"  class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/19.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/20.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <!-- Arrivel slider end -->
                </div>
                <!-- Second-Tab -->
                <!-- Third-Tab -->
                <div id="tab-3" class="tab-pane fade">
                    <!-- Arrivel slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Naham WiFi HD 1080P</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#"  class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/19.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/20.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <!-- Arrivel slider end -->
                </div>
                <!-- Third-Tab -->
            </div>
            <!-- tab content end-->
        </div>
    </div>
    <!-- Arrivals Area End -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12 mb-lm-100px mb-sm-30px">
                            <div class="quickview-wrapper">
                                <!-- slider -->
                                <div class="gallery-top">
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/8.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/14.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/15.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/11.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/19.jpg" alt="">
                                    </div>
                                </div>
                                <div class=" gallery-thumbs">
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/8.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/14.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/15.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/11.jpg" alt="">
                                    </div>
                                    <div class="single-slide">
                                        <img class="img-responsive m-auto" src="assets/images/product-image/19.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="product-details-content quickview-content">
                                <h2>Originals Kaval Windbr</h2>
                                <p class="reference">Reference:<span> demo_17</span></p>
                                <div class="pro-details-rating-wrap">
                                    <div class="rating-product">
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                        <i class="ion-android-star"></i>
                                    </div>
                                    <span class="read-review"><a class="reviews" href="#">Read reviews (1)</a></span>
                                </div>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price not-cut">€18.90</li>
                                    </ul>
                                </div>
                                <p class="quickview-para">Lorem ipsum dolor sit amet, consectetur adipisic elit eiusm tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim venialo quis nostrud exercitation ullamco</p>
                                <div class="pro-details-size-color">
                                    <div class="pro-details-color-wrap">
                                        <span>Color</span>
                                        <div class="pro-details-color-content">
                                            <ul>
                                                <li class="blue"></li>
                                                <li class="maroon active"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="pro-details-quality">
                                    <div class="cart-plus-minus">
                                        <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" />
                                    </div>
                                    <div class="pro-details-cart btn-hover">
                                        <a href="#"> + Add To Cart</a>
                                    </div>
                                </div>
                                <div class="pro-details-wish-com">
                                    <div class="pro-details-wishlist">
                                        <a href="wishlist.html"><i class="ion-android-favorite-outline"></i>Add to wishlist</a>
                                    </div>
                                    <div class="pro-details-compare">
                                        <a href="compare.html"><i class="ion-ios-shuffle-strong"></i>Add to compare</a>
                                    </div>
                                </div>
                                <div class="pro-details-social-info">
                                    <span>Share</span>
                                    <div class="social-info">
                                        <ul>
                                            <li>
                                                <a href="#"><i class="ion-social-facebook"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="ion-social-twitter"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="ion-social-google"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="ion-social-instagram"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->

@endsection
