@extends('layout') @section('title', 'Order Detail')
@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <a href="{{ url()->previous() }}"><button class="btn btn-info btn-sm icon">
                            <i class="bi bi-arrow-return-left"></i>
                        </button></a>
                    <a class="btn btn-success btn-sm icon" href="{{ route('receipt') }}" target="_blank">
                        <i class="bi bi-printer"></i>
                    </a>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Order Ref</td>
                                        <td class="fw-bold">{{ $order->order_id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Transaction ID</td>
                                        <td class="fw-bold">{{ $order->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Status</td>
                                        <td id="transaction_status">
                                            @if ($order->transaction_status == 'pending')
                                                <span class="badge text-bg-primary">
                                                    {{ $order->transaction_status }}
                                                </span>
                                            @elseif ($order->transaction_status == 'settlement')
                                                <span class="badge text-bg-success">
                                                    {{ $order->transaction_status }}
                                                </span>
                                            @elseif ($order->transaction_status == 'deny')
                                                <span class="badge text-bg-danger">
                                                    {{ $order->transaction_status }}
                                                </span>
                                            @elseif ($order->transaction_status == 'expire')
                                                <span class="badge text-bg-info">
                                                    {{ $order->transaction_status }}
                                                </span>
                                            @elseif ($order->transaction_status == 'cancel')
                                                <span class="badge text-bg-danger">
                                                    {{ $order->transaction_status }}
                                                </span>
                                            @else
                                                <span class="badge text-bg-info">
                                                    {{ $order->transaction_status }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Order Create</td>
                                        <td>{{ $order->created_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Confirmed</td>
                                        <td id="transaction_confirm">{{ $order->updated_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>
                                            <p>{{ $order->email }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Payment Type</td>
                                        <td class="text-uppercase">
                                            {{ $order->payment_type == 'bank_transfer' ? 'Bank Transfer' : $order->payment_type }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Bank</td>
                                        <td class="text-uppercase">{{ $order->bank ? $order->bank . ' ' . 'VA' : '-' }}
                                            {{ $order->va_number ? $order->va_number : null }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td class="fw-bold">Rp{{ number_format($order->gross_amount, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Information Table</td>
                                        <td>{{ $order->information_table }}</td>
                                    </tr>
                                    <tr>
                                        <td>Note</td>
                                        <td>
                                            <p>{{ $order->notes ? $order->notes : '-' }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td>
                                            <p>{{ $order->name }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Telephone</td>
                                        <td>
                                            <p>{{ $order->telephone ? $order->telephone : '-' }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit /Price</th>
                                <th>Sub Total</th>
                                <th>Total</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->detailorders as $item)
                                <tr>
                                    <td>{{ $item->product }}</td>
                                    <td>{{ $item->quantity }}<span>x</span></td>
                                    <td>
                                        Rp{{ number_format($item->unit_price, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        Rp{{ number_format($item->subtotal, 2, ',', '.') }}
                                    </td>
                                    <td class="fw-bold">
                                        Rp{{ number_format($item->total_price, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="rating" data-rating="{{ $item->rating }}"></div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/rater-js/lib/style.css') }}">
@endsection

@section('javascript')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('assets/extensions/rater-js/index.js') }}"></script>
    <script>
        const auth = {!! Auth::user() !!}
    </script>
    <script src="{{ asset('assets/src/orderdetail.js') }}"></script>
@endsection
