@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Orders </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">

                    <!-- Reset Button Start -->
                    <div class="card-title">
                        <form action="">
                            <button type="button" class="btn btn-danger"> <a href="{{ route('orders.index') }}"
                                    class="text-white text-decoration-none"> Reset </a> </button>
                        </form>
                    </div>
                    <!-- Reset Button End -->

                    <!-- Import And Export Functionality Start -->
                    <!-- Import And Export Functionality End -->


                    <!-- Export Button Start  -->
                    <button class="btn btn-primary mx-3">
                        <a href="{{ route('view-pdf') }}" class="text-white">
                            <i class="fa fa-thin fa-eye"></i>
                            View PDF
                        </a>
                    </button>
                    <!-- Export Button End  -->
                    <!-- Search  Start -->
                    <div class="card-tools">
                        <form action="">
                            <div class="input-group input-group" style="width: 250px;">
                                <input type="search" name="table_search" class="form-control float-right"
                                    placeholder="Search" value="{{ request()->get('table_search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Search  End -->

                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60"> Orders # </th>
                                <th> Customer </th>
                                <th> Email </th>
                                <th> Phone </th>
                                <th> Status </th>
                                <th>Total</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $order)
                                    <tr>
                                        <td> <a href="{{ route('orders.details', $order->id) }}"> {{ $order->id }} </a>
                                        </td>
                                        <td> {{ $order->name }} </td>
                                        <td> {{ $order->email }} </td>
                                        <td> {{ $order->mobile }} </td>
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-danger">Pending</span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge bg-info">Shipped</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td> ${{ number_format($order->grand_total, 2) }} </td>
                                        <td> {{ \Carbon\Carbon::parse($order->created_at)->format('d M y') }} </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5"> Record Not Found </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
@endsection
