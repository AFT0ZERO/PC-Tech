@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('user.index') }}" class="btn btn-primary p-2 float-start">Back</a>
        </button>
    </div>
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header"><strong>Edit User</strong></h5>

            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="row">
                        <!-- First Name -->
                        <div class="form-floating form-floating-outline col-6">
                            <input type="text" name="fname" value="{{ old('fname', $user->fname) }}" class="form-control @error('fname') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            <label for="exampleFormControlInput1">First Name</label>
                            @error('fname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="form-floating form-floating-outline col-6">
                            <input type="text" name="lname" value="{{ old('lname', $user->lname) }}" class="form-control @error('lname') is-invalid @enderror" id="exampleFormControlInput2" placeholder="Family">
                            <label for="exampleFormControlInput2">Last Name</label>
                            @error('lname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-floating form-floating-outline">
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" id="exampleFormControlInput3" placeholder="name@example.com">
                        <label for="exampleFormControlInput3">Email address</label>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="form-floating form-floating-outline mb-6">
                        <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="form-control @error('mobile') is-invalid @enderror" id="exampleFormControlInput4" placeholder="079">
                        <label for="exampleFormControlInput4">Phone Number</label>
                        @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Role -->
                        <div class="form-floating form-floating-outline mb-6 col-6">
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="exampleFormControlSelect5" aria-label="Default select example" @if($user->role == 'super-admin') disabled @endif>
                                <option value="user" @if(old('role', $user->role) == 'user') selected @endif>User</option>
                                <option value="admin" @if(old('role', $user->role) == 'admin') selected @endif>Admin</option>
                            </select>
                            <label for="exampleFormControlSelect5">User Permissions</label>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="form-floating form-floating-outline mb-6 col-6">
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="exampleFormControlSelect6" aria-label="Default select example">
                                <option value="male" @if(old('gender', $user->gender) == 'male') selected @endif>Male</option>
                                <option value="female" @if(old('gender', $user->gender) == 'female') selected @endif>Female</option>
                            </select>
                            <label for="exampleFormControlSelect6">Gender</label>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image -->
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
