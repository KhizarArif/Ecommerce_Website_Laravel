@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Coupen Discount </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="categories.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">

        <div class="container-fluid">
            <form action="{{ route('categories.store') }}" method="POST" id="categoryForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ isset($category->id) ? $category->id : 0 }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Coupen Code">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Coupen Code Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses">Max Uses </label>
                                    <input type="number" name="max_uses" id="max_uses" class="form-control"
                                        placeholder="Max Uses">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses_users">Max Uses Users </label>
                                    <input type="number" name="max_uses_users" id="max_uses_users" class="form-control"
                                        placeholder="Max Uses Users ">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="percent"> Percent </option>
                                        <option value="fixed"> Fixed </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount">Discount Amount</label>
                                    <input type="text" name="discount_amount" id="discount_amount" class="form-control"
                                        placeholder="Discount Amount ">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_amount">Min Amount</label>
                                    <input type="text" name="min_amount" id="min_amount" class="form-control"
                                        placeholder="Min Amount ">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="starts_at">Starts At </label>
                                    <input type="text" name="starts_at" id="starts_at" class="form-control"
                                        placeholder="Starts At  ">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_at">Expires At</label>
                                    <input type="text" name="expires_at" id="expires_at" class="form-control"
                                        placeholder="Expires At ">
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary"> {{ isset($category->id) ? 'Update' : 'Create' }}
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>

    </section>
@endsection


@section('customJs')
    <script>
        $('#categoryForm').on('submit', function(event) {
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
                    window.location.href = "{{ route('categories.index') }}";
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
