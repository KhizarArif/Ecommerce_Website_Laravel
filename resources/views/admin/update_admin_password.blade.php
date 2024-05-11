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
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h3">Administrative Panel</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg"> Enter new Password </p>
                <form id="updateAdminPasswordForm">
                    @csrf
                    <input type="hidden" id="token" name="token" value="{{ $tokenExist->token }}">
                    <div class="input-group mt-3">
                        <input type="password" name="new_password" id="new_password"
                            class="form-control @error('new_password') is-invalid @enderror" placeholder="New Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
					<p id="new_password_error" style="color: red; margin-top: 0; margin-bottom: 1rem;"></p>
                    @error('new_password')
                        <p style="color: red; margin-top: 0; margin-bottom: 1rem;"> {{ $message }} </p>
                    @enderror
                    <div class="input-group mt-3">
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="form-control @error('confirm_password') is-invalid @enderror"
                            placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
					<p id="confirm_password_error" style="color: red; margin-top: 0; margin-bottom: 1rem;"></p>
                    @error('confirm_password')
                        <p style="color: red; margin-top: 0; margin-bottom: 1rem;"> {{ $message }} </p>
                    @enderror
                    <div class="row">

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block mt-3">Update</button>
                        </div>

                    </div>
                </form>

            </div>

        </div>

    </div>

    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>

 
	<script>
		$("#updateAdminPasswordForm").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var submitButton = form.find("button[type=submit]");

    // Disable submit button and show loading spinner
    submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
        url: "{{ route('admin.processUpdateAdminPassword') }}",
        type: "post",
        data: form.serialize(),
        success: function(response) {
            submitButton.prop('disabled', false).html('Update');
            if (response.status) {
                window.location.href = "{{ route('admin.login') }}";
            } else {
                alert(response.message); // Show error message
            }
        },
        error: function(error) {
            // Enable submit button and show error message
            submitButton.prop('disabled', false).html('Update');
            if (error.status === 422) {
                var errors = $.parseJSON(error.responseText);
                $.each(errors['errors'], function(key, val) {
                    $("#" + key + "_error").text(val[0]); // Show error message below input
                });
            }
        }
    });
});

	</script>

</body>

</html>
