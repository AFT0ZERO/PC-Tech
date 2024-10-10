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
                        <!-- Right Side Start -->
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
                        <!-- Right Side End -->
                    </div>
                    <!-- Shop Top Area End -->

                    <!-- Shop Bottom Area Start -->
                    <div class="shop-bottom-area mt-35">
                        <!-- Shop Tab Content Start -->
                        <div class="tab-content jump">
                            <!-- Tab One Start -->
                            <div id="shop-1" class="tab-pane">
                                <div class="row m-0">
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                        </div>
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                                        <li class="new">-10%</li>
                                                    </ul>
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
                                        </div>
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class="mb-30px col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class=" col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class=" col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                    </div>
                                    <div class=" col-md-4 col-sm-6  p-0">
                                        <div class="slider-single-item">
                                            <!-- Single Item -->
                                            <article class="list-product p-0 text-center">
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
                                </div>
                            </div>
                            <!-- Tab One End -->
                            <!-- Tab Two Start -->
                            <div id="shop-2" class="tab-pane active">
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/7.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/8.jpg" alt="" />
                                                                </a>
                                                                <ul class="product-flag">
                                                                    <li class="new">-10%</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Fila Locker Room Varsity Jacket</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Galileo, and QZSS, Barometric Altimeter, Optical Heart Sensor, Accelerometer And Gyroscope, Ion-X Strengthened Glass</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="old-price">$21.90</li>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Naham WiFi HD 1080P</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Dimensions (W x H x D): TV without stand: 43.7" x 25.3" x 3", TV with stand: 43.7" x 27.7" x 8.5"</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Android 8.1 (Oreo), Hisilicon Kirin 710 (12 nm), Octa-core (4x2.2 GHz Cortex-A73 & 4x1.7 GHz Cortex-A53), Mali-G51 MP4</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>199 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">New Balance Arishi Sport V1</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Break old records and make new goals in the New Balance® Arishi Sport v1.Predecessor: None. Support Type: Neutral. Cushioning: High energizing cushioning.</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Calvin Klein® Jeans hoodie with reflective logo detailing at the sleeves.Sweatshirt crafted in a soft-knit fabric for superior comfort.Drawstring hood.Long sleeves.Full-zip front.</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                                                </a>
                                                                <ul class="product-flag">
                                                                    <li class="new">-12%</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>No one can deny your sleek style with these handsome Madden by Steve Madden® Cale 6 oxfords.Man-made upper features a plain toe.Lace-up closure.Man-made lining.</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>599 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="old-price">$21.90</li>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/7.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/8.jpg" alt="" />
                                                                </a>
                                                                <ul class="product-flag">
                                                                    <li class="new">-10%</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Fila Locker Room Varsity Jacket</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Galileo, and QZSS, Barometric Altimeter, Optical Heart Sensor, Accelerometer And Gyroscope, Ion-X Strengthened Glass</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="old-price">$21.90</li>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Naham WiFi HD 1080P</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Dimensions (W x H x D): TV without stand: 43.7" x 25.3" x 3", TV with stand: 43.7" x 27.7" x 8.5"</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Android 8.1 (Oreo), Hisilicon Kirin 710 (12 nm), Octa-core (4x2.2 GHz Cortex-A73 & 4x1.7 GHz Cortex-A53), Mali-G51 MP4</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>199 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">New Balance Arishi Sport V1</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Break old records and make new goals in the New Balance® Arishi Sport v1.Predecessor: None. Support Type: Neutral. Cushioning: High energizing cushioning.</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>Calvin Klein® Jeans hoodie with reflective logo detailing at the sleeves.Sweatshirt crafted in a soft-knit fabric for superior comfort.Drawstring hood.Long sleeves.Full-zip front.</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>299 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="shop-list-wrap mb-30px scroll-zoom">
                                    <div class="slider-single-item">
                                        <div class="row list-product m-0px">
                                            <div class="col-md-12 padding-0px product-inner">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                        <div class="left-img">
                                                            <div class="img-block">
                                                                <a href="single-product.html" class="thumbnail">
                                                                    <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                                                    <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                                                </a>
                                                                <ul class="product-flag">
                                                                    <li class="new">-12%</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                        <div class="product-desc-wrap">
                                                            <div class="product-decs">
                                                                <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                                                <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                                                <div class="product-intro-info">
                                                                    <p>No one can deny your sleek style with these handsome Madden by Steve Madden® Cale 6 oxfords.Man-made upper features a plain toe.Lace-up closure.Man-made lining.</p>
                                                                </div>
                                                            </div>
                                                            <div class="box-inner d-flex">
                                                                <div class="align-self-center">
                                                                    <div class="in-stock">Availability: <span>599 In Stock</span></div>
                                                                    <div class="pricing-meta">
                                                                        <ul>
                                                                            <li class="old-price">$21.90</li>
                                                                            <li class="current-price">$18.50</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="cart-btn">
                                                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                                                    </div>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Two End -->
                        </div>
                        <!-- Shop Tab Content End -->
                        <!--  Pagination Area Start -->
                        <div class="pro-pagination-style text-center mtb-50px">
                            <ul>
                                <li>
                                    <a class="prev" href="#"><i class="ion-ios-arrow-left"></i></a>
                                </li>
                                <li><a class="active" href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li>
                                    <a class="next" href="#"><i class="ion-ios-arrow-right"></i></a>
                                </li>
                            </ul>
                        </div>
                        <!--  Pagination Area End -->
                    </div>
                    <!-- Shop Bottom Area End -->
                </div>
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 order-lg-first col-md-12 order-md-last mb-md-60px mb-lm-60px">
                    <div class="shop-sidebar-wrap">
                        <div class="sidebar-widget mb-30px">
                            <h3 class="sidebar-title"><span>Phones & Accessories</span></h3>
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <a href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">Hot Category</a>
                                    </div>

                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul class="category-list">
                                                <li><a href="#">Dresses</a></li>
                                                <li><a href="#">Jackets &amp; Coats</a></li>
                                                <li><a href="#">Sweaters</a></li>
                                                <li><a href="#">Jeans</a></li>
                                                <li><a href="#">Blouses &amp; Shirts</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Outerwear &amp; Jackets</a>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul class="category-list">
                                                <li><a href="#">Basic Jackets</a></li>
                                                <li><a href="#">Real Fur</a></li>
                                                <li><a href="#">Down Coats</a></li>
                                                <li><a href="#">Blazers</a></li>
                                                <li><a href="#">Trench Coats </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Weddings &amp; Events</a>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul class="category-list">
                                                <li><a href="#">Wedding Dresses</a></li>
                                                <li><a href="#">Evening Dresses</a></li>
                                                <li><a href="#">Prom Dresses </a></li>
                                                <li><a href="#">Bridesmaid Dresses</a></li>
                                                <li><a href="#">Wedding Accessories</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                                <div class="card">
                                    <div class="card-header" id="headingFour">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Bottoms</a>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul class="category-list">
                                                <li><a href="#">Skirts</a></li>
                                                <li><a href="#">Leggings</a></li>
                                                <li><a href="#">Pants &amp; Capris</a></li>
                                                <li><a href="#">Wide Leg Pants</a></li>
                                                <li><a href="#">Shorts</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                                <div class="card">
                                    <div class="card-header" id="headingFive">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">Tops &amp; Sets</a>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul class="category-list">
                                                <li><a href="#">Tank Tops</a></li>
                                                <li><a href="#">Suits &amp; Sets</a></li>
                                                <li><a href="#">Jumpsuits</a></li>
                                                <li><a href="#">Rompers</a></li>
                                                <li><a href="#">Sleep &amp; Lounge</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                                <div class="card">
                                    <div class="card-header" id="headingSix">
                                        <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Accessories</a>
                                    </div>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul class="category-list">
                                                <li><a href="#">Eyewear &amp; Accessories</a></li>
                                                <li><a href="#">Hats &amp; Caps</a></li>
                                                <li><a href="#">Belts &amp; Cummerbunds</a></li>
                                                <li><a href="#">Scarves &amp; Wraps</a></li>
                                                <li><a href="#">Gloves &amp; Mittens</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> <!-- card -->
                            </div>
                        </div>
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
                        <div class="sidebar-widget tag mt-30px">
                            <div class="main-heading">
                                <h3 class="sidebar-title"><span>Tags</span></h3>
                            </div>
                            <div class="sidebar-widget-tag">
                                <ul>
                                    <li><a href="#">Tvs</a></li>
                                    <li><a href="#">Mobiles</a></li>
                                    <li><a href="#">Accessories</a></li>
                                    <li><a href="#">Audio</a></li>
                                    <li><a href="#">Cameras</a></li>
                                    <li><a href="#">Gamepad</a></li>
                                    <li><a href="#">Video</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Sidebar single item -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Shop Category Area End -->

@endsection
