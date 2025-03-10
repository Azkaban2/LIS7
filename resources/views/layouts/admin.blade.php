<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Adjust as needed -->
</head>
<body>
    <div class="admin-container">
        <nav class="admin-navbar">
            <!-- Add your admin navigation menu here -->
        </nav>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> <!-- Adjust as needed -->
</body>
</html>
