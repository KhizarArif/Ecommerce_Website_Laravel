<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo (!empty($title)) ? 'Title-'.$title: 'Home'; ?></title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />

	<meta property="og:locale" content="en_AU" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="" />
	<meta property="fb:app_id" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="" />
	<meta property="og:image:height" content="" />
	<meta property="og:image:alt" content="" />

	<meta name="twitter:title" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:image:alt" content="" />
	<meta name="twitter:card" content="summary_large_image" />
	

	{{-- Google Fonts --}}
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
        rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/bootstrap.min.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/video-js.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style1.css')}}" />

	{{-- Libraries --}}
	<link href="{{ asset('front-assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-assets/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-assets/lib/owlcarousel/owl.carousel.min.css') }}" rel="stylesheet">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="#" />
</head>

<body data-instant-intensity="mousedown">

{{-- <div class="bg-light top-header">        
	<div class="container">
		<div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
			<div class="col-lg-4 logo">
				<a href="{{ route('frontend.home') }}" class="text-decoration-none">
					<span class="h1 text-uppercase text-primary bg-dark px-2">Online</span>
					<span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">SHOP</span>
				</a>
			</div>
			<div class="col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
				<a href="#" class="nav-link text-dark">My Account</a>
				<form action="">					
					<div class="input-group">
						<input type="text" placeholder="Search For Products" class="form-control" aria-label="Amount (to the nearest dollar)">
						<span class="input-group-text">
							<i class="fa fa-search"></i>
					  	</span>
					</div>
				</form>
			</div>		
		</div>
	</div>
</div> --}}

<div class="container-fluid nav-bar ">
	<div class="container">
		<nav class="navbar navbar-light navbar-expand-lg py-4">
			<a href="index.html" class="navbar-brand">
				<h1 class="text-primary fw-bold mb-0">Art Wings </h1>
			</a>
			<button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
				data-bs-target="#navbarCollapse">
				<span class="fa fa-bars text-primary"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<div class="navbar-nav mx-auto">
					<a href="index.html" class="nav-item nav-link active">Home</a>
					<a href="about" class="nav-item nav-link">About</a>
					<a href="service" class="nav-item nav-link">Services</a>
					<a href="event.html" class="nav-item nav-link">Events</a>
					<a href="menu.html" class="nav-item nav-link">Menu</a>
					<div class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
						<div class="dropdown-menu bg-light">
							<a href="book.html" class="dropdown-item">Booking</a>
							<a href="blog.html" class="dropdown-item">Our Blog</a>
							<a href="team.html" class="dropdown-item">Our Team</a>
							<a href="testimonial.html" class="dropdown-item">Testimonial</a>
							<a href="404.html" class="dropdown-item">404 Page</a>
						</div>
					</div>
					<a href="contact.html" class="nav-item nav-link">Contact</a>
				</div>
				<button class="btn-search btn btn-primary btn-md-square me-4 rounded-circle d-none d-lg-inline-flex"
					data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></button>
				<a href="" class="btn btn-primary py-2 px-4 d-none d-xl-inline-block rounded-pill">Book
					Now</a>
			</div>
		</nav>
	</div>
</div>


<header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-xl" id="navbar">
                <a href="{{ route('frontend.home') }}" class="text-decoration-none mobile-logo">
                    <span class="h2 text-uppercase text-primary bg-dark">Online</span>
                    <span class="h2 text-uppercase text-white px-2">SHOP</span>
                </a>
                <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <!-- <span class="navbar-toggler-icon icon-menu"></span> -->
                    <i class="navbar-toggler-icon fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- <li class="nav-item">
          				<a class="nav-link active" aria-current="page" href="index.php" title="Products">Home</a>
        			</li> -->
                        <?php
                    use function App\Helpers\getCategories;

                        $categories =getCategories(); 
                        ?>
                        @if ($categories->isNotEmpty())
                        @foreach (getCategories() as $category )
                        <li class="nav-item dropdown">
                            <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ $category->name }}
                            </button>
                            @if ($category->subcategories->isNotEmpty())
                            <ul class="dropdown-menu dropdown-menu-dark">
                                @foreach ($category->subcategories as $subCategory )
                                <li><a class="dropdown-item nav-link" href="#"> {{ $subCategory->name}} </a></li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                        @endif

                    </ul>
                </div>
                <div class="right-nav py-0">
                    <a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
                        <i class="fas fa-shopping-cart text-primary"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>

