@extends('auth.layouts')
@section('title', 'Login')
@section('content')

@include('partials.alert')
<form action="{{route('login.store')}}" method="post">
  @csrf
  @method('POST')
    <div class="form-group position-relative has-icon-left mb-4">
        <input type="email" class="form-control form-control-xl" placeholder="Email" name="email" value="{{old('email')}}">
        <div class="form-control-icon">
            <i class="bi bi-person"></i>
        </div>
    </div>
    <div class="form-group position-relative has-icon-left mb-4">
        <input type="password" class="form-control form-control-xl" placeholder="Password" name="password">
        <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
    </div>
    <div class="form-check form-check-lg d-flex align-items-end">
        <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label text-gray-600" for="flexCheckDefault">
            Keep me logged in
        </label>
    </div>
    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
</form>
<div class="text-center mt-5 text-sm">
    <p class="text-red-600">Having problems? <a href="auth-register.html" class="font-bold">contact related parties</a>.</p>
    <p>
</div>
@endsection