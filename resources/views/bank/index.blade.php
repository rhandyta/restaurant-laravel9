@extends('layout') @section('title', 'Bank')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#bank"
            id="addbank">
            Add Bank
        </button>

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Bank</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($banks as $item)
                    <tr>
                        <td class="text-uppercase">{{ $item->paymenttype->payment_type }}</td>
                        <td class="text-uppercase">{{ $item->name }}</td>
                        <td>
                            @if ($item->status == 'AVAILABLE')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Not Available</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $item->id }}"
                                    data-bs-toggle="modal" data-bs-target="#bank_edit">Edit</button>
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
        {{ $banks->links('pagination::bootstrap-5') }}
    </div>
    <!--Store Modal -->
    <div class="modal fade text-left" id="bank" tabindex="-1" role="dialog" aria-labelledby="bank"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Bank Method</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formbank" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="payment_type_id">Payment Method</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="payment_type_id" id="payment_type_id" class="form-select">
                                        @forelse ($paymenttypes as $item)
                                            <option value="{{ $item->id }}" class="text-uppercase">{{ $item->payment_type }}</option>
                                        @empty
                                            <option>No Data</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="name">Bank</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="name" class="form-control" name="name"
                                        placeholder="Bank Name">
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
                                <span class="d-none d-sm-block">Add bank Method</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Edit Modal -->
    <div class="modal fade text-left" id="bank_edit" tabindex="-1" role="dialog" aria-labelledby="bank_edit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bank</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formbank_edit" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="payment_type_id">Payment Method</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="payment_type_id" id="payment_type_id" class="form-select">
                                        @forelse ($paymenttypes as $item)
                                            <option value="{{ $item->id }}" class="text-uppercase">{{ $item->payment_type }}</option>
                                        @empty
                                            <option>No Data</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="bank_edit">Bank</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="hidden" name="id" >
                                    <input type="text" id="bank_edit" class="form-control" name="name"
                                        placeholder="Bank Name" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="status_edit">Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="status" id="status_edit" class="form-select">
                                        <option value="AVAILABLE">Available</option>
                                        <option value="NOT AVAILABLE">Not Available</option>
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
                                <span class="d-none d-sm-block">Add bank Method</span>
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
    <script src="{{ asset('assets/src/bank.js') }}"></script>
@endsection
