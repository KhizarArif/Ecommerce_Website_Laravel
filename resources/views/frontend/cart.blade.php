@extends('frontend.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item">Cart</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-9 pt-4">
    <div class="container">
        <div class="row">
    
                <div class="col-md-12">
                    <div class="alert  d-none alert-dismissible fade show" role="alert"> 
                        {{ Session::get("success") }}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @if (Session::has("error"))
                <div class="col-md-12">
                    <div class="alert  d-none alert-dismissible fade show" role="alert"> 
                        {{ Session::get("error") }}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>
                    </div>
                </div>
            @endif
            @if (Cart::count() > 0)
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($contentCart))
                                @foreach ($contentCart as $item )
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center ">  
                                            @if ($item->options->productImage)
                                            <img
                                                src="{{ asset('uploads/product/large/'. $item->options->productImage->image) }}">
                                            @endif
                                            <h2> {{ $item->name }}</h2>
                                        </div>
                                    </td>
                                    <td> Rs {{ $item->price }}</td>
                                    <td>
                                        <div class="input-group quantity mx-auto" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub rounded" data-id="{{$item->rowId}}">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm  border-0 text-center" name="qty"
                                                value="{{ $item->qty}}" >
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add rounded" data-id="{{$item->rowId}}" >
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        Rs {{ $item-> price * $item->qty }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger rounded" onclick="deleteToCart('{{ $item->rowId }}')"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif



                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cart-summery">
                        <div class="card-body">
                            <div class="sub-title">
                                <h2 class="bg-white">Cart Summery</h3>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>Rs {{Cart::subtotal()}}</div>
                            </div> 
                            <div class="pt-3">
                                <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block rounded w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                    
                </div>

            @else
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title d-flex justify-content-center align-items-center py-2 ">Your Cart is Empty!. </h5>
                        </div>
                    </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $('.add').click(function(){
            var qtyElement = $(this).parent().prev();
            var qtyValue = parseInt(qtyElement.val());
            console.log(qtyValue);
            if($('div').hasClass('alert-danger')) {
                return; 
            }

            if(qtyValue < 10 ){
                qtyElement.val(qtyValue + 1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }

        });

        $('.sub').click(function(){
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if(qtyValue > 1){
                qtyElement.val(qtyValue - 1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty);
            }
        });

        function updateCart(rowId, qty){
            console.log("rowId", rowId);
            $.ajax({
                url: "{{ route('front.updateCart') }}",
                type: "POST",
                data: {
                    rowId: rowId,
                    qty: qty
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    var $alert = $('.alert');
                    if (response.status) {   
                        $alert.removeClass('alert-danger');
                        $alert.removeClass('d-none').addClass('alert-success').text(response.message);
                        setTimeout(function() {
                            $alert.addClass('d-none'); 
                        }, 2000); 

                        window.location.href = "{{ route('front.cart') }}";
                   
                    } else { 
                    $alert.removeClass('d-none').addClass('alert-danger').text(response.message);
                     setTimeout(function() {
                        $alert.addClass('d-none'); 
                        }, 2000);
                    }
                }
            })
        }

        function deleteToCart(rowId){
            if(confirm("Are you sure You want to delete ?")){
                $.ajax({
                url: "{{ route('front.deleteToCart') }}",
                type: "delete",
                data: { rowId: rowId },
                dataType: 'json',
                    success: function(response) {
                        window.location.href = "{{ route('front.cart') }}";
                    }
                })

            }
        }

       
    </script>
@endsection