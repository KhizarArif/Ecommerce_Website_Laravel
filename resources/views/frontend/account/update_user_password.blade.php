@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Reset Password </li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">
            <div class="login-form">
                <form method="post" id="userUpdatePasswordForm">
                    @csrf 
                    <input type="hidden" name="token" id="token" value="{{ $tokenExist->token }}">
                    <h4 class="modal-title"> Update Password </h4>

                    <div class="form-group">
                        <input type="password" class="form-control @error('new_password')  is-invalid @enderror"
                            placeholder="Password" name="new_password" id="new_password">
                            <p id="change-password-error" class="text-danger"></p>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('confirm_password')  is-invalid @enderror"
                            placeholder="Confirm Password" name="confirm_password" id="confirm_password" >
                       
                        <p id="change-password-error" class="text-danger"></p>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block rounded"> Submit </button>
                </form>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        $("#userUpdatePasswordForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('account.processUpdatePassword') }}",
                type: "POST",
                data: $(this).serializeArray(),
                dataType: 'json',
                // success: function(response) {
                //     console.log("response", response);
                //     window.location.href = "{{ route('account.login') }}";

                //     if(response.status == false){
                //         window.location.href = "{{ route('account.userForgotPassword') }}";
                //     }
                // }
                success: function(response) {
                    if (response.status) {
                        $("saveBtn").prop('disabled', false);
                        $('#change-password-error').removeClass('text-danger').html('');
                        window.location.href = "{{ route('account.login') }}";
                    } else {
                        // Error with old password
                        $('#change-password-error').removeClass('text-danger').html('');
                    }
                },
                error: function(error) {
                    $("saveBtn").prop('disabled', false); 
                    var errorMsg = error.responseJSON.errors;
                    console.log("errorMsg", errorMsg);
                    if (error.status == 422) {
                        $.each(errorMsg, function(key, value) {
                            $("#" + key).siblings('p').addClass('text-danger').html(value[0]);
                        });
                    }
                }
            })
        });
    </script>
@endsection
