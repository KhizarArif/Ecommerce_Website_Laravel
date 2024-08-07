@extends('frontend.layouts.app')

@section('content')

<style>
    .style_login_page {
        background-color: #6366F1;
        border: 2px solid #6366F1;
        border-radius: 5px;
        margin: 1rem;
        text-align: center; 
    }

    .style_login_anchor {
        background-color: #6366F1;
        color: #fff !important; 

    }

    .style_login_anchor:hover {
        background-color: #6366F1;
        color: #fff !important;
    }
</style>

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">
            <form action="{{ route('account.authenticate') }}" method="post">
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" class="form-control @error('email')  is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                    @error('email')
                    <p class="text-danger"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control @error('password')  is-invalid @enderror" placeholder="Password" name="password">
                    @error('password')
                    <p class="text-danger"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="form-group style_login_page">
                    <a href="{{ route('login.google') }}" class="btn btn-light rounded style_login_anchor"><i class="fab fa-google px-2"></i> Login With Google </a>
                </div>
                <div class="form-group small">
                    <a href="{{ route('account.userForgotPassword') }}" class="forgot-link">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-dark rounded w-40 "> Login</button>

            </form>
            <div class="text-center small">Don't have an account? <a href="{{ route('account.register') }}">Sign up</a>
            </div>
        </div>
    </div>
</section>
@endsection