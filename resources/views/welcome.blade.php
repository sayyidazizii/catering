<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Catering System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Figtree', sans-serif;
        }

        .full-screen {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            background-color: #f9fafb;
            color: #333;
            text-align: center;
        }

        .welcome-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .welcome-container h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .welcome-container p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            background-color: #FF2D20;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .button:hover {
            background-color: #e2241b;
        }

        .button-secondary {
            background-color: #4CAF50;
        }

        .button-secondary:hover {
            background-color: #45a049;
        }

        .button i {
            margin-right: 8px;
        }

        nav a {
            margin-right: 15px;
        }
    </style>
</head>
<body>

    <div class="full-screen">
        <div class="welcome-container">
            <h1>Selamat Datang di Sistem Informasi Catering</h1>
            <p>Temukan kemudahan dalam memesan layanan catering.</p>

            <!-- Navigation for login, register, and dashboard -->
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

            <div class="button-container">
                <div class="button-container">
                    @auth
                        <a href="/home" class="button"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    @else
                        <a href="/login" class="button"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <a href="/register-customer" class="button button-secondary"><i class="fas fa-user"></i> Register sebagai Customer</a>
                        <a href="/register-merchant" class="button button-secondary"><i class="fas fa-store"></i> Register sebagai Merchant</a>
                    @endauth
                </div>
                
            </div>
        </div>
    </div>

</body>
</html>
