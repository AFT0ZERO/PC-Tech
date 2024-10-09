@extends('userSide.layout.app')

@section('title', 'Home')
@section('content')

{{--    <div style="color: #00aced">--}}
<div class="swiper mySwiper1 mb-3">
    <div class="swiper-wrapper">
        <div class="swiper-slide hero-swiper-section">
            <div class="content">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam
                    asperiores commodi dolore ex explicabo fuga, fugit illum incidunt
                    ipsam iure laboriosam necessitatibus quam sint, tempora temporibus,
                    ut veritatis. Consequatur, inventore?
                </p>
                <a href="#" class="">
                    <button class="btn btn-primary btn-lg float-start mt-5">
                        Build Your Pc
                    </button>
                </a>
            </div>
            <div class="image-container">
                <img src="{{ asset('../assets/img/image/heroPc.png') }}" alt="PC Image" class="img-fluid ">
            </div>
        </div>
        <div class="swiper-slide hero-swiper-section">
            <div class="content">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam
                    asperiores commodi dolore ex explicabo fuga, fugit illum incidunt
                    ipsam iure laboriosam necessitatibus quam sint, tempora temporibus,
                    ut veritatis. Consequatur, inventore?
                </p>
                <a href="#" class="">
                    <button class="btn btn-primary btn-lg float-start mt-5">
                        Build Your Pc
                    </button>
                </a>
            </div>
            <div class="image-container">
                <img src="{{ asset('../assets/img/image/heroPc.png') }}" alt="PC Image" class="img-fluid ">
            </div>
        </div>
        <div class="swiper-slide hero-swiper-section">
            <div class="content">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam
                    asperiores commodi dolore ex explicabo fuga, fugit illum incidunt
                    ipsam iure laboriosam necessitatibus quam sint, tempora temporibus,
                    ut veritatis. Consequatur, inventore?
                </p>
                <a href="#" class="">
                    <button class="btn btn-primary btn-lg float-start mt-5">
                        Build Your Pc
                    </button>
                </a>
            </div>
            <div class="image-container">
                <img src="{{ asset('../assets/img/image/heroPc.png') }}" alt="PC Image" class="img-fluid ">
            </div>
        </div>

        <div class="swiper-slide">Slide 4</div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>

<section class="components">
    <div class="components-title d-flex justify-content-between align-items-center">
        <p>Components</p>
        <a href="#">Show All</a>
    </div>
    <div class="row components-flex">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <a href="#" class="anker">
                <button class="main-container-components w-100">
                    <div class="components-content">
                        <p class="components-name">CPU</p>
                    </div>
                    <div>
                        <img src={{ asset('../assets/img/image/cpu.png') }} alt="cpu" class="img-fluid">
                    </div>
                </button>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <a href="#" class="anker">
                <button class="main-container-components w-100">
                    <div class="components-content">
                        <p class="components-name">Motherboard</p>
                    </div>
                    <div>
                        <img src={{ asset('../assets/img/image/cpu.png') }} alt="cpu" class="img-fluid">
                    </div>
                </button>
            </a>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <a href="#" class="anker">
                <button class="main-container-components w-100">
                    <div class="components-content">
                        <p class="components-name">Memory</p>
                    </div>
                    <div>
                        <img src={{ asset('../assets/img/image/cpu.png') }} alt="cpu" class="img-fluid">
                    </div>
                </button>
            </a>
        </div>
    </div>
</section>


<p> the title </p>
    <div class="swiper mySwiper2 mb-5">
        <div class="swiper-wrapper">
            <div class="swiper-slide">Slide 1</div>
            <div class="swiper-slide">Slide 2</div>
            <div class="swiper-slide">Slide 3</div>
            <div class="swiper-slide">Slide 4</div>
            <div class="swiper-slide">Slide 5</div>
            <div class="swiper-slide">Slide 6</div>
            <div class="swiper-slide">Slide 7</div>
            <div class="swiper-slide">Slide 8</div>
            <div class="swiper-slide">Slide 9</div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

<p> the title </p>
    <div class="swiper mySwiper3">
        <div class="swiper-wrapper">
            <div class="swiper-slide">Slide 1</div>
            <div class="swiper-slide">Slide 2</div>
            <div class="swiper-slide">Slide 3</div>
            <div class="swiper-slide">Slide 4</div>
            <div class="swiper-slide">Slide 5</div>
            <div class="swiper-slide">Slide 6</div>
            <div class="swiper-slide">Slide 7</div>
            <div class="swiper-slide">Slide 8</div>
            <div class="swiper-slide">Slide 9</div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
@endsection
