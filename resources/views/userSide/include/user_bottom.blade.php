

<!-- Vendors JS -->
<script src="{{asset('assets/asset/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/asset/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/asset/js/vendor/jquery-migrate-3.3.2.min.js')}}"></script>
<script src="{{asset('assets/asset/js/vendor/modernizr-3.11.2.min.js')}}"></script>
<script src="{{asset('assets/asset/js/faq.js')}}"></script>

<!-- Plugins JS -->
<script src="{{asset('assets/asset/js/plugins/jquery-ui.min.js')}}"></script>
{{--silder --}}
<script src="{{asset('assets/asset/js/plugins/slick.js')}}"></script>
{{--silder --}}
<script src="{{asset('assets/asset/js/plugins/countdown.js')}}"></script>
<script src="{{asset('assets/asset/js/plugins/scrollup.js')}}"></script>
<script src="{{asset('assets/asset/js/plugins/elevateZoom.js')}}"></script>

<!-- Main Activation JS -->
<script src="{{asset('assets/asset/js/main.js')}}"></script>

</script>

<script>
    // add and remove function (favorite) in card
    $(document).on('click', '.add-to-favorite', function(e) {
        e.preventDefault();

        let productId = $(this).data('product-id');
        let heartIcon = $(this).find('i'); // Get the heart icon inside the clicked link

        $.ajax({
            url: "{{ route('favorite.toggle', '') }}/" + productId,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                // Toggle the heart icon color
                if (response.status === 'added') {
                    heartIcon.addClass('favorite-added');
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Favorites',
                        text: 'The product has been added to your favorites!',
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else if (response.status === 'removed') {
                    heartIcon.removeClass('favorite-added');
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed from Favorites',
                        text: 'The product has been removed from your favorites!',
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                console.error("Error: " + xhr.responseText); // Handle errors
            }
        });
    });

    // remove function in off-canvas (favorite)
    $(document).on('click', '.remove-favorite', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');

        $.ajax({
            url: `/favorites/remove/${productId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    loadFavorites(); // Reload the favorites list
                    Swal.fire({
                        title: 'Removed',
                        text: response.message,
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                console.error("Error: " + xhr.responseText);
            }
        });
    });

</script>


<script>
    $(document).on('click', '.offcanvas-toggle', function(e) {
        e.preventDefault();
        loadFavorites();  // Load favorite products into the offcanvas when opened
    });

    function loadFavorites() {
        $.ajax({
            url: "{{ route('favorite.list') }}",  // URL to get favorite products
            type: "GET",
            success: function(response) {
                $('#offcanvas-favorites-content').html(response); // Populate the offcanvas with favorite products
            },
            error: function(xhr) {
                console.error("Error: " + xhr.responseText); // Handle errors
            }
        });
    }
</script>
