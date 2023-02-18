@extends('layout') @section('title', 'Food Management')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#categorytable"
            id="addcategorytable">
            Add Category Table
        </button>

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Category Food</th>
                    <th>Category Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($foodCategories as $food)
                    <tr>
                        <td>{{ $food->category_name }}</td>
                        <td>
                            {{ $food->category_description }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $food->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editcategorytable">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $food->id }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="3">No records data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $foodCategories->links('pagination::bootstrap-5') }}
    </div>
    <!--Store Modal -->
    {{-- <div class="modal fade text-left" id="categorytable" tabindex="-1" role="dialog" aria-labelledby="categorytable"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Categoty Table</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formcategorytable" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="categorytable">Category Table</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="categorytable" class="form-control" name="category"
                                        placeholder="Category Table">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="status" id="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
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
                                <span class="d-none d-sm-block">Add Categoty Table</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!--Edit Modal -->
    {{-- <div class="modal fade text-left" id="editcategorytable" tabindex="-1" role="dialog" aria-labelledby="edit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Categoty Table</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editformcategorytable" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="categorytable">Category Table</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="categorytableedit" class="form-control" name="category"
                                        placeholder="Category Table">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Status</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="status" id="statusedit" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
                                    </select>
                                </div>
                                <input type="hidden" name="categoryId" id='categoryId'>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block ">Edit Categoty Table</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/simple-datatables.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/src/foodmanagement.js') }}"></script>
@endsection
