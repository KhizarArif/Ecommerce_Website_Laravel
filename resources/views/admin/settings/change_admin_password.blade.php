@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Update Password </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('setting.changeAdminPassword') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">

        <div class="container-fluid">
            <form action="{{ route('setting.updateAdminPassword') }}" method="POST" id="admin_password_form">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control"
                                        placeholder="Old Password..">
                                    <div id="change_password_error" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="new_password">New Password </label>
                                    <input type="password" name="new_password" id="new_password" class="form-control"
                                        placeholder="New Password..">
                                    <div id="change_password_error" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="confirm_password">Confirm Password </label>
                                    <input type="password" name="confirm_password" id="confirm_password"
                                        class="form-control" placeholder="Confirm Password..">
                                    <div id="change_password_error" class="text-danger"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary"> Update </button>
                    <a href="{{ route('setting.changeAdminPassword') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>

    </section>
@endsection


@section('customJs')
    <script type="text/javascript">
        $('#admin_password_form').on('submit', function(event) {
            event.preventDefault();
            var form = $(this);
            let actionUrl = form.attr('action');
            $.ajax({
                url: actionUrl,
                type: "POST",
                data: new FormData(form[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        $("saveBtn").prop('disabled', false);
                        $('.error').removeClass('text-danger').html('');
                        window.location.href = "{{ route('admin.dashboard') }}"
                    } else {
                        // Error with old password 
                        $("#change_password_error").html(response.message).show();
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
            })
        });
    </script>
@endsection
