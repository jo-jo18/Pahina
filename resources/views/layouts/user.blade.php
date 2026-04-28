<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pahina - Bookstore</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @stack('styles')
</head>
<body>

    @include('partials.user.header')

    <div class="main-wrapper">
        @yield('content')
    </div>

    @include('partials.user.modals')

    <div id="toast"></div>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            userId: {{ Auth::id() ?? 'null' }},
            userIsAdmin: {{ Auth::check() && Auth::user()->is_admin ? 'true' : 'false' }}
        };
    </script>

    <script src="{{ asset('js/user.js') }}"></script>
    @stack('scripts')

</body>
</html>