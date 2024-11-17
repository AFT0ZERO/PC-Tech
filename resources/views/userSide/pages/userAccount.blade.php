@extends('userSide.layout.app')
@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{route('landing')}}">Home</a></li>
                            <li>Account</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->

    <!-- account area start -->
    <div class="checkout-area mtb-50px">
        <div class="container">
            <div class="row">
                <div class="ms-auto me-auto col-lg-9">
                    <div class="checkout-wrapper">
                        <div id="faq" class="panel-group">
                            {{--edit user info start--}}
                            <div class="panel panel-default single-my-account">
                                <div class="panel-heading my-account-title">

                                    <h3 class="panel-title"><span>1 .</span> <a data-bs-toggle="collapse"
                                                                                data-bs-target="#my-account-1">Edit your
                                            account information </a></h3>
                                </div>
                                <div id="my-account-1" class="panel-collapse collapse show" data-bs-parent="#faq">
                                    <form action="{{ route('updateAccount', Auth::user()->id) }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="myaccount-info-wrapper">
                                            <div class="account-info-wrapper">
                                                <h4>My Account Information</h4>
                                                <h5>Your Personal Details</h5>
                                                @if (session('success'))
                                                    <div class="alert alert-success alert-dismissible fade show"
                                                         role="alert">
                                                        {{ session('success') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                                aria-label="Close"></button>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <img src='{{asset(Auth::user()->image)}}' alt="{{Auth::user()->fname}}"
                                                     class="img-fluid w-px-120 h-px-120 mb-5">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>First Name</label>
                                                        <input type="text" name="fname"
                                                               value="{{ old('fname', Auth::user()->fname) }}"
                                                               class="form-control @error('fname') is-invalid @enderror">
                                                        @error('fname')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Last Name</label>
                                                        <input type="text" name="lname"
                                                               value="{{ old('lname', Auth::user()->lname) }}"
                                                               class="form-control @error('lname') is-invalid @enderror">
                                                        @error('lname')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="billing-info">
                                                        <label>Email Address</label>
                                                        <input type="email" name="email"
                                                               value="{{ old('email', Auth::user()->email) }}"
                                                               class="form-control @error('email') is-invalid @enderror">
                                                        @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Phone Number</label>
                                                        <input type="text" name="mobile"
                                                               value="{{ old('mobile', Auth::user()->mobile) }}"
                                                               class="form-control @error('mobile') is-invalid @enderror">
                                                        @error('mobile')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="billing-info">
                                                        <label>Gender</label>
                                                        <select
                                                            class="form-select @error('gender') is-invalid @enderror"
                                                            name="gender" id="exampleFormControlSelect6"
                                                            aria-label="Default select example">
                                                            <option value="male"
                                                                    @if(old('gender', Auth::user()->gender) == 'male') selected @endif>
                                                                Male
                                                            </option>
                                                            <option value="female"
                                                                    @if(old('gender', Auth::user()->gender) == 'female') selected @endif>
                                                                Female
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class=" form-floating-outline mb-6">
                                                    <label >Upload Image</label>
                                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                                                    @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <button class="btn btn btn-primary dlt-btn-t mt-4">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--edit user info end--}}


                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        // Wait until the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Select all delete buttons with the class 'dlt-btn-t'
            const deleteButtons = document.querySelectorAll('.dlt-btn-t');

            deleteButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
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
    <!-- account area end -->
@endsection
