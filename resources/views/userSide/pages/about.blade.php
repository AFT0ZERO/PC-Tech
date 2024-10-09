@extends('userSide.layout.app')

@section('title', 'About Us')
@section('content')
    <section class="main-page">
        <!-- Image Start -->
        <figure class="about-img">
            <img src="{{asset('../assets/img/image/about (Custom).jpg')}}" alt="background Image">
        </figure>
        <!-- Image End -->

        <!-- Services Start -->
        <section class="services-container">
            <h2>Services</h2>
            <section class="card__container">
                <div class="card__bx" style="--clr: #89ec5b">
                    <div class="card__data">
                        <div class="card__icon">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <div class="card__content">
                            <h3>Designing</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                </div>
                <div class="card__bx" style="--clr: #eb5ae5">
                    <div class="card__data">
                        <div class="card__icon">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <div class="card__content">
                            <h3>Develoment</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

                        </div>
                    </div>
                </div>
                <div class="card__bx" style="--clr: #5b98eb">
                    <div class="card__data">
                        <div class="card__icon">
                            <i class="fa-brands fa-searchengin"></i>
                        </div>
                        <div class="card__content">
                            <h3>SEO</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

                        </div>
                    </div>
                </div>

            </section>

        </section>
        <!-- Services End -->

        <section class="about-section">
            <h2>More About Us</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque ut ipsa ratione accusamus reiciendis corrupti corporis eius voluptates! Voluptates nemo quia animi vitae non inventore beatae vero? In, odit explicabo. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Neque esse, omnis perferendis placeat pariatur magni aperiam earum, iusto in suscipit nihil molestias quae eaque ratione voluptatum quo ex veritatis. Quis? Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam accusamus rem, possimus vitae dolorum sequi officia laboriosam! Magnam quae exercitationem non eius dicta, recusandae laudantium impedit ex excepturi porro fugit.</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque ut ipsa ratione accusamus reiciendis corrupti corporis eius voluptates! Voluptates nemo quia animi vitae non inventore beatae vero? In, odit explicabo. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Neque esse, omnis perferendis placeat pariatur magni aperiam earum, iusto in suscipit nihil molestias quae eaque ratione voluptatum quo ex veritatis. Quis? Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam accusamus rem, possimus vitae dolorum sequi officia laboriosam! Magnam quae exercitationem non eius dicta, recusandae laudantium impedit ex excepturi porro fugit.</p>
        </section>

    </section>
@endsection
