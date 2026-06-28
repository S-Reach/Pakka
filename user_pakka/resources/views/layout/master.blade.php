<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pakka</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Add Battambang font -->
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .khmer {
            font-family: 'Battambang', sans-serif;
        }

        /* IMPORTANT: fix header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: white;
            font-family: 'Battambang', sans-serif; /* ensure header uses it too */
        }

        /* push content below header */
        .container {
            margin-top: 80px;
            padding: 20px;
            font-family: 'Battambang', sans-serif;
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