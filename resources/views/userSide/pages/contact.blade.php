@extends('userSide.layout.app')

@section('title', 'Contact')
@section('content')
    <section class="Contact-section">
        <div class="container">
            <div class="row">

                <!-- Contact Info Start -->
                <div class="col-md-6">
                    <div class="Contact-Contant">
                        <div class="Contact-title">
                            <h2>Get in touch with us</h2>
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing
                                elit. Doloremque fugiat, molestias aliquam nihil modi
                                iusto ad hic suscipit impedit, expedita eligendi quod?
                                Voluptate sit qui laboriosam tenetur quia provident accusamus.
                            </p>
                        </div>
                        <div class="Contact-Info">
                            <i class="fa-solid fa-phone fa-lg"></i>
                            <p>+962791580267</p>
                        </div>

                        <div class="Contact-Info">
                            <i class="fa-solid fa-envelope fa-lg"></i>
                            <p>abdallahtamimi54@gmail.com</p>
                        </div>

                        <div class="Contact-Info">
                            <i class="fa-solid fa-location-dot fa-lg"></i>
                            <p>Jordan, Aqaba</p>
                        </div>
                        <div class="Contact-Info">
                            <i class="fa-solid fa-headset fa-xl"></i>
                            <p>24/7</p>
                        </div>
                    </div>
                </div>
                <!-- Contact Info End -->

                <!-- Form Start -->
                <div class="col-md-6">
                    <div class="Contact-Form">
                        <form action="">
                            <textarea name="messages" id="messages" placeholder="Messages" rows="10" class="form-control"></textarea>
                            <input type="text" name="name" placeholder="Your Name" class="form-control">
                            <input type="email" name="email" placeholder="Your Email" class="form-control">
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                    </div>
                </div>
                <!-- Form End -->

            </div>
        </div>
    </section>
@endsection
