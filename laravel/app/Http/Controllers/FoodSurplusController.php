<?php

namespace App\Http\Controllers;

use App\Models\FoodSurplus;
use Illuminate\Http\Request;

class FoodSurplusController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $category = $request->query('category');

        if ($user->role === 'merchant') {
            $merchant = $user->merchant;
            if (!$merchant) {
                return redirect()->route('merchants.create');
            }

            $surpluses = $merchant->foodSurpluses()->latest()->get();
            return view('food_surpluses.index', compact('surpluses', 'category'));
        }

        $query = FoodSurplus::with('merchant')->available();

        if ($category) {
            $query->whereHas('merchant', fn($q) => $q->where('category', $category));
        }

        $surpluses = $query->orderBy('expired_at')->get();
        return view('food_surpluses.index', compact('surpluses', 'category'));
    }

    public function create()
    {
        $merchant = auth()->user()->merchant;
        abort_if(!$merchant, 403);

        if (!$merchant->is_verified) {
            return redirect()->route('food-surpluses.index')
                ->withErrors(['merchant' => 'Akun mitra Anda belum diverifikasi Admin.']);
        }

        return view('food_surpluses.create');
    }

    public function store(Request $request)
    {
        $merchant = auth()->user()->merchant;
        abort_if(!$merchant || !$merchant->is_verified, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_portions' => 'required|integer|min:1',
            'weight_per_portion' => 'required|integer|min:50',
            'expired_at' => 'required|date|after:now',
        ]);

        $validated['merchant_id'] = $merchant->id;
        $validated['remaining_portions'] = $validated['initial_portions'];

        FoodSurplus::create($validated);

        return redirect()->route('food-surpluses.index')
            ->with('success', 'Katalog surplus makanan berhasil dipublikasikan.');
    }

    public function edit(FoodSurplus $foodSurplus)
    {
        abort_if($foodSurplus->merchant->user_id !== auth()->id(), 403);
        return view('food_surpluses.edit', compact('foodSurplus'));
    }

    public function update(Request $request, FoodSurplus $foodSurplus)
    {
        abort_if($foodSurplus->merchant->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'remaining_portions' => 'required|integer|min:0|max:' . $foodSurplus->initial_portions,
            'weight_per_portion' => 'required|integer|min:50',
            'expired_at' => 'required|date',
        ]);

        $foodSurplus->update($validated);

        return redirect()->route('food-surpluses.index')->with('success', 'Katalog surplus diperbarui.');
    }

    public function destroy(FoodSurplus $foodSurplus)
    {
        abort_if($foodSurplus->merchant->user_id !== auth()->id(), 403);

        if ($foodSurplus->claims()->exists()) {
            return back()->withErrors(['delete' => 'Item sudah memiliki riwayat klaim dan tidak dapat dihapus.']);
        }

        $foodSurplus->delete();
        return back()->with('success', 'Item surplus makanan dihapus.');
    }
}