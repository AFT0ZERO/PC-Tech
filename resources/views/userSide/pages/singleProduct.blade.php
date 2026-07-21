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
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                            <tr>
                                                <th scope="col">Store</th>
                                                <!-- <th scope="col">Status</th> -->
                                                <th scope="col">Price</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($product->stores->count() > 0)
                                                @php
        // Get the lowest price among valid active prices to highlight the row
        $lowestPrice = null;
        $storePrices = [];
        foreach ($product->stores as $store) {
            if (strtolower($store->pivot->product_status) === 'not found')
                continue;
            $livePrice = isset($priceHistory) && $priceHistory->has($store->name) ? $priceHistory->get($store->name) : null;
            $displayPrice = $livePrice ? $livePrice->price : $store->pivot->product_price;
            $storePrices[$store->id] = $displayPrice;
            if ($lowestPrice === null || $displayPrice < $lowestPrice) {
                $lowestPrice = $displayPrice;
            }
        }
                                                @endphp

                                                @foreach($product->stores as $store)
                                                    @if(strtolower($store->pivot->product_status) === 'not found')
                                                        @continue
                                                    @endif
                                                    @php
            $livePrice = isset($priceHistory) && $priceHistory->has($store->name) ? $priceHistory->get($store->name) : null;
            $displayPrice = $storePrices[$store->id];
            $isLive = $livePrice ? true : false;
            $isBestDeal = ($displayPrice == $lowestPrice);
                                                    @endphp
                                                    <tr class="{{ $isBestDeal ? 'table-success border-success' : '' }}">
                                                        <td>
                                                            @if(isset($store->image) && $store->image)
                                                                <img src="{{asset($store->image)}}" alt="{{ $store->name }}" width="90" class="me-2 rounded">
                                                            @endif
                                                        </td>
                                                        <!-- <td style="font-size: 15px; font-weight: 600;">
                                                            @if(strtolower($store->pivot->product_status) == 'out of stock' || strtolower($store->pivot->product_status) == 'not found')
                                                                <span class="text-danger">{{ ucwords($store->pivot->product_status) }}</span>
                                                            @else
                                                                <span class="text-success">{{ ucwords($store->pivot->product_status) }}</span>
                                                            @endif
                                                        </td> -->
                                                        <td>
                                                            <div class="mb-0 {{ $isBestDeal ? 'text-success' : '' }}" style="font-size: 15px; font-weight: 600;">
                                                                {{ $isLive ? $livePrice->currency . ' ' : 'JOD ' }}{{ number_format($displayPrice, 2) }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($store->pivot->product_url && $store->pivot->product_url !== '#')
                                                                <a href="{{ $store->pivot->product_url }}" target="_blank" rel="noopener">
                                                                    <button class="btn btn-primary btn-sm">View Deal</button>
                                                                </a>
                                                            @else
                                                                <button class="btn btn-secondary btn-sm" disabled>No Link</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No pricing data available for this product.</td>
                                                </tr>
                                            @endif
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
    if (Auth::check()) {
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

            <!-- Price History Chart Area Start -->
            @if(isset($priceHistoryChart) && $priceHistoryChart->isNotEmpty())
            <section class="price-history-area ptb-50px" style="background: #f8f9fa;">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-3" style="font-weight: 700; color: #333;">Price History</h4>
                            <div style="background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.07);">
                                <canvas id="priceHistoryChart" style="width:100%; max-height:360px;"></canvas>
                            </div>
                            <p class="mt-2 text-muted" style="font-size:13px;">Prices are updated automatically via our scraper. Currency: JOD.</p>
                        </div>
                    </div>
                </div>
            </section>

            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
            <script>

            (function () {
                var rawData = @json($priceHistoryChart);

                // Palette for multiple stores
                var palette = [
                    { border: '#4e73df', background: 'rgba(78,115,223,0.10)' },
                    { border: '#e74a3b', background: 'rgba(231,74,59,0.10)'  },
                    { border: '#1cc88a', background: 'rgba(28,200,138,0.10)' },
                    { border: '#f6c23e', background: 'rgba(246,194,62,0.10)' },
                    { border: '#36b9cc', background: 'rgba(54,185,204,0.10)' },
                ];

                // Collect all unique date labels across all stores
                var allDates = [];
                Object.values(rawData).forEach(function (points) {
                    points.forEach(function (p) {
                        if (allDates.indexOf(p.date) === -1) allDates.push(p.date);
                    });
                });
                allDates.sort();

                var datasets = Object.keys(rawData).map(function (storeName, idx) {
                    var color = palette[idx % palette.length];
                    var pointMap = {};
                    rawData[storeName].forEach(function (p) { pointMap[p.date] = p.price; });
                    return {
                        label: storeName,
                        data: allDates.map(function (d) { return pointMap[d] !== undefined ? pointMap[d] : null; }),
                        borderColor: color.border,
                        backgroundColor: color.background,
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.3,
                        spanGaps: true,
                    };
                });

                var ctx = document.getElementById('priceHistoryChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: { labels: allDates, datasets: datasets },
                    options: {
                        responsive: true,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: { font: { size: 13 }, usePointStyle: true }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (ctx) {
                                        return ctx.dataset.label + ': JOD ' + (ctx.parsed.y !== null ? ctx.parsed.y.toFixed(2) : 'N/A');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    maxTicksLimit: 10,
                                    maxRotation: 30,
                                    font: { size: 11 }
                                },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            y: {
                                beginAtZero: false,
                                ticks: {
                                    callback: function (val) { return 'JOD ' + val.toFixed(2); },
                                    font: { size: 11 }
                                },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            }
                        }
                    }
                });
            })();
            </script>
            @endif
            <!-- Price History Chart Area End -->

            <!-- product details description area start -->
            <div class="description-review-area mb-50px bg-light-gray-3 ptb-50px">
                <div class="container" id="des">
                    <div class="description-review-wrapper" >
                        <div class="description-review-topbar nav"  >
                            <a class="active" data-bs-toggle="tab" href="#des-details2">Product Details</a>
                            <a data-bs-toggle="tab" href="#des-details3">Reviews ({{$feedbacks->count()}})</a>
                        </div>

                        <div class="tab-content description-review-bottom">
                            {{-- Product Details start --}}
                            <div id="des-details2" class="tab-pane active">
                                <div class="product-anotherinfo-wrapper">
                                    <ul>
                                        @foreach($description as $key => $value)
                                        <li><span>{{$key}} </span> {{$value}}</li>
                                        @endforeach
                                    </ul>
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

                                                    <img src="{{asset($feedback->user->image)}}" class="w-50 h-50 rounded-circle" alt="{{$feedback->user->fname . " " . $feedback->user->lname}}" />
                                                </div>
                                                <div class="review-content">
                                                    <div class="review-top-wrap">
                                                        <div class="review-left">
                                                            <div class="review-name">
                                                                <h4>{{$feedback->user->fname . " " . $feedback->user->lname}}</h4>
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
                                                        <label for="rate" class="form-label">Rating <span class="text-danger">*</span></label>
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
                                                                <input type="hidden" name="user_id" value="{{ Auth::user() ? Auth::user()->id : NUll }}" />

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
                                                    <a href="{{route('singlePage', $CategoryProduct->id)}}" class="thumbnail">
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
                                                    <h2><a href="{{route('singlePage', $CategoryProduct->id)}}" class="product-link">{{$CategoryProduct->name}}</a></h2>
                                                    <div class="pricing-meta">
                                                        <ul>
                                                            <li class="current-price">{{$CategoryProduct->cheapest_price}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="cart-btn">
                                                    <a href="{{route('singlePage', $CategoryProduct->id)}}" class="add-to-curt" title="Add to cart">Show</a>
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
