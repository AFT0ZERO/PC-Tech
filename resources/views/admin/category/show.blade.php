@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('category.index') }}" class="btn btn-primary p-2 float-start">Back</a>
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
           <b>Category Info</b>
        </h2>
        <div class="card-body">
            <p class="card-text">
                @if($category->image != null)
                    <img src="{{asset($category->image)}}" alt="category image" style="width: 200px ;">
                @endif
            </p>
            <h5 class="card-title"><b>Name : </b>  {{$category->name}}</h5>
            <p class="card-text"><b>Updated At : </b> {{$category->updated_at->format('y-m-d')}} </p>
            <p class="card-text"><b>Created At : </b> {{$category->created_at->format('y-m-d')}} </p>
        </div>
    </div>





@endsection
