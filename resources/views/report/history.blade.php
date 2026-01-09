@extends('layouts.app')

@section('content')

<h3>Riwayat Laporan Bulanan</h3>

<div class="mt-3">
    <a href="{{ route('report.index') }}" class="btn btn-secondary">Kembali ke Laporan Bulanan</a>
</div>

@if(count($history) > 0)
<div class="mt-4">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Penghasilan</th>
                    <th>Pengeluaran</th>
                    <th>Budget Total</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $item)
                <tr>
                    <td>{{ $item->month_name }}</td>
                    <td>Rp {{ number_format($item->income, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->spent, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->budget, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->remaining, 0, ',', '.') }}</td>
                    <td>
                        @if($item->remaining >= 0)
                            <span class="badge bg-success">Surplus</span>
                        @else
                            <span class="badge bg-danger">Defisit</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('report.index', ['year' => $item->year, 'month' => $item->month]) }}" class="btn btn-sm btn-primary">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="mt-4">
    <p>Tidak ada data riwayat yang tersedia.</p>
</div>
@endif

@endsection
