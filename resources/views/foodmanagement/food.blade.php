@extends('layout') @section('title', 'Food List')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-target="#food" id="addfood">
            Add Food
        </button>
        <div class="progress my-2" style="display: none;" id="progress">
            <div class="progress-bar" role="progressbar" aria-label="progress with label" style="width: 25%;"
                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" id="progressbar"></div>
        </div>
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
                        <td>
                            @foreach ($food->foodimages as $image)
                                <img src="{{ $image->image_url }}" alt="{{ $food->food_name }}" width="50">
                            @endforeach
                        </td>
                        <td>{{ $food->foodcategory->category_name }}</td>
                        <td>{{ $food->food_name }}</td>
                        <td>{{ number_format($food->price, 2) }}</td>
                        <td>
                            {{ $food->food_description }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-success btn-sm btn-edit" data-id="{{ $food->id }}"
                                    data-bs-toggle="modal" data-bs-target="#editfood">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete"
                                    data-id="{{ $food->id }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="6">No records data</td>
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
                    <form id="formfood" autocomplete="off" enctype="multipart/form-data" method="POST">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="food_category_id">Food Category</label>
                                        <select name="food_category_id" id="food_category_id" class="form-select">
                                            @forelse ($foodCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @empty
                                                <h5>No data</h5>
                                            @endforelse
                                        </select>
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
                                        <label for="price">Price</label>
                                        <input type="number" min="0" id="price" class="form-control"
                                            name="price">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
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
                                <span class="d-none d-sm-block">Add Food</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- start model end --}}
    <!--Edit Modal -->
    <div class="modal modal-lg fade text-left" id="editfood" tabindex="-1" role="dialog" aria-labelledby="edit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Food</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formeditfood" autocomplete="off" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="food_category_id">Food Category</label>
                                        <select name="food_category_id" id="food_category_id" class="form-select">
                                            @forelse ($foodCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}
                                                </option>
                                            @empty
                                                <h5>No data</h5>
                                            @endforelse
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
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/simple-datatables.css') }}">

@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/src/food.js') }}"></script>
@endsection
