@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> {{ isset($user->id) ? 'Update User' : 'Create User' }} </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">

        <div class="container-fluid">
            <form action="{{ route('users.store') }}" method="POST" id="userForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ isset($user->id) ? $user->id : 0 }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ isset($user->name) ? $user->name : '' }}" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email"> Email </label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ isset($user->email) ? $user->email : '' }}" placeholder="Enter Email..">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password"> Password (Optional) </label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter Password..">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone"> Phone </label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        value="{{ isset($user->phone) ? $user->phone : '' }}"
                                        placeholder="Enter Phone Number..">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control"
                                    value="{{ isset($user->status) ? $user->status : '' }}">
                                    <option {{ isset($user->status) == 1 ? 'selected' : '' }} value="1"> Active
                                    </option>
                                    <option {{ isset($user->status) == 0 ? 'selected' : '' }} value="0"> InActive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary"> {{ isset($user->id) ? 'Update' : 'Create' }} </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
        </form>
        </div>

    </section>
@endsection


@section('customJs')
    <script>
        $('#userForm').on('submit', function(event) {
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
                    window.location.href = "{{ route('users.index') }}";
                },
                error: function(error) {
                    if (error.status === 422) {
                        var errors = $.parseJSON(error.responseText);
                        $.each(errors['errors'], function(key, val) {
                            $("#" + key + "_error").text(val[0]);
                        });
                    }
                }
            })
        });
    </script>
@endsection
