@extends('frontend.layouts.app')

@section('content')
    <div class="container card my-5">
        <div class="col-md-12 text-center py-5">
            <h4>Thank you for your order!. Your order number is {{ $order->id }}. Please Check Your Email For Confirmation. </h4>
        </div>
    </div>
@endsection
