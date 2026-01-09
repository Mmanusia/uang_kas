@extends('layouts.app')

@section('content')

<h3>Laporan Bulanan</h3>

<div class="mt-3">
    <a href="{{ route('report.history') }}" class="btn btn-info">Lihat Riwayat Bulanan</a>
</div>

<form class="mt-3">
    <div class="row">
        <div class="col-md-2">
            <input type="number" name="month" placeholder="Bulan" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="number" name="year" placeholder="Tahun" class="form-control">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Lihat</button>
        </div>
    </div>
</form>

@if(isset($report))
<div class="mt-4">

    <h4>Penghasilan: Rp {{ number_format($report->income_main, 0, ',', '.') }}</h4>

    <div class="row mt-3">
    @foreach ($report->budget_summary as $group => $data)
        <div class="col-md-4">
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h5>{{ $group }}</h5>
                    <p>Limit: Rp {{ number_format($data->limit, 0, ',', '.') }}</p>
                    <p>Dibayar: Rp {{ number_format($data->spent, 0, ',', '.') }}</p>
                    <p>Sisa: Rp {{ number_format($data->remaining, 0, ',', '.') }}</p>
                    <span class="badge bg-{{ $data->status == 'overbudget' ? 'danger' : 'success' }}">
                        {{ $data->status }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach
    </div>

</div>
@endif

@endsection
