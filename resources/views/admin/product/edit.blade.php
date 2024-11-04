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
            <h5 class="card-header"><strong>Edit Product</strong></h5>

            <form action="{{ route('product.update' , $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="form-floating form-floating-outline ">
                        <input type="text" name="name" value="{{$product->name}}" class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                        <label for="exampleFormControlInput1">Name</label>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating-outline">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="exampleFormControlTextarea1" placeholder="Description">{{$product->smallDescription }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class=" form-floating-outline ">
                        <label for="exampleFormControlInput3">Brand</label>
                        <input type="text" name="brand" value="{{ $product->brand }}" class="form-control @error('brand') is-invalid @enderror" id="exampleFormControlInput3" placeholder="Asus">
                        @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <select name="category" class="form-select" aria-label="Default select example" id="exampleFormControlSelect1">
                        @foreach ($categories as $category )
                            <option value={{$category->id}} @if($category->id == $product->category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                    <div id="key-value-container">
                        @foreach($descriptions as $key => $value)
                            <div class="row key-value-fields">
                                <div class="mb-3 col-6">
                                    <label for="key" class="form-label">Specification</label>
                                    <input type="text" name="key[]" value="{{$key}}" class="form-control" required>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="value" class="form-label">Value</label>
                                    <input type="text" name="value[]" value="{{$value}}" class="form-control" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-key-value" class="btn btn-secondary">Add More</button>
                    <br><br>
                    @foreach($stores as $store)
                        <div class="store-section mb-4">
                            <div class="col-12">
                                <p class="fs-5 fw-bold">{{$store->name}}</p>
                            </div>

                            <div id="store-entries-{{$store->id}}">
                                @foreach($store->products as $index => $product)
                                    <div class="row mb-2">
                                        <input type="hidden" name="store_id[]" value="{{$store->id}}">
                                        <input type="hidden" name="product_id[{{$store->id}}][]" value="{{$product->id}}">

                                        <div class="mb-3 col-4">
                                            <label for="price-{{$store->id}}-{{$index}}" class="form-label">Price</label>
                                            <input type="text" name="price[{{$store->id}}][]" value="{{$product->pivot->product_price}}" class="form-control" id="price-{{$store->id}}-{{$index}}" required>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label for="url-{{$store->id}}-{{$index}}" class="form-label">URL</label>
                                            <input type="url" name="url[{{$store->id}}][]" value="{{$product->pivot->product_url}}" class="form-control" id="url-{{$store->id}}-{{$index}}" required>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status[{{$store->id}}][]" class="form-select" >
                                                <option value="in stock" @if($product->pivot->product_status == 'in stock') selected @endif>In Stock</option>
                                                <option value="out of stock" @if($product->pivot->product_status == 'out of stock') selected @endif>Out of Stock</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                    <button class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to dynamically add more key-value fields
        document.getElementById('add-key-value').addEventListener('click', function() {
            const newField = document.createElement('div');
            newField.classList.add('row', 'key-value-fields');
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
            document.getElementById('key-value-container').appendChild(newField);
        });
    </script>
@endsection
