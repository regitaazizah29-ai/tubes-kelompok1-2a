@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Lengkapi Profil Mitra Kuliner</h4>
                <form action="{{ route('merchants.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Restoran / Toko Roti</label>
                        <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Usaha</label>
                        <select name="category" class="form-select" required>
                            <option value="restoran">Restoran / Rumah Makan</option>
                            <option value="toko_roti">Toko Roti (Bakery)</option>
                            <option value="katering">Jasa Katering</option>
                            <option value="hotel">Hotel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap Pengambilan Makanan</label>
                        <textarea name="address" rows="3" class="form-control" required>{{ old('address') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Profil Mitra</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection