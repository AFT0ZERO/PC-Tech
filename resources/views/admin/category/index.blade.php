@extends('admin.layouts.admin')
@section('search')
    <form action="{{ route('category.index') }}" method="get" class="d-flex align-items-center gap-2">
        <i class="bx bx-search bx-md"></i>
        <input type="text" name="search" value="{{ request('search') }}"
            class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
            style="min-width: 140px; max-width: 220px;" />
        <button type="submit" class="btn btn-sm btn-primary">Search</button>
    </form>
@endsection
@section('content')

    <div id="category-index-config" class="d-none" data-update-base="{{ e(url('/dashboard/categories')) }}"></div>

    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            + Add Category
        </button>
        @if(Auth::user()->role == 'super-admin')
            <a href="{{ route('category.showRestore') }}">
                <button type="button" class="btn btn-danger">Trash</button>
            </a>
        @endif
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Categories ({{ $categories->total() }})</h5>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible m-3" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
                        <th>Image</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td>
                                @if($category->image != null)
                                    <img alt="category image" style="width: 100px" src="{{ asset($category->image) }}">
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->created_at->format('y-m-d') }}</td>
                            <td>
                                <button type="button" class="btn btn-primary p-2 btn-sm btn-edit-category"
                                    data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="{{ $category->id }}"
                                    data-name="{{ e($category->name) }}"
                                    data-image="{{ $category->image ? asset($category->image) : '' }}"
                                    data-specs-table="{{ e($category->specs_table) }}"
                                    data-open-db-name="{{ e($category->open_db_name) }}">
                                    Edit
                                </button>
                                <form style="display:inline;" method="post"
                                    action="{{ route('category.destroy', $category->id) }}">
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
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Specs Table</label>
                            <input type="text" name="specs_table" value="{{ old('specs_table') }}"
                                class="form-control @error('specs_table') is-invalid @enderror">
                            @error('specs_table')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Open DB Name</label>
                            <input type="text" name="open_db_name" value="{{ old('open_db_name') }}"
                                class="form-control @error('open_db_name') is-invalid @enderror">
                            @error('open_db_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current image</label>
                            <div>
                                <img id="editCategoryImagePreview" src="" alt="" class="rounded border"
                                    style="max-width: 120px; max-height: 120px; object-fit: cover; display: none;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="editCategoryName"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New image (optional)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Specs Table</label>
                            <input type="text" name="specs_table" id="editSpecsTable"
                                class="form-control @error('specs_table') is-invalid @enderror">
                            @error('specs_table')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Open DB Name</label>
                            <input type="text" name="open_db_name" id="editOpenDbName"
                                class="form-control @error('open_db_name') is-invalid @enderror">
                            @error('open_db_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cfg = document.getElementById('category-index-config');
            const updateBase = cfg ? cfg.dataset.updateBase : '';

            const editModal = document.getElementById('editCategoryModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    if (!btn || !btn.classList.contains('btn-edit-category')) return;
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '';
                    const img = btn.getAttribute('data-image') || '';
                    const specsTable = btn.getAttribute('data-specs-table') || '';
                    const openDbName = btn.getAttribute('data-open-db-name') || '';
                    document.getElementById('editCategoryForm').action = updateBase + '/' + id;
                    document.getElementById('editCategoryName').value = name;
                    document.getElementById('editSpecsTable').value = specsTable;
                    document.getElementById('editOpenDbName').value = openDbName;
                    const preview = document.getElementById('editCategoryImagePreview');
                    if (img) {
                        preview.src = img;
                        preview.style.display = 'inline-block';
                    } else {
                        preview.removeAttribute('src');
                        preview.style.display = 'none';
                    }
                });
            }

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