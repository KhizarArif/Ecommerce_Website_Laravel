@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <h1 class="h5 mb-3">Shipping Address</h1>
                                    <address>
                                        <strong>{{ $order->first_name }} {{ $order->last_name }} </strong><br>
                                        {{ $order->address }}<br>
                                        {{ $order->city }}, {{ $order->state }}<br>
                                        {{ $order->countryName }} - {{ $order->zip }} <br>
                                        Phone: {{ $order->mobile }}<br>
                                        Email: {{ $order->email }}
                                    </address>
                                    <strong> Shipped Date </strong> <br>
                                    @if (!empty($order->shipping_date))
                                        {{ \Carbon\Carbon::parse($order->shipping_date)->format('d M, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </div>



                                <div class="col-sm-4 invoice-col">
                                    <b>Invoice #007612</b><br>
                                    <br>
                                    <b>Order ID:</b> {{ $order->id }} <br>
                                    <b>Total:</b> ${{ number_format($order->grand_total, 2) }}<br>
                                    <b>Status:</b>
                                    @if ($order->status == 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @elseif ($order->status == 'shipped')
                                        <span class="badge bg-info">Shipped</span>
                                    @elseif ($order->status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($order->orderItems)
                                        @foreach ($order->orderItems as $item)
                                            <tr>
                                                <td> {{ $item->name }} </td>
                                                <td> ${{ number_format($item->price, 2) }} </td>
                                                <td> {{ $item->qty }} </td>
                                                <td> ${{ number_format($item->total, 2) }} </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>${{ number_format($order->shipping, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Discount:</th>
                                        <td>${{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>${{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                            @csrf
                            <div class="card-body">
                                <h2 class="h4 mb-3">Order Status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                        </option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_date"> Shipping Date </label>
                                    <input value="{{ $order->shipping_date }}" type="datetime-local" name="shipping_date"
                                        id="shipping_date" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <form action="" method="post" name="sendEmailInvoiceForm" id="sendEmailInvoiceForm">
                                <div class="mb-3">
                                    <select name="userType" id="userType" class="form-control">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Send</button>
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
        // $("#shipping_date").datetimepicker({
        //     format: "Y-m-d H:i:s"
        // });

        $("#changeOrderStatusForm").on('submit', function(e) {
            if (confirm(" Are you sure you want to change status?")) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('orders.changeOrderStatus', $order->id) }}",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = "{{ route('orders.edit', $order->id) }}";
                    }
                })
            }
        })

        $("#sendEmailInvoiceForm").on('submit', function(e) {
            if (confirm("Do you want to send invoice email?")) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('orders.sendEmailInvoice', $order->id) }}",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = "{{ route('orders.edit', $order->id) }}";
                    }
                })
            }
        })
    </script>
@endsection
