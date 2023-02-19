@extends('layout') @section('title', 'Food List')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#foodcategory"
            id="addfoodcategory">
            Add Food Category
        </button>

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Preview Image</th>
                    <th>Category Food</th>
                    <th>Food Name</th>
                    <th>Food Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($foodLists as $food)
                    <tr>
                        <td>{{ $food->img_url }}</td>
                        <td>{{ $food->foodcategory->category_name }}</td>
                        <td>{{ $food->food_name }}</td>
                        <td>{{ $food->price }}</td>
                        <td>
                            {{ $food->food_description }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $food->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editcategoryfood">Edit</button>
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
        {{ $foodLists->links('pagination::bootstrap-5') }}
    </div>
    <!--Store Modal -->
    {{-- <div class="modal fade text-left" id="foodcategory" tabindex="-1" role="dialog" aria-labelledby="foodcategory"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Food Category</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formfoodcategory" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="category_name">Category Name</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="category_name" class="form-control" name="category_name"
                                        placeholder="Category Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Description</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea name="category_description" rows="5" class="form-control"></textarea>
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
                                <span class="d-none d-sm-block">Add Category</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!--Edit Modal -->
    {{-- <div class="modal fade text-left" id="editcategoryfood" tabindex="-1" role="dialog" aria-labelledby="edit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category Food</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formeditcategoryfood" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="category_name">Category Name</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="category_name" class="form-control" name="category_name"
                                        placeholder="Category Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Description</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <textarea name="category_description" rows="5" class="form-control"></textarea>
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
                                <span class="d-none d-sm-block">Edit Category</span>
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
    <script src="{{ asset('assets/src/food.js') }}"></script>
@endsection
