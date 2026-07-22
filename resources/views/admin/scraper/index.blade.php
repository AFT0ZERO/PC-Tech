@extends('admin.layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-white">Price Scraper Control Panel</h4>
                </div>

                <div class="card-body text-center p-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <i class="bx bx-bot text-primary" style="font-size: 80px;"></i>
                    </div>

                    <h5 class="card-title">Automated Price Updater</h5>
                    <p class="card-text text-muted mb-4">
                        The scraper runs as a background process to collect live prices for all configured parts across supported stores.
                    </p>

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <h6 class="text-uppercase text-muted mb-1" style="font-size: 12px;">Last Run</h6>
                                <strong>{{ $lastRun ? \Carbon\Carbon::parse($lastRun)->diffForHumans() : 'Never' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <h6 class="text-uppercase text-muted mb-1" style="font-size: 12px;">Prices Updated (24H)</h6>
                                <strong>{{ $recentCount }}</strong>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('scraper.run') }}" method="POST">
                        @csrf
                        <div class="mb-3 w-75 mx-auto text-start">
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label fw-semibold mb-0">Target Stores</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.querySelector('select[name=\'stores[]\']').value=null">Clear</button>
                            </div>
                            <select name="stores[]" class="form-select" multiple>
                                @foreach($stores as $store)
                                    <option value="{{ $store->name }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Leave empty to run the scraper for all stores. Hold Ctrl/Cmd to select multiple.</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg px-5 mt-2 rounded-pill shadow-sm" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> Running... Please Wait'; this.disabled=true; this.form.submit();">
                            <i class="bx bx-play-circle me-1"></i> Run Scraper Now
                        </button>
                    </form>
                    <div class="mt-3 text-muted" style="font-size: 0.85rem;">
                        <i class="bx bx-info-circle"></i> Running manually may take several minutes depending on the number of configured stores and products.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
