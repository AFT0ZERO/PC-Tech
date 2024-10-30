@extends('userSide.layout.app')
@section('content')

    <!-- Slider Start -->
    <div class="slider-area">
        <div class="hero-slider-wrapper">
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/main-image/test.jpg')}}">
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
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/main-image/test.jpg')}}">
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
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/main-image/test.jpg')}}">
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
                            @php
                                $isFavorited = auth()->user()->favorites->contains($lastProduct->id);
                            @endphp
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
                                                        <a href="javascript:void(0);" class="add-to-favorite" data-product-id="{{ $lastProduct->id }}" title="Add to Favorite">
                                                            <i class="lnr lnr-heart {{ $isFavorited ? 'favorite-added' : '' }}"></i>
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

            </div>
            <!-- tab content end-->
        </div>
    </div>
    <!-- Arrivals Area End -->

    <!-- Category Area Start -->
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
                            @php
                                $isFavorited = auth()->user()->favorites->contains($CategoryProduct->id);
                            @endphp
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
                                                        <a href="javascript:void(0);" class="add-to-favorite" data-product-id="{{ $CategoryProduct->id }}" title="Add to Favorite">
                                                            <i class="lnr lnr-heart {{ $isFavorited ? 'favorite-added' : '' }}"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            {{-- model end--}}

                                        </div>
                                        <div class="product-decs">
                                            <a class="inner-link" href="shop-4-column.html"><span>{{$CategoryProduct->category->name}}</span></a>
                                            <h2><a href="single-product.html" class="product-link">{{$CategoryProduct->name}}</a></h2>
                                        </div>
                                        <div class="cart-btn">
                                            <a href="#" class="add-to-curt" title="Add to cart">View</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach

                    </div>
                    <!-- CPU slider end -->
                </div>
                <!-- First-Tab -->

            </div>
            <!-- tab content end-->
        </div>
    </div>
    <!-- Arrivals Area End -->



@endsection
