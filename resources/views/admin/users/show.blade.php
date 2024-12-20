@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('users.index') }}" class="btn btn-primary p-2 float-start">Back to List</a>
        </button>
    </div>
    <div class="card mt-4">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="card-header">
           <b> User Info</b>
        </h2>
        <div class="card-body">
            <p class="card-text">
                    <img src="{{asset($user->image)}}" alt="user image" style="width: 150px ;height: 150px">
            </p>
            <h5 class="card-title">User Name: {{$user->fname ." ". $user->lname}}</h5>
            <p class="card-text "><b>User Email:</b> {{$user->email}}</p>
            <p class="card-text"><b>User Mobile:</b> {{$user->mobile}} </p>
            <p class="card-text"><b>User Permissions:</b>
                @if($user->role == 'admin')
                    <span class="badge bg-label-primary me-1">Admin</span>
                @elseif($user->role =='user')
                    <span class="badge bg-label-success me-1">User</span>
                @else
                    <span class="badge bg-label-info me-1">Super-Admin</span>
                @endif
            </p>
            <p class="card-text"><b>User Gender:</b>
                @if($user->gender =='male')
                    <span class="badge bg-label-info me-1">Male</span>
                @else
                    <span class="badge bg-label-danger me-1">Female</span>
                @endif
            </p>
            <p class="card-text"><b>User Created At : </b> {{$user->created_at->format('y-m-d')}} </p>


        </div>
    </div>





@endsection
