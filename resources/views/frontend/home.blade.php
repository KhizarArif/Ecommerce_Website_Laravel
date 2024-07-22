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

        .intro_img_container {
            width: 100%;
            height: 50vh;

        }

        .intro_heading {
            font-family: 'Playball', cursive;
            font-size: 2.5rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.6);
            word-spacing: 1rem;
            color: #000;
            min-width: fit-content; 
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);

            @media screen and (max-width: 768px) {
                font-size: 1.5rem;
                word-spacing: 0.5rem;
                width: 90%;
            }
        }

        .slider {
            height: 250px;
            margin: auto;
            position: relative;
            width: 100%;
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .slide-track {
            display: flex;
            width: calc(250px * {{ $productImageCount }});
            animation: scroll 40s linear infinite;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(calc(-250px * {{ $productImageCount / 2 }}));
            }
        }

        .slide {
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            padding: 15px;
            perspective: 100px;
        }

        .carousel_image {
            width: 100%;
            height: 100%;
            transition: transform 1s;
        }

        .carousel_image:hover {
            transform: translateZ(20px);
        }

        .slider::before,
        .slider::after {
            height: 100%;
            width: 15%;
            content: "";
            position: absolute;
            z-index: 2;
            background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .slider::before {
            left: 0;
            top: 0;
        }

        .slider::after {
            top: 0;
            right: 0;
            transform: rotateZ(180deg);
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
            <div class="contianer bg-light ">
                <div class="container">
                    <div class="intro_img_container">
                        <img src="{{ asset('front-assets/images/hero.png') }}"
                            class="img-fluid rounded animated zoomIn w-100 h-100 border rounded position-relative"
                            style="object-fit: cover;" alt="">
                        <h1 class="translate-middle intro_heading"> Art Wings By Sidra Munawar </h1>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12 ">
                            <div class="slider">
                                <div class="slide-track">
                                    {{-- Loop Start Here --}}

                                    @if ($productAllImages->count() > 0)
                                        @foreach ($productAllImages as $productAllImage)
                                            <div class="slide">
                                                <img src="{{ asset('uploads/product/small/' . $productAllImage->image) }}"
                                                    alt="" class="carousel_image">
                                            </div>
                                        @endforeach
                                    @endif 

                                    {{-- Loop Ends Here --}}
                                </div>
                            </div>
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
                            <img src="{{ asset('front-assets/images/artwings_01.jpeg') }}" class="img-fluid rounded"
                                alt="">
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




        <section class="section-3 wow bounceInUp pt-5">
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
                                        <a href="{{ route('allCategories.show', encrypt($category->id)) }}">
                                        <img src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt=""
                                        class="">
                                        </a>
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
                    <h1>Latest Products</h1>
                </div>
                <div class="row pb-3">
                    @if ($featuredProducts->isNotEmpty())
                        @foreach ($featuredProducts as $featuredProduct) 
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">
                                        <a 
                                        {{-- href="{{ route('front.product', $featuredProduct->slug) }}"  --}}
                                        href="javascript:void(0)" 
                                        class="product-img">
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
                                                        onclick="addToCart('{{ $featuredProduct->id }}', '{{$featuredProduct->productImages->first()->id}}')">
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
                                            <span class="h5"><strong> Rs {{ $featuredProduct->price }} </strong></span>
                                            @if ($featuredProduct->compare_price > 0)
                                                <span class="h6 text-underline"><del> Rs
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
            <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp" data-wow-delay="0.1s">
                <li class="nav-item p-2">
                    <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                        <span class="text-dark" style="width: 150px;"> All Events</span>
                    </a>
                </li>
                @foreach ($exhibitions as $index => $exhibition)
                    <li class="nav-item p-2">
                        <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-{{ $index + 2 }}">
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
                    <div class="col-4 col-md-3 col-lg-2 wow bounceInUp" data-wow-delay="0.1s">
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
                        <div class="col-4 col-md-3 col-lg-2 wow bounceInUp" data-wow-delay="0.1s">
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
