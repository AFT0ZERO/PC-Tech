@extends('userSide.layout.app')

@section('content')
    <!-- SweetAlert success message -->
    @if(session('success'))
        <div id="contact-flash-success" class="d-none" data-message="{{ e(session('success')) }}"></div>
        <script>
            (function () {
                var el = document.getElementById('contact-flash-success');
                if (!el) return;
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: el.getAttribute('data-message'),
                    confirmButtonText: 'OK'
                });
            })();
        </script>
    @endif

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('landing') }}">Home</a></li>
                            <li>Contact Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <!-- contact area start -->
    <div class="contact-area mtb-50px">
        <div class="container">
            <div class="contact-map mb-10">
                <div class="contact-photo-wrap overflow-hidden rounded">
                    <img
                        src="{{ asset('assets/img/image/about.jpg') }}"
                        alt="Pc Tech"
                        class="img-fluid w-100 d-block"
                        style="max-height: 500px; object-fit: cover;"
                    >
                </div>
            </div>
            <div class="custom-row-2">

                <div class="col-lg-4 col-md-5 mb-lm-30px">
                    <div class="contact-info-wrap">
                        {{-- <div class="single-contact-info">
                            <div class="contact-icon">
                                <i class="ion-android-call"></i>
                            </div>
                            <div class="contact-info-dec">
                                <p><a href="tel://+962 791580267">+962 791580267</a></p>
                                <p><a href="tel://+962 791580267">+962 791580267</a></p>
                            </div>
                        </div> --}}
                        {{-- <div class="single-contact-info">
                            <div class="contact-icon">
                                <i class="ion-android-globe"></i>
                            </div>
                            <div class="contact-info-dec">
                                <p><a href="mailto://abdallahtamimi54@gmail.com">abdallah@gmail.com</a></p>

                            </div>
                        </div> --}}
                        <div class="contact-social">
                            <h3>Follow Us</h3>
                            <div class="social-info">
                                <ul>
                                    <li>
                                        <a href="#"><i class="ion-social-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="ion-social-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="ion-social-youtube"></i></a>
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

                <div class="col-lg-8 col-md-7">
                    <div class="contact-form">
                        <div class="contact-title mb-30">
                            <h2>Get In Touch</h2>
                        </div>
                        <form class="contact-form-style" id="contact-form" action="{{ route('contact.store') }}" method="post">
                            @csrf
                            <div class="row">
                                @auth
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                @endauth

                                <div class="col-lg-6">
                                    <input name="name" placeholder="Name*" type="text" value="{{ Auth::check() ? Auth::user()->fname : '' }}" />
                                </div>

                                <div class="col-lg-6">
                                    <input name="email" placeholder="Email*" type="email" value="{{ Auth::check() ? Auth::user()->email : '' }}" />
                                </div>

                                <div class="col-lg-12">
                                    <input name="mobile" placeholder="Mobile*" type="text" value="{{ Auth::check() ? Auth::user()->mobile : '' }}" />
                                </div>

                                <div class="col-lg-12">
                                    <textarea name="message" placeholder="Your Message*"></textarea>
                                    <button class="submit" type="submit">SEND</button>
                                </div>
                            </div>
                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contact area end -->
@endsection
