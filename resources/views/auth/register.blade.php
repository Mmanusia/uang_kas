<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register - Buku Kas</title>
    <style>
        /* Styling sederhana supaya langsung terlihat rapi */
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f6f7fb; color:#222; }
        .container { max-width:420px; margin:48px auto; background:#fff; padding:24px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
        h1 { margin:0 0 12px; font-size:20px; }
        label { display:block; margin-top:12px; font-weight:600; font-size:13px; }
        input[type="text"], input[type="email"], input[type="password"] {
            width:100%; padding:10px 12px; margin-top:6px; border:1px solid #e2e8f0; border-radius:6px;
        }
        .error { color:#b91c1c; font-size:13px; margin-top:6px; }
        .btn { display:inline-block; margin-top:18px; padding:10px 14px; border-radius:6px; background:#2563eb; color:#fff; text-decoration:none; border:none; cursor:pointer; }
        .muted { color:#6b7280; font-size:14px; margin-top:12px; }
        .top-links { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
        .small { font-size:13px; color:#374151; }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-links">
            <h1>Daftar Akun</h1>
            <a class="small" href="{{ route('login') }}">Sudah punya akun? Masuk</a>
        </div>

        @if(session('success'))
            <div style="background:#ecfdf5;padding:10px;border-radius:6px;color:#065f46;margin-bottom:12px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin:8px 0 0;padding-left:18px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" novalidate>
            @csrf

            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

            <button class="btn" type="submit">Daftar</button>
        </form>

    </div>
</body>
</html>
