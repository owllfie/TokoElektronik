<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $items = Item::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('stok', 'like', "%{$search}%")
                    ->orWhere('harga', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%");
            })
            ->orderBy('nama_barang')
            ->paginate(5)
            ->withQueryString();

        return view('items', [
            'items' => $items,
            'search' => $search,
            'types' => \App\Models\Type::query()
                ->select('id_tipe', 'tipe')
                ->orderBy('tipe')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['nullable', 'string', 'max:50'],
        ]);

        Item::create($data);

        return redirect()
            ->route('items')
            ->with('status', 'Item created.');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['nullable', 'string', 'max:50'],
        ]);

        $item->update($data);

        return redirect()
            ->route('items')
            ->with('status', 'Item updated.');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('items')
            ->with('status', 'Item deleted.');
    }
}
