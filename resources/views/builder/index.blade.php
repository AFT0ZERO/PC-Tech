@extends('userSide.layout.app')

@section('extraHeader')
<style>
    .builder-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        color: #fff;
        padding: 50px 0 40px;
        margin-bottom: 40px;
    }
    .builder-hero h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 8px; }
    .builder-hero p  { opacity: .75; font-size: 1.05rem; }

    .category-card {
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        padding: 20px;
        transition: border-color .25s, box-shadow .25s;
        background: #fff;
        height: 100%;
    }
    .category-card:hover         { border-color: #0f3460; box-shadow: 0 4px 18px rgba(15,52,96,.12); }
    .category-card.has-selection { border-color: #28a745; }

    .category-card .card-icon { font-size: 2rem; margin-bottom: 10px; display: block; }
    .category-card h5 { font-weight: 700; margin-bottom: 14px; color: #1a1a2e; }
    .category-card select { border-radius: 6px; border: 1px solid #ced4da; font-size: .9rem; }

    .part-price { font-size: .9rem; font-weight: 600; color: #28a745; min-height: 22px; margin-top: 6px; }

    .total-bar {
        position: sticky;
        bottom: 0;
        background: #0f3460;
        color: #fff;
        padding: 14px 0;
        z-index: 1000;
        box-shadow: 0 -4px 16px rgba(0,0,0,.2);
    }
    .total-bar .total-label { font-size: 1rem; opacity: .8; }
    .total-bar .total-price { font-size: 1.5rem; font-weight: 700; }

    #compat-panel .alert { margin-bottom: 8px; }
    #saveModal .modal-header { background: #0f3460; color: #fff; }
    #saveModal .modal-header .btn-close { filter: invert(1); }
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="builder-hero">
    <div class="container">
        <h1><i class="fa fa-desktop me-2"></i> PC Builder</h1>
        <p>Select components for each slot. We'll check compatibility as you go.</p>
        @auth
            <a href="{{ route('builder.myBuilds') }}" class="btn btn-outline-light btn-sm mt-2">
                <i class="fa fa-list me-1"></i> My Saved Builds
            </a>
        @endauth
    </div>
</div>

<div class="container mb-5">

    {{-- Compatibility panel --}}
    <div id="compat-panel" class="mb-4" style="display:none;">
        <div id="compat-success" class="alert alert-success d-none" role="alert">
            <i class="fa fa-check-circle me-2"></i> All selected parts are compatible!
        </div>
        <div id="compat-warnings"></div>
    </div>

    {{-- Category cards --}}
    <div class="row g-4" id="builder-grid">
        @php
            $icons = [
                'cpu'         => '🖥️',
                'motherboard' => '📋',
                'ram'         => '💾',
                'gpu'         => '🎮',
                'storage'     => '💿',
                'psu'         => '⚡',
                'cooler'      => '❄️',
                'case'        => '🗄️',
            ];
        @endphp

        @foreach($builderCategories as $cat)
            @php $key = strtolower($cat->name); @endphp
            <div class="col-lg-3 col-md-6">
                <div class="category-card" id="card-{{ $cat->id }}">
                    <span class="card-icon">{{ $icons[$key] ?? '🔧' }}</span>
                    <h5>{{ $cat->name }}</h5>

                    <select class="form-select part-select"
                            id="select-{{ $cat->id }}"
                            data-category-id="{{ $cat->id }}"
                            data-category-name="{{ $cat->name }}">
                        <option value="">Loading parts…</option>
                    </select>

                    <div class="part-price" id="price-{{ $cat->id }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="height:90px;"></div>
</div>

{{-- Sticky total bar --}}
<div class="total-bar">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div class="total-label">Estimated Total</div>
            <div class="total-price" id="grand-total">JOD 0.00</div>
        </div>
        <button class="btn btn-warning btn-lg fw-bold px-5" id="save-build-btn">
            <i class="fa fa-save me-2"></i> Save Build
        </button>
    </div>
</div>

{{-- Save Modal --}}
<div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveModalLabel"><i class="fa fa-save me-2"></i> Save Your Build</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="save-error" class="alert alert-danger d-none"></div>
                <div class="mb-3">
                    <label for="build-name" class="form-label fw-semibold">Build Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="build-name" maxlength="150"
                           placeholder="e.g. My Gaming Rig 2024">
                </div>
                <div class="mb-3">
                    <label for="build-notes" class="form-label fw-semibold">Notes <small class="text-muted">(optional)</small></label>
                    <textarea class="form-control" id="build-notes" rows="3"
                              placeholder="Any notes about this build…"></textarea>
                </div>
                <div class="alert alert-info py-2 mb-0">
                    <small id="save-parts-count"></small>
                    &nbsp;|&nbsp;
                    <strong id="save-total-display"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success px-4" id="confirm-save-btn">
                    <i class="fa fa-check me-1"></i> Save Build
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    const selectedParts   = {};
    const csrfToken       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    const saveModal       = new bootstrap.Modal(document.getElementById('saveModal'));

    // ── Fetch parts for every category on load ──────────────────────────��────
    document.querySelectorAll('.part-select').forEach(function (select) {
        const catId   = select.dataset.categoryId;
        const catName = select.dataset.categoryName;

        fetch('/builder/parts/' + catId, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(function (products) {
            select.innerHTML = '<option value="">— Select ' + catName + ' —</option>';
            products.forEach(function (p) {
                const opt        = document.createElement('option');
                opt.value        = p.id;
                opt.textContent  = p.brand + ' ' + p.name;
                opt.dataset.price         = p.cheapest_price;
                opt.dataset.categoryName  = p.category_name;
                select.appendChild(opt);
            });
            if (products.length === 0) {
                select.innerHTML = '<option value="">No products available</option>';
            }
        })
        .catch(function () {
            select.innerHTML = '<option value="">Failed to load</option>';
        });
    });

    // ── React to part selection ──────────────────────────────────────────────
    document.querySelectorAll('.part-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const catId   = this.dataset.categoryId;
            const catName = this.dataset.categoryName;
            const card    = document.getElementById('card-' + catId);
            const priceEl = document.getElementById('price-' + catId);
            const opt     = this.options[this.selectedIndex];

            if (this.value) {
                const price = parseFloat(opt.dataset.price) || 0;
                selectedParts[catId] = { id: parseInt(this.value), name: opt.textContent, price: price, categoryName: catName };
                priceEl.textContent  = price > 0 ? 'JOD ' + price.toFixed(2) : 'Price unavailable';
                card.classList.add('has-selection');
            } else {
                delete selectedParts[catId];
                priceEl.textContent = '';
                card.classList.remove('has-selection');
            }

            updateTotal();
            checkCompatibility();
        });
    });

    // ── Grand total ──────────────────────────────────────────────────────────
    function updateTotal() {
        const total = Object.values(selectedParts).reduce((s, p) => s + p.price, 0);
        document.getElementById('grand-total').textContent = 'JOD ' + total.toFixed(2);
    }

    // ── Compatibility check ──────────────────────────────────────────────────
    function checkCompatibility() {
        const partIds = Object.values(selectedParts).map(p => p.id);

        if (partIds.length === 0) {
            document.getElementById('compat-panel').style.display = 'none';
            return;
        }

        fetch('/builder/check-compatibility', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body:    JSON.stringify({ part_ids: partIds }),
        })
        .then(r => r.json())
        .then(function (data) {
            const panel   = document.getElementById('compat-panel');
            const success = document.getElementById('compat-success');
            const warnDiv = document.getElementById('compat-warnings');

            panel.style.display = 'block';
            warnDiv.innerHTML   = '';

            if (data.warnings && data.warnings.length > 0) {
                success.classList.add('d-none');
                data.warnings.forEach(function (msg) {
                    const a       = document.createElement('div');
                    a.className   = 'alert alert-warning alert-dismissible fade show';
                    a.innerHTML   = '<i class="fa fa-exclamation-triangle me-2"></i>' + msg +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    warnDiv.appendChild(a);
                });
            } else {
                success.classList.remove('d-none');
            }
        })
        .catch(console.error);
    }

    // ── Save Build button ────────────────────────────────────────────────────
    document.getElementById('save-build-btn').addEventListener('click', function () {
        if (!isAuthenticated) {
            window.location.href = '{{ route("login") }}';
            return;
        }

        const partCount = Object.keys(selectedParts).length;
        if (partCount === 0) {
            Swal.fire({ icon: 'warning', title: 'No parts selected', text: 'Please select at least one component.' });
            return;
        }

        const total = Object.values(selectedParts).reduce((s, p) => s + p.price, 0);
        document.getElementById('save-parts-count').textContent  = partCount + ' component' + (partCount > 1 ? 's' : '') + ' selected';
        document.getElementById('save-total-display').textContent = 'Total: JOD ' + total.toFixed(2);
        document.getElementById('save-error').classList.add('d-none');
        document.getElementById('build-name').value  = '';
        document.getElementById('build-notes').value = '';

        saveModal.show();
    });

    // ── Confirm save ─────────────────────────────────────────────────────────
    document.getElementById('confirm-save-btn').addEventListener('click', function () {
        const name    = document.getElementById('build-name').value.trim();
        const notes   = document.getElementById('build-notes').value.trim();
        const errorEl = document.getElementById('save-error');

        if (!name) {
            errorEl.textContent = 'Please enter a name for your build.';
            errorEl.classList.remove('d-none');
            return;
        }

        const partIds = Object.values(selectedParts).map(p => p.id);
        const btn     = this;
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving…';

        fetch('/builder/save', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body:    JSON.stringify({ name, notes, part_ids: partIds }),
        })
        .then(r => r.json())
        .then(function (data) {
            saveModal.hide();
            Swal.fire({
                icon: 'success', title: 'Build Saved!', text: data.message || 'Your build has been saved.',
                timer: 2000, showConfirmButton: false,
            }).then(function () {
                window.location.href = '{{ route("builder.myBuilds") }}';
            });
        })
        .catch(function () {
            errorEl.textContent = 'Something went wrong. Please try again.';
            errorEl.classList.remove('d-none');
        })
        .finally(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fa fa-check me-1"></i> Save Build';
        });
    });

})();
</script>
@endsection
