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
            <h5 class="card-header"><strong>Add User</strong></h5>

            <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="row">
                        <div class="form-floating form-floating-outline col-6">
                            <input type="text" name="fname" value="{{ old('fname') }}" class="form-control @error('fname') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            <label for="exampleFormControlInput1">First Name</label>
                            @error('fname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline col-6">
                            <input type="text" name="lname" value="{{ old('lname') }}" class="form-control @error('lname') is-invalid @enderror" id="exampleFormControlInput2" placeholder="Family">
                            <label for="exampleFormControlInput2">Last Name</label>
                            @error('lname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-floating form-floating-outline">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="exampleFormControlInput3" placeholder="name@example.com">
                        <label for="exampleFormControlInput3">Email address</label>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating form-floating-outline mb-6">
                        <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" id="exampleFormControlInput4" placeholder="079">
                        <label for="exampleFormControlInput4">Phone Number</label>
                        @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="form-floating form-floating-outline mb-6 col-6">
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="exampleFormControlSelect5">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <label for="exampleFormControlSelect5">User Permissions</label>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-6 col-6">
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="exampleFormControlSelect6">
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <label for="exampleFormControlSelect6">Gender</label>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-floating form-floating-outline mb-6">
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" id="exampleFormControlInput7" placeholder="********">
                        <label for="exampleFormControlInput7">Password</label>
                        @error('password')
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

                    <button class="btn btn-success">ADD +</button>
                </div>
            </form>
        </div>
    </div>
@endsection
