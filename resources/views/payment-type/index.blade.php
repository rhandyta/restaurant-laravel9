@extends('layout') @section('title', 'Payment Method')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#payment"
            id="addpayment">
            Add Payment Method
        </button>

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Via</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($types as $item)
                    <tr>
                        <td class="text-uppercase">{{ $item->payment_type }}</td>
                        <td>
                            @if ($item->status == 'available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Not Available</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $item->id }}"
                                    data-bs-toggle="modal" data-bs-target="#payment_edit">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $item->id }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="4">No records data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $types->links('pagination::bootstrap-5') }}
    </div>
    <!--Store Modal -->
    <div class="modal fade text-left" id="payment" tabindex="-1" role="dialog" aria-labelledby="payment"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Method</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formpayment" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="payment_type">Payment Method</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="payment_type" class="form-control" name="payment_type"
                                        placeholder="Payment Method">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="status" id="status" class="form-select">
                                        <option value="available">Available</option>
                                        <option value="not available">Not Available</option>
                                    </select>
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
                                <span class="d-none d-sm-block">Add Payment Method</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Edit Modal -->
    <div class="modal fade text-left" id="payment_edit" tabindex="-1" role="dialog" aria-labelledby="payment_edit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Method</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formpayment_edit" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="payment_type_edit">Payment Method</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="hidden" name="id" >
                                    <input type="text" id="payment_type_edit" class="form-control" name="payment_type"
                                        placeholder="Payment Method">
                                </div>
                                <div class="col-md-4">
                                    <label for="status_edit">Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="status" id="status_edit" class="form-select">
                                        <option value="available">Available</option>
                                        <option value="not available">Not Available</option>
                                    </select>
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
                                <span class="d-none d-sm-block">Add Payment Method</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/simple-datatables.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/src/payment-method.js') }}"></script>
@endsection
