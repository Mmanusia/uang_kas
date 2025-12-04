@extends('layouts.app')

@section('content')
<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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

    <div class="d-flex justify-content-between align-items-center mt-4">
        <h4>Budget Bulan Ini</h4>
        <div>
            <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </button>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editBudgetModal">
                <i class="fas fa-edit"></i> Ubah Persentase
            </button>
        </div>
    </div>

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

    <!-- transaksi -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTransactionForm" action="/transactions" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="transactionCategory" class="form-label">Kategori</label>
                            <select class="form-control" name="category_id" id="transactionCategory" required>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->group ? $c->group->name : 'No Group' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="transactionDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="date" id="transactionDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="transactionAmount" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="amount" id="transactionAmount" required>
                        </div>
                        <div class="mb-3">
                            <label for="transactionDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" id="transactionDescription" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- budget percentages -->
    <div class="modal fade" id="editBudgetModal" tabindex="-1" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBudgetModalLabel">Ubah Persentase Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBudgetForm">
                        <div class="mb-3">
                            <label for="livingPercent" class="form-label">Living (%)</label>
                            <input type="number" class="form-control" id="livingPercent" min="0" max="100" value="{{ $currentPercentages['living'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="playingPercent" class="form-label">Playing (%)</label>
                            <input type="number" class="form-control" id="playingPercent" min="0" max="100" value="{{ $currentPercentages['playing'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="savingPercent" class="form-label">Saving (%)</label>
                            <input type="number" class="form-control" id="savingPercent" min="0" max="100" value="{{ $currentPercentages['saving'] }}" required>
                        </div>
                        <div id="totalError" class="alert alert-danger d-none">
                            Total persentase harus 100%.
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editBudgetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const living = parseInt(document.getElementById('livingPercent').value);
            const playing = parseInt(document.getElementById('playingPercent').value);
            const saving = parseInt(document.getElementById('savingPercent').value);
            const total = living + playing + saving;

            if (total !== 100) {
                document.getElementById('totalError').classList.remove('d-none');
                return;
            }

            document.getElementById('totalError').classList.add('d-none');

            // AJAX request to update percentages
            fetch('/budgets/update-percentages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    living: living,
                    playing: playing,
                    saving: saving
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal mengupdate persentase.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan.');
            });
        });
    </script>

</div>
@endsection
