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

                    <div class="form-floating-outline">
                        <label for="field-category">Category</label>
                        <select name="category" id="field-category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category') == $cat->id ? 'selected' : '' }}>
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
                        <div id="key-value-section" class="mt-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Description (Key : Value)</h6>
                            <div id="key-value-fields" class="row">
                                <div class="mb-3 col-6">
                                    <label class="form-label">Key</label>
                                    <input type="text" name="key[]" class="form-control">
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Value</label>
                                    <input type="text" name="value[]" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" id="add-key-value" class="btn btn-secondary">Add More</button>
                                <button type="button" id="clear-all-fields" class="btn btn-outline-danger">Clear All</button>
                            </div>
                        </div>
                        <div id="spec-fields-container" style="display: none;">
                            <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Specifications</h6>
                            <div id="spec-fields-inner"></div>
                        </div>
                    </div>

                    <div id="store-section">
                        <br>
                        @foreach($stores as $store)
                            <div class="col-2">
                                <p class="fs-5 fw-bold">{{ $store->name }}</p>
                            </div>
                            <div class="row">
                                <input type="hidden" name="store_id[]" value="{{ $store->id }}">
                                <div class="mb-3 col-4">
                                    <label for="price-{{ $store->id }}" class="form-label">Price</label>
                                    <input type="text" name="price[]" class="form-control" id="price-{{ $store->id }}" required>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="url-{{ $store->id }}" class="form-label">Url</label>
                                    <input type="text" name="url[]" class="form-control" id="url-{{ $store->id }}" required>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="status-{{ $store->id }}" class="form-label">Status</label>
                                    <select name="status[]" class="form-select">
                                        <option value="in stock">In Stock</option>
                                        <option value="out of stock">Out of Stock</option>
                                        <option value="not found">Not Found</option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="btn btn-success" id="submit-btn" style="display: none;">ADD +</button>
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
        window.oldInput = @json(old());
        window.formErrors = @json($errors->toArray());
        window.currentFields = null;

        const fieldsRoute = '{{ route('product.fields', ['category' => '__ID__']) }}';
        const autocompleteRoute = '{{ route('product.autocomplete') }}';

        let autocompleteTimer = null;

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
            submitBtn.style.display = 'none';

            fetch(fieldsRoute.replace('__ID__', categoryId), {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                window.currentFields = data;
                renderProductFields(data.product_fields);
                renderSpecFields(data.spec_fields);
                loading.style.display = 'none';
                dynamic.style.display = 'block';
                submitBtn.style.display = 'inline-block';
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
            document.getElementById('submit-btn').style.display = 'none';
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
                container.appendChild(createFieldElement(field));
            });
        }

        function createFieldElement(field) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('mb-3', 'form-floating-outline');

            if (field.name === 'name') {
                wrapper.classList.add('autocomplete-wrapper');
            }

            const label = document.createElement('label');
            label.textContent = field.label;

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

            if (window.oldInput && window.oldInput[field.name]) {
                input.value = window.oldInput[field.name];
            }

            if (window.formErrors && window.formErrors[field.name]) {
                input.classList.add('is-invalid');
                const msg = document.createElement('div');
                msg.classList.add('invalid-feedback');
                msg.textContent = window.formErrors[field.name][0];
                wrapper.appendChild(msg);
            }

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            return wrapper;
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

            // 1. Brand
            if (flat['manufacturer'] !== undefined) {
                const brandField = document.getElementById('field-brand');
                if (brandField) {
                    brandField.value = flat['manufacturer'];
                }
                delete flat['manufacturer'];
            }

            // 2. Power draw
            if (flat['tdp'] !== undefined) {
                const tdpField = document.getElementById('field-power_draw_watts');
                if (tdpField) {
                    tdpField.value = flat['tdp'];
                }
                delete flat['tdp'];
            }

            // 3. Spec fields (dynamic, based on spec table column names)
            if (window.currentFields && window.currentFields.spec_fields) {
                const specNames = new Set(window.currentFields.spec_fields.map(f => f.name));
                for (const key of Object.keys(flat)) {
                    if (specNames.has(key)) {
                        const specField = document.getElementById('field-' + key);
                        if (specField) {
                            specField.value = flat[key];
                        }
                        delete flat[key];
                    }
                }
            }

            // 4. Remaining → description key:value
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
                const row = document.createElement('div');
                row.classList.add('row');
                row.innerHTML = `
                    <div class="mb-3 col-6">
                        <label class="form-label">Key</label>
                        <input type="text" name="key[]" class="form-control" value="${escapeAttr(key)}">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Value</label>
                        <input type="text" name="value[]" class="form-control" value="${escapeAttr(String(value))}">
                    </div>
                `;
                container.appendChild(row);
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

        // ── Autofill button (existing, to be removed after validation) ────

        @if(old('category'))
            document.addEventListener('DOMContentLoaded', function () {
                loadFields('{{ old('category') }}');
            });
        @endif

        document.getElementById('add-key-value').addEventListener('click', function () {
            const row = document.createElement('div');
            row.classList.add('row');
            row.innerHTML = `
                <div class="mb-3 col-6">
                    <label class="form-label">Key</label>
                    <input type="text" name="key[]" class="form-control">
                </div>
                <div class="mb-3 col-6">
                    <label class="form-label">Value</label>
                    <input type="text" name="value[]" class="form-control">
                </div>
            `;
            document.getElementById('key-value-fields').appendChild(row);
        });

        document.getElementById('clear-all-fields').addEventListener('click', function () {
            document.querySelectorAll('#product-fields-container input, #product-fields-container textarea').forEach(el => {
                el.value = '';
            });
            document.querySelectorAll('#spec-fields-inner input, #spec-fields-inner textarea').forEach(el => {
                el.value = '';
            });
            document.getElementById('key-value-fields').innerHTML = `
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">Key</label>
                        <input type="text" name="key[]" class="form-control">
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Value</label>
                        <input type="text" name="value[]" class="form-control">
                    </div>
                </div>
            `;
        });
    </script>
@endsection
