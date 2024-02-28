<footer class="bg-dark mt-5">
    <div class="container pb-5 pt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-card">
                    <h3>Get In Touch</h3>
                    <p>No dolore ipsum accusam no lorem. <br>
                        123 Street, New York, USA <br>
                        exampl@example.com <br>
                        000 000 0000</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-card">
                    <h3>Important Links</h3>
                    <ul>
                        <li><a href="about-us.php" title="About">About</a></li>
                        <li><a href="contact-us.php" title="Contact Us">Contact Us</a></li>
                        <li><a href="#" title="Privacy">Privacy</a></li>
                        <li><a href="#" title="Privacy">Terms & Conditions</a></li>
                        <li><a href="#" title="Privacy">Refund Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="footer-card">
                    <h3>My Account</h3>
                    <ul>
                        <li><a href="#" title="Sell">Login</a></li>
                        <li><a href="#" title="Advertise">Register</a></li>
                        <li><a href="#" title="Contact Us">My Orders</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
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
<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js')}}"></script>
<script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js')}}"></script>
<script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js')}}"></script>
<script src="{{ asset('front-assets/js/slick.min.js')}}"></script>
<script src="{{ asset('front-assets/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('front-assets/js/custom.js')}}"></script>
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
                success: function(response) {
                    console.log(response);
                    if(response.status == true){
                        window.location.href = "{{ route('front.cart') }}";
                    }else{
                        console.log("Errrorr");
                        alert(response.message);
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