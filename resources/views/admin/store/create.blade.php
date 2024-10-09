@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('store.index') }}" class="btn btn-primary p-2 float-start">Back</a>
        </button>
    </div>
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header"><strong>Add Store</strong></h5>

            <form action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">

                        <div class="form-floating form-floating-outline ">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            <label for="exampleFormControlInput1">Name</label>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    <div class="form-floating form-floating-outline mb-6">
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        <label class="form-label">Upload Image</label>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-success">ADD +</button>
                </div>
            </form>
        </div>
    </div>
@endsection
