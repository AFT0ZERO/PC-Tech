@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('category.index') }}" class="btn btn-primary p-2 float-start">Back</a>
        </button>
    </div>
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header"><strong>Edit Category</strong></h5>

            <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">

                    <div class="form-floating form-floating-outline ">
                        <input type="text" name="name" value="{{$category->name }}" class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                        <label for="exampleFormControlInput1">Name</label>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-6">
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        <label class="form-label">Upload Image</label>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>





                    <button class="btn btn-success dlt-btn-t">Edit</button>
                </div>
            </form>
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
                        confirmButtonText: 'Yes, Edit it!'
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
