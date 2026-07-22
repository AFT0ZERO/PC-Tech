@extends('admin.layouts.admin')
@section('search')
    <form action="{{ route('product.index') }}" method="get" class="d-flex align-items-center gap-2 w-100">
        <i class="bx bx-search bx-md flex-shrink-0"></i>
        <input type="text" name="search" value="{{ request('search') }}"
            class="form-control border-0 shadow-none ps-1 ps-sm-2 flex-grow-1" placeholder="Search..."
            style="min-width: 120px;" />
        <select name="category_id" class="form-select border-0 shadow-none flex-grow-1 flex-sm-grow-0"
            style="min-width: 160px;">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>{{ $cat->name }}
                </option>
            @endforeach
        </select>
        <select name="sort" class="form-select border-0 shadow-none flex-grow-1 flex-sm-grow-0" style="min-width: 150px;">
            <option value="name_asc" @selected(request('sort', 'name_asc') === 'name_asc')>Name A–Z</option>
            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name Z–A</option>
            <option value="created_asc" @selected(request('sort') === 'created_asc')>Date oldest</option>
            <option value="created_desc" @selected(request('sort') === 'created_desc')>Date newest</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary flex-shrink-0">Apply</button>
    </form>
@endsection
@section('content')

    <div class="demo-inline-spacing mt-5">
        <a href="{{ route('product.create') }}">
            <button type="button" class="btn btn-primary">+ Add Product</button>
        </a>
        @if(Auth::user()->role == 'super-admin')
            <a href="{{ route('product.showRestore') }}">
                <button type="button" class="btn btn-danger">Trash</button>
            </a>
            <form action="{{ route('product.rebuildDb') }}" method="post" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">Rebuild Component DB</button>
            </form>
        @endif
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Product Info ({{ $products->total() }})</h5>
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
                        <th>Category</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->created_at->format('y-m-d') }}</td>
                            <td><a href="{{ route('product.upload.images', $product->id) }}"
                                    class="btn btn-outline-warning btn-sm">Add / View</a></td>
                            <td>
                                <a class="btn btn-info p-2 btn-sm" href="{{ route('product.show', $product->id) }}">View</a>
                                <a class="btn btn-primary p-2 btn-sm" href="{{ route('product.edit', $product->id) }}">Edit</a>
                                <form style="display:inline;" method="post"
                                    action="{{ route('product.destroy', $product->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger p-2 btn-sm dlt-btn-t">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="ps-4 pb-3">
                {{ $products->links() }}
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