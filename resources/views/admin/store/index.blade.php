@extends('admin.layouts.admin')
@section('search')
    <form action="{{ route('store.index') }}" method="get" class="d-flex align-items-center gap-2">
        <i class="bx bx-search bx-md"></i>
        <input type="text" name="search" value="{{ request('search') }}"
            class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
            style="min-width: 140px; max-width: 220px;" />
        <button type="submit" class="btn btn-sm btn-primary">Search</button>
    </form>
@endsection
@section('content')

    <div id="store-index-config" class="d-none" data-update-base="{{ e(url('/dashboard/stores')) }}"></div>

    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStoreModal">
            + Add Store
        </button>
        @if(Auth::user()->role == 'super-admin')
            <a href="{{ route('store.showRestore') }}">
                <button type="button" class="btn btn-danger">Trash</button>
            </a>
        @endif
    </div>

    <div class="card mt-10">
        <h5 class="card-header fw-bold">Stores ({{ $stores->total() }})</h5>
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
                    @foreach($stores as $store)
                        <tr>
                            <td>{{ $loop->iteration + ($stores->currentPage() - 1) * $stores->perPage() }}</td>
                            <td>
                                @if($store->image != null)
                                    <img alt="store image" style="width:90px" src="{{ asset($store->image) }}">
                                @endif
                            </td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->created_at->format('y-m-d') }}</td>
                            <td>
                                <button type="button" class="btn btn-primary p-2 btn-sm btn-edit-store" data-bs-toggle="modal"
                                    data-bs-target="#editStoreModal" data-id="{{ $store->id }}"
                                    data-name="{{ e($store->name) }}"
                                    data-image="{{ $store->image ? asset($store->image) : '' }}">
                                    Edit
                                </button>
                                <form style="display:inline;" method="post" action="{{ route('store.destroy', $store->id) }}">
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
                {{ $stores->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStoreModalLabel">Add Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">Image (optional)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/jpg">
                            @error('image')
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

    <div class="modal fade" id="editStoreModal" tabindex="-1" aria-labelledby="editStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStoreModalLabel">Edit Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStoreForm" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current image</label>
                            <div>
                                <img id="editStoreImagePreview" src="" alt="" class="rounded border"
                                    style="max-width: 120px; max-height: 120px; object-fit: cover; display: none;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="editStoreName"
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
            const cfg = document.getElementById('store-index-config');
            const updateBase = cfg ? cfg.dataset.updateBase : '';

            const editModal = document.getElementById('editStoreModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    if (!btn || !btn.classList.contains('btn-edit-store')) return;
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '';
                    const img = btn.getAttribute('data-image') || '';
                    document.getElementById('editStoreForm').action = updateBase + '/' + id;
                    document.getElementById('editStoreName').value = name;
                    const preview = document.getElementById('editStoreImagePreview');
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