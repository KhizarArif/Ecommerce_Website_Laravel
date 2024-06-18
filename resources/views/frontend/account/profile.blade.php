@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
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
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <div class="card-body p-4">
                            <form id="personal_info_form" name="personal_info_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" placeholder="Enter Your Name"
                                            value="{{ $user->name }}" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" placeholder="Enter Your Email"
                                            value="{{ $user->email }}" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" placeholder="Enter Your Phone"
                                            value="{{ $user->phone }}" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark rounded" type="submit">Update</button>
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
    <script>
        $("#personal_info_form").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('account.updateProfile') }}",
                type: "get",
                data: $(this).serializeArray(),
                dataType: "json",
                success: function(response) {
                    $("#personal_info_form").find('div.mb-3 p').removeClass('text-danger').html('');
                    window.location.href = "{{ route('frontend.home') }}";
                },
                error: function(error) {
                    if (error.status == 422) {
                        console.log("error: ", error.responseJSON);
                        var errors = error.responseJSON.errors; // Access errors directly
                        $.each(errors, function(key, value) {
                            console.log("key: ", key, "value: ", value);
                            $("#" + key).siblings('p').addClass('text-danger').html(value[0]);
                        })
                    }
                }
            })
        })
    </script>
@endsection
