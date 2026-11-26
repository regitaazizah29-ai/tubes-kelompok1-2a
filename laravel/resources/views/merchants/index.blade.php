@extends('layouts.app')

@section('content')
@if(auth()->user()->role === 'admin')
    <h4 class="fw-bold mb-3">Verifikasi Mitra & Rekapitulasi Dampak CSR</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="small opacity-75">Total Mitra Terdaftar</div>
                    <h3 class="fw-bold mb-0">{{ $merchants->count() }} Mitra</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="small opacity-75">Total Porsi Terselamatkan</div>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPlatformPortions) }} Porsi</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-dark text-white">
                <div class="card-body">
                    <div class="small opacity-75">Total Limbah Pangan Dicegah</div>
                    <h3 class="fw-bold mb-0">{{ number_format($totalPlatformWasteKg, 2) }} Kg</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Usaha</th>
                        <th>Pemilik</th>
                        <th>Kategori</th>
                        <th>Dampak CSR</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merchants as $m)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $m->business_name }}</div>
                            <small class="text-muted">{{ $m->address }}</small>
                        </td>
                        <td>{{ $m->user->name }}<br><small class="text-muted">{{ $m->user->phone }}</small></td>
                        <td class="text-uppercase small">{{ str_replace('_', ' ', $m->category) }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $m->saved_portions }} Porsi</span>
                            <span class="badge bg-secondary">{{ $m->saved_waste_kg }} Kg</span>
                        </td>
                        <td>
                            @if($m->is_verified)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('merchants.verify', $m) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $m->is_verified ? 'btn-outline-secondary' : 'btn-success' }}">
                                    {{ $m->is_verified ? 'Batalkan' : 'Verifikasi' }}
                                </button>
                            </form>
                            <a href="{{ route('merchants.edit', $m) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada mitra terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Profil Mitra & Laporan Dampak CSR</h4>
        <a href="{{ route('merchants.edit', $myMerchant) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-pencil"></i> Edit Profil Usaha
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span class="text-muted small">Status Akun Mitra</span>
                    <h5 class="mt-1 mb-0">
                        @if($myMerchant->is_verified)
                            <span class="badge bg-success"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                        @else
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi Admin</span>
                        @endif
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span class="text-muted small">Porsi Terselamatkan</span>
                    <h4 class="fw-bold text-success mt-1 mb-0">{{ $myMerchant->saved_portions }} Porsi</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span class="text-muted small">Pengurangan Limbah Pangan</span>
                    <h4 class="fw-bold text-primary mt-1 mb-0">{{ $myMerchant->saved_waste_kg }} Kg</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold">{{ $myMerchant->business_name }}</h5>
            <p class="mb-1"><strong>Kategori:</strong> {{ ucwords(str_replace('_', ' ', $myMerchant->category)) }}</p>
            <p class="mb-0"><strong>Alamat Pengambilan:</strong> {{ $myMerchant->address }}</p>
        </div>
    </div>
@endif
@endsection