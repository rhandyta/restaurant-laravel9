@extends('manager.layout') @section('title', 'Menu Management')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <table class="table table-bordered" id="table1">
            <thead>
                <tr>
                    <th>Label Menu</th>
                    <th>Menu</th>
                    <th>Sub Menu</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($label_menus as $label)
                    <tr>
                        <td>{{ $label->label_title }}</td>
                        <td>
                            @foreach ($label->menus as $menu)
                                <div class="form-check">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input form-check-primary"
                                            name="menu_{{ $menu->id }}" id="menu_{{ $menu->id }}">
                                        <label class="form-check-label"
                                            for="menu_{{ $menu->id }}">{{ $menu->label_menu }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td>(01653) 27844</td>
                        <td>
                            <fieldset class="form-group">
                                <select class="form-select" id="basicSelect">
                                    <option>Manager</option>
                                    <option>Cashier</option>
                                    <option>Both</option>
                                </select>
                            </fieldset>
                        </td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No records data</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/simple-datatables.css') }}">

@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/simple-datatables.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            console.log('oke')

        });
    </script>
@endsection
