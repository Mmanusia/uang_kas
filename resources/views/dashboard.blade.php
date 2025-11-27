@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Dashboard Bulan {{ now()->translatedFormat('F Y') }}</h3>

    <div class="row">

        {{-- Income --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5>Pemasukan</h5>
                    <h3 class="text-success">Rp {{ number_format($income, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Expense --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body">
                    <h5>Pengeluaran</h5>
                    <h3 class="text-danger">Rp {{ number_format($expense, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Balance --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5>Sisa Saldo</h5>
                    <h3 class="text-primary">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <h4 class="mt-4">Budget Bulan Ini</h4>

    <div class="row">
        @foreach ($budgets as $b)
        <div class="col-md-4 mt-3">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5>{{ $b->group ? $b->group->name : 'Unknown Group' }}</h5>

                    @php
                        // Ambil pengeluaran sesuai group
                        $used = \App\Models\Transaction::where('user_id', auth()->id())
                            ->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)
                            ->whereHas('category', function ($q) use ($b) {
                                $q->where('group_id', $b->group_id);
                            })
                            ->sum('amount');

                        $limit = $b->limit_amount ?? 0;

                        $percent = $limit > 0 ? round(($used / $limit) * 100) : 0;
                        $percent = min($percent, 100);
                    @endphp

                    <p class="mb-1">Digunakan: <b>Rp {{ number_format($used) }}</b></p>
                    <p class="mb-1">Batas: <b>Rp {{ number_format($limit) }}</b></p>

                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar"
                            style="width: {{ $percent }}%">
                        </div>
                    </div>

                    <p class="small text-muted">{{ $percent }}% dari budget</p>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr>

    <h4 class="mt-4">Transaksi Terbaru</h4>

    <div class="card shadow-sm mt-3">
        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latest as $t)
                    <tr>
                        <td>{{ $t->date }}</td>
                        <td>{{ $t->category->name }}</td>
                        <td>{{ $t->description ?? '-' }}</td>
                        <td class="text-end
                            @if($t->category->type == 'expense') text-danger @else text-success @endif">
                            {{ $t->category->type == 'expense' ? '-' : '+' }}
                            Rp {{ number_format($t->amount) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
