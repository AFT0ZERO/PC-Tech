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
            <h5 class="card-header"><strong>Edit Product</strong></h5>

            <form action="{{ route('product.update' , $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="form-floating form-floating-outline ">
                        <input type="text" name="name" value="{{$product->name}}" class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                        <label for="exampleFormControlInput1">Name</label>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating-outline">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="exampleFormControlTextarea1" placeholder="Description">{{$product->smallDescription }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class=" form-floating-outline ">
                        <label for="exampleFormControlInput3">Brand</label>
                        <input type="text" list="brandOptions" name="brand" value="{{ $product->brand }}" class="form-control @error('brand') is-invalid @enderror" id="exampleFormControlInput3" placeholder="Search or enter brand manually (e.g. Asus)">
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
                            <option value={{$category->id}} @if($category->id == $product->category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                    <div id="key-value-container">
                        @foreach($descriptions as $key => $value)
                            <div class="row key-value-fields">
                                <div class="mb-3 col-6">
                                    <label for="key" class="form-label">Specification</label>
                                    <input type="text" name="key[]" value="{{$key}}" class="form-control" required>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="value" class="form-label">Value</label>
                                    <input type="text" name="value[]" value="{{$value}}" class="form-control" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-key-value" class="btn btn-secondary">Add More</button>
                    <br><br>
                    <h6 class="fw-bold mb-3">Existing Stores</h6>
                    @foreach($stores as $store)
                        @if($store->products->count() > 0)
                        <div class="store-section mb-4 p-3 border rounded">
                            <div class="col-12">
                                <p class="fs-5 fw-bold mb-2">{{$store->name}}</p>
                            </div>

                            <div id="store-entries-{{$store->id}}">
                                @foreach($store->products as $index => $storeProduct)
                                    <div class="row mb-2">
                                        <input type="hidden" name="store_id[]" value="{{$store->id}}">
                                        <input type="hidden" name="product_id[{{$store->id}}][]" value="{{$storeProduct->id}}">

                                        <div class="mb-3 col-4">
                                            <label for="price-{{$store->id}}-{{$index}}" class="form-label">Price</label>
                                            <input type="number" step="0.01" name="price[{{$store->id}}][]" value="{{$storeProduct->pivot->product_price}}" class="form-control" id="price-{{$store->id}}-{{$index}}" required>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label for="url-{{$store->id}}-{{$index}}" class="form-label">URL</label>
                                            <input type="text" name="url[{{$store->id}}][]" value="{{$storeProduct->pivot->product_url}}" class="form-control" id="url-{{$store->id}}-{{$index}}" required>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label for="status-{{$store->id}}-{{$index}}" class="form-label">Status</label>
                                            <select name="status[{{$store->id}}][]" class="form-select" id="status-{{$store->id}}-{{$index}}">
                                                <option value="in stock" @if($storeProduct->pivot->product_status == 'in stock') selected @endif>In Stock</option>
                                                <option value="out of stock" @if($storeProduct->pivot->product_status == 'out of stock') selected @endif>Out of Stock</option>
                                                <option value="not found" @if($storeProduct->pivot->product_status == 'not found') selected @endif>Not Found</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        @endif
                    @endforeach

                    {{-- ── Add New Store section ──────────────────────────────── --}}
                    @php
                        $attachedStoreIds = $stores->filter(fn($s) => $s->products->count() > 0)->pluck('id')->toArray();
                        $availableStores  = $stores->filter(fn($s) => $s->products->count() === 0);
                    @endphp
                    @if($availableStores->count() > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Add New Stores</h6>
                        <div id="new-store-entries">
                            {{-- Dynamically added rows will appear here --}}
                        </div>
                        <button type="button" id="add-new-store-btn" class="btn btn-outline-primary btn-sm mt-2">
                            + Add Store
                        </button>
                    </div>

                    {{-- Hidden template row --}}
                    <template id="new-store-template">
                        <div class="new-store-row p-3 border rounded mb-3" style="background:#f9fafb;">
                            <div class="row align-items-end">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-semibold">Store</label>
                                    <select name="new_store_id[]" class="form-select new-store-select" required>
                                        <option value="">-- Select Store --</option>
                                        @foreach($availableStores as $avStore)
                                            <option value="{{$avStore->id}}">{{$avStore->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" name="new_price[]" class="form-control" placeholder="e.g. 199.99" required>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">URL</label>
                                    <input type="text" name="new_url[]" class="form-control" placeholder="https://..." required>
                                </div>
                                <div class="mb-3 col-md-1">
                                    <label class="form-label">Status</label>
                                    <select name="new_status[]" class="form-select">
                                        <option value="in stock">In Stock</option>
                                        <option value="out of stock">Out of Stock</option>
                                        <option value="not found">Not Found</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-new-store-btn w-100">Remove</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    @endif

                    <button class="btn btn-success mt-3">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to dynamically add more key-value fields
        document.getElementById('add-key-value').addEventListener('click', function() {
            const newField = document.createElement('div');
            newField.classList.add('row', 'key-value-fields');
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
            document.getElementById('key-value-container').appendChild(newField);
        });

        // Add new store row
        const addNewStoreBtn = document.getElementById('add-new-store-btn');
        if (addNewStoreBtn) {
            addNewStoreBtn.addEventListener('click', function () {
                const template = document.getElementById('new-store-template');
                const clone = template.content.cloneNode(true);
                document.getElementById('new-store-entries').appendChild(clone);
            });

            // Remove store row (delegated)
            document.getElementById('new-store-entries').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-new-store-btn')) {
                    e.target.closest('.new-store-row').remove();
                }
            });
        }
    </script>
@endsection
