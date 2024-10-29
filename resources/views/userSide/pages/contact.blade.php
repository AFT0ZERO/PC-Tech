@extends('userSide.layout.app')

@section('content')
    <!-- SweetAlert success message -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="index.html">Home</a></li>
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
                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe id="gmap_canvas" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d660.3942931351413!2d35.01256668734012!3d29.535576259740235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x150071875a9fca41%3A0xf5d61d999f967371!2sOrange%20Digital%20Village%20Aqaba!5e1!3m2!1sen!2sjo!4v1728558897835!5m2!1sen!2sjo" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        <a href="https://sites.google.com/view/maps-api-v2/mapv2"></a>
                    </div>
                </div>
            </div>
            <div class="custom-row-2">

                <div class="col-lg-4 col-md-5 mb-lm-30px">
                    <div class="contact-info-wrap">
                        <div class="single-contact-info">
                            <div class="contact-icon">
                                <i class="ion-android-call"></i>
                            </div>
                            <div class="contact-info-dec">
                                <p><a href="tel://+962 791580267">+962 791580267</a></p>
                                <p><a href="tel://+962 791580267">+962 791580267</a></p>
                            </div>
                        </div>
                        <div class="single-contact-info">
                            <div class="contact-icon">
                                <i class="ion-android-globe"></i>
                            </div>
                            <div class="contact-info-dec">
                                <p><a href="mailto://abdallahtamimi54@gmail.com">abdallah@gmail.com</a></p>
                                <p><a href="mailto://abdallahtamimi54@gmail.com">abdallah@gmail.com</a></p>

                            </div>
                        </div>
                        <div class="single-contact-info">
                            <div class="contact-icon">
                                <i class="ion-android-pin"></i>
                            </div>
                            <div class="contact-info-dec">
                                <p>Address goes here,</p>
                                <p>street, Crossroad 123.</p>
                            </div>
                        </div>
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
                                <input type="hidden" name="user_id" value="{{ Auth::check() ? Auth::user()->id : '' }}">

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
