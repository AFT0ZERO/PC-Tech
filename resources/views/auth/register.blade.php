@extends('layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-6">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-heading fw-bold">Pc Tech</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Adventure starts here 🚀</h4>
                        <p class="mb-6">Create your computer in easy and fun! way</p>

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <div class="row">
                                <div class=" col-6">
                                    <label for="exampleFormControlInput1">First Name</label>
                                    <input type="text" name="fname" value="{{ old('fname') }}" class="form-control @error('fname') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                                    @error('fname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class=" col-6">
                                    <label for="exampleFormControlInput2">Last Name</label>
                                    <input type="text" name="lname" value="{{ old('lname') }}" class="form-control @error('lname') is-invalid @enderror" id="exampleFormControlInput2" placeholder="Family">
                                    @error('lname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="">
                                <label for="exampleFormControlInput3">Email address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="exampleFormControlInput3" placeholder="name@example.com">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="row">
                                <div class="col-6">
                                    <label for="exampleFormControlInput4">Phone Number</label>
                                    <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" id="exampleFormControlInput4" placeholder="079">
                                    @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class=" col-6">
                                    <label for="exampleFormControlSelect6">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="exampleFormControlSelect6">
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class=" ">
                                <label for="exampleFormControlInput7">Password</label>
                                <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" id="exampleFormControlInput7" placeholder="********">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12 ">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Sign up') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href={{route('login')}}>
                                <span>Sign in instead</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>

@endsection
