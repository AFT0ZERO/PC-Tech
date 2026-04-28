@extends('auth.layouts.guest')

@section('extraHeader')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .card:hover {
            transform: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-xxl min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container-p-y w-100">
            <div class="d-flex justify-content-center">
                <!-- Register Card -->
                <div class="card px-sm-6 px-0 w-px-400">
                    <div class="card-body d-flex flex-column ">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{route('landing')}}" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-heading fw-bold">Pc Tech</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Adventure starts here 🚀</h4>
                        <p class="mb-6">Create your computer in easy and fun! way</p>

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class=" demo-vertical-spacing demo-only-element">
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
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="registerPassword" placeholder="********">
                                    <span class="input-group-text cursor-pointer toggle-password" data-target="registerPassword"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class=" ">
                                <label for="registerPasswordConfirmation">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password_confirmation" class="form-control" id="registerPasswordConfirmation" placeholder="********">
                                    <span class="input-group-text cursor-pointer toggle-password" data-target="registerPasswordConfirmation"><i class="bx bx-hide"></i></span>
                                </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-password').forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const icon = this.querySelector('i');
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    if (icon) {
                        icon.classList.toggle('bx-hide', !isPassword);
                        icon.classList.toggle('bx-show', isPassword);
                    }
                });
            });
        });
    </script>

@endsection
