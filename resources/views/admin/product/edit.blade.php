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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('product.update' , $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body demo-vertical-spacing demo-only-element">

                    <div class="form-floating-outline">
                        <label for="field-category">Category <span class="text-danger">*</span></label>
                        <select name="category" id="field-category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="fields-loading" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading form fields...</p>
                    </div>

                    <div id="dynamic-fields" style="display: none;">
                        <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Product Details</h6>
                        <div id="product-fields-container"></div>
                        <div id="spec-fields-container" style="display: none;">
                            <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Specifications</h6>
                            <div id="spec-fields-inner"></div>
                        </div>
                        <div id="key-value-section" class="mt-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Description (Key : Value)</h6>
                            <div id="key-value-fields" class="row"></div>
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" id="add-key-value" class="btn btn-secondary">Add More</button>
                                <button type="button" id="clear-all-fields" class="btn btn-outline-danger">Clear All</button>
                            </div>
                        </div>
                    </div>

                    <div id="store-section">
                        <br>
                        <h6 class="fw-bold mb-3">Existing Stores</h6>
                        @foreach($stores as $store)
                            @php $storeEntry = $product->stores->find($store->id) @endphp
                            @if($storeEntry)
                            <div class="store-section mb-4 p-3 border rounded">
                                <div class="col-12">
                                    <p class="fs-5 fw-bold mb-2">{{$store->name}}</p>
                                </div>
                                <div id="store-entries-{{$store->id}}">
                                    <div class="row mb-2">
                                        <input type="hidden" name="store_id[]" value="{{$store->id}}">
                                        <input type="hidden" name="product_id[{{$store->id}}][]" value="{{$product->id}}">
                                        <div class="mb-3 col-4">
                                            <label for="price-{{$store->id}}-0" class="form-label">Price</label>
                                            <input type="number" step="0.01" name="price[{{$store->id}}][]" value="{{$storeEntry->pivot->product_price}}" class="form-control" id="price-{{$store->id}}-0" required>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label for="url-{{$store->id}}-0" class="form-label">URL</label>
                                            <input type="text" name="url[{{$store->id}}][]" value="{{$storeEntry->pivot->product_url}}" class="form-control" id="url-{{$store->id}}-0" required>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label for="status-{{$store->id}}-0" class="form-label">Status</label>
                                            <select name="status[{{$store->id}}][]" class="form-select" id="status-{{$store->id}}-0">
                                                <option value="in stock" @if($storeEntry->pivot->product_status == 'in stock') selected @endif>In Stock</option>
                                                <option value="out of stock" @if($storeEntry->pivot->product_status == 'out of stock') selected @endif>Out of Stock</option>
                                                <option value="not found" @if($storeEntry->pivot->product_status == 'not found') selected @endif>Not Found</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        @php
                            $availableStores = $stores->filter(fn($s) => !$product->stores->contains($s->id));
                        @endphp
                        @if($availableStores->count() > 0)
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Add New Stores</h6>
                            <div id="new-store-entries"></div>
                            <button type="button" id="add-new-store-btn" class="btn btn-outline-primary btn-sm mt-2">
                                + Add Store
                            </button>
                        </div>

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

                        <button class="btn btn-success mt-3" id="submit-btn">Update</button>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Product Images</h6>
                        @if($product->images->isNotEmpty())
                            <div class="row">
                                @foreach($product->images as $image)
                                    <div class="col-md-4 mb-4">
                                        <div class="border rounded p-3">
                                            <img src="{{ asset($image->image) }}" class="img-fluid mb-2" style="max-height: 200px; object-fit: contain;" alt="Product Image">
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replaceImageModal-{{ $image->id }}">
                                                    Replace
                                                </button>
                                                <form action="{{ route('product.image.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="replaceImageModal-{{ $image->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('product.image.replace', $image->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Replace Image</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Current Image</label>
                                                                <img src="{{ asset($image->image) }}" class="img-fluid mb-2" alt="Current Image">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Choose New Image</label>
                                                                <input type="file" name="image" class="form-control" accept="image/png,image/jpeg,image/webp" required>
                                                                <small class="text-muted">Supported formats: PNG, JPG, JPEG, WebP</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No images uploaded yet.</p>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .autocomplete-dropdown {
            position: absolute;
            z-index: 1050;
            background: #fff;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            box-shadow: 0 0.25rem 1rem rgba(0,0,0,0.1);
            max-height: 240px;
            overflow-y: auto;
            width: 100%;
            display: none;
        }
        .autocomplete-item {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            font-size: 0.875rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .autocomplete-item:last-child { border-bottom: none; }
        .autocomplete-item:hover, .autocomplete-item.active { background: #f5f5f9; }
        .autocomplete-wrapper { position: relative; }
    </style>

    <script>
        window.oldInput = <?php echo json_encode(array_merge($product->only(['name', 'brand', 'power_draw_watts']), $specData)); ?>;
        window.existingDescriptions = <?php echo json_encode($descriptions ?? []); ?>;
        window.formErrors = <?php echo json_encode($errors->toArray()); ?>;
        window.currentFields = null;

        const fieldsRoute = '{{ route('product.fields', ['category' => '__ID__']) }}';
        const autocompleteRoute = '{{ route('product.autocomplete') }}';

        let autocompleteTimer = null;

        document.addEventListener('DOMContentLoaded', function () {
            const categoryId = '{{ $product->category_id }}';
            if (categoryId) {
                loadFields(categoryId);
            }
        });

        document.getElementById('field-category').addEventListener('change', function () {
            const categoryId = this.value;
            if (categoryId) {
                loadFields(categoryId);
            } else {
                hideDynamicFields();
            }
        });

        function loadFields(categoryId) {
            destroyAutocomplete();
            const loading = document.getElementById('fields-loading');
            const dynamic = document.getElementById('dynamic-fields');
            const submitBtn = document.getElementById('submit-btn');

            loading.style.display = 'block';
            dynamic.style.display = 'none';
            if (submitBtn) submitBtn.style.display = 'none';

            fetch(fieldsRoute.replace('__ID__', categoryId), {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                window.currentFields = data;
                renderProductFields(data.product_fields);
                renderSpecFields(data.spec_fields);
                renderDescriptionFields();
                loading.style.display = 'none';
                dynamic.style.display = 'block';
                if (submitBtn) submitBtn.style.display = 'inline-block';
                setupAutocomplete(categoryId);
            })
            .catch(err => {
                loading.style.display = 'none';
                console.error('Failed to load fields:', err);
            });
        }

        function hideDynamicFields() {
            destroyAutocomplete();
            document.getElementById('dynamic-fields').style.display = 'none';
            document.getElementById('product-fields-container').innerHTML = '';
            document.getElementById('spec-fields-inner').innerHTML = '';
        }

        function renderProductFields(fields) {
            const container = document.getElementById('product-fields-container');
            container.innerHTML = '';
            fields.forEach(field => {
                container.appendChild(createFieldElement(field));
            });
        }

        function renderSpecFields(fields) {
            const section = document.getElementById('spec-fields-container');
            const container = document.getElementById('spec-fields-inner');
            container.innerHTML = '';
            if (fields.length === 0) {
                section.style.display = 'none';
                return;
            }
            section.style.display = 'block';
            fields.forEach(field => {
                field.required = true;
                container.appendChild(createFieldElement(field));
            });
        }

        function renderDescriptionFields() {
            const container = document.getElementById('key-value-fields');
            container.innerHTML = '';
            const descriptions = window.existingDescriptions || {};
            const entries = Object.entries(descriptions);
            if (entries.length === 0) {
                container.appendChild(createKeyValueRow('', ''));
            } else {
                for (const [key, value] of entries) {
                    container.appendChild(createKeyValueRow(key, String(value)));
                }
            }
        }

        function createFieldElement(field) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('mb-3', 'form-floating-outline');

            if (field.name === 'name') {
                wrapper.classList.add('autocomplete-wrapper');
            }

            const label = document.createElement('label');
            label.innerHTML = field.label + (field.required ? ' <span class="text-danger">*</span>' : '');

            let input;
            if (field.type === 'textarea') {
                input = document.createElement('textarea');
                input.rows = 4;
            } else {
                input = document.createElement('input');
                input.type = field.type;
                if (field.type === 'number') {
                    input.step = 'any';
                }
            }

            input.name = field.name;
            input.id = 'field-' + field.name;
            input.classList.add('form-control');
            input.placeholder = field.label;

            if (field.required) {
                input.required = true;
            }

            if (window.oldInput && window.oldInput[field.name] !== undefined && window.oldInput[field.name] !== null) {
                input.value = window.oldInput[field.name];
            }

            wrapper.appendChild(label);
            wrapper.appendChild(input);

            if (window.formErrors && window.formErrors[field.name]) {
                input.classList.add('is-invalid');
                const msg = document.createElement('div');
                msg.classList.add('invalid-feedback');
                msg.textContent = window.formErrors[field.name][0];
                wrapper.appendChild(msg);
            }

            return wrapper;
        }

        function createKeyValueRow(key, value) {
            const row = document.createElement('div');
            row.classList.add('row');
            row.innerHTML = `
                <div class="mb-3 col-6">
                    <label class="form-label">Key</label>
                    <input type="text" name="key[]" class="form-control" value="${escapeAttr(key)}">
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Value</label>
                    <input type="text" name="value[]" class="form-control" value="${escapeAttr(value)}">
                </div>
            `;
            return row;
        }

        // ── Autocomplete ──────────────────────────────────────────────────

        function setupAutocomplete(categoryId) {
            const data = window.currentFields;
            if (!data || !data.open_db_name || !data.product_fields) return;

            const nameField = document.getElementById('field-name');
            if (!nameField) return;

            let dropdown = document.getElementById('autocomplete-dropdown');
            if (!dropdown) {
                dropdown = document.createElement('div');
                dropdown.id = 'autocomplete-dropdown';
                dropdown.className = 'autocomplete-dropdown';
                nameField.parentNode.appendChild(dropdown);
            }

            nameField.addEventListener('input', function () {
                clearTimeout(autocompleteTimer);
                const query = this.value.trim();
                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }
                autocompleteTimer = setTimeout(() => {
                    const url = autocompleteRoute + '?query=' + encodeURIComponent(query) + '&category_id=' + categoryId;
                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(acData => {
                            if (!acData.enabled || !acData.results.length) {
                                dropdown.style.display = 'none';
                                return;
                            }
                            renderDropdown(acData.results, dropdown);
                        });
                }, 300);
            });

            nameField.addEventListener('blur', function () {
                setTimeout(() => { dropdown.style.display = 'none'; }, 150);
            });

            document.addEventListener('click', function handler(e) {
                if (!dropdown.contains(e.target) && e.target !== nameField) {
                    dropdown.style.display = 'none';
                }
            });
        }

        function destroyAutocomplete() {
            const dropdown = document.getElementById('autocomplete-dropdown');
            if (dropdown) dropdown.remove();
        }

        function renderDropdown(results, dropdown) {
            dropdown.innerHTML = results.map(r =>
                `<div class="autocomplete-item" role="option">${escapeHtml(r.name)}</div>`
            ).join('');
            dropdown.style.display = 'block';

            dropdown.querySelectorAll('.autocomplete-item').forEach((item, i) => {
                item.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    const result = results[i];
                    document.getElementById('field-name').value = result.name;
                    applyMapping(result.specs);
                    dropdown.style.display = 'none';
                });
            });
        }

        function applyMapping(specs) {
            if (!specs) return;
            const flat = flattenObject(specs);

            delete flat['opendb_id'];

            if (flat['manufacturer'] !== undefined) {
                const brandField = document.getElementById('field-brand');
                if (brandField) {
                    brandField.value = flat['manufacturer'];
                }
            }

            if (flat['tdp'] !== undefined) {
                const tdpField = document.getElementById('field-power_draw_watts');
                if (tdpField) {
                    tdpField.value = flat['tdp'];
                }
            }

            if (window.currentFields && window.currentFields.spec_fields) {
                const specNames = new Set(window.currentFields.spec_fields.map(f => f.name));
                for (const key of Object.keys(flat)) {
                    if (specNames.has(key)) {
                        const specField = document.getElementById('field-' + key);
                        if (specField) {
                            specField.value = flat[key];
                        }
                    }
                }
            }

            populateKeyValuePairs(flat);
        }

        function flattenObject(obj, prefix) {
            const result = {};
            for (const key in obj) {
                const val = obj[key];
                if (val !== null && typeof val === 'object' && !Array.isArray(val)) {
                    Object.assign(result, flattenObject(val, key));
                } else {
                    if (!(key in result)) {
                        result[key] = val;
                    }
                }
            }
            return result;
        }

        function populateKeyValuePairs(pairs) {
            const container = document.getElementById('key-value-fields');
            container.innerHTML = '';
            for (const [key, value] of Object.entries(pairs)) {
                container.appendChild(createKeyValueRow(key, String(value)));
            }
            if (container.children.length === 0) {
                container.appendChild(createKeyValueRow('', ''));
            }
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function escapeAttr(str) {
            return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        document.getElementById('add-key-value').addEventListener('click', function () {
            document.getElementById('key-value-fields').appendChild(createKeyValueRow('', ''));
        });

        document.getElementById('clear-all-fields').addEventListener('click', function () {
            document.querySelectorAll('#product-fields-container input, #product-fields-container textarea').forEach(el => {
                el.value = '';
            });
            document.querySelectorAll('#spec-fields-inner input, #spec-fields-inner textarea').forEach(el => {
                el.value = '';
            });
            const container = document.getElementById('key-value-fields');
            container.innerHTML = '';
            container.appendChild(createKeyValueRow('', ''));
        });

        // Add new store row
        const addNewStoreBtn = document.getElementById('add-new-store-btn');
        if (addNewStoreBtn) {
            addNewStoreBtn.addEventListener('click', function () {
                const template = document.getElementById('new-store-template');
                const clone = template.content.cloneNode(true);
                document.getElementById('new-store-entries').appendChild(clone);
            });

            document.getElementById('new-store-entries').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-new-store-btn')) {
                    e.target.closest('.new-store-row').remove();
                }
            });
        }
    </script>
@endsection