@extends('admin.layouts.admin')
@section('search')

@endsection
@section('content')
    <div class="text-left">
        <button class="btn ">
            <a href="{{ route('faq.index') }}" class="btn btn-primary p-2 float-start">Back</a>
        </button>
    </div>
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header"><strong>Add FAQs</strong></h5>

            <form action="{{ route('faq.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">

                        <div class="form-floating form-floating-outline ">
                            <input type="text" name="question" value="{{ old('question') }}" class="form-control @error('question') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            <label for="exampleFormControlInput1">Question</label>
                            @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline ">
                            <input type="text" name="answer" value="{{ old('answer') }}" class="form-control @error('answer') is-invalid @enderror" id="exampleFormControlInput2" placeholder="Family">
                            <label for="exampleFormControlInput2">Answer</label>
                            @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    <button class="btn btn-success">ADD +</button>
                </div>
            </form>
        </div>
    </div>
@endsection
