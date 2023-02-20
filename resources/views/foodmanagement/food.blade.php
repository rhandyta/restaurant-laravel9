@extends('layout') @section('title', 'Food List')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#food" id="addfood">
            Add Food
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
    <div class="modal modal-lg fade text-left" id="food" tabindex="-1" role="dialog" aria-labelledby="food"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Food</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formfood" autocomplete="off">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">First Name</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="First Name" name="fname-column">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="food_name">Food Name</label>
                                        <input type="text" id="food_name" class="form-control" placeholder="Last Name"
                                            name="food_name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="food_description">Food Description</label>
                                        <textarea name="food_description" id="food_description" rows="5" class="form-control"></textarea>
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
                                    <div class="filepond--root image-resize-filepond filepond--hopper"
                                        data-style-button-remove-item-position="left"
                                        data-style-button-process-item-position="right"
                                        data-style-load-indicator-position="right"
                                        data-style-progress-indicator-position="right"
                                        data-style-button-remove-item-align="false" style="height: 76px;"><input
                                            class="filepond--browser" type="file" id="filepond--browser-mtubuqc1x"
                                            aria-controls="filepond--assistant-mtubuqc1x"
                                            aria-labelledby="filepond--drop-label-mtubuqc1x" name="filepond">
                                        <div class="filepond--drop-label"
                                            style="transform: translate3d(0px, 0px, 0px); opacity: 1;"><label
                                                for="filepond--browser-mtubuqc1x" id="filepond--drop-label-mtubuqc1x"
                                                aria-hidden="true">Drag &amp; Drop your files or <span
                                                    class="filepond--label-action" tabindex="0">Browse</span></label>
                                        </div>
                                        <div class="filepond--list-scroller" style="transform: translate3d(0px, 0px, 0px);">
                                            <ul class="filepond--list" role="list"></ul>
                                        </div>
                                        <div class="filepond--panel filepond--panel-root" data-scalable="true">
                                            <div class="filepond--panel-top filepond--panel-root"></div>
                                            <div class="filepond--panel-center filepond--panel-root"
                                                style="transform: translate3d(0px, 8px, 0px) scale3d(1, 0.6, 1);"></div>
                                            <div class="filepond--panel-bottom filepond--panel-root"
                                                style="transform: translate3d(0px, 68px, 0px);"></div>
                                        </div><span class="filepond--assistant" id="filepond--assistant-mtubuqc1x"
                                            role="status" aria-live="polite" aria-relevant="additions"></span>
                                        <fieldset class="filepond--data"></fieldset>
                                        <div class="filepond--drip"></div>
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
                                <span class="d-none d-sm-block">Add Food</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/filepond.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script src="{{ asset('assets/js/pages/filepond.js') }}"></script>
    <script src="{{ asset('assets/src/food.js') }}"></script>
@endsection
