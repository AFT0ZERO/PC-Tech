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
                <a href="{{route('category',$category->id)}}">
                <div class="card p-4">
                    <div class="card-body">

                        <div class="text">
                            <h5 class="card-title">{{$category->name}}</h5>
                            <a href="{{route('category', $category->id)}}" class="shop-now">Show All <span>&#x27A4;</span></a>
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
                            <a  href="#" class="active" data-bs-toggle="tab">
                                <i class="fa fa-th"></i>
                            </a>

                            <p>There is {{$products->count()}} Components</p>
                        </div>
                        <!-- Left Side End -->

                        <!-- Sort  Side Start -->
                        <div class="select-shoing-wrap d-flex">
                            <div class="shot-product">
                                <p>Sort By:</p>
                            </div>
                            <div class="shop-select">
                                <select id="sort-products">
                                    <option value="newness">Sort by newness</option>
                                    <option value="name-asc">Name (A to Z)</option>
                                    <option value="name-desc">Name (Z to A)</option>
                                    <option value="price-asc">Price (Low to High)</option>
                                    <option value="price-desc">Price (High to Low)</option>
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

                                    <div id="product-container" class="row">
                                        @foreach($products as $product)
                                            @php
                                                if (Auth::check())
                                                {
                                                    $isFavorited = auth()->user()->favorites->contains($product->id);
                                                }
                                            @endphp
                                            <div class="mb-30px col-md-4 col-sm-6 p-1"
                                                 data-name="{{ $product->name }}"
                                                 data-price="{{ $product->cheapest_price }}"
                                                 data-brand="{{ $product->brand }}">

                                                <div class="slider-single-item">
                                                    <!-- Single Item -->
                                                    <article class="list-product p-0 text-center">
                                                        <div class="product-inner">
                                                            <div class="img-block">
                                                                <a href="{{ route('singlePage', $product->id) }}" class="thumbnail">
                                                                    <img class="first-img" src="{{ asset($product->images[0]->image) }}" alt="{{ $product->name }}" />
                                                                    <img class="second-img" src="{{ asset($product->images[0]->image) }}" alt="{{ $product->name }}" />
                                                                </a>
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
                                                            </div>

                                                            <div class="product-decs">
                                                                <a class="inner-link" href="{{ route('category', $product->category->id) }}">
                                                                    <span>{{ $product->category->name }}</span>
                                                                </a>
                                                                <h2><a href="{{ route('singlePage', $product->id) }}" class="product-link">{{ $product->name }}</a></h2>
                                                                <div class="pricing-meta">
                                                                    <ul>
                                                                        <li class="current-price">{{ $product->cheapest_price }}</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="cart-btn">
                                                                <a href="{{ route('singlePage', $product->id) }}" class="add-to-curt" title="Add to cart">Show</a>
                                                            </div>
                                                        </div>
                                                    </article>
                                                    <!-- Single Item -->
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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

                        <!-- Filter Header -->
                        <div class="sidebar-widget-group mt-20">
                            <h3 class="sidebar-title m-0"><span>Filter By</span></h3>

                            <!-- Price Filter -->
                            <div class="sidebar-widget mt-20">
                                <h4 class="pro-sidebar-title">Price</h4>
                                <div class="price-filter mt-10">
                                    <div class="price-slider-amount">
                                        <input type="text" id="amount" name="price" placeholder="Enter your price" readonly />
                                    </div>
                                    <div id="slider-range"></div>
                                </div>
                            </div>

                            <!-- Brand Filter -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Brand</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        @foreach($brands as $brand)
                                            <li>
                                                <div class="sidebar-widget-list-left">
                                                    <input type="checkbox" id="brand-{{$brand->id}}" name="brand" value="{{$brand->brand}}" />
                                                    <span class="checkmark "></span>
                                                    <label for="brand-{{$brand->id}}"class="ms-4">
                                                        {{$brand->brand}} <span>({{$brand->product_count}})</span>
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
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
    <!-- Shop Category Area End -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        document.getElementById('sort-products').addEventListener('change', function () {
            const sortValue = this.value; // Get the selected value from the dropdown
            const productContainer = document.getElementById('product-container'); // Reference to the parent container of products
            const products = Array.from(productContainer.children); // Convert the product nodes to an array for sorting

            // Sort the products based on the selected sorting option
            pproducts.sort((first_product, second_product) => {
                if (sortValue === 'name-asc') {
                    // Sort by name (A to Z)
                    return first_product.dataset.name.localeCompare(second_product.dataset.name);
                } else if (sortValue === 'name-desc') {
                    // Sort by name (Z to A)
                    return second_product.dataset.name.localeCompare(first_product.dataset.name);
                } else if (sortValue === 'price-asc') {
                    // Sort by price (Low to High)
                    return parseFloat(first_product.dataset.price) - parseFloat(second_product.dataset.price);
                } else if (sortValue === 'price-desc') {
                    // Sort by price (High to Low)
                    return parseFloat(second_product.dataset.price) - parseFloat(first_product.dataset.price);
                }
                return 0; // Default: No sorting
            });

            // Reorder DOM elements based on the sorted array
            products.forEach(product => productContainer.appendChild(product));
        });

    </script>
    <script>
        $(document).ready(function () {
            // Initialize the slider
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 2000,
                values: [0, 2000],
                slide: function (event, ui) {
                    // Update the price input field when the slider moves
                    $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                }
            });

            // Set initial values in the price input field
            $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));

            // Handle brand filter checkboxes
            const brandCheckboxes = $(".sidebar-widget-list input[type='checkbox']");

            // Product filtering function
            function filterProducts() {
                // Get the price range from the slider
                const minPrice = $("#slider-range").slider("values", 0);
                const maxPrice = $("#slider-range").slider("values", 1);

                console.log("Filtering products between $" + minPrice + " and $" + maxPrice);

                // Get selected brands (from checked checkboxes)
                const selectedBrands = brandCheckboxes.filter(":checked").map(function () {
                    return this.value;
                }).get();

                // Get all products
                const products = $("#product-container .col-md-4");

                // Loop through each product
                products.each(function () {
                    const productPrice = parseFloat($(this).data("price"));
                    const productBrand = $(this).data("brand");

                    // Check if product matches the filters
                    const isPriceMatch = productPrice >= minPrice && productPrice <= maxPrice;
                    const isBrandMatch = selectedBrands.length === 0 || selectedBrands.includes(productBrand);

                    // Show or hide the product
                    if (isPriceMatch && isBrandMatch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Event listener for brand checkboxes (onchange)
            brandCheckboxes.on("change", function () {
                filterProducts();  // Trigger filtering when brand checkbox changes
            });

            // Event listener for slider (onchange)
            $("#slider-range").on("slidechange", function () {
                filterProducts();  // Trigger filtering when slider values change
            });

            // Initial filtering based on default slider values and checked brands
            filterProducts();
        });

    </script>


@endsection
