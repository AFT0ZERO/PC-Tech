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
            <h5 class="card-header"><strong>Edit FAQ</strong></h5>

            <form action="{{ route('faq.update', $faq->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body demo-vertical-spacing demo-only-element">

                        <div class="form-floating form-floating-outline">
                            <input type="text" name="question" value="{{ old('question', $faq->question) }}" class="form-control @error('question') is-invalid @enderror" id="exampleFormControlInput1" placeholder="Name">
                            <label for="exampleFormControlInput1">Question</label>
                            @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline ">
                            <input type="text" name="answer" value="{{ old('answer', $faq->answer) }}" class="form-control @error('answer') is-invalid @enderror" id="exampleFormControlInput2" placeholder="Family">
                            <label for="exampleFormControlInput2">Answer</label>
                            @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>





                    <button class="btn btn-success dlt-btn-t">Edit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Wait until the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Select all delete buttons with the class 'dlt-btn-t'
            const deleteButtons = document.querySelectorAll('.dlt-btn-t');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the form from submitting
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Edit it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form if the user confirms
                            button.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
