@if($favorites->isEmpty())
    <p class="text-center">Your favorites list is empty.</p>
@else
    @foreach($favorites as $favorite)
        <li>
            <a href="{{ route('product.show', $favorite->id) }}" class="image">
                <img src="{{ asset($favorite->images[0]->image) }}" alt="{{ $favorite->name }}">
            </a>
            <div class="content d-flex justify-content-between">
                <a href="{{ route('product.show', $favorite->id) }}" class="title">{{ $favorite->name }}</a>
                <a href="#" class="remove-favorite pr-5" data-product-id="{{ $favorite->id }}">X</a>
            </div>
        </li>
    @endforeach

@endif
