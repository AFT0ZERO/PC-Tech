@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('product.index') }}" class="btn btn-primary p-2 float-start">Back</a>
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
           <b>Product Info</b>
        </h2>
        <div class="card-body">
            <h5 class="card-title"><b>Name : </b>  {{$product->name}}</h5>
            @foreach($product->stores as $store)
            <h5 class="card-title"><b> {{$store->name}} Price : </b>  {{$store->pivot->product_price}}</h5>
            @endforeach
            @if($descriptions != null)
            @foreach($descriptions as $key => $value)
                <p class="card-text"><b>{{$key}} : </b> {{$value}}</p>
            @endforeach
            @endif
            <p class="card-text"><b>Updated At : </b> {{$product->updated_at->format('y-m-d')}} </p>
            <p class="card-text"><b>Created At : </b> {{$product->created_at->format('y-m-d')}} </p>
{{--            <p class="card-text">--}}
{{--                @if($store->image != null)--}}
{{--                    <img src="{{asset($store->image)}}" alt="category image" style="width: 200px ;">--}}
{{--                @endif--}}
{{--            </p>--}}
        </div>
    </div>
@endsection
