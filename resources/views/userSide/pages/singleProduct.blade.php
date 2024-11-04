@extends('userSide.layout.app')
@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{route('landing')}}">Home</a></li>
                            <li> Product</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->

    <!-- Shop details Area start -->
    <section class="product-details-area mtb-60px ">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="product-details-img product-details-tab">
                        <div class="zoompro-wrap zoompro-2">
                            <div class="zoompro-border zoompro-span">
                                <!-- Main Image -->
                                <img id="mainProductImage" class="" src="{{ asset($product->images[0]->image) }}"  alt="product image" />
                            </div>
                        </div>
                        <!-- Thumbnail Gallery -->
                        <div id="gallery" class="product-dec-slider-2">
                            @foreach($product->images as $image)
                                <div class="single-slide-item">
                                    <img class="img-responsive thumbnail-image" data-image="{{ asset($image->image) }}" src="{{ asset($image->image) }}" alt="product image" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="product-details-content">
                        <h2>{{$product->name}}</h2>
                        <div class="pro-details-rating-wrap">
                            <div class="rating-product">
                                <i class="ion-android-star"></i>
                                <i class="ion-android-star"></i>
                                <i class="ion-android-star"></i>
                                <i class="ion-android-star"></i>
                                <i class="ion-android-star"></i>
                            </div>
                            <span class="read-review"><a class="reviews" href="#des">Read reviews ({{$feedbacks->count()}})</a></span>
                        </div>
                        <div class="pricing-meta">
                            <div class="product-prices">

                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">Shop</th>
                                        <th scope="col">In-Stock</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($product->stores as $store)
                                    <tr>
                                        <td><img src="{{asset($store->image)}}" alt="store image" width="100"></td>
                                        <td>{{$store->pivot->product_status}}</td>
                                        <td>
                                            <a href="{{ $store->pivot->product_url }}" target="_blank" rel="noopener">
                                                <button class="btn btn-primary btn-lg">
                                                    ${{ number_format($store->pivot->product_price, 2) }}
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
{{--                            <ul>--}}
{{--                                <li class="old-price">$23.90</li>--}}
{{--                                <li class="cuttent-price">$21.51</li>--}}
{{--                                <li class="discount-flag">save 10%</li>--}}
{{--                            </ul>--}}
                        </div>
                        <div class="pro-details-list">
                            <p>{{$product->smallDescription}}</p>
                        </div>
                        <div class="pro-details-quality mt-0px">

                            <div class="pro-details-cart btn-hover">
                                <a href="#" class="btn-disabled" title="Coming Soon">Add To Build [Soon]</a>
                            </div>

                        </div>
                        <div class="pro-details-wish-com">
                            <div class="pro-details-wishlist">
                                @php
                                    if (Auth::check())
                                    {
                                        $isFavorited = auth()->user()->favorites->contains($product->id);
                                    }
                                @endphp
                                @auth
                                    <a href="javascript:void(0);" class="add-to-favorite" data-product-id="{{ $product->id }}" title="Add to Favorite">
                                        <i class="lnr lnr-heart {{ auth()->user()->favorites->contains($product->id) ? 'favorite-added' : '' }}"></i>
                                        {{ auth()->user()->favorites->contains($product->id) ? 'Favorited' : 'Add to Favorites' }}
                                    </a>
                                @endauth
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop details Area End -->

    <!-- product details description area start -->
    <div class="description-review-area mb-50px bg-light-gray-3 ptb-50px">
        <div class="container" id="des">
            <div class="description-review-wrapper" >
                <div class="description-review-topbar nav"  >
                    <a data-bs-toggle="tab" href="#des-details1">Description</a>
                    <a class="active" data-bs-toggle="tab" href="#des-details2">Product Details</a>
                    <a data-bs-toggle="tab" href="#des-details3">Reviews ({{$feedbacks->count()}})</a>
                </div>

                <div class="tab-content description-review-bottom">
                    {{-- Description start --}}
                    <div id="des-details2" class="tab-pane active">
                        <div class="product-anotherinfo-wrapper">
                            <ul>
                                @foreach($description as $key => $value)
                                <li><span>{{$key}} </span> {{$value}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    {{-- Description end --}}

                    {{-- Product Details start --}}
                    <div id="des-details1" class="tab-pane">
                        <div class="product-description-wrapper">
                            <p>{{$product->smallDescription}}</p>

                        </div>
                    </div>
                    {{-- Product Details end --}}

                    {{-- Product Reviews start --}}
                    <div id="des-details3" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="review-wrapper">
                                    @foreach($feedbacks as $feedback)
                                    <div class="single-review">
                                        <div class="review-img">

                                            <img src="{{asset($feedback->user->image)}}" class="w-50 h-50 rounded-circle" alt="{{$feedback->user->fname ." ". $feedback->user->lname}}" />
                                        </div>
                                        <div class="review-content">
                                            <div class="review-top-wrap">
                                                <div class="review-left">
                                                    <div class="review-name">
                                                        <h4>{{$feedback->user->fname ." ". $feedback->user->lname}}</h4>
                                                    </div>

                                                    <div class="rating-product">
                                                        @for($i = 0; $i < $feedback->rate; $i++)
                                                            <i class="ion-android-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="review-bottom">
                                                <p>
                                                    {{$feedback->message}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="ratting-form-wrapper pl-50">
                                    <h3>Add a Review</h3>
                                    <div class="ratting-form">
                                        <form action="{{ route('feedback.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="rate" class="form-label">Rating</label>
                                                <select id="rate" name="rate" class="form-select rating-product" required>
                                                    <option value="5" class="rating-product">★★★★★</option>
                                                    <option value="4" class="rating-product">★★★★☆</option>
                                                    <option value="3" class="rating-product">★★★☆☆</option>
                                                    <option value="2" class="rating-product">★★☆☆☆</option>
                                                    <option value="1" class="rating-product">★☆☆☆☆</option>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="rating-form-style form-submit">

                                                        <textarea name="message" placeholder="Message">{{ old('message') }}</textarea>

                                                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                                        <input type="hidden" name="user_id" value="{{ Auth::user()?Auth::user()->id : NUll }}" />

                                                        <input type="submit" value="Submit" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Product Reviews end --}}

                </div>
            </div>
        </div>
    </div>
    <!-- product details description area end -->

    <!-- Similar Area Start -->
    <div class="arrival-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Similar Products</h2>
                    </div>
                </div>
            </div>
            <!-- tab content -->
            <div class="tab-content">
                <!-- First-Tab -->
                <div id="tab-1" class="tab-pane active fade">
                    <!-- Similar slider start -->
                    <div class="arrival-slider-wrapper slider-nav-style-1">
                        @foreach($CategoryProducts as $CategoryProduct)
                            <div class="slider-single-item">
                                <!-- Single Item -->
                                <article class="list-product text-center">
                                    <div class="product-inner">
                                        <div class="img-block">
                                            <a href="{{route('singlePage',$CategoryProduct->id)}}" class="thumbnail">
                                                <img class="first-img" src="{{asset($CategoryProduct->images[0]->image)}}" alt="{{$product->name}}" />
                                            </a>
                                            {{-- model start--}}
                                            <div class="add-to-link">
                                                <ul>
                                                    <li>
                                                        @if (Auth::check())
                                                            <a href="javascript:void(0);" class="add-to-favorite" data-product-id="{{ $product->id }}" title="Add to Favorite">
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
                                            <a href="{{route('singlePage',$CategoryProduct->id)}}" class="add-to-curt" title="Add to cart">Show</a>
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



@endsection
