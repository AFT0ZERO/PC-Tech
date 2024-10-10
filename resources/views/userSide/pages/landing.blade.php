@extends('userSide.layout.app')
@section('content')

    <!-- Slider Start -->
    <div class="slider-area">
        <div class="hero-slider-wrapper">
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{asset('assets/asset/images/slider-image/sample-1.jpg')}}">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-1 text-left pl-60px">
                        <h1 class="animated color-black">
                            Xbox One Pro <br />
                            Wireless Controller
                        </h1>
                        <p class="animated color-gray">Revolution Pro Controller.</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-2.jpg">
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
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-3.jpg">
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

    <!-- Feature Area Start -->
    <div class="feature-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Featured Products</h2>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slider-wrapper slider-nav-style-1">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
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
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
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
                                        <h2><a href="single-product.html" class="product-link">Madden by Steve Madden</a></h2>
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
                            <article class="list-product text-center mb-30px">
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
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/10.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/11.jpg" alt="" />
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
                                        <h2><a href="single-product.html" class="product-link">Wireless Stereo Speaker</a></h2>
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
                            <article class="list-product text-center mb-30px">
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
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/14.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/15.jpg" alt="" />
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
                                        <h2><a href="single-product.html" class="product-link">Sony WH-CH700N</a></h2>
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
                            <article class="list-product text-center mb-30px">
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
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/18.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/19.jpg" alt="" />
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
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
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
                            <article class="list-product text-center mb-30px">
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
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">TCL 49S5 49” 4K Ultra HD</a></h2>
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
                    </div>
                </div>
                <!-- Feature Area End -->
                <div class="col-lg-4 col-md-4 mt-lm-30px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Hot Deals</h2>
                            </div>
                        </div>
                    </div>
                    <div class="hot-deal-slider-wrapper slider-nav-style-1">
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
                                        <h2><a href="single-product.html" class="product-link">TCL 49S5 49” 4K Ultra HD</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$21.51</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="availability-list in-stock">Availability: <span>300 In Stock</span></div>
                                    <div class="clockdiv">
                                        <div data-countdown="2023/12/01"></div>
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
                                            <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
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
                                        <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$21.51</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="availability-list in-stock">Availability: <span>300 In Stock</span></div>
                                    <div class="clockdiv">
                                        <div data-countdown="2023/12/01"></div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area End -->

    <!-- Banner Area Start -->
    <div class="banner-area mt-30px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/1.jpg" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area End -->

    <!-- Best sell Area Start -->
    <div class="best-sell-area mt-20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Best Sellers</h2>
                    </div>
                </div>
            </div>
            <div class="best-sell-area-wrapper slider-nav-style-1 slider-nav-style-4">
                <div class="single-slider-item">
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="single-product.html" class="thumbnail">
                                    <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                    <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
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
                                <a class="inner-link" href="shop-4-column.html"><span>Graphic Corner</span></a>
                                <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                <div class="availability-list in-stock">Availability: <span>900 In Stock</span></div>
                                <p>Android 8.1 (Oreo), Hisilicon Kirin 710 (12 nm), Octa-core (4x2.2 GHz Cortex-A73 &amp; 4x1.7 GHz Cortex-A53), Mali-G51 MP4</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="current-price">$29.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
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
                            <ul class="product-flag">
                                <li class="new">-12%</li>
                            </ul>
                            <div class="product-decs">
                                <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                <h2><a href="single-product.html" class="product-link">Wireless Stereo Speaker</a></h2>
                                <div class="availability-list in-stock">Availability: <span>300 In Stock</span></div>
                                <p>Galileo, and QZSS, Barometric Altimeter, Optical Heart Sensor, Accelerometer And Gyroscope, Ion-X Strengthened Glass</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price">$25.51</li>
                                        <li class="current-price">$21.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="single-slider-item">
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="single-product.html" class="thumbnail">
                                    <img class="first-img" src="assets/images/product-image/15.jpg" alt="" />
                                    <img class="second-img" src="assets/images/product-image/16.jpg" alt="" />
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
                                <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                <div class="availability-list in-stock">Availability: <span>1200 In Stock</span></div>
                                <p>Dimensions (W x H x D): TV without stand: 43.7" x 25.3" x 3", TV with stand: 43.7" x 27.7" x 8.5"</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price">$21.51</li>
                                        <li class="current-price">$21.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="single-product.html" class="thumbnail">
                                    <img class="first-img" src="assets/images/product-image/11.jpg" alt="" />
                                    <img class="second-img" src="assets/images/product-image/12.jpg" alt="" />
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
                                <h2><a href="single-product.html" class="product-link">Sony WH-CH700N</a></h2>
                                <div class="availability-list in-stock">Availability: <span>300 In Stock</span></div>
                                <p>Android 8.1 (Oreo), Hisilicon Kirin 710 (12 nm), Octa-core (4x2.2 GHz Cortex-A73 &amp; 4x1.7 GHz Cortex-A53), Mali-G51 MP4</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="current-price">$21.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="single-slider-item">
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="single-product.html" class="thumbnail">
                                    <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                    <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
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
                                <a class="inner-link" href="shop-4-column.html"><span>Graphic Corner</span></a>
                                <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                <div class="availability-list in-stock">Availability: <span>900 In Stock</span></div>
                                <p>Android 8.1 (Oreo), Hisilicon Kirin 710 (12 nm), Octa-core (4x2.2 GHz Cortex-A73 &amp; 4x1.7 GHz Cortex-A53), Mali-G51 MP4</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="current-price">$29.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
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
                            <ul class="product-flag">
                                <li class="new">-12%</li>
                            </ul>
                            <div class="product-decs">
                                <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                <h2><a href="single-product.html" class="product-link">Wireless Stereo Speaker</a></h2>
                                <div class="availability-list in-stock">Availability: <span>300 In Stock</span></div>
                                <p>Galileo, and QZSS, Barometric Altimeter, Optical Heart Sensor, Accelerometer And Gyroscope, Ion-X Strengthened Glass</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price">$25.51</li>
                                        <li class="current-price">$21.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="single-slider-item">
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="single-product.html" class="thumbnail">
                                    <img class="first-img" src="assets/images/product-image/15.jpg" alt="" />
                                    <img class="second-img" src="assets/images/product-image/16.jpg" alt="" />
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
                                <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                <div class="availability-list in-stock">Availability: <span>1200 In Stock</span></div>
                                <p>Dimensions (W x H x D): TV without stand: 43.7" x 25.3" x 3", TV with stand: 43.7" x 27.7" x 8.5"</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="old-price">$21.51</li>
                                        <li class="current-price">$21.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="single-product.html" class="thumbnail">
                                    <img class="first-img" src="assets/images/product-image/11.jpg" alt="" />
                                    <img class="second-img" src="assets/images/product-image/12.jpg" alt="" />
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
                                <h2><a href="single-product.html" class="product-link">Sony WH-CH700N</a></h2>
                                <div class="availability-list in-stock">Availability: <span>300 In Stock</span></div>
                                <p>Android 8.1 (Oreo), Hisilicon Kirin 710 (12 nm), Octa-core (4x2.2 GHz Cortex-A73 &amp; 4x1.7 GHz Cortex-A53), Mali-G51 MP4</p>
                                <div class="pricing-meta">
                                    <ul>
                                        <li class="current-price">$21.51</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
    <!-- Best sell Area End -->

    <!-- Banner Area Start -->
    <div class="banner-area mt-30px mb-20px">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-6 ">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/2.jpg" alt="" /></a>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/3.jpg" alt="" /></a>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/4.jpg" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area End -->

    <!-- Arrivals Area Start -->
    <div class="arrival-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>New Arrivals</h2>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs sub-category">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab-1">Home Audio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-2">Headphones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-3">Cameras</a>
                            </li>
                        </ul>
                        <!-- Nav tabs -->
                    </div>
                </div>
            </div>
            <!-- tab content -->
            <div class="tab-content">
                <!-- First-Tab -->
                <div id="tab-1" class="tab-pane active fade">
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

    <!-- Banner Area Start -->
    <div class="banner-area mt-20px mb-20px ">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/5.jpg" alt="" /></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/6.jpg" alt="" /></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="banner-wrapper">
                        <a href="shop-4-column.html"><img src="assets/images/banner-image/7.jpg" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area End -->

    <!-- Catogery slider area Start-->
    <div class="category-slider-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-md-30px mb-lm-30px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Home Audio</h2>
                            </div>
                        </div>
                    </div>
                    <div class="category-slider-wraper slider-nav-style-1">

                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/19.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/20.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/13.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/14.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/11.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/12.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
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
                                            <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/10.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/19.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Madden by Steve Madden Cale 6</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Wireless Stereo Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-md-30px mb-lm-30px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Headphones</h2>
                            </div>
                        </div>
                    </div>
                    <div class="category-slider-wraper slider-nav-style-1">

                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/10.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/19.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Madden by Steve Madden Cale 6</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Wireless Stereo Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
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
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/13.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/14.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/11.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/12.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Television & Video</h2>
                            </div>
                        </div>
                    </div>
                    <div class="category-slider-wraper slider-nav-style-1 ">

                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/19.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/20.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Silicon Sleeping Earbuds</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/13.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/14.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/11.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/12.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
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
                                            <img class="first-img" src="assets/images/product-image/6.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/7.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/10.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/19.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Madden by Steve Madden Cale 6</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                            <article class="list-product text-center">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-decs">
                                        <h2><a href="single-product.html" class="product-link">Wireless Stereo Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Catogery slider area End-->

    <!-- Brand area start -->
    <div class="brand-area mt-20px">
        <div class="container">
            <div class="brand-slider">
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/1.jpg" alt="" /></a>
                </div>
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/2.jpg" alt="" /></a>
                </div>
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/3.jpg" alt="" /></a>
                </div>
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/4.jpg" alt="" /></a>
                </div>
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/5.jpg" alt="" /></a>
                </div>
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/1.jpg" alt="" /></a>
                </div>
                <div class="brand-slider-item">
                    <a href="#"><img class=" img-responsive" src="assets/images/brand-logo/2.jpg" alt="" /></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Brand area end -->

    <!-- category Area Start -->
    <div class="popular-categories-area mt-50px mb-50px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2 class="section-heading">Popular Categories</h2>
                    </div>
                </div>
            </div>
            <div class="popular-category-slider-wrapper slider-nav-style-1 slider-nav-style-2 ">
                <!-- single-item -->
                <div class="single-slider-item">
                    <div class="thumb-category">
                        <a href="single-product.html">
                            <img src="assets/images/popular-image/1.jpg" alt="product-image.jpg" />
                        </a>
                    </div>
                    <div class="category-discript">
                        <h4>Phones & Accessories</h4>
                        <ul>
                            <li><a href="#">Hot Categories</a></li>
                            <li><a href="#">Outerwear & Jackets</a></li>
                            <li><a href="#">Weddings & Events</a></li>
                            <li><a href="#">Bottoms</a></li>
                            <li><a href="#">Tops & Sets</a></li>
                        </ul>
                        <a href="shop-4-column.html" class="view-all-btn">View All</a>
                    </div>
                </div>
                <!-- single-item -->
                <div class="single-slider-item">
                    <div class="thumb-category">
                        <a href="single-product.html">
                            <img src="assets/images/popular-image/2.jpg" alt="product-image.jpg" />
                        </a>
                    </div>
                    <div class="category-discript">
                        <h4>Home Audio</h4>
                        <ul>
                            <li><a href="#">Skirts</a></li>
                            <li><a href="#">Leggings</a></li>
                            <li><a href="#">Jeans</a></li>
                            <li><a href="#">Pants & Capris</a></li>
                            <li><a href="#">Shorts</a></li>
                        </ul>
                        <a href="shop-4-column.html" class="view-all-btn">View All</a>
                    </div>
                </div>
                <!-- single-item -->
                <div class="single-slider-item">
                    <div class="thumb-category">
                        <a href="single-product.html">
                            <img src="assets/images/popular-image/3.jpg" alt="product-image.jpg" />
                        </a>
                    </div>
                    <div class="category-discript">
                        <h4>Audio & Video</h4>
                        <ul>
                            <li><a href="#">Accessories & Parts</a></li>
                            <li><a href="#">Audio & Video</a></li>
                            <li><a href="#">Camera & Photo</a></li>
                            <li><a href="#">Portable Audio & Video</a></li>
                            <li><a href="#">Smart Electronics</a></li>
                        </ul>
                        <a href="shop-4-column.html" class="view-all-btn">View All</a>
                    </div>
                </div>
                <!-- single-item -->
                <div class="single-slider-item">
                    <div class="thumb-category">
                        <a href="single-product.html">
                            <img src="assets/images/popular-image/4.jpg" alt="product-image.jpg" />
                        </a>
                    </div>
                    <div class="category-discript">
                        <h4>Cameras</h4>
                        <ul>
                            <li><a href="#">Televisions</a></li>
                            <li><a href="#">TV Receivers</a></li>
                            <li><a href="#">Projectors</a></li>
                            <li><a href="#">Audio Amplifier Boards</a></li>
                            <li><a href="#">TV Sticks</a></li>
                        </ul>
                        <a href="shop-4-column.html" class="view-all-btn">View All</a>
                    </div>
                </div>
                <!-- single-item -->
                <div class="single-slider-item">
                    <div class="thumb-category">
                        <a href="single-product.html">
                            <img src="assets/images/popular-image/1.jpg" alt="product-image.jpg" />
                        </a>
                    </div>
                    <div class="category-discript">
                        <h4>Television & Video</h4>
                        <ul>
                            <li><a href="#">Wearable Devices</a></li>
                            <li><a href="#">Handheld Game Players</a></li>
                            <li><a href="#">Game Controllers</a></li>
                            <li><a href="#">Joysticks</a></li>
                            <li><a href="#">Stickers</a></li>
                        </ul>
                        <a href="shop-4-column.html" class="view-all-btn">View All</a>
                    </div>
                </div>
                <!-- single-item end-->
            </div>
        </div>
    </div>
    <!-- category Area End -->

    <!-- News letter area -->
    <div class="news-letter-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4 mb-md-30px mb-lm-30px">
                    <div class="title-newsletter">
                        <h2>Sign Up For Newsletters</h2>
                        <p class="des">Be the First to Know. Sign up for newsletter today !</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div id="mc_embed_signup" class="subscribe-form">
                        <form
                            id="mc-embedded-subscribe-form"
                            class="validate"
                            novalidate=""
                            target="_blank"
                            name="mc-embedded-subscribe-form"
                            method="post"
                            action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"
                        >
                            <div id="mc_embed_signup_scroll" class="mc-form">
                                <input class="email" type="email" required="" placeholder="Enter your email here.." name="EMAIL" value="" />
                                <div class="mc-news" aria-hidden="true">
                                    <input type="text" value="" tabindex="-1" name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef" />
                                </div>
                                <div class="clear">
                                    <input id="mc-embedded-subscribe" class="button" type="submit" name="subscribe" value="Sign Up" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News letter area  End -->


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
