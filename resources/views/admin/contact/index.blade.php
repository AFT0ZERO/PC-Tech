@extends('admin.layouts.admin')
@section('search')
 <i class="bx bx-search bx-md"></i>
    <form action="{{route('user.index')}}" method="get">
    <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
           aria-label="Search..." name="search" style="display: inline"/>
    </form>
@endsection
@section('content')

    <div class="demo-inline-spacing mt-5">
        <a href="#">
            <button type="button" class="btn btn-lg btn-primary ">+ Add User </button>
        </a>
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Users Info ({{$users->count()}})</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach( $users as $user)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$user->fname . " ". $user->lname}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->mobile}}</td>
                    <td>
                        @if($user->role =='0')
                            <span class="badge bg-label-primary me-1">Admin</span>
                        @elseif($user->role =='1')
                            <span class="badge bg-label-success me-1">User</span>
                        @else
                            <span class="badge bg-label-info me-1">Super-Admin</span>
                        @endif
                    </td>
                    <td>{{$user->created_at->format('y-m-d')}}</td>
                    <td >
                        <a class="btn btn-info p-2 btn-sm" href="#">View </a>
                        <a class="btn btn-primary p-2 btn-sm " href="#">Edit</a>
                        <form style="display:inline;" method="post" action="#">
                            @csrf
                            @method('delete')
                            <button type="submit"  class="btn btn-danger p-2 btn-sm dlt-btn-t">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
