@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-6">
                        <!-- Account -->
                        <div class="card-body">
                        <div class="demo-inline-spacing mb-5">
                            <a href={{route('admin.editProfile')}}>
                                <button type="button" class="btn  btn-primary ">Edit</button>
                            </a>
                        </div>
                            <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                @if(Auth::user()->image == null)
                                    <img src="https://afn.ca/wp-content/uploads/2022/12/unknown_staff-500x500.webp" alt="user image"  class="d-block w-px-100 h-px-100 rounded">
                                @else
                                    <img src="{{asset(Auth::user()->image)}}" alt="user image"  class="d-block w-px-100 h-px-100 rounded">
                                @endif

                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <p><strong>Name : </strong> {{Auth::user()->fname . " " . Auth::user()->lname}}</p>
                            <p><strong>Email : </strong> {{Auth::user()->email }}</p>
                            <p><strong>Mobile : </strong> {{Auth::user()->mobile }}</p>
                            <p><strong>Permissions : </strong>
                                @if(Auth::user()->role == 'admin')
                                    <span class="badge bg-label-primary me-1">Admin</span>
                                @else
                                    <span class="badge bg-label-info me-1">Super-Admin</span>
                                @endif
                            </p>
                            <p><strong>Gender : </strong>
                                @if(Auth::user()->gender =='male')
                                    <span class="badge bg-label-info me-1">Male</span>
                                @else
                                    <span class="badge bg-label-danger me-1">Female</span>
                                @endif
                            </p>
                            <p><strong>Created At : </strong> {{Auth::user()->created_at->format('y-m-d') }}</p>
                        </div>
                        <!-- /Account -->
                    </div>

                </div>
            </div>
        </div>
        <!-- / Content -->



        <div class="content-backdrop fade"></div>
    </div>

@endsection
