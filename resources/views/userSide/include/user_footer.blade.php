<footer class="footer-area">
    <div class="footer-container">
        <div class="footer-top py-5">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <!-- Logo and Contact Info -->
                    <div class="col-md-12 col-lg-4 mb-4 text-center text-lg-start">
                        <div class="single-wedge">
                            <div class="footer-logo mb-3">
                                <a href="index.html">
                                    <img class="img-fluid w-50" src="{{ asset('assets/asset/images/main-image/logo.png') }}" alt="logo.jpg" />
                                </a>
                            </div>
                            <p class="text-infor">We are a team of designers and developers that create PC Tech.</p>
                            <div class="need_help">
                                <p><span class="fw-bold">Address:</span> 4710-4890 Jordan, Aqaba</p>
                                <p><span class="fw-bold">Email:</span> <a href="mailto:abdallahtamimi54@gmail.com">abdallahtamimi54@gmail.com</a></p>
                                <p><span class="fw-bold">Call Us:</span> <a href="tel:+962791580267">+962791580267</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- Pages Links -->
                    <div class="col-md-6 col-lg-2 mb-4 text-center text-lg-start">
                        <div class="single-wedge">
                            <h4 class="footer-heading">Pages</h4>
                            <ul class="list-unstyled footer-links">
                                <li><a href="{{ route('landing') }}">Home</a></li>
                                <li><a href="{{ route('categoryNull') }}">Components</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="{{ route('faqs') }}">FAQs</a></li>
                                <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Dynamic Categories -->
                    <div class="col-md-6 col-lg-2 mb-4 text-center text-lg-start">
                        <div class="single-wedge">
                            <h4 class="footer-heading">Components</h4>
                            <ul class="list-unstyled footer-links">
                                @foreach($categories as $category)
                                    <li><a href="{{ route('category', $category->id) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-tags py-3">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0">© {{ date('Y') }} Abdallah. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
