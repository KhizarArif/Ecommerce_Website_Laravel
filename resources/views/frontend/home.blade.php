@extends('frontend.layouts.app')

@section('content')

    <style>
        .event-img {
            position: relative;
        }

        .event-img .event-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--bs-primary);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .event-img:hover .event-overlay {
            opacity: 0.7;
        }

        .event-overlay .search-icon {
            display: none;
        }

        .event-img:hover .search-icon {
            display: block;
        }
    </style>

    {{-- Modal  --}}
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal -->
        <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close rounded" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <main>
        <section>
            <div class="container bg-light mt-5 wow bounceInUp">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-7 col-md-12">
                            <h1 class=" mb-4 animated bounceInDown"> Art wings offers a <span class="text-primary">
                                    hassle-free and convenient way </span> to enhance your interior design. </h1>
                            
                        </div>
                        <div class="col-lg-5 col-md-12">
                            <img src="{{ asset('front-assets/images/hero.png') }}" class="img-fluid rounded animated zoomIn"
                                alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="wow bounceInUp" style="margin-top: 4rem">
            <div class="container-fluid pt-6">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
                            <img src="{{ asset('front-assets/images/artwings_01.jpeg') }}" class="img-fluid rounded" alt="">
                        </div>
                        <div class="col-lg-7 wow bounceInUp" data-wow-delay="0.3s">
                            <small
                                class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">About
                                Us</small>
                            <h1 class="display-5 mb-4">Trusted By 200 + satisfied clients</h1>
                            <p class="mb-4">Welcome to Art Wings by Sidra Munawar, where art meets craftsmanship! We're a
                                handmade business dedicated to creating unique and stunning resin and texture paste-based
                                pieces that add a touch of elegance to any space. From decorative home accents to
                                personalized gifts, our products are carefully crafted with love and attention to detail.
                                Our passion is to provide high-quality, eco-friendly, and sustainable resin creations that
                                inspire and delight. Explore our collection and discover the beauty of handmade resin art!"
                            </p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="section-2 wow bounceInUp">
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
                            <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-3 wow bounceInUp">
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
                        @foreach ($categories as $category)
                            <div class="col-lg-3">
                                <div class="cat-card">
                                    <div class="left">
                                        <img src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt=""
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

        <section class="section-4 pt-5 wow bounceInUp">
            <div class="container">
                <div class="section-title">
                    <h2>Featured Products</h2>
                </div>
                <div class="row pb-3">
                    @if ($featuredProducts->isNotEmpty())
                        @foreach ($featuredProducts as $featuredProduct)
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        <a href="{{ route('front.product', $featuredProduct->slug) }}" class="product-img">
                                            @if (!empty($featuredProduct->productImages->first()->image))
                                                <img class="card-img-top"
                                                    src="{{ asset('uploads/product/small/' . $featuredProduct->productImages->first()->image) }}">
                                            @endif
                                        </a>
                                        <a onclick="addToWishList('{{ $featuredProduct->id }}')" class="whishlist"
                                            href="javascript:void(0)"><i class="far fa-heart"></i></a>

                                        <div class="product-action">
                                            @if ($featuredProduct->track_qty == 'Yes')
                                                @if ($featuredProduct->qty > 0)
                                                    <a class="btn btn-dark rounded" href="javascript:void(0)"
                                                        onclick="addToCart('{{ $featuredProduct->id }}')">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                @else
                                                    <a class="btn btn-dark" href="javascript:void(0)">
                                                        Out Of Stock
                                                    </a>
                                                @endif
                                            @endif
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


        {{-- Latest Products Section Start  --}}
        {{-- <section class="section-4 pt-5 wow bounceInUp">
            <div class="container">
                <div class="section-title">
                    <h2>Latest Produsts</h2>
                </div>
                <div class="row pb-3">
                    @if ($featuredProducts->isNotEmpty())
                        @foreach ($featuredProducts as $featuredProduct)
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        <a href="{{ route('front.product', $featuredProduct->slug) }}" class="product-img">
                                            @if (!empty($featuredProduct->productImages->first()->image))
                                                <img class="card-img-top"
                                                    src="{{ asset('uploads/product/small/' . $featuredProduct->productImages->first()->image) }}">
                                            @endif
                                        </a>
                                        <a onclick="addToWishList('{{ $featuredProduct->id }}')" class="whishlist"
                                            href="javascript:void(0)"><i class="far fa-heart"></i></a>

                                        <div class="product-action">
                                            @if ($featuredProduct->track_qty == 'Yes')
                                                @if ($featuredProduct->qty > 0)
                                                    <a class="btn btn-dark rounded" href="javascript:void(0)"
                                                        onclick="addToCart('{{ $featuredProduct->id }}')">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                @else
                                                    <a class="btn btn-dark rounded" href="javascript:void(0)">
                                                        Out Of Stock
                                                    </a>
                                                @endif
                                            @endif
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
        </section> --}}
        {{-- Latest Products Section End  --}}

        {{-- Exhibitions Section Start --}}
        <!-- Events Start -->
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small
                    class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">
                    Latest Events
                </small>
                <h1 class="display-5 mb-5">Our Social & Professional Events Gallery</h1>
            </div>
            <div class="tab-class text-center">
                @if ($exhibitions->isNotEmpty())
                    <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp"
                        data-wow-delay="0.1s">
                        <li class="nav-item p-2">
                            <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill active"
                                data-bs-toggle="pill" href="#tab-1">
                                <span class="text-dark" style="width: 150px;"> All Events</span>
                            </a>
                        </li>
                        @foreach ($exhibitions as $index => $exhibition)
                            <li class="nav-item p-2">
                                <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill"
                                    data-bs-toggle="pill" href="#tab-{{ $index + 2 }}">
                                    <span class="text-dark" style="width: 150px;"> {{ $exhibition->name }} </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">

                        </div>
                    </div>

                    @foreach ($exhibitions as $index => $exhibition)
                        <div id="tab-{{ $index + 2 }}" class="tab-pane fade show p-0">
                            <div class="row g-4">

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Events End -->
        {{-- Exhibitions Section End  --}}


        {{-- Testimonial Section Start --}} 
            <div class="container-fluid py-6">
                <div class="container">
                    <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                        <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Testimonial</small>
                        <h1 class="display-5 mb-5">What Our Customers says!</h1>
                    </div>
                    <div class="owl-carousel owl-theme testimonial-carousel testimonial-carousel-1 mb-4 wow bounceInUp" data-wow-delay="0.1s">
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-2.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-3.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-4.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                    </div>
                    <div class="owl-carousel testimonial-carousel testimonial-carousel-2 wow bounceInUp" data-wow-delay="0.3s">
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-1.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-2.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-3.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                        <div class="testimonial-item rounded bg-light">
                            <div class="d-flex mb-3">
                                <img src="img/testimonial-4.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                                <div class="position-absolute" style="top: 15px; right: 20px;">
                                    <i class="fa fa-quote-right fa-2x"></i>
                                </div>
                                <div class="ps-3 my-auto">
                                    <h4 class="mb-0">Person Name</h4>
                                    <p class="m-0">Profession</p>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="d-flex">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <p class="fs-5 m-0 pt-3">Lorem ipsum dolor sit amet elit, sed do eiusmod tempor ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        {{-- Testimonial Section End --}}

    </main>
@endsection


@section('customJs')
    <script>
        $(document).ready(function() {
            // Ensure tab-1 is selected by default
            $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("href"); // activated tab
                if (target === "#tab-1") {
                    loadTabContent(1);
                } else {
                    loadTabContent(target.replace('#tab-', ''));
                }
            });

            function loadTabContent(tabIndex) {
                var tabPane = $("#tab-" + tabIndex);
                tabPane.find('.row.g-4').empty();

                if (tabIndex == 1) {
                    @foreach ($exhibitions as $exhibition)
                        @foreach ($exhibition->exhibitionImages->take(2) as $image)
                            tabPane.find('.row.g-4').append(`
                        <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.1s">
                            <div class="event-img position-relative">
                                <img class="img-fluid rounded w-100" src="{{ asset('uploads/exhibition/small/' . $image->image) }}" alt="{{ $exhibition->name }}">
                                <div class="event-overlay d-flex flex-column p-4">
                                    <h4 class="me-auto">{{ $exhibition->name }}</h4>
                                    <a href="{{ asset('uploads/exhibition/small/' . $image->image) }}" data-lightbox="event-{{ $image->id }}" class="my-auto"><i class="fas fa-search-plus text-dark fa-2x"></i></a>
                                </div>
                            </div>
                        </div>
                    `);
                        @endforeach
                    @endforeach
                } else {
                    @foreach ($exhibitions as $index => $exhibition)
                        if (tabIndex == {{ $index + 2 }}) {
                            @foreach ($exhibition->exhibitionImages as $image)
                                tabPane.find('.row.g-4').append(`
                            <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.1s">
                                <div class="event-img position-relative">
                                    <img class="img-fluid rounded w-100" src="{{ asset('uploads/exhibition/small/' . $image->image) }}" alt="{{ $exhibition->name }}">
                                    <div class="event-overlay d-flex flex-column p-4">
                                        <h4 class="me-auto">{{ $exhibition->name }}</h4>
                                        <a href="{{ asset('uploads/exhibition/small/' . $image->image) }}" data-lightbox="event-{{ $image->id }}" class="my-auto"><i class="fas fa-search-plus text-dark fa-2x"></i></a>
                                    </div>
                                </div>
                            </div>
                        `);
                            @endforeach
                        }
                    @endforeach
                }
            }

            // Initial load for tab-1
            loadTabContent(1);
        });
    </script>
@endsection
