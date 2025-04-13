@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Catering') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

     <!-- Add Select2 CSS -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

     <!-- Add jQuery (required for Select2) -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <!-- Add Select2 JS -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Styles & Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family:'Poppins','Nunito', sans-serif;
            background-color: #f8fafc;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
        }
        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #0d6efd;
        }
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-white" href="{{ url('/') }}">
                    🍽️ Catering
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('home') }}">🏠 {{ __('Home') }}</a>
                        </li>
                        @if($user && $user->hasRole('merchant'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('merchant.menu.index') }}">📋 {{ __('Menu') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('merchant.orders.list') }}">🛒 {{ __('Daftar Order') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('merchant.profile.edit') }}">📋 {{ __('Profil Merchant') }}</a>
                            </li>
                        @endif

                        @if($user && $user->hasRole('customer'))
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('customer.order.create') }}">🛒 {{ __('Order') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('customer.order.history') }}">🧾 {{ __('Riwayat Pesanan') }}</a>
                            </li>
                            
                        @endif

                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">🔐 {{ __('Login') }}</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('register') }}">📝 {{ __('Register') }}</a>
                            </li> --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="rounded-circle bg-white text-primary fw-bold d-inline-block text-center" style="width: 32px; height: 32px; line-height: 32px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        ⚙️ Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        📤 Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="py-5">
            @yield('content')
        </main>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Lokasi",
                allowClear: true
            });
        });
    </script>

</body>
</html>
