@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Edit Profil Mitra</h4>
                <form action="{{ route('merchants.update', $merchant) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Restoran / Toko Roti</label>
                        <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $merchant->business_name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Usaha</label>
                        <select name="category" class="form-select" required>
                            @foreach(['restoran' => 'Restoran', 'toko_roti' => 'Toko Roti', 'katering' => 'Katering', 'hotel' => 'Hotel'] as $val => $label)
                                <option value="{{ $val }}" {{ $merchant->category === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Pengambilan</label>
                        <textarea name="address" rows="3" class="form-control" required>{{ old('address', $merchant->address) }}</textarea>
                    </div>
                    <a href="{{ route('merchants.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Perbarui Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection