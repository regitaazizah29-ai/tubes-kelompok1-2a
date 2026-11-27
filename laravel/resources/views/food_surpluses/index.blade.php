@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">
            {{ auth()->user()->role === 'merchant' ? 'Kelola Surplus Makanan Saya' : 'Katalog Surplus Makanan Layak Konsumsi' }}
        </h4>
        <small class="text-muted">Selamatkan pangan berlebih sebelum melewati batas waktu konsumsi</small>
    </div>

    @if(auth()->user()->role === 'merchant')
        <a href="{{ route('food-surpluses.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Posting Surplus Makanan
        </a>
    @endif
</div>

@if(auth()->user()->role !== 'merchant')
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="category" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Kategori Mitra</option>
            <option value="restoran" {{ $category === 'restoran' ? 'selected' : '' }}>Restoran</option>
            <option value="toko_roti" {{ $category === 'toko_roti' ? 'selected' : '' }}>Toko Roti</option>
            <option value="katering" {{ $category === 'katering' ? 'selected' : '' }}>Katering</option>
            <option value="hotel" {{ $category === 'hotel' ? 'selected' : '' }}>Hotel</option>
        </select>
    </div>
</form>
@endif

<div class="row g-3">
    @forelse($surpluses as $item)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-success-subtle text-success border border-success">
                        {{ $item->merchant->business_name }}
                    </span>
                    <span class="badge {{ $item->expired_at->isPast() ? 'bg-danger' : 'bg-warning text-dark' }}">
                        Batas: {{ $item->expired_at->format('d M H:i') }}
                    </span>
                </div>

                <h5 class="fw-bold">{{ $item->title }}</h5>
                <p class="text-muted small flex-grow-1">{{ $item->description ?? 'Makanan layak konsumsi siap diambil di lokasi mitra.' }}</p>

                <div class="bg-light p-2 rounded small mb-3">
                    <div><strong>Sisa Kuota:</strong> {{ $item->remaining_portions }} / {{ $item->initial_portions }} porsi</div>
                    <div><strong>Berat / Porsi:</strong> {{ $item->weight_per_portion }} gram
                        ({{ round(($item->remaining_portions * $item->weight_per_portion)/1000, 2) }} kg tersisa)
                    </div>
                    <div><strong>Lokasi:</strong> {{ $item->merchant->address }}</div>
                </div>

                @if(auth()->user()->role === 'recipient')
                    <form action="{{ route('claims.store') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="food_surplus_id" value="{{ $item->id }}">
                        <input type="number" name="claimed_portions" class="form-control form-control-sm"
                               min="1" max="{{ $item->remaining_portions }}" placeholder="Jml Porsi" required>
                        <button type="submit" class="btn btn-success btn-sm text-nowrap fw-semibold">
                            Klaim Kuota
                        </button>
                    </form>
                @elseif(auth()->user()->role === 'merchant')
                    <div class="d-flex gap-2">
                        <a href="{{ route('food-surpluses.edit', $item) }}" class="btn btn-outline-primary btn-sm w-50">Edit</a>
                        <form action="{{ route('food-surpluses.destroy', $item) }}" method="POST" class="w-50"
                              onsubmit="return confirm('Hapus item surplus ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-secondary text-center">Belum ada stok surplus makanan yang tersedia saat ini.</div>
    </div>
    @endforelse
</div>
@endsection