<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!-- General CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/page.css') }}">
    <!-- CSS Libraries -->
    @yield('css')
</head>

<body>
    <div
        class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed authImage">
        @yield('content')
    </div>
    <footer>
        <div class="container-fluid padding-0">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-6">
                    <div class="copyright text-center text-muted">
                        Copyright
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('messages.js') }}"></script>
    <script src="{{ asset('assets/js/auth-third-party.js') }}"></script>
    <script src="{{ asset('assets/js/auth/auth.js') }}"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('.alert').delay(5000).slideUp(300)
        });
    </script>
</body>

</html>
