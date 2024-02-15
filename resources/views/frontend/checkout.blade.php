@extends('frontend.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home')}}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop')}}">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form action="" id="orderForm" name="orderForm " method="POST">
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            placeholder="First Name" value="{{ $customerAddress ? $customerAddress->first_name : "" }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $customerAddress ? $customerAddress->last_name : "" }}"
                                            placeholder="Last Name">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control" value="{{ $customerAddress ? $customerAddress->email : "" }}"
                                            placeholder="Email">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-select">
                                            <option value="">Select a Country</option>
                                            @foreach ($countries as $country ) 
                                            <option {{ $customerAddress && $customerAddress->country_id == $country->id ? "selected" : "" }} value="{{ $country->id }}"> {{ $country->name }} </option>
                                            @endforeach
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" 
                                            class="form-control"> {{ $customerAddress ? $customerAddress->address : "" }} </textarea>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="appartment" id="appartment" class="form-control" value="{{ $customerAddress ? $customerAddress->appartment : "" }}"
                                            placeholder="Apartment, suite, unit, etc. (optional)">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" class="form-control" value="{{ $customerAddress ? $customerAddress->city : "" }}"
                                            placeholder="City">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="state" id="state" class="form-control" value="{{ $customerAddress ? $customerAddress->state : "" }}"
                                            placeholder="State">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ $customerAddress ? $customerAddress->zip : "" }}">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ $customerAddress ? $customerAddress->mobile : "" }}"
                                            placeholder="Mobile No.">
                                        <p></p>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="notes" id="notes" cols="30" rows="2"
                                            placeholder="Order Notes (optional)" class="form-control" > {{ $customerAddress ? $customerAddress->notes : "" }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>
                    <div class="card cart-summery">
                        <div class="card-body">
                            @foreach (Cart::content() as $item )
                            <div class="d-flex justify-content-between pb-2">
                                <div class="h6"> {{ $item->name }} </div>
                                <div class="h6"> ${{ $item->price }} </div>
                            </div>
                            @endforeach

                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong> ${{ Cart::subtotal() }} </strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong>$0</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong>${{ Cart::subtotal() }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="card payment-form ">
                        <h3 class="card-title h5 mb-3">Payment Method</h3>
                        <div>
                            <input type="radio" id="payment_method_one" name="payment_method" value="cod" checked>
                            <label for="cod"> Cash on delivery </label>
                        </div>
                        <div class="my-2">
                            <input type="radio" id="payment_method_two" name="payment_method" value="stripe">
                            <label for="stripe"> Stripe </label>
                        </div>
                        <div class="card-body p-0 d-none my-2" id="card-payment-form">
                            <div class="mb-3">
                                <label for="card_number" class="mb-2">Card Number</label>
                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number"
                                    class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">CVV Code</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-dark btn btn-block w-100"> Pay Now </button>
                    </div>

                    <!-- CREDIT CARD FORM ENDS HERE -->

                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customJs')
<script type="text/javascript">
$("#payment_method_one").click(function() {
    if ($(this).is(':checked') == true) {
        $("#card-payment-form").addClass("d-none");
    }
});
$("#payment_method_two").click(function() {
    if ($(this).is(':checked') == true) {
        $("#card-payment-form").removeClass("d-none");
    }
});

$('#orderForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('front.processCheckout') }}",
        type: "POST",
        data: $(this).serializeArray(),
        dataType: "json",
        success: function(response) {
            console.log(response); 
            $('button[type="submit"]').prop('disabled', true);
            if (response.status == false) {
                $('button[type="submit"]').prop('disabled', false);
                $.each(response.error, function(key, value) {
                    $('#' + key).siblings('p').addClass('text-danger').html(value);
                });
            } else {
                $.each(response.errors, function(key, value) {
                    $('#' + key).siblings('p').removeClass('text-danger').html('');
                });
                window.location.href = "{{ route('front.thankyou', ['id' => 1]) }}";

            }
        }
    });
});
</script>
@endsection