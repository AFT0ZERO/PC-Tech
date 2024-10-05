@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('contact.index') }}" class="btn btn-primary p-2 float-start">Back to List</a>
        </button>
    </div>
    <div class="d-flex">
        <!-- User Info Card -->
        <div class="card mt-4 w-50">
            <h2 class="card-header">
                <b> User Info</b>
            </h2>
            <div class="card-body">
                <p class="card-text">
                    @if($contact->user->image == null)
                        <img src="https://afn.ca/wp-content/uploads/2022/12/unknown_staff-500x500.webp" alt="user image" style="width: 150px ;height: 150px">
                    @else
                        <img src="{{asset($contact->user->image)}}" alt="user image" style="width: 150px ;height: 150px">
                    @endif
                </p>
                <h5 class="card-title"><b>Name:</b>  {{$contact->user->fname ." ".$contact->user->lname}}</h5>
                <p class="card-text"><b> Email:</b> {{$contact->user->email}}</p>
                <p class="card-text"><b> Mobile:</b> {{$contact->user->mobile}} </p>
                <p class="card-text"><b> Permissions:</b>
                    @if($contact->user->role == 'admin')
                        <span class="badge bg-label-primary me-1">Admin</span>
                    @elseif($contact->user->role =='user')
                        <span class="badge bg-label-success me-1">User</span>
                    @else
                        <span class="badge bg-label-info me-1">Super-Admin</span>
                    @endif
                </p>
                <p class="card-text"><b> Gender:</b>
                    @if($contact->user->gender =='male')
                        <span class="badge bg-label-info me-1">Male</span>
                    @else
                        <span class="badge bg-label-danger me-1">Female</span>
                    @endif
                </p>
                <p class="card-text"><b> Created At : </b> {{$contact->user->created_at->format('y-m-d')}} </p>
            </div>
        </div>

        <!-- Message Info Card -->
        <div class="card mt-4  w-50">
            <h2 class="card-header">
                <b> Message Info</b>
            </h2>
            <div class="card-body">
                <h5 class="card-title"> Name: {{$contact->name }}</h5>
                <p class="card-text"><b> Email:</b> {{$contact->email}}</p>
                <p class="card-text"><b> Mobile:</b> {{$contact->mobile}} </p>

                <p class="card-text fs-5 "><b> Message:</b>
                    {{$contact->message}}
                </p>
                <p class="card-text"><b> Created At : </b> {{$contact->created_at->format('y-m-d')}} </p>
            </div>
        </div>
    </div>






@endsection
