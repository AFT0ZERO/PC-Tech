@extends('userSide.layout.app')

@section('extraHeader')
<style>
    .builder-hero {
        padding: 50px 0 40px;
        margin-bottom: 40px;
    }
    .builder-hero h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 8px; }
    .builder-hero p  { opacity: .75; font-size: 1.05rem; }

    .compat-bar {
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }

    .builder-table {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }
    .builder-table table { margin-bottom: 0; }
    .builder-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e8e8e8;
        font-weight: 600;
        font-size: .85rem;
        color: #555;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .builder-table tbody td {
        vertical-align: middle;
        padding: 14px 16px;
        border-bottom: 1px solid #eee;
    }
    .builder-table tbody tr:last-child td { border-bottom: none; }
    .builder-table tbody tr:hover { background: #fafafa; }

    .component-link {
        font-weight: 600;
        color: #0f3460;
        text-decoration: none;
    }
    .component-link:hover { color: #1a5276; text-decoration: underline; }

    .choose-btn {
        background: #0f3460;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        transition: background .2s;
        display: inline-block;
        text-decoration: none;
    }
    .choose-btn:hover { background: #1a5276; color: #fff; }

    .add-additional-btn {
        background: #e9ecef;
        color: #0f3460;
        border: 1px solid #ced4da;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-block;
        text-decoration: none;
        margin-top: 8px;
    }
    .add-additional-btn:hover { background: #dee2e6; color: #0f3460; }

    .selected-part {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }
    .selected-part img {
        width: 40px;
        height: 40px;
        object-fit: contain;
        border-radius: 4px;
        background: #f5f5f5;
    }
    .selected-part .part-name { font-weight: 500; color: #333; }

    .selected-part-info {
        min-height: 40px;
        display: flex;
        align-items: center;
        margin-bottom: 4px;
        font-size: .9rem;
    }

    .remove-cell-btn {
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2rem;
        font-weight: 700;
        background: none;
        border: none;
        padding: 4px 8px;
        line-height: 1;
    }
    .remove-cell-btn:hover { color: #a71d2a; }

    .price-cell { font-weight: 600; color: #28a745; }
    .price-cell.empty { color: #999; font-weight: 400; }
    .store-cell { color: #666; font-size: .9rem; }
    .availability-cell { font-size: .85rem; }
    .availability-cell.in-stock { color: #28a745; }
    .availability-cell.out-of-stock { color: #dc3545; }

    .total-bar {
        background: #0f3460;
        color: #fff;
        padding: 14px 0;
        margin-top: 30px;
        border-radius: 8px 8px 0 0;
    }
    .total-bar .total-label { font-size: 1rem; opacity: .8; }
    .total-bar .total-price { font-size: 1.5rem; font-weight: 700; }

    #saveModal .modal-header { background: #0f3460; color: #fff; }
    #saveModal .modal-header .btn-close { filter: invert(1); }
</style>
@endsection

@section('content')

<div class="builder-hero">
    <div class="container">
        <h1><i class="fa fa-desktop me-2"></i> PC Builder</h1>
        <p>Build your dream PC step by step. Click "Choose" to browse parts for each component.</p>
        @auth
            <a href="{{ route('builder.myBuilds') }}" class="btn btn-outline-primary btn-sm mt-2">
                <i class="fa fa-list me-1"></i> My Saved Builds
            </a>
        @endauth
    </div>
</div>

<div class="container mb-5">

    {{-- Compatibility panel --}}
    <div id="compat-panel" class="compat-bar mb-4" style="display:none;">
        <div class="d-flex align-items-center justify-content-between px-3 py-2" style="background: #d4edda; border-radius: 8px;">
            <div id="compat-success" class="d-flex align-items-center" style="color: #155724;">
                <i class="fa fa-check-circle me-2"></i>
                <span><strong>Compatibility:</strong> No issues or incompatibilities found.</span>
            </div>
            <div id="compat-wattage" class="d-flex align-items-center px-3 py-1" style="background: #0f3460; color: #fff; border-radius: 4px; font-size: .85rem;">
                <i class="fa fa-bolt me-2"></i>
                <span>Estimated Wattage: <strong id="wattage-value">0W</strong></span>
            </div>
        </div>
        <div id="compat-warnings" class="mt-2"></div>
    </div>

    {{-- Builder table --}}
    <div class="builder-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 15%;">Component</th>
                    <th style="width: 35%;">Selection</th>
                    <th style="width: 12%;">Price</th>
                    <th style="width: 15%;">Availability</th>
                    <th style="width: 13%;">Store</th>
                    <th style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody id="builder-tbody">
                @foreach($builderCategories as $cat)
                    @php
                        $slot = $cat->buildSlot;
                        $maxQty = $slot ? (int)$slot->max_qty : 1;
                    @endphp
                    <tr data-category-id="{{ $cat->id }}"
                        data-category-name="{{ $cat->name }}"
                        data-max-qty="{{ $maxQty }}">
                        <td>
                            <a href="{{ route('builder.parts', $cat->id) }}" class="component-link">{{ $cat->name }}</a>
                        </td>
                        <td id="selection-{{ $cat->id }}"></td>
                        <td class="price-cell empty" id="price-{{ $cat->id }}">—</td>
                        <td class="availability-cell" id="availability-{{ $cat->id }}">—</td>
                        <td class="store-cell" id="store-{{ $cat->id }}">—</td>
                        <td id="remove-{{ $cat->id }}"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="height:60px;"></div>
</div>

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

    const STORAGE_KEY = 'pc_builder_selections';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    const saveModal = new bootstrap.Modal(document.getElementById('saveModal'));

    function loadSelections() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; }
        catch (e) { return {}; }
    }

    function saveSelections(selections) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(selections));
    }

    function getSelectedPartsArray() {
        const selections = loadSelections();
        const parts = [];
        Object.values(selections).forEach(catSelections => {
            if (Array.isArray(catSelections)) {
                catSelections.forEach(p => { if (p && p.productId) parts.push(p); });
            } else if (catSelections && catSelections.productId) {
                parts.push(catSelections);
            }
        });
        return parts;
    }

    function renderCategorySelection(catId, catName, maxQty) {
        const selections = loadSelections();
        const catSelections = selections[catId] || [];
        const container = document.getElementById('selection-' + catId);
        const priceEl = document.getElementById('price-' + catId);
        const availEl = document.getElementById('availability-' + catId);
        const storeEl = document.getElementById('store-' + catId);
        const removeEl = document.getElementById('remove-' + catId);
        const partsUrl = '{{ route('builder.parts', '__CATID__') }}'.replace('__CATID__', catId);

        if (!catSelections.length || !catSelections[0].productId) {
            container.innerHTML = '<a href="' + partsUrl + '" class="choose-btn"><i class="fa fa-plus me-1"></i> Choose ' + catName + '</a>';
            priceEl.textContent = '—';
            priceEl.classList.add('empty');
            availEl.textContent = '—';
            availEl.className = 'availability-cell';
            storeEl.textContent = '—';
            removeEl.innerHTML = '';
            return;
        }

        let selHtml = '';
        let priceHtml = '';
        let availHtml = '';
        let storeHtml = '';
        let removeHtml = '';
        let totalPrice = 0;

        catSelections.forEach((sel, idx) => {
            if (!sel || !sel.productId) return;
            totalPrice += parseFloat(sel.price) || 0;

            const imgHtml = sel.image ? '<img src="' + sel.image + '" alt="">' : '';
            if (maxQty > 1) {
                selHtml += '<div class="selected-part">' + imgHtml + '<span class="part-name">' + sel.name + '</span></div>';
                removeHtml += '<div class="selected-part-info">' +
                    '<button class="remove-cell-btn" data-cat="' + catId + '" data-idx="' + idx + '" title="Remove">' +
                    '<i class="fa fa-times"></i>' +
                    '</button></div>';
            } else {
                selHtml += '<div class="selected-part">' + imgHtml + '<span class="part-name">' + sel.name + '</span></div>';
            }

            priceHtml += '<div class="selected-part-info">JOD ' + parseFloat(sel.price).toFixed(2) + '</div>';

            const availClass = sel.availability === 'In Stock' ? 'in-stock' : sel.availability === 'Out of Stock' ? 'out-of-stock' : '';
            availHtml += '<div class="selected-part-info"><span class="availability-cell ' + availClass + '">' + (sel.availability || '—') + '</span></div>';

            storeHtml += '<div class="selected-part-info">' + (sel.store || '—') + '</div>';
        });

        if (maxQty > 1 && catSelections.length < maxQty) {
            selHtml += '<a href="' + partsUrl + '" class="add-additional-btn"><i class="fa fa-plus me-1"></i> Add additional ' + catName + '</a>';
        }

        container.innerHTML = selHtml;

        if (maxQty > 1) {
            if (catSelections.length > 1) {
                priceHtml += '<div class="selected-part-info" style="font-weight:700; color:#0f3460; padding-top:4px;">Total: JOD ' + totalPrice.toFixed(2) + '</div>';
            }
            priceEl.innerHTML = priceHtml;
            availEl.innerHTML = availHtml;
            storeEl.innerHTML = storeHtml;
            removeEl.innerHTML = removeHtml;
            removeEl.querySelectorAll('.remove-cell-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    removePart(this.dataset.cat, parseInt(this.dataset.idx));
                });
            });
        } else {
            priceEl.textContent = totalPrice > 0 ? 'JOD ' + totalPrice.toFixed(2) : '—';
            priceEl.classList.toggle('empty', totalPrice === 0);
            const lastSel = catSelections[catSelections.length - 1];
            const lastAvail = lastSel?.availability || '';
            availEl.textContent = lastAvail || '—';
            availEl.className = 'availability-cell' + (lastAvail === 'In Stock' ? ' in-stock' : lastAvail === 'Out of Stock' ? ' out-of-stock' : '');
            storeEl.textContent = lastSel?.store || '—';
            removeEl.innerHTML = '<button class="remove-cell-btn" data-cat="' + catId + '" title="Remove"><i class="fa fa-times"></i></button>';
            removeEl.querySelector('.remove-cell-btn').addEventListener('click', function() {
                removeAllParts(this.dataset.cat);
            });
        }
    }

    function removePart(catId, idx) {
        const selections = loadSelections();
        const catSelections = selections[catId] || [];
        catSelections.splice(idx, 1);
        if (catSelections.length === 0) {
            delete selections[catId];
        } else {
            selections[catId] = catSelections;
        }
        saveSelections(selections);
        refreshAll();
    }

    function removeAllParts(catId) {
        const selections = loadSelections();
        delete selections[catId];
        saveSelections(selections);
        refreshAll();
    }

    function refreshAll() {
        document.querySelectorAll('#builder-tbody tr').forEach(tr => {
            const catId = tr.dataset.categoryId;
            const catName = tr.dataset.categoryName;
            const maxQty = parseInt(tr.dataset.maxQty) || 1;
            renderCategorySelection(catId, catName, maxQty);
        });
        updateTotal();
        checkCompatibility();
    }

    function updateTotal() {
        const parts = getSelectedPartsArray();
        const total = parts.reduce((sum, p) => sum + (parseFloat(p.price) || 0), 0);
        document.getElementById('grand-total').textContent = 'JOD ' + total.toFixed(2);
    }

    function checkCompatibility() {
        const parts = getSelectedPartsArray();
        const partIds = parts.map(p => p.productId);

        if (partIds.length === 0) {
            document.getElementById('compat-panel').style.display = 'none';
            return;
        }

        let totalWattage = 0;
        parts.forEach(p => { totalWattage += parseFloat(p.power) || 0; });
        document.getElementById('wattage-value').textContent = totalWattage + 'W';

        fetch('/builder/check-compatibility', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ part_ids: partIds }),
        })
        .then(r => r.json())
        .then(function (data) {
            const panel = document.getElementById('compat-panel');
            const success = document.getElementById('compat-success');
            const warnDiv = document.getElementById('compat-warnings');

            panel.style.display = 'block';
            warnDiv.innerHTML = '';

            if (data.warnings && data.warnings.length > 0) {
                success.parentElement.style.background = '#fff3cd';
                success.parentElement.style.color = '#856404';
                success.classList.add('d-none');
                data.warnings.forEach(function (msg) {
                    const a = document.createElement('div');
                    a.className = 'alert alert-warning alert-dismissible fade show mb-2';
                    a.innerHTML = '<i class="fa fa-exclamation-triangle me-2"></i>' + msg +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    warnDiv.appendChild(a);
                });
            } else {
                success.parentElement.style.background = '#d4edda';
                success.parentElement.style.color = '#155724';
                success.classList.remove('d-none');
            }

            if (data.notes && data.notes.length > 0) {
                data.notes.forEach(function (msg) {
                    const a = document.createElement('div');
                    a.className = 'alert alert-secondary alert-dismissible fade show mb-2';
                    a.innerHTML = '<i class="fa fa-info-circle me-2"></i>' + msg +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    warnDiv.appendChild(a);
                });
            }
        })
        .catch(console.error);
    }

    document.getElementById('save-build-btn').addEventListener('click', function () {
        if (!isAuthenticated) { window.location.href = '{{ route("login") }}'; return; }
        const parts = getSelectedPartsArray();
        if (parts.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No parts selected', text: 'Please select at least one component.' });
            return;
        }
        const total = parts.reduce((s, p) => s + (parseFloat(p.price) || 0), 0);
        document.getElementById('save-parts-count').textContent = parts.length + ' component' + (parts.length > 1 ? 's' : '') + ' selected';
        document.getElementById('save-total-display').textContent = 'Total: JOD ' + total.toFixed(2);
        document.getElementById('save-error').classList.add('d-none');
        document.getElementById('build-name').value = '';
        saveModal.show();
    });

    document.getElementById('confirm-save-btn').addEventListener('click', function () {
        const name = document.getElementById('build-name').value.trim();
        const errorEl = document.getElementById('save-error');
        if (!name) { errorEl.textContent = 'Please enter a name for your build.'; errorEl.classList.remove('d-none'); return; }

        const parts = getSelectedPartsArray();
        const partIds = parts.map(p => p.productId);
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving…';

        fetch('/builder/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ name, part_ids: partIds }),
        })
        .then(r => r.json())
        .then(function (data) {
            saveModal.hide();
            localStorage.removeItem(STORAGE_KEY);
            Swal.fire({
                icon: 'success', title: 'Build Saved!', text: data.message || 'Your build has been saved.',
                timer: 2000, showConfirmButton: false,
            }).then(function () { window.location.href = '{{ route("builder.myBuilds") }}'; });
        })
        .catch(function () { errorEl.textContent = 'Something went wrong. Please try again.'; errorEl.classList.remove('d-none'); })
        .finally(function () { btn.disabled = false; btn.innerHTML = '<i class="fa fa-check me-1"></i> Save Build'; });
    });

    window.addEventListener('storage', function(e) { if (e.key === STORAGE_KEY) refreshAll(); });
    document.addEventListener('DOMContentLoaded', refreshAll);
    refreshAll();

})();
</script>
@endsection
