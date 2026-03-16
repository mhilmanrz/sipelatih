@extends('layout.app')

@section('body')
<div class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    @include('layout.navbar')

    <div class="d-flex flex-grow-1">
        <!-- Sidebar -->
        @include('layout.sidebar')

        <!-- Main Content -->
        <main class="flex-grow-1 p-4 bg-light">
            @yield('content')
        </main>
    </div>
</div>
@endsection