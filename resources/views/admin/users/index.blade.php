@extends('admin.layouts.admin')
@section('search')
    <form action="{{ route('users.index') }}" method="get" class="d-flex align-items-center gap-2">
        <i class="bx bx-search bx-md"></i>
        <input type="text" name="search" value="{{ request('search') }}"
            class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search name, email..."
            style="min-width: 140px; max-width: 220px;" />
        <select name="role" class="form-select border-0 shadow-none" style="max-width: 170px;">
            <option value="">All roles</option>
            <option value="user" @selected(request('role') === 'user')>User</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
            <option value="super-admin" @selected(request('role') === 'super-admin')>Super-Admin</option>
        </select>
        <select name="sort" class="form-select border-0 shadow-none" style="max-width: 190px;">
            <option value="created_desc" @selected(request('sort', 'created_desc') === 'created_desc')>Newest first</option>
            <option value="created_asc" @selected(request('sort') === 'created_asc')>Oldest first</option>
            <option value="name_asc" @selected(request('sort') === 'name_asc')>Name A–Z</option>
            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name Z–A</option>
            <option value="role_asc" @selected(request('sort') === 'role_asc')>Role A–Z</option>
            <option value="role_desc" @selected(request('sort') === 'role_desc')>Role Z–A</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
    </form>
@endsection
@section('content')

    <div class="demo-inline-spacing mt-5">
        <a href="{{ route('users.create') }}">
            <button type="button" class="btn btn-primary">+ Add Admin</button>
        </a>
        @if(Auth::user()->role == 'super-admin')
            <a href="{{ route('users.showRestore') }}">
                <button type="button" class="btn btn-danger">Trash</button>
            </a>
        @endif
    </div>


    <div class="card mt-10">
        <h5 class="card-header fw-bold">Users ({{ $users->total() }})</h5>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>{{ $user->fname . ' ' . $user->lname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge bg-label-primary me-1">Admin</span>
                                @elseif($user->role == 'user')
                                    <span class="badge bg-label-success me-1">User</span>
                                @else
                                    <span class="badge bg-label-info me-1">Super-Admin</span>
                                @endif
                            </td>
                            <td><x-local-time :date="$user->created_at" date-only /></td>
                            @if($user->role != 'super-admin')
                                <td>
                                    <a class="btn btn-info p-2 btn-sm" href="{{ route('users.show', $user->id) }}">View</a>
                                    <a class="btn btn-primary p-2 btn-sm" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    <form style="display:inline;" method="post" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger p-2 btn-sm dlt-btn-t">Delete</button>
                                    </form>
                                </td>
                            @else
                                <td><span class="text-muted">—</span></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="ps-4 pb-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dlt-btn-t').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            button.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection