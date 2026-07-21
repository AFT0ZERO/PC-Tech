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
                <!-- Register -->
                <div class="card px-sm-6 px-0 w-px-400 ">
                    <div class="card-body d-flex flex-column ">
                        <!-- Logo -->
                        <div class="app-brand  mb-3 ">
                            <a href="{{route('landing')}}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo"></span>
                                <span class="app-brand-text demo text-heading fw-bold">Pc Tech</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Welcome to Pc Tech! </h4>
                        <p class="mb-6">Please sign-in to your account and start the adventure</p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    placeholder="Enter your email "
                                    autofocus />
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        placeholder="*********"
                                        aria-describedby="password"
                                        required
                                        autocomplete="current-password"
                                        autofocus />
                                    <span class="input-group-text cursor-pointer toggle-password" data-target="password"><i class="bx bx-hide"></i></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-12 text-end pb-3">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12 ">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <p class="text-center">
                            <span>New on our platform?</span>
                            <a href={{route('register')}}>
                                <span>Create an account</span>
                            </a>
                        </p>
                    </div>
                </div>
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
