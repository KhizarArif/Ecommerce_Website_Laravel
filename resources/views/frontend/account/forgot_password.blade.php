@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                    <li class="breadcrumb-item"> Forgot Password </li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">    
                <form action="{{ route('account.processForgotPassword') }}" method="post">
                @csrf
                    <h4 class="modal-title"> Forgot Password. </h4>
                    <div class="form-group">
                        <input type="text" class="form-control @error('email')  is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-danger"> {{ $message }} </p>
                        @enderror
                    </div>
                   
                    <button type="submit" class="btn btn-dark rounded">  Submit  </button>          
                </form>			
                <div class="text-center small"> <a href="{{ route('account.login') }}"> Login </a></div>
            </div>
        </div>
    </section>
@endsection