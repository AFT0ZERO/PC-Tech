@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('product.index') }}" class="btn btn-primary p-2 float-start">Back</a>
        </button>
    </div>
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header"><strong>Add Product</strong></h5>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class=" form-floating-outline ">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    <div class="form-floating-outline">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="exampleFormControlTextarea1" placeholder="Description">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class=" form-floating-outline ">
                        <label for="exampleFormControlInput3">Brand</label>
                        <input type="text" list="brandOptions" name="brand" value="{{ old('brand') }}" class="form-control @error('brand') is-invalid @enderror" id="exampleFormControlInput3" placeholder="Search or enter brand manually (e.g. Asus)">
                        <datalist id="brandOptions">
                            <option value="Asus">
                            <option value="Gigabyte">
                            <option value="MSI">
                            <option value="Corsair">
                            <option value="Nzxt">
                            <option value="Intel">
                            <option value="AMD">
                            <option value="Nvidia">
                            <option value="Kingston">
                            <option value="Samsung">
                            <option value="Crucial">
                            <option value="Evga">
                            <option value="Cooler Master">
                        </datalist>
                        @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <select name="category" class="form-select" aria-label="Default select example" id="exampleFormControlSelect1">
                        @foreach ($categories as $category )
                            <option value={{$category->id}}>{{$category->name}}</option>
                        @endforeach
                    </select>
                        <div id="key-value-fields" class="row">
                            <div class="mb-3 col-6">
                                <label for="key" class="form-label">Key</label>
                                <input type="text" name="key[]" class="form-control" >
                            </div>

                            <div class="mb-3 col-6">
                                <label for="value" class="form-label">Value</label>
                                <input type="text" name="value[]" class="form-control" >
                            </div>
                        </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" id="add-key-value" class="btn btn-secondary">Add More</button>
                        <button type="button" id="autofill-specs" class="btn btn-primary d-flex align-items-center gap-2">
                            Autofill Specs
                        </button>
                        <span id="autofill-status" class="text-muted ms-2" style="display: none;">Loading...</span>
                    </div>
                    <br><br>
                    @foreach($stores as $store)
                        <div class="col-2">
                            <p class="fs-5 fw-bold">{{ $store->name }}</p>
                        </div>
                        <div class="row">
                            <input type="hidden" name="store_id[]" value="{{$store->id}}"> <!-- Hidden input for store ID -->
                            <div class="mb-3 col-4">
                                <label for="price-{{$store->id}}" class="form-label">Price</label>
                                <input type="text" name="price[]" class="form-control" id="price-{{$store->id}}" required>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="url-{{$store->id}}" class="form-label">Url</label>
                                <input type="text" name="url[]" class="form-control" id="url-{{$store->id}}" required>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="status" class="form-label">Status</label>
                                <select name="status[]" class="form-select" >
                                    <option value="in stock">In Stock</option>
                                    <option value="out of stock">Out of Stock</option>
                                    <option value="not found">Not Found</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                    <button class="btn btn-success">ADD +</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to dynamically add more key-value fields
        document.getElementById('add-key-value').addEventListener('click', function() {
            const newField = document.createElement('div');
            newField.classList.add('row');
            newField.innerHTML = `
                <div class="mb-3 col-6">
                    <label for="key" class="form-label">Key</label>
                    <input type="text" name="key[]" class="form-control" required>
                </div>
                <div class="mb-3 col-6">
                    <label for="value" class="form-label">Value</label>
                    <input type="text" name="value[]" class="form-control" required>
                </div>
            `;
            document.getElementById('key-value-fields').appendChild(newField);
        });

        document.getElementById('autofill-specs').addEventListener('click', function() {
            const partName = document.getElementById('exampleFormControlInput1').value;
            if (!partName) {
                alert('Please enter a Product Name first to autofill specs.');
                return;
            }

            const statusEl = document.getElementById('autofill-status');
            statusEl.style.display = 'inline-block';
            statusEl.innerText = 'Loading specs from openDB...';

            fetch('{{ route('product.fetchSpecs') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ query: partName })
            })
            .then(response => response.json())
            .then(data => {
                statusEl.style.display = 'none';
                if (data.error) {
                    alert('Error: ' + data.error);
                } else if (data.specs) {
                    const container = document.getElementById('key-value-fields');
                    container.innerHTML = '';
                    
                    for (const [key, value] of Object.entries(data.specs)) {
                        const newField = document.createElement('div');
                        newField.classList.add('row');
                        newField.innerHTML = `
                            <div class="mb-3 col-6">
                                <label for="key" class="form-label">Key</label>
                                <input type="text" name="key[]" class="form-control" value="${key}" required>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="value" class="form-label">Value</label>
                                <input type="text" name="value[]" class="form-control" value="${value}" required>
                            </div>
                        `;
                        container.appendChild(newField);
                    }
                }
            })
            .catch(error => {
                statusEl.style.display = 'none';
                alert('Failed to fetch specs: ' + error);
            });
        });
    </script>
@endsection
