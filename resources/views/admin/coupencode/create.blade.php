@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> {{ isset($coupon->id) ? "Update" : "Create"}} Coupen Discount </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('coupen.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('coupen.store') }}" method="POST" id="discountForm">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ isset($coupon->id) ? $coupon->id : 0 }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        value="{{ isset($coupon->code) ? $coupon->code : '' }}" placeholder="Coupen Code">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ isset($coupon->name) ? $coupon->name : '' }}"
                                        placeholder="Coupen Code Name">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses">Max Uses </label>
                                    <input type="number" name="max_uses" id="max_uses" class="form-control"
                                        value="{{ isset($coupon->max_uses) ? $coupon->max_uses : '' }}"
                                        placeholder="Max Uses">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses_users">Max Uses Users </label>
                                    <input type="number" name="max_uses_users" id="max_uses_users" class="form-control"
                                        value="{{ isset($coupon->max_uses_users) ? $coupon->max_uses_users : '' }}"
                                        placeholder="Max Uses Users ">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option {{ isset($coupon->type) && $coupon->type == 'percent' ? 'selected' : '' }}
                                            value="percent"> Percent </option>
                                        <option {{ isset($coupon->type) && $coupon->type == 'fixed' ? 'selected' : '' }}
                                            value="fixed"> Fixed </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount">Discount Amount</label>
                                    <input type="text" name="discount_amount" id="discount_amount" class="form-control"
                                        value="{{ isset($coupon->discount_amount) ? $coupon->discount_amount : '' }}"
                                        placeholder="Discount Amount ">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_amount">Min Amount</label>
                                    <input type="text" name="min_amount" id="min_amount" class="form-control"
                                        value="{{ isset($coupon->min_amount) ? $coupon->min_amount : '' }}"
                                        placeholder="Min Amount ">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ isset($coupon->status) && $coupon->status == 1 ? 'selected' : '' }}
                                            value="1"> Active </option>
                                        <option {{ isset($coupon->status) && $coupon->status == 0 ? 'selected' : '' }}
                                            value="0"> InActive </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="starts_at">Starts At </label>
                                    <input type="text" name="starts_at" id="starts_at" class="form-control"
                                        value="{{ isset($coupon->starts_at) ? $coupon->starts_at : '' }}"
                                        placeholder="Starts At  ">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_at">Expires At</label>
                                    <input type="text" name="expires_at" id="expires_at" class="form-control"
                                        value="{{ isset($coupon->expires_at) ? $coupon->expires_at : '' }}"
                                        placeholder="Expires At ">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ isset($coupon->description) ? $coupon->description : '' }}</textarea>
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary"> {{ isset($coupon->id) ? 'Update' : 'Create' }}
                    </button>
                    <a href="{{ route('coupen.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>

    </section>
@endsection


@section('customJs')
    <script>
        $(document).ready(function() {
            $('#starts_at').datetimepicker({
                format: 'Y-m-d H:i:s',
            });

            $('#expires_at').datetimepicker({
                format: 'Y-m-d H:i:s',
            });
        });

        $('#discountForm').on('submit', function(event) {
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
                        $('.error').removeClass('text-danger').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                        window.location.href = "{{ route('coupen.index') }}";

                    } else {
                        var errors = response.error;
                        $('.error').removeClass('text-danger').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is_invalid').siblings('p').addClass(
                                'text-danger').html(value);
                        });
                    }
                },
                error: function(error) {
                    errorMessage(error);
                    console.log("Error");
                }
            })
        });

        
    </script>
@endsection
