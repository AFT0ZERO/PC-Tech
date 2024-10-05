@extends('layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="fixthis" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo"></span>
                                <span class="app-brand-text demo text-heading fw-bold">Pc Tech</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Welcome to Pc Tech! 👋</h4>
                        <p class="mb-6">Please sign-in to your account and start the adventure</p>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
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
                                <label class="form-label" for="password">Password</label>
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
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end pb-3">
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
@endsection