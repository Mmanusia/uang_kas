<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Aplikasi Kas</a>

        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
            <a href="{{ route('income.index') }}" class="btn btn-outline-light btn-sm me-2">Income</a>
            <a href="{{ route('transaction.index') }}" class="btn btn-outline-light btn-sm me-2">Transaksi</a>
            <a href="{{ route('report.index') }}" class="btn btn-outline-light btn-sm">Laporan</a>
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm ms-2">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>
