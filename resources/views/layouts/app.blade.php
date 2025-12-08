<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Kas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg,#f0fdf4,#ecfdf5);">

<nav class="navbar navbar-expand-lg" style="background:#14532d;">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
            Aplikasi Kas
        </a>

        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
            <a href="{{ route('income.index') }}" class="btn btn-outline-light btn-sm me-2">Income</a>
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm ms-2">Logout</a>
        </div>
    </div>
</nav>


<div class="container">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
