@extends('userSide.layout.app')
@section('content')

    <!-- Slider Start -->
    <div class="slider-area">
        <div class="hero-slider-wrapper">
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/img/image/slider3.jpg')}}" >
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-1 text-left pl-60px">
                        <h1 class="animated color-black ">
                            Compare Prices   <br />
                            And Build Your PC.
                        </h1>

                        <a href="{{route('categoryNull')}}" class="shop-btn animated mt-4">SHOW NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/img/image/about.jpg')}}">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-2 text-left pl-60px">
                        <h1 class="animated color-black">
                            Find The Best  <br />
                            Deals On PC Components
                        </h1>

                        <a href="{{route('categoryNull')}}" class="shop-btn animated mt-4">SHOW NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex"data-bg-image="{{asset('assets/asset/images/main-image/test.jpg')}}" >
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-3 text-left pl-60px">
                        <h1 class="animated color-white">
                            Stay ahead   <br />
                            with the latest tech
                        </h1>
                        <a href="{{route('categoryNull')}}" class="shop-btn animated mt-4">SHOW NOW</a>
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
                            <div class="single-static-meta">
                                <h4><i class="fas fa-tags ps-1 "></i> Discover unbeatable deals </h4>
                                <p>Stay updated with exclusive discounts </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <div class="single-static-meta">
                                <h4><i class="fas fa-exchange-alt"></i>  Your one-stop platform</h4>
                                <p>Easily compare prices from multiple stores </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <div class="single-static-meta">
                                <h4> <i class="fas fa-tools"></i> Build smarter, shop better</h4>
                                <p>Find everything you need </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <div class="single-static-meta">
                                <h4><i class="fas fa-arrow-up"></i> Upgrade your tech game</h4>
                                <p>Access top deals and the latest products</p>
                            </div>
                        </div>
                    </div>



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
                            if (Auth::check())
                                {
                                $isFavorited = auth()->user()->favorites->contains($lastProduct->id);
                                }
                            @endphp
                            <div class="slider-single-item">
                                <!-- Single Item -->
                                <article class="list-product text-center">
                                    <div class="product-inner">
                                        <div class="img-block">
                                            <a href="{{route('singlePage',$lastProduct->id)}}" class="thumbnail">
                                                <img class="first-img" src="{{asset($lastProduct->images[0]->image)}}" alt="fix" />
                                            </a>
                                            {{-- model start--}}
                                            <div class="add-to-link">
                                                <ul>
                                                    <li>
                                                            @if(Auth::check())
                                                        <a href="javascript:void(0);" class="add-to-favorite" data-product-id="{{ $lastProduct->id }}" title="Add to Favorite">
                                                            <i class="lnr lnr-heart {{ $isFavorited ? 'favorite-added' : '' }}"></i>
                                                        </a>
                                                            @endif
                                                    </li>
                                                </ul>
                                            </div>
                                            {{-- model end--}}

                                        </div>
                                        <div class="product-decs">
                                            <a class="inner-link" href="{{route('category', $lastProduct->category->id)}}"><span>{{$lastProduct->category->name}}</span></a>
                                            <h2><a href="{{route('singlePage',$lastProduct->id)}}" class="product-link">{{$lastProduct->name}}</a></h2>
                                            <div class="pricing-meta">
                                                <ul>
                                                    <li class="current-price">{{$lastProduct->cheapest_price}}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="cart-btn">
                                            <a href="{{route('singlePage',$lastProduct->id)}}" class="add-to-curt" title="Add to cart">Show</a>
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
                                if (Auth::check())
                               {
                               $isFavorited = auth()->user()->favorites->contains($lastProduct->id);
                               }
                            @endphp
                            <div class="slider-single-item">
                                <!-- Single Item -->
                                <article class="list-product text-center">
                                    <div class="product-inner">
                                        <div class="img-block">
                                            <a href="{{route('singlePage',$CategoryProduct->id)}}" class="thumbnail">
                                                <img class="first-img" src="{{asset($CategoryProduct->images[0]->image)}}" alt="fix" />
                                            </a>
                                            {{-- model start--}}
                                            <div class="add-to-link">
                                                <ul>
                                                    <li>
                                                        @if(Auth::check())
                                                        <a href="javascript:void(0);" class="add-to-favorite" data-product-id="{{ $CategoryProduct->id }}" title="Add to Favorite">
                                                            <i class="lnr lnr-heart {{ $isFavorited ? 'favorite-added' : '' }}"></i>
                                                        </a>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                            {{-- model end--}}

                                        </div>
                                        <div class="product-decs">
                                            <a class="inner-link" href="{{route('category', $CategoryProduct->category->id)}}"><span>{{$CategoryProduct->category->name}}</span></a>
                                            <h2><a href="{{route('singlePage',$CategoryProduct->id)}}" class="product-link">{{$CategoryProduct->name}}</a></h2>
                                            <div class="pricing-meta">
                                                <ul>
                                                    <li class="current-price">{{$CategoryProduct->cheapest_price}}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="cart-btn">
                                            <a href="{{route('singlePage',$CategoryProduct->id)}}" class="add-to-curt" title="Add to cart">View</a>
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
