@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('product.index') }}" class="btn btn-primary p-2 float-start">Back</a>
        </button>
    </div>
    <div class="card mt-4">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="card-header">
           <b>Product Info</b>
        </h2>
        <div class="card-body">
            <h5 class="card-title mb-3"><b>Name : </b>  {{$product->name}}</h5>

            <h5 class="card-title mb-2"><b>Product images</b></h5>
            @if($product->images->isNotEmpty())
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach($product->images as $pimg)
                        <a href="{{ asset($pimg->image) }}" target="_blank" rel="noopener">
                            <img src="{{ asset($pimg->image) }}" alt="{{ $product->name }}" class="rounded border" style="width: 120px; height: 120px; object-fit: cover;">
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-4">No images uploaded yet. <a href="{{ route('product.upload.images', $product->id) }}">Add images</a></p>
            @endif

            <h5 class="card-title mb-3"><b>Brand : </b>  {{$product->brand}}</h5>
            <h5 class="card-title mb-3"><b>Description : </b>  {{$product->smallDescription}}</h5>
            @if($descriptions != null)
            @foreach($descriptions as $key => $value)
                <p class="card-text"><b>{{$key}} : </b> {{$value}}</p>
            @endforeach
            @endif
            <p class="card-text"><b>Updated At : </b> {{$product->updated_at->format('y-m-d')}} </p>
            <p class="card-text"><b>Created At : </b> {{$product->created_at->format('y-m-d')}} </p>

            <hr class="mt-4 mb-4">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">
                    <i class="bx bx-history text-primary me-1"></i>
                    <b>Price History</b>
                </h4>
                @if($priceHistory && $priceHistory->count() > 0)
                    <span class="badge bg-label-primary fs-6">{{ $priceHistory->count() }} {{ Str::plural('Store', $priceHistory->count()) }}</span>
                @endif
            </div>

            @if($priceHistory && $priceHistory->count() > 0)
                <div class="row g-3 mb-4">
                    @foreach($priceHistory as $history)
                        @php
                            $storeHistory = $allHistory->where('store_id', $history->store_id)->sortByDesc('scraped_at')->values();
                            $previousPrice = $storeHistory->count() > 1 ? $storeHistory->get(1)->price : null;
                            $priceDiff = $previousPrice ? $history->price - $previousPrice : null;
                        @endphp
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-primary">{{ $history->store_name }}</h6>
                                            <small class="text-muted">
                                                <i class="bx bx-time-five me-1"></i>
                                                {{ \Carbon\Carbon::parse($history->scraped_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                        <a href="{{ $history->store_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-link-external me-1"></i>Visit
                                        </a>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="fs-4 fw-bold text-dark">{{ number_format($history->price, 2) }}</span>
                                        <span class="badge bg-label-secondary">{{ $history->currency }}</span>
                                        @if($priceDiff !== null)
                                            @if($priceDiff < 0)
                                                <span class="badge bg-label-success ms-1">
                                                    <i class="bx bx-trending-down me-1"></i>{{ number_format(abs($priceDiff), 2) }} down
                                                </span>
                                            @elseif($priceDiff > 0)
                                                <span class="badge bg-label-danger ms-1">
                                                    <i class="bx bx-trending-up me-1"></i>{{ number_format($priceDiff, 2) }} up
                                                </span>
                                            @else
                                                <span class="badge bg-label-secondary ms-1">
                                                    <i class="bx bx-minus me-1"></i>No change
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <button
                                        class="btn btn-sm btn-outline-secondary w-100"
                                        data-bs-toggle="modal"
                                        data-bs-target="#historyModal-{{ $history->store_id }}">
                                        <i class="bx bx-bar-chart-alt-2 me-1"></i>
                                        View Full History ({{ $storeHistory->count() }} {{ Str::plural('record', $storeHistory->count()) }})
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for Full Price History -->
                        <div class="modal fade text-start" id="historyModal-{{ $history->store_id }}" tabindex="-1" aria-labelledby="historyModalLabel-{{ $history->store_id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="historyModalLabel-{{ $history->store_id }}">
                                            <i class="bx bx-history me-2"></i>Price History &mdash; {{ $history->store_name }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="ps-3">#</th>
                                                        <th>Price</th>
                                                        <th>Currency</th>
                                                        <th>Change</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($storeHistory as $idx => $historyItem)
                                                        @php
                                                            $prev = $storeHistory->get($idx + 1);
                                                            $diff = $prev ? $historyItem->price - $prev->price : null;
                                                        @endphp
                                                        <tr>
                                                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                                            <td class="fw-semibold">{{ number_format($historyItem->price, 2) }}</td>
                                                            <td><span class="badge bg-label-secondary">{{ $historyItem->currency }}</span></td>
                                                            <td>
                                                                @if($diff === null)
                                                                    <span class="text-muted small">—</span>
                                                                @elseif($diff < 0)
                                                                    <span class="text-success small"><i class="bx bx-down-arrow-alt"></i> {{ number_format(abs($diff), 2) }}</span>
                                                                @elseif($diff > 0)
                                                                    <span class="text-danger small"><i class="bx bx-up-arrow-alt"></i> {{ number_format($diff, 2) }}</span>
                                                                @else
                                                                    <span class="text-muted small">—</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-muted small">{{ \Carbon\Carbon::parse($historyItem->scraped_at)->format('Y-m-d H:i') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center gap-2 mt-3">
                    <i class="bx bx-info-circle fs-5"></i>
                    <span>No scraped price history available for this product yet.</span>
                </div>
            @endif

        </div>
    </div>
@endsection
