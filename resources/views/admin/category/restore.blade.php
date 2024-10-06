@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')

    <div class="demo-inline-spacing mt-5">
        <a href="{{route('category.index')}}">
            <button type="button" class="btn btn-primary "> Back</button>
        </a>
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Category Info ({{$categories->count()}})</h5>
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
                    <th>Image</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach( $categories as $category)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @if($category->image != null)
                                <img  alt="category image" style="width: 100px" src={{asset($category->image)}} >
                            @endif
                        </td>
                        <td>{{ $category->name}}</td>
                        <td>{{$category->created_at->format('y-m-d')}}</td>
                        <td><span class="badge bg-label-danger me-1">{{$category->deleted_at->format('y-m-d')}}</span></td>

                        <td >
                            <a class="btn btn-danger p-2 btn-sm" href="{{route('category.restore',$category->id)}}">Restore </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="ps-4">
                {{$categories->links()}}
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
