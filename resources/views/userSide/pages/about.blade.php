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
                            <li>About Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <!-- About Area Start -->
    <section class="about-area mtb-50px">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-left-image mb-md-30px mb-lm-30px ">
                        <img src="{{asset('assets/img/image/about.jpg')}}" alt="test" class="img-responsive" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="about-title">
                            <h2>Welcome To Pc Tech</h2>
                        </div>
                        <p class="mb-30px">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore aperiam fugit consequuntur voluptatibus ex sint iure in, distinctio sed dolorem aspernatur veritatis repellendus dolorum voluptate, animi
                            libero officiis eveniet accusamus recusandae. Temporibus amet ducimus sapiente voluptatibus autem dolorem magnam quas, porro suscipit. Quibusdam culpa asperiores exercitationem architecto quo distinctio sed dolorem aspernatur veritatis repellendus dolorum voluptate!
                        </p>
                        <p>
                            Sint voluptatum beatae necessitatibus quos mollitia vero, optio asperiores aut tempora iusto eum rerum, possimus, minus quidem ut saepe laboriosam. Praesentium aperiam accusantium minus repellendus
                            accusamus neque iusto pariatur laudantium provident quod recusandae exercitationem natus dignissimos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row mt-50px">
                <div class="col-md-4 mb-lm-30px">
                    <div class="single-about">
                        <h4>Our Company</h4>
                        <p>
                            Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Lorem ipsum dolor sit amet conse .
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-lm-30px">
                    <div class="single-about">
                        <h4>Our Team</h4>
                        <p>
                            Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Lorem ipsum dolor sit amet conse .
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-about">
                        <h4>Testimonial</h4>
                        <p>
                            Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Lorem ipsum dolor sit amet conse .
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Area End -->
@endsection
