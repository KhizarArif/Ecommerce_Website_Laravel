<footer class="bg-dark mt-5">
    <div class="container-fluid footer py-6 my-6 mb-0 wow bounceInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h1 class="text-primary">Art<span class="text-light">Wings</span></h1>
                        <p class="lh-lg mb-4 text-light"> Art Wings Where art meets precision. Handcrafted resin
                            creations for the modern home, sustainably made with love. </p>

                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4 text-light">Special Facilities</h4>
                        <div class="d-flex flex-column align-items-start">
                            <a class="mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Jewellery</a>
                            <a class="mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Wall Clock</a>
                            <a class="mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Costers</a>
                            <a class="mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Candles</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="mb-4 text-light">Contact Us</h4>
                        <div class="d-flex flex-column align-items-start">
                            <p><i class="fa fa-map-marker-alt text-primary me-2"></i> Faisalabad, Pakistan</p>
                            <p><i class="fa fa-phone-alt text-primary me-2"></i> (+92) 324 9660909</p>
                            <p><i class="fas fa-envelope text-primary me-2"></i> info@example.com</p>
                            <p><i class="fa fa-clock text-primary me-2"></i> 24/7 Hours Service</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-area bg-light text-dark">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="copy-right text-center">
                        <p> © Copyright 2022 Amazing Shop. All Rights Reserved </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('front-assets/js/custom.js') }}"></script>

{{-- Carousel  --}}
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

{{-- JS Libraries  --}}
<script src="{{ asset('front-assets/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('front-assets/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('front-assets/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('front-assets/lib/counterup/counterup.min.js') }}"></script>
<script src="{{ asset('front-assets/lib/lightbox/js/lightbox.min.js') }}"></script>
<script src="{{ asset('front-assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front-assets/js/main.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function addToCart(id) {
        console.log("id: ", id);
        $.ajax({
            url: "{{ route('front.addToCart') }}",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.status == true) {
                    window.location.href = "{{ route('front.cart') }}";
                } else {
                    console.log("Errrorr");
                    alert(response.message);
                }
            }
        })
    }

    function addToWishList(id) {
        console.log("id: ", id);
        $.ajax({
            url: "{{ route('frontend.addToWishlist') }}",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.status == true) {
                    $("#wishlistModal .modal-body").html(response.message);
                    $("#wishlistModal").modal('show');
                } else {
                    window.location.href = "{{ route('account.login') }}";
                }
            }
        })
    }
</script>
<script>
    window.onscroll = function() {
        myFunction()
    };

    var navbar = document.getElementById("navbar");
    var sticky = navbar.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navbar.classList.add("sticky")
        } else {
            navbar.classList.remove("sticky");
        }
    }
</script>
@yield('customJs')
</body>

</html>
