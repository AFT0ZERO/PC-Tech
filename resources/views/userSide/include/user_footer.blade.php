<footer class="footer-area" >
    <div class="footer-container">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-md-30px mb-lm-30px">
                        <div class="single-wedge">
                            <div class="footer-logo">
                                <a href="index.html"><img class="img-responsive w-50 " src="{{asset("assets/asset/images/main-image/logo.png")}}" alt="logo.jpg" /></a>
                            </div>
                            <p class="text-infor">We are a team of designers and developers that create Pc Tech</p>
                            <div class="need_help">
                                <p class="add"><span class="address">Address:</span> 4710-4890 Jordan, Aqaba</p>
                                <p class="mail"><span class="email">Email:</span> <a href="mailto:abdallahtamimi54@gmail.com">abdallahtamimi54@gmail.com</a></p>
                                <p class="phone"><span class="call us">Call Us:</span> <a href="tel:+962791580267">+962791580267</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-wedge">
                            <h4 class="footer-herading">Pages</h4>
                            <div class="footer-links">
                                <ul>
                                    <li><a href="{{route('landing')}}">Home</a></li>
                                    <li><a href="{{route('categoryNull')}}">Components</a></li>
                                    <li><a href="{{route('about')}}">About Us</a></li>
                                    <li><a href="{{route('faqs')}}">FAQs</a></li>
                                    <li><a href="{{route('contact')}}">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 col-sm-6 mb-sm-30px mb-lm-30px">
                        <div class="single-wedge">
                            <h4 class="footer-herading">Components</h4>
                            <div class="footer-links">
                                <ul>
                                    @foreach($categories as $category)
                                    <li><a href="{{route('category',$category->id)}}">{{$category->name}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 ">
                        <div class="single-wedge">
                            <h4 class="footer-herading">From Our Blog</h4>
                            <div class="footer-blog-slider">
                                <div class="footer-blog-slider-wrapper slider-nav-style-3 ">
                                    <!-- Single-item -->
                                    <div class="single-slider-item">
                                        <div class="footer-blog-post d-flex mb-30px">
                                            <div class="footer-blog-post-top">
                                                <div class="post-thumbnail">
                                                    <a href="blog-single-left-sidebar.html">
                                                        <img src="assets/images/blog-image/blog-8.jpg" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="footer-blog-content">
                                                <h4><a href="blog-single-left-sidebar.html">This is First Post For XipBlog</a></h4>
                                                <div class="footer-blog-meta">
                                                    <span class="autor">Posted by <a href="#">Demo Hasthemes</a> </span>
                                                    <span class="date">Jun 29,2022</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer-blog-post">
                                            <div class="footer-blog-post-top">
                                                <div class="post-thumbnail">
                                                    <a href="blog-single-left-sidebar.html">
                                                        <img src="assets/images/blog-image/blog-9.jpg" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="footer-blog-content">
                                                <h4><a href="blog-single-left-sidebar.html">This is Secound Post For XipBlog</a></h4>
                                                <div class="footer-blog-meta">
                                                    <span class="autor">Posted by <a href="#">Demo Hasthemes</a> </span>
                                                    <span class="date">Jun 29,2022</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single-item -->
                                    <div class="single-slider-item">
                                        <div class="footer-blog-post d-flex mb-30px">
                                            <div class="footer-blog-post-top">
                                                <div class="post-thumbnail">
                                                    <a href="blog-single-left-sidebar.html">
                                                        <img src="assets/images/blog-image/blog-10.jpg" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="footer-blog-content">
                                                <h4><a href="blog-single-left-sidebar.html">This is Third Post For XipBlog</a></h4>
                                                <div class="footer-blog-meta">
                                                    <span class="autor">Posted by <a href="#">Demo Hasthemes</a> </span>
                                                    <span class="date">Jun 29,2022</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer-blog-post">
                                            <div class="footer-blog-post-top">
                                                <div class="post-thumbnail">
                                                    <a href="blog-single-left-sidebar.html">
                                                        <img src="assets/images/blog-image/blog-11.jpg" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="footer-blog-content">
                                                <h4><a href="blog-single-left-sidebar.html">This is Fourth Post For XipBlog</a></h4>
                                                <div class="footer-blog-meta">
                                                    <span class="autor">Posted by <a href="#">Demo Hasthemes</a> </span>
                                                    <span class="date">Jun 29,2022</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single-item end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-tags">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                    <div class="col-md-12 text-center">
                        <p class="copy-text">Copyright  Abdallah . All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
