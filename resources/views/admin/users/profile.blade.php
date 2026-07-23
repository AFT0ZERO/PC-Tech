@extends('admin.layouts.admin')
@section('search')
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-6">
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom flex-column flex-sm-row">
                                <div class="d-flex flex-column align-items-center align-items-sm-start gap-3">
                                    @if(Auth::user()->image == null)
                                        <img src="https://afn.ca/wp-content/uploads/2022/12/unknown_staff-500x500.webp" alt="user image" class="d-block w-px-100 h-px-100 rounded">
                                    @else
                                        <img src="{{ asset(Auth::user()->image) }}" alt="user image" class="d-block w-px-100 h-px-100 rounded">
                                    @endif
                                    <a href="{{ route('admin.editProfile') }}">
                                        <button type="button" class="btn btn-primary btn-sm">Edit profile</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if (session('password_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('password_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <p><strong>Name : </strong> {{ Auth::user()->fname . ' ' . Auth::user()->lname }}</p>
                            <p><strong>Email : </strong> {{ Auth::user()->email }}</p>
                            <p><strong>Mobile : </strong> {{ Auth::user()->mobile }}</p>
                            <p><strong>Permissions : </strong>
                                @if(Auth::user()->role == 'admin')
                                    <span class="badge bg-label-primary me-1">Admin</span>
                                @else
                                    <span class="badge bg-label-info me-1">Super-Admin</span>
                                @endif
                            </p>
                            <p><strong>Gender : </strong>
                                @if(Auth::user()->gender == 'male')
                                    <span class="badge bg-label-info me-1">Male</span>
                                @else
                                    <span class="badge bg-label-danger me-1">Female</span>
                                @endif
                            </p>
                            <p><strong>Created At : </strong> <x-local-time :date="Auth::user()->created_at" date-only /></p>

                            <hr class="my-4">

                            <h5 class="mb-3">Change password</h5>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('admin.updatePassword') }}" method="POST" class="row g-3" style="max-width: 480px;">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label class="form-label">Current password <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
                                    @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">New password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Confirm new password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update password</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </div>

@endsection
