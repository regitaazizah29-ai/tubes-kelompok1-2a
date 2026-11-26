<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $merchants = Merchant::with('user')->latest()->get();
            $totalPlatformPortions = $merchants->sum->saved_portions;
            $totalPlatformWasteKg = $merchants->sum->saved_waste_kg;

            return view('merchants.index', compact('merchants', 'totalPlatformPortions', 'totalPlatformWasteKg'));
        }

        abort_if($user->role !== 'merchant', 403);

        $myMerchant = $user->merchant;
        if (!$myMerchant) {
            return redirect()->route('merchants.create');
        }

        return view('merchants.index', compact('myMerchant'));
    }

    public function create()
    {
        abort_if(auth()->user()->role !== 'merchant', 403);
        if (auth()->user()->merchant) {
            return redirect()->route('merchants.index');
        }

        return view('merchants.create');
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'merchant', 403);

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'required|in:restoran,toko_roti,katering,hotel',
            'address' => 'required|string',
        ]);

        auth()->user()->merchant()->create($validated);

        return redirect()->route('merchants.index')
            ->with('success', 'Profil mitra berhasil disimpan. Menunggu verifikasi Admin.');
    }

    public function edit(Merchant $merchant)
    {
        $user = auth()->user();
        abort_if($user->role !== 'admin' && $merchant->user_id !== $user->id, 403);

        return view('merchants.edit', compact('merchant'));
    }

    public function update(Request $request, Merchant $merchant)
    {
        $user = auth()->user();
        abort_if($user->role !== 'admin' && $merchant->user_id !== $user->id, 403);

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'required|in:restoran,toko_roti,katering,hotel',
            'address' => 'required|string',
        ]);

        $merchant->update($validated);

        return redirect()->route('merchants.index')->with('success', 'Profil mitra berhasil diperbarui.');
    }

    public function verify(Merchant $merchant)
    {
        abort_if(auth()->user()->role !== 'admin', 403);

        $merchant->update(['is_verified' => !$merchant->is_verified]);

        return back()->with('success', 'Status verifikasi mitra ' . $merchant->business_name . ' diperbarui.');
    }

    public function destroy(Merchant $merchant)
    {
        abort_if(auth()->user()->role !== 'admin', 403);

        $merchant->delete();
        return back()->with('success', 'Data mitra berhasil dihapus.');
    }
}