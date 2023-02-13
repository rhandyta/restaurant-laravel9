@include('partials.header')
@include('partials.sidebar')
</div>
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>@yield('title')</h3>
    </div>
    <div class="page-content">
        @yield('content')
    </div>


    @include('partials.footer')
