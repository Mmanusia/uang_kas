<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register - Buku Kas</title>

    <style>
        body{
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: linear-gradient(135deg,#eef2ff,#fdf2f8);
            display:flex; align-items:center; justify-content:center;
            min-height:100vh; margin:0;
        }

        .card{
            width:380px; background:white;
            padding:32px 28px; border-radius:14px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
            animation:fade 0.5s ease;
        }

        @keyframes fade{
            from{opacity:0; transform:translateY(10px);}
            to{opacity:1; transform:translateY(0);}
        }

        h2{margin:0 0 12px; font-size:22px; text-align:center; font-weight:700;}
        p.sub{margin:4px 0 20px; text-align:center; color:#6b7280; font-size:14px;}

        label{font-size:13px;font-weight:600;color:#374151;margin-top:14px;display:block;}
        input{
            width:100%; padding:10px 12px; margin-top:6px;
            border:1px solid #d1d5db; border-radius:8px;
            transition:.2s;
        }
        input:focus{
            border-color:#2563eb; box-shadow:0 0 0 2px rgba(37,99,235,.2);
        }

        .error{color:#b91c1c;font-size:13px;margin-top:6px;}

        .btn{
            width:100%; border:none; cursor:pointer;
            margin-top:22px; padding:11px 14px; font-size:15px;
            background:#2563eb; color:white; border-radius:8px;
            font-weight:600; transition:.25s;
        }
        .btn:hover{background:#1e40af;}

        .link{display:block;text-align:center;margin-top:14px;font-size:14px;}
        .link a{text-decoration:none;color:#2563eb;font-weight:600;}
    </style>
</head>

<body>

<div class="card">

    <h2>Buat Akun Baru</h2>
    <p class="sub">web kas</p>

    @if(session('success'))
        <div style="background:#ecfdf5;padding:10px;border-radius:6px;color:#065f46;margin-bottom:12px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2;padding:10px;border-radius:6px;color:#991b1b;margin-bottom:12px;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin:8px 0 0;padding-left:18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.process') }}" method="POST">
        @csrf

        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name') <div class="error">{{ $message }}</div> @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label>Password</label>
        <input type="password" name="password" required>
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit" class="btn">Daftar</button>
    </form>

    <div class="link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </div>

</div>

</body>
</html>
