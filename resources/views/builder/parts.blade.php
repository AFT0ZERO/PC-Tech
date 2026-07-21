@extends('userSide.layout.app')

@section('extraHeader')
<style>
    .parts-hero {
        padding: 40px 0 30px;
        margin-bottom: 30px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .parts-hero h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: 4px; }
    .parts-hero p { opacity: .7; font-size: .95rem; }

    .back-link {
        color: #0f3460;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }
    .back-link:hover { color: #1a5276; text-decoration: underline; }

    .search-box {
        margin-bottom: 20px;
    }
    .search-box input {
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 10px 16px;
        font-size: .95rem;
        width: 100%;
        max-width: 400px;
    }
    .search-box input:focus {
        border-color: #0f3460;
        outline: none;
        box-shadow: 0 0 0 3px rgba(15,52,96,.1);
    }

    .parts-table {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }
    .parts-table table { margin-bottom: 0; }
    .parts-table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e8e8e8;
        font-weight: 600;
        font-size: .85rem;
        color: #555;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .parts-table tbody td {
        vertical-align: middle;
        padding: 12px 16px;
        border-bottom: 1px solid #eee;
    }
    .parts-table tbody tr:last-child td { border-bottom: none; }
    .parts-table tbody tr:hover { background: #fafafa; }

    .part-image {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border-radius: 4px;
        background: #f5f5f5;
    }

    .part-name {
        font-weight: 500;
        color: #333;
    }
    .part-brand {
        font-size: .8rem;
        color: #888;
    }

    .price-cell {
        font-weight: 600;
        color: #28a745;
    }

    .availability-cell {
        font-size: .85rem;
    }
    .availability-cell.in-stock { color: #28a745; }
    .availability-cell.out-of-stock { color: #dc3545; }

    .store-cell {
        color: #666;
        font-size: .9rem;
    }

    .select-btn {
        background: #0f3460;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        transition: background .2s;
    }
    .select-btn:hover { background: #1a5276; color: #fff; }
    .select-btn.selected {
        background: #28a745;
    }
    .select-btn.selected:hover { background: #218838; }

    .no-results {
        text-align: center;
        padding: 40px;
        color: #888;
    }
</style>
@endsection

@section('content')

<div class="container mb-5">
    <a href="{{ route('builder.index') }}" class="back-link">
        <i class="fa fa-arrow-left"></i> Back to PC Builder
    </a>

    <div class="parts-hero">
        <h1>Choose {{ $category->name }}</h1>
        <p>Browse and select from available {{ strtolower($category->name) }} products</p>
    </div>

    <div class="search-box">
        <input type="text" id="search-input" placeholder="Search products..." />
    </div>

    <div class="parts-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;"></th>
                    <th style="width: 40%;">Product</th>
                    <th style="width: 15%;">Price</th>
                    <th style="width: 15%;">Availability</th>
                    <th style="width: 15%;">Store</th>
                    <th style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody id="parts-tbody">
                @forelse($products as $product)
                    @php
                        $image = $product->images->first() ? $product->images->first()->image : '';
                        $price = $product->cheapest_price;
                        $storeName = $product->cheapest_store_name;
                        $isInStock = $product->cheapest_status === 'in stock';
                    @endphp
                    <tr data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->brand }} {{ $product->name }}"
                        data-product-price="{{ $price ?? 0 }}"
                        data-product-image="{{ $image }}"
                        data-product-store="{{ $storeName ?? '' }}"
                        data-product-availability="{{ $isInStock ? 'In Stock' : 'Out of Stock' }}"
                        data-product-power="{{ $product->power_draw_watts ?? 0 }}">
                        <td>
                            @if($image)
                                <img src="{{ asset($image) }}" alt="{{ $product->name }}" class="part-image">
                            @else
                                <div class="part-image d-flex align-items-center justify-content-center text-muted" style="font-size: 1.2rem;">
                                    <i class="fa fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="part-name">{{ $product->brand }} {{ $product->name }}</div>
                            <div class="part-brand">{{ $product->category->name }}</div>
                        </td>
                        <td class="price-cell">
                            @if($price)
                                JOD {{ number_format($price, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="availability-cell {{ $isInStock ? 'in-stock' : 'out-of-stock' }}">
                            {{ $isInStock ? 'In Stock' : 'Out of Stock' }}
                        </td>
                        <td class="store-cell">
                            {{ $storeName ?? '—' }}
                        </td>
                        <td>
                            <button type="button" class="select-btn" data-product-id="{{ $product->id }}">Select</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-results">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            No products available for this category.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    'use strict';

    const STORAGE_KEY = 'pc_builder_selections';
    const categoryId = {{ $category->id }};
    const maxQty = {{ $maxQty ?? 1 }};

    function loadSelections() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {};
        } catch (e) {
            return {};
        }
    }

    function saveSelections(selections) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(selections));
    }

    function selectProduct(tr) {
        const productId = parseInt(tr.dataset.productId);
        const name = tr.dataset.productName;
        const price = parseFloat(tr.dataset.productPrice) || 0;
        const image = tr.dataset.productImage;
        const store = tr.dataset.productStore;
        const availability = tr.dataset.productAvailability;
        const power = parseFloat(tr.dataset.productPower) || 0;

        const selections = loadSelections();
        let catSelections = selections[categoryId] || [];

        if (maxQty === 1) {
            catSelections = [{ productId, name, price, image, store, availability, power }];
        } else {
            const alreadySelected = catSelections.some(s => s.productId === productId);
            if (alreadySelected) {
                Swal.fire({ icon: 'info', title: 'Already selected', text: 'This item is already in your build.' });
                return;
            }
            if (catSelections.length >= maxQty) {
                Swal.fire({ icon: 'info', title: 'Limit reached', text: 'You can select up to ' + maxQty + ' items for this category.' });
                return;
            }
            catSelections.push({ productId, name, price, image, store, availability, power });
        }

        selections[categoryId] = catSelections;
        saveSelections(selections);

        window.dispatchEvent(new StorageEvent('storage', {
            key: STORAGE_KEY,
            newValue: JSON.stringify(selections)
        }));

        window.location.href = '{{ route("builder.index") }}';
    }

    document.querySelectorAll('.select-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const tr = this.closest('tr');
            if (!tr) {
                console.error('Select button is not inside a table row.');
                return;
            }
            try {
                selectProduct(tr);
            } catch (err) {
                console.error('Failed to select product:', err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Oops', text: 'Could not add this part. Please try again.' });
                } else {
                    alert('Could not add this part. Please try again.');
                }
            }
        });
    });

    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#parts-tbody tr').forEach(tr => {
            const name = tr.dataset.productName.toLowerCase();
            const brand = tr.querySelector('.part-brand').textContent.toLowerCase();
            const match = name.includes(query) || brand.includes(query);
            tr.style.display = match ? '' : 'none';
        });
    });

})();
</script>
@endsection
