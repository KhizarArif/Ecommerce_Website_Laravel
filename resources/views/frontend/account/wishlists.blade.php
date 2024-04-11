<?php
use function App\Helpers\getProductImage;

?>

@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">My Wishlists</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2"> My Wishlists </h2>
                        </div>
                        <div class="card-body p-4"> 
                                    @if ($wishlists->isNotEmpty())
                                        @foreach ($wishlists as $wishlist)
                                            <div
                                                class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                                <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                                    <a class="d-block flex-shrink-0 mx-auto me-sm-4"
                                                        href="{{ route('front.product', $wishlist->product->slug) }}"
                                                        style="width: 10rem;">
                                                        @php
                                                            $productImage = getProductImage($wishlist->product_id);
                                                        @endphp

                                                        @if (!empty($productImage->first()->image))
                                                            <img class="img-fluid"
                                                                src="{{ asset('uploads/product/small/' . $productImage->first()->image) }}">
                                                        @endif
                                                    </a>
                                                    <div class="pt-2">
                                                        <a href="{{ route('front.product', $wishlist->product->slug) }}">
                                                            <h2 class="product-title fs-base mb-2">
                                                                {{ $wishlist->product->title }}</h2>
                                                        </a>
                                                        <div class="fs-lg text-accent pt-2">
                                                            <span class="h5"> <strong> ${{ $wishlist->product->price }}
                                                                </strong> </span>
                                                            @if ($wishlist->product->compare_price > 0)
                                                                <span class="h6 text-underline">
                                                                    <del>${{ $wishlist->product->compare_price }}</del></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                                    <button class="btn btn-outline-danger btn-sm" type="button"
                                                        onclick="removeWishlist({{ $wishlist->id }})"><i
                                                            class="fas fa-trash-alt me-2"></i>Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div>
                                            <h3 class="text-center text-info light-font  "> Your Wishlist is empty!!.. </h3>
                                        </div>
                                    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        function removeWishlist(id) {
            $.ajax({
                url: "{{ route('account.removeFromWishlist') }}",
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    window.location.href = "{{ route('account.wishlists') }}";
                }
            })
        }
    </script>
@endsection
