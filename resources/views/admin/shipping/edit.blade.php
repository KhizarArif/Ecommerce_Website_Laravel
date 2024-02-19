@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="categories.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="" method="POST" id="shippingForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="country" id="country" class="form-control">
                                    <option value=""> Select Country </option>
                                    @if ($countries->isnotEmpty())
                                        @foreach ($countries as $country)
                                            <option {{ $country->id == $shippingCharges->country_id ? 'selected' : '' }}
                                                value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                        <option value="rest_of_world"> Rest Of The World </option>
                                    @endif
                                </select>
                                <p></p>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="amount" id="amount" class="form-control"
                                    placeholder="Amount" value="{{ $shippingCharges->amount }}">
                                <p></p>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"> Update </button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

    </section>
@endsection

@section('customJs')
    <script>
        $('#shippingForm').on('submit', function(event) {
            event.preventDefault();
            var element = $(this);
            $.ajax({
                url: "{{ route('shipping.update', $shippingCharges->id) }}",
                type: "PUT",
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    window.location.href = "{{ route('shipping.create') }}";
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
