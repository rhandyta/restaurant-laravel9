@extends('layout') @section('title', 'Information Tables')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#addinformationtable"
            id="btnaddinformationtable">
            Add Table
        </button>

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Category Table</th>
                    <th>Category Status</th>
                    <th>Seating Capacity</th>
                    <th>Availability</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($informationTables as $table)
                    <tr>
                        <td>{{ $table->tablecategory->category }}</td>
                        <td>
                            @if ($table->tablecategory->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Deactive</span>
                            @endif
                        </td>
                        <td>{{ $table->seating_capacity }}</td>
                        <td>{{ $table->location }}</td>
                        <td>
                            @if ($table->available == 'available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Not Available</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $table->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editcategorytable">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $table->id }}">Delete</button>
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
        {{ $informationTables->links('pagination::bootstrap-5') }}
    </div>
    <!--Store Modal -->
    <div class="modal fade text-left" id="addinformationtable" tabindex="-1" role="dialog"
        aria-labelledby="addinformationtable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Information Table</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formaddinformationtable" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="categorytablestore">Category Table</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="category_table_id" id="category_table_id" class="form-select">
                                        @forelse ($categoriesTables as $category)
                                            <option value="{{ $category->id }}" data-status="{{ $category->status }}">
                                                {{ $category->category }} -
                                                {{ $category->status }}</option>

                                        @empty
                                            <option disabled selected>No data</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="seatingcapacity">Seating Capacity</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="number" min="0" id="seatingcapacity" class="form-control"
                                        name="seating_capacity">
                                </div>
                                <div class="col-md-4">
                                    <label for="available">Available</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <select name="available" id="available" class="form-select">
                                        <option value="Available">Available</option>
                                        <option value="Not Available">Not Available</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="location">Location</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="location" class="form-control" name="location"
                                        placeholder="Location Table">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" id="submitbutton">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Add Information Table</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                            <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal"
                                id="submitbutton">
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
    <script src="{{ asset('assets/src/informationtables.js') }}"></script>
@endsection
