@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Upload Product Images
                            <a href="{{ route('product.index') }}" class="btn btn-primary float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success">{{session('status')}}</div>
                        @endif

                        <h5>Product Name: {{ $product->name }}</h5>
                        <hr>

                        @if ($errors->any())
                            <ul class="alert alert-warning">
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form action="{{ route('product.store.images',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label>Upload Images (Max:10 images only)</label>
                                <input type="file" name="images[]" multiple class="form-control" />
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-4">
                @foreach ($ProductImages as $ProductImage)
                    <div>
                        <img src="{{ asset($ProductImage->image) }}" class="border p-2 m-3" style="width: 150px; height: 150px;" alt="Img" />
                        <form action="{{ route('product.destroy.images',$ProductImage->id , )}}" method="post" style="display: inline">
                            @csrf
                            @method('delete')
                            {{--                    <a href="{{ route('car.destroy.images',$car->id)}}" class="dlt-btn-t" >Delete</a>--}}
                            <input type="hidden" name="product_id" value="{{$product->id}}">

                            <button type="submit" class="dlt-btn-t btn btn-danger">Delete</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        // Wait until the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Select all delete buttons with the class 'dlt-btn-t'
            const deleteButtons = document.querySelectorAll('.dlt-btn-t');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the form from submitting
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form if the user confirms
                            button.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
