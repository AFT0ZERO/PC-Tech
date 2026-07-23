@extends('userSide.layout.app')

@section('extraHeader')
<style>
    .builds-hero {
        padding: 45px 0 35px;
        margin-bottom: 40px;
    }
    .builds-hero h1 { font-size: 2rem; font-weight: 700; margin-bottom: 6px; }
    .builds-hero p  { opacity: .75; }

    .build-card {
        border: 2px solid #e8e8e8;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow .2s;
    }
    .build-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.1); }

    .build-card .card-header {
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .build-card .card-header h5   { margin: 0; font-size: 1.1rem; font-weight: 700; }
    .build-card .card-header .meta{ font-size: .82rem; opacity: .75; }

    .total-badge {
        background: #28a745;
        color: #fff;
        border-radius: 20px;
        padding: 4px 14px;
        font-weight: 700;
        font-size: .95rem;
        white-space: nowrap;
    }

    .parts-table            { margin-bottom: 0; }
    .parts-table td         { vertical-align: middle; padding: 10px 16px; }
    .parts-table .cat-badge {
        background: #e9ecef;
        color: #495057;
        border-radius: 4px;
        font-size: .78rem;
        padding: 2px 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .parts-table .part-name { font-weight: 500; color: #1a1a2e; }

    .build-notes {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 12px 16px;
        font-size: .88rem;
        color: #6c757d;
        font-style: italic;
    }

    .btn-delete-build { border-color: rgba(255,255,255,.4); color: #fff; font-size: .82rem; }
    .btn-delete-build:hover { background: #dc3545; border-color: #dc3545; color: #fff; }

    .empty-state           { text-align: center; padding: 70px 20px; color: #6c757d; }
    .empty-state .empty-icon{ font-size: 4rem; margin-bottom: 16px; display: block; }
    .empty-state h3        { font-weight: 700; margin-bottom: 10px; }
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="builds-hero">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1><i class="fa fa-list-alt me-2"></i> My Saved Builds</h1>
            <p>{{ $builds->count() }} build{{ $builds->count() !== 1 ? 's' : '' }} saved</p>
        </div>
        <a href="{{ route('builder.index') }}" class="btn btn-warning fw-bold">
            <i class="fa fa-plus me-1"></i> New Build
        </a>
    </div>
</div>

<div class="container mb-5">

    @if($builds->isEmpty())
        <div class="empty-state">
            <span class="empty-icon">🖥️</span>
            <h3>No builds saved yet</h3>
            <p>Start the PC Builder and save your first custom build!</p>
            <a href="{{ route('builder.index') }}" class="btn btn-primary btn-lg mt-2 px-5">
                <i class="fa fa-tools me-2"></i> Go to PC Builder
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($builds as $build)
                <div class="col-12" id="build-row-{{ $build->id }}">
                    <div class="build-card">

                        {{-- Card header --}}
                        <div class="card-header">
                            <div>
                                <h5>{{ $build->name }}</h5>
                                <span class="meta">
                                    <i class="fa fa-calendar me-1"></i>
                                    Saved <x-local-time :date="$build->created_at" format="d M Y, H:i" />
                                    &nbsp;·&nbsp;
                                    {{ $build->products->count() }} part{{ $build->products->count() !== 1 ? 's' : '' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="total-badge">JOD {{ number_format($build->estimatedTotal(), 2) }}</span>
                                <button class="btn btn-sm btn-outline-light btn-delete-build"
                                        onclick="deleteBuild({{ $build->id }}, '{{ addslashes($build->name) }}')"
                                        title="Delete this build">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Parts list --}}
                        @if($build->products->isNotEmpty())
                            <table class="table parts-table">
                                <tbody>
                                    @foreach($build->products as $product)
                                        <tr>
                                            <td style="width:130px;">
                                                <span class="cat-badge">{{ $product->category->name ?? '' }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('singlePage', $product->id) }}"
                                                   class="part-name text-decoration-none">
                                                     {{ $product->brand }} {{ $product->name }}
                                                </a>
                                                @if(($product->pivot->quantity ?? 1) > 1)
                                                    <span class="text-muted">× {{ $product->pivot->quantity }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-3 text-muted fst-italic">No parts recorded for this build.</div>
                        @endif


                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection

@section('scripts')
<script>
function deleteBuild(buildId, buildName) {
    Swal.fire({
        title:             'Delete "' + buildName + '"?',
        text:              'This action cannot be undone.',
        icon:              'warning',
        showCancelButton:  true,
        confirmButtonColor:'#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText:  'Cancel',
    }).then(function (result) {
        if (!result.isConfirmed) return;

        fetch('/builder/' + buildId, {
            method:  'DELETE',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(r => r.json())
        .then(function (data) {
            if (data.success) {
                const row = document.getElementById('build-row-' + buildId);
                if (row) {
                    row.style.transition = 'opacity .4s';
                    row.style.opacity    = '0';
                    setTimeout(function () { row.remove(); }, 400);
                }
                Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, timer: 1500, showConfirmButton: false });
            }
        })
        .catch(function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Could not delete the build. Please try again.' });
        });
    });
}
</script>
@endsection
