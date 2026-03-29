<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pahina - Bookstore</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.user.header')

    <div class="container">
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