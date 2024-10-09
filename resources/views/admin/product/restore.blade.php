@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')

    <div class="demo-inline-spacing mt-5">
        <a href="{{route('product.index')}}">
            <button type="button" class="btn btn-primary "> Back</button>
        </a>
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Product Info ({{$products->count()}})</h5>
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach( $products as $product)
                    <tr>
                        <td>{{$loop->iteration}}</td>

                        <td>{{ $product->name}}</td>
                        <td>{{$product->created_at->format('y-m-d')}}</td>
                        <td><span class="badge bg-label-danger me-1">{{$product->deleted_at->format('y-m-d')}}</span></td>

                        <td >
                            <a class="btn btn-danger p-2 btn-sm" href="{{route('product.restore',$product->id)}}">Restore </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="ps-4">
                {{$products->links()}}
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
