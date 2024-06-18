@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
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
                            <h2 class="h5 mb-0 pt-2 pb-2">Change Password</h2>
                        </div>
                        <div class="card-body p-4">
                            <form action="" id="change_password_form">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Old Password</label>
                                        <input type="password" name="old_password" id="old_password"
                                            placeholder="Old Password" class="form-control">
                                        <div id="change-password-error" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">New Password</label>
                                        <input type="password" name="new_password" id="new_password"
                                            placeholder="New Password" class="form-control">
                                        <div id="change-password-error" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            placeholder="Confirm Password" class="form-control">
                                        <div id="change-password-error" class="text-danger"></div>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" id="saveBtn" name="saveBtn"
                                            class="btn btn-dark rounded">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script type="text/javascript">
        $("#change_password_form").submit(function(e) {
            e.preventDefault();
            $("saveBtn").prop('disabled', true);
            $.ajax({
                url: "{{ route('account.changePassword') }}",
                type: "POST",
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $("saveBtn").prop('disabled', false);
                        $('.error').removeClass('text-danger').html('');
                        window.location.href = "{{ route('account.profile') }}"
                    } else {
                        // Error with old password
                        $("#change-password-error").html(response.message).show();
                    }
                },
                error: function(error) {
                    $("saveBtn").prop('disabled', false);
                    var errorMsg = error.responseJSON.errors;
                    if (error.status == 422) {
                        $.each(errorMsg, function(key, value) {
                            $("#" + key).siblings('p').addClass('text-danger').html(value[0]);
                        });
                    }
                }
            });
        });
    </script>
@endsection
