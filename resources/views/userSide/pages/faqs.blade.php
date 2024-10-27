@extends('userSide.layout.app')

@section('title', 'FAQs')
@section('content')
    <div class="fcontainer col-12">
        <h2>Frequently Asked Questions</h2>
        <div class="accordion">
            @foreach($faqs as $faq)
            <div class="accordion-item">
                <button id="accordion-button-1" aria-expanded="false"><span class="accordion-title">{{$faq->question}}</span><span class="icon" aria-hidden="true"></span></button>
                <div class="accordion-content">
                    <p>{{$faq->answer}}.</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
