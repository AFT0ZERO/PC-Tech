@extends('admin.layouts.admin')
@section('search')
    <i class="bx bx-search bx-md"></i>
    <form action="{{route('store.index')}}" method="get">
        <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
               aria-label="Search..." name="search" style="display: inline"/>
    </form>
@endsection
@section('content')

    <div class="demo-inline-spacing mt-5">
        <a href="{{route('store.create')}}">
            <button type="button" class="btn btn-primary ">+ Add Store </button>
        </a>
        @if(Auth::user()->role == 'super-admin')
            <a href="{{route('store.showRestore')}}">
                <button type="button" class="btn  btn-danger ">Trash </button>
            </a>
        @endif
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Store Info ({{$stores->count()}})</h5>
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
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach( $stores as $store)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        @if($store->image != null)
                        <img  alt="store image" style="width:90px" src={{asset($store->image)}} >

                        @endif
                    </td>
                    <td>{{ $store->name}}</td>
                    <td>{{$store->created_at->format('y-m-d')}}</td>
                    <td >
                        <a class="btn btn-info p-2 btn-sm" href="{{route('store.show',$store->id)}}">View </a>
                        <a class="btn btn-primary p-2 btn-sm " href="{{route('store.edit' , $store->id)}}">Edit</a>
                        <form style="display:inline;" method="post" action="{{route('store.destroy', $store->id)}}">
                            @csrf
                            @method('delete')
                            <button type="submit"  class="btn btn-danger p-2 btn-sm dlt-btn-t">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="ps-4">
            {{$stores->links()}}
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
