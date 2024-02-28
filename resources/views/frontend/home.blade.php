@extends('frontend.layouts.app')

@section('content')
<main>
       <section>
        <div class="container bg-light mt-5 ">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-7 col-md-12">
                        <h1 class=" mb-4 animated bounceInDown"> Art wings offers a <span class="text-primary">
                                hassle-free and convenient way </span> to enhance your interior design. </h1>
                        <a href=""
                            class="btn btn-primary border-0 rounded-pill py-3 px-4 px-md-5 me-4 animated bounceInLeft"> Book your
                            order </a>
                        <a href=""
                            class="btn btn-primary border-0 rounded-pill py-3 px-4 px-md-5 animated bounceInLeft">All
                            products</a>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <img src="{{ asset('front-assets/images/hero.png') }}" class="img-fluid rounded animated zoomIn" alt="">
                    </div>
                </div>
            </div>
        </div>
       </section>


        <section class="section-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-3">
            <div class="container">
                <div class="section-title">
                    <h2>Categories</h2>
                </div>
                <div class="row pb-3">
                <?php
                    use function App\Helpers\getCategories;

                        $categories = getCategories(); 
                        ?>
                    @if ($categories->isNotEmpty())
                    @foreach ($categories as $category )
                    <div class="col-lg-3">
                        <div class="cat-card">
                            <div class="left">
                                <img src="{{ asset('uploads/category/thumb/'.$category->image)}}" alt=""
                                    class="img-fluid">
                            </div>
                            <div class="right">
                                <div class="cat-data">
                                    <h2> {{ $category->name }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </section>

        <section class="section-4 pt-5">
            <div class="container">
                <div class="section-title">
                    <h2>Featured Products</h2>
                </div>
                <div class="row pb-3">
                    @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $featuredProduct )
                    <div class="col-md-3">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('front.product', $featuredProduct->slug) }}" class="product-img">
                                    @if (!empty($featuredProduct->productImages->first()->image))
                                    <img class="card-img-top"
                                        src="{{ asset('uploads/product/small/'.$featuredProduct->productImages->first()->image)}}">
                                    @endif
                                </a>
                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                <div class="product-action">
                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $featuredProduct->id }}')" >
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                </div>
                            </div>
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="product.php"> {{ $featuredProduct->title }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>$ {{ $featuredProduct->price }} </strong></span>
                                    @if ($featuredProduct->compare_price > 0)
                                    <span class="h6 text-underline"><del>$
                                            {{ $featuredProduct->compare_price }}</del></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif


                </div>
            </div>
        </section>

        <section class="section-4 pt-5">
            <div class="container">
                <div class="section-title">
                    <h2>Latest Produsts</h2>
                </div>
                <div class="row pb-3">
                    @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $featuredProduct )
                    <div class="col-md-3">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="{{ route('front.product', $featuredProduct->slug) }}" class="product-img">
                                    @if (!empty($featuredProduct->productImages->first()->image))
                                    <img class="card-img-top"
                                        src="{{ asset('uploads/product/small/'.$featuredProduct->productImages->first()->image)}}">
                                    @endif
                                </a>
                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                <div class="product-action">
                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $featuredProduct->id }}')"  >
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                </div>
                            </div>
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="product.php"> {{ $featuredProduct->title }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>$ {{ $featuredProduct->price }} </strong></span>
                                    @if ($featuredProduct->compare_price > 0)
                                    <span class="h6 text-underline"><del>$
                                            {{ $featuredProduct->compare_price }}</del></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection