@extends('admin.layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Stat Cards --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between mb-4">
                        <div class="avatar flex-shrink-0 user">
                            <i class='bx bx-user bx-border bx-lg'></i>
                        </div>
                    </div>
                    <p class="mb-1 fs-5">Users</p>
                    <h4 class="card-title mb-3">{{ $user }}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between mb-4">
                        <div class="avatar flex-shrink-0 category">
                            <i class='bx bx-category bx-border bx-lg'></i>
                        </div>
                    </div>
                    <p class="mb-1 fs-5">Categories</p>
                    <h4 class="card-title mb-3">{{ $categories }}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between mb-4">
                        <div class="avatar flex-shrink-0 product">
                            <i class='bx bx-basket bx-border bx-lg'></i>
                        </div>
                    </div>
                    <p class="mb-1 fs-5">Products</p>
                    <h4 class="card-title mb-3">{{ $products }}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between mb-4">
                        <div class="avatar flex-shrink-0 shop">
                            <i class='bx bx-store-alt bx-border bx-lg'></i>
                        </div>
                    </div>
                    <p class="mb-1 fs-5">Stores</p>
                    <h4 class="card-title mb-3">{{ $shop }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="row">
        {{-- Chart 1: Products per Category --}}
        <div class="col-lg-6 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Products per Category</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 2: New Users per Month --}}
        <div class="col-lg-6 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">New Users — Last 6 Months</h5>
                </div>
                <div class="card-body">
                    <canvas id="usersLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="row">
        {{-- Chart 3: Feedback Ratings Distribution --}}
        <div class="col-lg-4 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Feedback Ratings Distribution</h5>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="ratingsChart" style="max-height:280px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 4: Top Rated Products --}}
        <div class="col-lg-8 col-md-12 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Rated Products</h5>
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Hidden data bridge --}}
<div id="dash-data" class="d-none"
     data-category-names="{{ $categoryNames->toJson() }}"
     data-category-counts="{{ $categorycounts->toJson() }}"
     data-user-months="{{ $userMonths->toJson() }}"
     data-user-counts="{{ $userCounts->toJson() }}"
     data-ratings="{{ $ratingsData->toJson() }}"
     data-top-names="{{ $topProductNames->toJson() }}"
     data-top-ratings="{{ $topProductRatings->toJson() }}">
</div>

<script>
(function () {
    const d = document.getElementById('dash-data');
    const parse = key => JSON.parse(d.dataset[key] || '[]');

    const categoryNames  = parse('categoryNames');
    const categoryCounts = parse('categoryCounts');
    const userMonths     = parse('userMonths');
    const userCounts     = parse('userCounts');
    const ratingsData    = parse('ratings');
    const topNames       = parse('topNames');
    const topRatings     = parse('topRatings');

    // ── Chart 1: Products per Category (horizontal bar) ──────────────────────
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: categoryNames,
            datasets: [{
                label: 'Products',
                data: categoryCounts,
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // ── Chart 2: New Users per Month (line) ───────────────────────────────────
    new Chart(document.getElementById('usersLineChart'), {
        type: 'line',
        data: {
            labels: userMonths,
            datasets: [{
                label: 'New Users',
                data: userCounts,
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // ── Chart 3: Ratings Distribution (doughnut) ─────────────────────────────
    new Chart(document.getElementById('ratingsChart'), {
        type: 'doughnut',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                data: ratingsData,
                backgroundColor: [
                    'rgba(239,68,68,0.8)',
                    'rgba(249,115,22,0.8)',
                    'rgba(234,179,8,0.8)',
                    'rgba(34,197,94,0.8)',
                    'rgba(99,102,241,0.8)',
                ],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
            }
        }
    });

    // ── Chart 4: Top Rated Products (horizontal bar) ──────────────────────────
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: topNames,
            datasets: [{
                label: 'Avg Rating',
                data: topRatings,
                backgroundColor: 'rgba(251, 146, 60, 0.75)',
                borderColor: 'rgba(251, 146, 60, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, min: 0, max: 5, ticks: { stepSize: 1 } }
            }
        }
    });
})();
</script>
@endsection
