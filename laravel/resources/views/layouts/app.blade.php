<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodSave — Penyelamatan Surplus Pangan (SDG 2)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('food-surpluses.index') }}">
                <i class="bi bi-box2-heart-fill me-1"></i> FoodSave
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                @auth
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('food-surpluses.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('food-surpluses.index') }}">Katalog Surplus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('claims.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('claims.index') }}">Transaksi Klaim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('distributions.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('distributions.index') }}">Bukti Penyaluran</a>
                    </li>
                    @if(in_array(auth()->user()->role, ['admin', 'merchant']))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('merchants.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('merchants.index') }}">Mitra & CSR</a>
                    </li>
                    @endif
                    @if(in_array(auth()->user()->role, ['admin', 'recipient']))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('recipients.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('recipients.index') }}">Profil Penerima</a>
                    </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center text-white gap-3">
                    <span class="small">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        <span class="badge bg-warning text-dark text-uppercase ms-1">{{ auth()->user()->role }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Keluar</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>