<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pahina - Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @include('partials.admin.header')
    @include('partials.admin.notification')

    <div class="container">
        @yield('content')
    </div>

    @include('partials.admin.modals')

    <div id="toast"></div>

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>