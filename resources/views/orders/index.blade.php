@extends('layout') @section('title', 'Order Lists')
@section('content')


    <!-- table bordered -->
    <div class="d-flex justify-content-between flex-wrap">
        @if (Auth::user()->roles != 'manager')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#order" id="addorder">
                Add Order
            </button>
        @else
            <div></div>
        @endif
        <div class="d-flex align-items-center gap-1">
            <label for="page" class="text-nowrap me-2">entries per page</label>
            <select class="form-select" aria-label="entries per page" id="page">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
            </select>
            <div class="col-6">
                <form class="d-flex gap-1" id="search">
                    <input type="search" class="form-control" placeholder="search..." name="search">
                    <button class="btn btn-success"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive">

        <table class="table table-hover" id="table1" style="font-size: 0.95rem;">
            <thead>
                <tr>
                    <th>Order Ref</th>
                    <th>Transaction Status</th>
                    <th>Information Table</th>
                    <th>Gross Amount</th>
                    <th>Order Date</th>
                    <th>Payment Method</th>
                    <th>Bank</th>
                    @if (Auth::user()->roles != 'manager')
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="fw-bold">
                            <a href="/{{ request()->path() }}/{{ $order->order_id }}/show">{{ $order->order_id }}</a>
                        </td>
                        <td>
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
                        <td class="text-nowrap">{{ $order->information_table }}</td>
                        <td class="gross_amount">{{ $order->gross_amount }}</td>
                        <td style="white-space: nowrap;">{{ $order->created_at }}</td>
                        <td style="text-transform: uppercase;">
                            {{ $order->payment_type == 'bank_transfer' ? 'Bank Transfer' : $order->payment_type }}</td>
                        <td class="text-uppercase">{{ $order->payment_type == 'bank_transfer' ? $order->bank : '-' }}</td>
                        @if (Auth::user()->roles != 'manager')
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-success btn-sm btn-edit" data-id="{{ $order->id }}"
                                        data-bs-toggle="modal" data-bs-target="#editorder">Edit</button>
                                    <button class="btn btn-danger btn-sm btn-delete"
                                        data-id="{{ $order->id }}">Delete</button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="10">No records data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    <!--Store Modal -->
    <div class="modal modal-lg fade text-left" id="order" tabindex="-1" role="dialog" aria-labelledby="order"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Order</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formorder" autocomplete="off" method="POST">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-sm table-hover" id="table_order_add">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Product
                                                </th>
                                                <th>
                                                    Quantity
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_table_order_add">
                                            <tr id="product0">
                                                <td class="col-12 col-md-9">
                                                    <select name="products[]" required>
                                                        <option data-display="Select">--choose product--</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->food_name }}
                                                                - <span id="rupiah">{{ $product->price }}</span>
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="col-auto">
                                                    <input type="number" class="form-control" name="quantities[]"
                                                        placeholder="5" min="1" required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="add_row">Add
                                            Row</button>
                                        <button type="button" class="btn btn-danger btn-sm" id="delete_row">Delete
                                            Row</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type</label>
                                        <select id="payment_type" class="form-select" name="payment_type" required>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none" id="bank_add">
                                    <div class="form-group">
                                        <label for="bank">Bank</label>
                                        <select id="bank" class="form-select" name="bank">
                                            <option value="bca">BCA</option>
                                            <option value="bri">BRI</option>
                                            <option value="btn">BTN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tables_add">Category Tables</label>
                                        <select id="tables_add" class="form-select" name="tables" required>
                                            <option>--choose tables--</option>
                                            @foreach ($tables as $item)
                                                <option value="{{ $item->id }}">{{ $item->category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none" id="tableshidden">
                                    <div class="form-group">
                                        <label for="table_add">Table</label>
                                        <select id="table_add" class="form-select" name="table">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="firstname">Name</label>
                                        <input type="firstname" name="firstname" id="firstname" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="phone" name="phone" id="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">Note</label>
                                        <textarea name="notes" id="notes" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive" style="text-align: right;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sub total</th>
                                                    <th>Discount</th>
                                                    <th>Tax</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="add_subtotal">Rp0</td>
                                                    <td id="add_discount">-Rp0</td>
                                                    <td id="add_tax">Rp0</td>
                                                    <td id="add_total">Rp0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Add Order</span>
                            </button>
                            {{-- <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Add Order</span>
                            </button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- start model end --}}
    <!--Edit Modal -->
    <div class="modal modal-lg fade text-left" id="editorder" tabindex="-1" role="dialog" aria-labelledby="edit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Order</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formeditorder" autocomplete="off">
                        <div class="form-body">
                            <div class="row">

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="products">Products</label>
                                        <select class="" name="products[]" required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->food_name }}
                                                    - <span id="rupiah">{{ $product->price }}</span>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="food_name">Food Name</label>
                                        <input type="text" id="food_name" class="form-control"
                                            placeholder="Last Name" name="food_name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" min="0" id="price" class="form-control"
                                            name="price">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="card w-full h-20">
                                        <div class="card-body" id="imagepreview" style="padding: 0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="images">Images</label>
                                        <input type="file" name="images[]" id="images" multiple
                                            class="form-control inputFile">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="food_description">Food Description</label>
                                        <textarea name="food_description" id="food_description" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Edit Food</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- edit model end --}}

@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/select2/css/nice-select2.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/select2/js/nice-select2.js') }}"></script>
    <script>
        const products = {!! json_encode($products) !!}
        const getAllTables = {!! json_encode($tables) !!}
    </script>
    <script src="{{ asset('assets/src/order.js') }}"></script>
@endsection
