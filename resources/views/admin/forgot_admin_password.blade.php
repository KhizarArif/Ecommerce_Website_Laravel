<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Shop :: Administrative Panel</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="javascript:void(0)" class="h3"> Forgot Password </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Enter Email Address to reset Password. </p>
                <form  id="adminForgotPasswordForm">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <p style="color: red; margin-top: 0; margin-bottom: 1rem;"> {{ $message }} </p>
                    @enderror

                    <div class="row">

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block mt-3">Submit</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>

    <script type="text/javascript">
        $("#adminForgotPasswordForm").submit(function(e) {
			e.preventDefault();
            $(this).find("button[type=submit]").prop('disabled', true);
            $(this).find("button[type=submit]").html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('admin.processAdminPassword') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $("#adminForgotPasswordForm").find("button[type=submit]").prop('disabled', false);
                    if (response.status) {
                        window.location.href = "{{ route('admin.login') }}";
                    } else {
                        $("#adminForgotPasswordForm").find("button[type=submit]").prop('disabled', false);
                        $("#email_error").html(response.message).show();
                    }


                }
            });
        });
    </script>

</body>

</html>
