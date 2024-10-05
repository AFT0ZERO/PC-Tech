@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('faq.index') }}" class="btn btn-primary p-2 float-start">Back to List</a>
        </button>
    </div>
    <div class="card mt-4">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="card-header">
           <b>FAQ Info</b>
        </h2>
        <div class="card-body">
            <h5 class="card-title"><b>Question : </b>  {{$faq->question}}</h5>
            <p class="card-text "><b>Answer:</b> {{$faq->answer}}</p>
            <p class="card-text"><b>Updated At : </b> {{$faq->updated_at->format('y-m-d')}} </p>
            <p class="card-text"><b>Created At : </b> {{$faq->created_at->format('y-m-d')}} </p>
        </div>
    </div>





@endsection
