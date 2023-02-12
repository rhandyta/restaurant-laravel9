@extends('manager.layout') @section('title', 'Menu Management')
@section('content')
    <!-- table bordered -->
    <div class="table-responsive">
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Label Menu</th>
                    <th>Menu</th>
                    <th>Sub Menu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($label_menus as $label)
                    <tr>
                        <td>
                            <div class="d-flex justify-content-between">
                                <label class="form-check-label"
                                    for="label_{{ $label->id }}">{{ $label->label_title }}</label>
                                <fieldset class="form-group">
                                    <select class="form-select form-select-sm label_menu" data-id="{{ $label->id }}">
                                        <option value="manager" {{ $label->role == 'manager' ? 'selected' : null }}>
                                            Manager</option>
                                        <option value="cashier" {{ $label->role == 'cashier' ? 'selected' : null }}>
                                            Cashier</option>
                                        <option value="both" {{ $label->role == null ? 'selected' : null }}>Both
                                        </option>
                                    </select>
                                </fieldset>
                            </div>
                        </td>
                        <td>
                            @foreach ($label->menus as $menu)
                                <div class="d-flex justify-content-between">
                                    <label class="form-check-label"
                                        for="menu_{{ $menu->id }}">{{ $menu->label_menu }}</label>
                                    <fieldset class="form-group ">
                                        <select class="form-select form-select-sm menu_menu" data-id="{{ $menu->id }}">
                                            <option value="manager" {{ $menu->role == 'manager' ? 'selected' : null }}>
                                                Manager</option>
                                            <option value="cashier" {{ $menu->role == 'cashier' ? 'selected' : null }}>
                                                Cashier</option>
                                            <option value="both" {{ $menu->role == null ? 'selected' : null }}>Both
                                            </option>
                                        </select>
                                    </fieldset>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($label->menus as $menu)
                                @foreach ($menu->submenus as $submenu)
                                    <div class="d-flex justify-content-between submenu">
                                        <label class="form-check-label"
                                            for="submenu_{{ $submenu->id }}">{{ $submenu->label_submenu }}</label>
                                        <fieldset class="form-group ">
                                            <select class="form-select form-select-sm" data-id="{{ $submenu->id }}">
                                                <option value="manager"
                                                    {{ $submenu->role == 'manager' ? 'selected' : null }}>
                                                    Manager</option>
                                                <option value="cashier"
                                                    {{ $submenu->role == 'cashier' ? 'selected' : null }}>
                                                    Cashier</option>
                                                <option value="both" {{ $submenu->role == null ? 'selected' : null }}>
                                                    Both
                                                </option>
                                            </select>
                                        </fieldset>
                                    </div>
                                @endforeach
                            @endforeach
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
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/src/manager/main.js') }}"></script>
    <script src="{{ asset('assets/src/manager/menumanagement.js') }}"></script>
@endsection
