@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h3>Input Transaksi</h3>

<form method="POST" action="{{ route('transaction.store') }}" class="mt-4">
    @csrf

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-control" required>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->type }})</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="amount" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>

    <button class="btn btn-success">Simpan Transaksi</button>
</form>

@endsection
