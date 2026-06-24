<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pakka</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        /* IMPORTANT: fix header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: white;
        }

        /* push content below header */
        .container {
            margin-top: 80px; /* adjust based on navbar height */
            padding: 20px;
        }
    </style>
    
</head>

<body>

    <header class="header">
        @include('layout.header')
    </header>

    <div class="container">
        @yield('content')
    </div>

</body>
</html>