<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $stocks = Stock::query()
            ->when($search, function ($query, $search) {
                $query->where('id_barang', 'like', "%{$search}%")
                    ->orWhere('jumlah', 'like', "%{$search}%")
                    ->orWhere('harga_satuan', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%");
            })
            ->orderBy('id_barang')
            ->paginate(5)
            ->withQueryString();

        return view('stock', [
            'stocks' => $stocks,
            'search' => $search,
            'barangs' => Item::query()
                ->select('id_barang', 'nama_barang')
                ->orderBy('nama_barang')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang' => ['required', 'integer', Rule::exists('barang', 'id_barang')],
            'jumlah' => ['required', 'integer', 'min:0'],
            'harga_satuan' => ['required', 'integer', 'min:0'],
            'total_harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['required', 'in:in,out'],
        ]);

        DB::transaction(function () use ($data) {
            $item = Item::where('id_barang', $data['id_barang'])->lockForUpdate()->firstOrFail();

            $newStok = $item->stok;
            if ($data['tipe'] === 'in') {
                $newStok += $data['jumlah'];
            } else {
                $newStok -= $data['jumlah'];
            }

            if ($newStok < 0) {
                throw new \RuntimeException('Stock cannot be negative.');
            }

            Stock::create($data);
            $item->update(['stok' => $newStok]);
        });

        return redirect()
            ->route('stock')
            ->with('status', 'Stock created.');
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $data = $request->validate([
            'id_barang' => ['required', 'integer', Rule::exists('barang', 'id_barang')],
            'jumlah' => ['required', 'integer', 'min:0'],
            'harga_satuan' => ['required', 'integer', 'min:0'],
            'total_harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['required', 'in:in,out'],
        ]);

        DB::transaction(function () use ($stock, $data) {
            $oldIdBarang = $stock->id_barang;
            $oldJumlah = $stock->jumlah;
            $oldTipe = $stock->tipe;

            $oldItem = Item::where('id_barang', $oldIdBarang)->lockForUpdate()->firstOrFail();
            $newItem = $oldIdBarang == $data['id_barang']
                ? $oldItem
                : Item::where('id_barang', $data['id_barang'])->lockForUpdate()->firstOrFail();

            // Revert previous stock effect
            $oldStok = $oldItem->stok;
            if ($oldTipe === 'in') {
                $oldStok -= $oldJumlah;
            } else {
                $oldStok += $oldJumlah;
            }

            if ($oldStok < 0) {
                throw new \RuntimeException('Stock cannot be negative.');
            }

            // Apply new stock effect
            $newStok = $newItem->stok;
            if ($data['tipe'] === 'in') {
                $newStok += $data['jumlah'];
            } else {
                $newStok -= $data['jumlah'];
            }

            if ($newStok < 0) {
                throw new \RuntimeException('Stock cannot be negative.');
            }

            $stock->update($data);
            $oldItem->update(['stok' => $oldStok]);
            if ($newItem->id_barang !== $oldItem->id_barang) {
                $newItem->update(['stok' => $newStok]);
            } else {
                $oldItem->update(['stok' => $newStok]);
            }
        });

        return redirect()
            ->route('stock')
            ->with('status', 'Stock updated.');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()
            ->route('stock')
            ->with('status', 'Stock deleted.');
    }
}
