@extends('userSide.layout.app')
@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="index.html">Home</a></li>
                            <li>Shop</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <div class="container mt-5">
        <div class="row g-4">
            <!-- Card Category -->
            @foreach($categories as $category)
            <div class="col-lg-4 col-md-6">
                <a href="#">
                <div class="card p-4">
                    <div class="card-body">

                        <div class="text">
                            <h5 class="card-title">{{$category->name}}</h5>
                            <a href="#" class="shop-now">Show All <span>&#x27A4;</span></a>
                        </div>
                        <img src="{{asset($category->image)}}" alt="Game Joysticks">
                    </div>
                </div>
                </a>

            </div>
            @endforeach
        </div>
    </div>
    <!-- Shop Category Area End -->
    <div class="shop-category-area mt-30px">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
                    <!-- Shop Top Area Start -->
                    <div class="shop-top-bar d-flex">
                        <!-- Left Side start -->
                        <div class="shop-tab nav d-flex">
                            <a  href="#shop-1" data-bs-toggle="tab">
                                <i class="fa fa-th"></i>
                            </a>
                            <a class="active" href="#shop-2" data-bs-toggle="tab">
                                <i class="fa fa-list"></i>
                            </a>
                            <p>There Are 13 Products.</p>
                        </div>
                        <!-- Left Side End -->

                        <!-- Sort  Side Start -->
                        <div class="select-shoing-wrap d-flex">
                            <div class="shot-product">
                                <p>Sort By:</p>
                            </div>
                            <div class="shop-select">
                                <select>
                                    <option value="">Sort by newness</option>
                                    <option value="">A to Z</option>
                                    <option value=""> Z to A</option>
                                    <option value="">In stock</option>
                                </select>
                            </div>
                        </div>
                        <!-- Sort Side End -->
                    </div>
                    <!-- Shop Top Area End -->

                    <!-- Shop Bottom Area Start -->
                    <div class="shop-bottom-area mt-35">
                        <!-- Shop Tab Content Start -->
                        <div class="tab-content jump">
                            <!-- Tab One Start -->
                            <div id="shop-1" class="tab-pane active">
                                <div class="row m-0">

                                    @foreach($products as $product)

                                    <div class="mb-30px col-md-4 col-sm-6  p-1">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
                                                <div class="product-inner">
                                                    <div class="img-block">
                                                        <a href="single-product.html" class="thumbnail">
                                                            <img class="first-img" src="{{asset($product->images[0]->image)}}" alt="{{$product->name}}" />
                                                            <img class="second-img" src="{{asset($product->images[0]->image)}}" alt="{{$product->name}}" />
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

                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="product-decs">
                                                        <a class="inner-link" href="shop-4-column.html"><span>{{$product->category->name}}</span></a>
                                                        <h2><a href="single-product.html" class="product-link">{{$product->name}}</a></h2>
                                                        <div class="pricing-meta">
                                                            <ul>
                                                                <li class="current-price">{{$product->cheapest_price}}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="cart-btn">
                                                        <a href="#" class="add-to-curt" title="Add to cart">Show</a>
                                                    </div>
                                                </div>
                                            </article>
                                            <!-- Single Item -->
                                        </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div>
                            <!-- Tab One End -->
                            <!-- Tab Two Start -->

                            <!-- Tab Two End -->
                        </div>
                        <!-- Shop Tab Content End -->
                        <!--  Pagination Area Start -->
                        {{$products->links()}}
{{--                        <div class="pro-pagination-style text-center mtb-50px">--}}
{{--                            <ul>--}}
{{--                                <li>--}}
{{--                                    <a class="prev" href="#"><i class="ion-ios-arrow-left"></i></a>--}}
{{--                                </li>--}}
{{--                               --}}
{{--                                <li>--}}
{{--                                    <a class="next" href="#"><i class="ion-ios-arrow-right"></i></a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                        <!--  Pagination Area End -->
                    </div>
                    <!-- Shop Bottom Area End -->
                </div>
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 order-lg-first col-md-12 order-md-last mb-md-60px mb-lm-60px">
                    <div class="shop-sidebar-wrap">

                        <!-- Sidebar single item -->
                        <div class="sidebar-widget-group mt-20">
                            <h3 class="sidebar-title m-0"><span>Filter By</span></h3>
                            <!-- Sidebar single item -->
                            <div class="sidebar-widget no-cba mt-20">
                                <h4 class="pro-sidebar-title">Colour</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" /> <a href="#">Grey<span>(2)</span> </a>
                                                <span class="checkmark grey"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">White<span>(4)</span></a>
                                                <span class="checkmark white"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Black<span>(4)</span> </a>
                                                <span class="checkmark black"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Camel<span>(4)</span> </a>
                                                <span class="checkmark camel"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Sidebar single item -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Size</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" /> <a href="#">X<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">M<span>(4)</span></a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">L<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">XL<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Sidebar single item -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Paper Type</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Doted<span>(3)</span></a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Plain<span>(3)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" /> <a href="#">Ruled<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Squarred<span>(3)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Sidebar single item -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Compositions</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" /> <a href="#">Cotton<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Elastane<span>(4)</span></a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Polyester<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Wool<span>(4)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Sidebar single item -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Brand</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" /> <a href="#">Studio Design<span>(10)</span> </a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-widget-list-left">
                                                <input type="checkbox" value="" /> <a href="#">Graphic Corner<span>(7)</span></a>
                                                <span class="checkmark"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="sidebar-widget mt-20">
                                <h4 class="pro-sidebar-title">Price</h4>
                                <div class="price-filter mt-10">
                                    <div class="price-slider-amount">
                                        <input type="text" id="amount" name="price" placeholder="Add Your Price" />
                                    </div>
                                    <div id="slider-range"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar single item -->

                        <!-- Sidebar single item -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Shop Category Area End -->

@endsection
