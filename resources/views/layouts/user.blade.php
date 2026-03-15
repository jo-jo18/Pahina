<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pahina - Bookstore</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>
    @include('partials.user.header')

    <div class="container">
        @yield('content')
    </div>

    @include('partials.user.modals')

    <div id="toast"></div>

    <script src="{{ asset('js/user.js') }}"></script>
</body>
</html>