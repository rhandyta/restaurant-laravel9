@extends('layout') @section('title', 'Order Lists')
@section('content')


    <!-- table bordered -->
    <div class="d-flex justify-content-between flex-wrap">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#order" id="addorder">
            Add Order
        </button>
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
                    <th>Order ID</th>
                    <th>Transaction Status</th>
                    <th>Information Table</th>
                    <th>Notes</th>
                    <th>Gross Amount</th>
                    <th>Order Date</th>
                    <th>Payment Method</th>
                    <th>Bank</th>
                    <th>Virtual Account</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
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
                        <td>
                            <p style="width: 200px"> {{ $order->notes }} </p>
                        </td>
                        <td class="gross_amount">{{ $order->gross_amount }}</td>
                        <td style="white-space: nowrap;">{{ $order->created_at }}</td>
                        <td style="text-transform: uppercase;">
                            {{ $order->payment_type == 'bank_transfer' ? $order->bank : $order->payment_type }}</td>
                        <td>{{ $order->payment_type == 'bank_transfer' ? $order->va_number : '-' }}</td>
                        <td>{{ $order->payment_type == 'bank_transfer' ? $order->va_number : '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $order->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editorder">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $order->id }}">Delete</button>
                            </div>
                        </td>
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
                                                    <select class="form-select" name="products[]">
                                                        <option value="">--choose product--</option>
                                                    </select>
                                                </td>
                                                <td class="col-auto">
                                                    <input type="number" class="form-control" name="quantities[]"
                                                        placeholder="5">
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
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="foods">Foods</label>
                                        <select id="foods" class="form-select" name="foods" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="food_name">Food Name</label>
                                        <input type="text" id="food_name" class="form-control"
                                            placeholder="Last Name" name="food_name">
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
                                <span class="d-none d-sm-block">Add Order</span>
                            </button>
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
                                        <label for="food_category_id">Food Category</label>
                                        <select name="food_category_id" id="food_category_id" class="form-select">
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
@endsection

@section('javascript')
    <script src="{{ asset('assets/src/order.js') }}"></script>
@endsection
