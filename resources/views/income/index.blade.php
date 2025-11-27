@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h3>Input Penghasilan Bulanan</h3>

<form method="POST" action="{{ route('income.store') }}" class="mt-4">
    @csrf

    <div class="mb-3">
        <label>Bulan</label>
        <select name="month" class="form-control" required>
            <option value="" hidden>Pilih Bulan</option>
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Tahun</label>
        <input type="number" name="year" class="form-control" placeholder="2025" required>
    </div>

    <div class="mb-3">
        <label>Jumlah Penghasilan</label>
        <input type="number" name="amount" class="form-control" placeholder="10000000" required>
    </div>

    <button class="btn btn-primary mt-2">Simpan</button>
</form>

@endsection
