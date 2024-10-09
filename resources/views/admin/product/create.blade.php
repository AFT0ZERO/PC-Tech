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

            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="form-floating form-floating-outline ">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            <label for="exampleFormControlInput1">Name</label>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    <select name="category" class="form-select" aria-label="Default select example" id="exampleFormControlSelect1">
                        @foreach ($categories as $category )
                            <option value={{$category->id}}>{{$category->name}}</option>
                        @endforeach
                    </select>
{{--                    <div class="row">--}}
                        <div id="key-value-fields" class="row">
                            <div class="mb-3 col-6">
                                <label for="key" class="form-label">Key</label>
                                <input type="text" name="key[]" class="form-control" required>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="value" class="form-label">Value</label>
                                <input type="text" name="value[]" class="form-control" required>
                            </div>
                        </div>
{{--                    </div>--}}


                    <button type="button" id="add-key-value" class="btn btn-secondary">Add More</button>
                    <br><br>

                    <button class="btn btn-success">ADD +</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to dynamically add more key-value fields
        document.getElementById('add-key-value').addEventListener('click', function() {
            const newField = document.createElement('div');
            newField.classList.add('row');
            newField.innerHTML = `
                <div class="mb-3 col-6">
                    <label for="key" class="form-label">Key</label>
                    <input type="text" name="key[]" class="form-control" required>
                </div>
                <div class="mb-3 col-6">
                    <label for="value" class="form-label">Value</label>
                    <input type="text" name="value[]" class="form-control" required>
                </div>
            `;
            document.getElementById('key-value-fields').appendChild(newField);
        });
    </script>
@endsection
