<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Panel')</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    @stack('styles')

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI', sans-serif;
        }

        body{
            background:#f5f6fa;
        }

        a{
            text-decoration:none;
        }
    </style>

</head>
<body>

    <div class="admin-layout">

        {{-- SIDEBAR --}}
        @include('layout.sidebar')

        {{-- MAIN CONTENT --}}
        <div class="main-wrapper">
            @yield('content')
        </div>

    </div>

    @stack('scripts')

</body>
</html>