<?php

namespace App\Http\Controllers;

use App\Exports\StockTableExport;
use App\Models\Item;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $stockIn = $this->stockQuery($search, 'in')
            ->orderByDesc('stock.created_at')
            ->paginate(5, ['*'], 'in_page')
            ->withQueryString();

        $stockOut = $this->stockQuery($search, 'out')
            ->orderByDesc('stock.created_at')
            ->paginate(5, ['*'], 'out_page')
            ->withQueryString();

        return view('stock', [
            'stockIn' => $stockIn,
            'stockOut' => $stockOut,
            'search' => $search,
            'barangs' => Item::query()
                ->select('id_barang', 'nama_barang')
                ->orderBy('nama_barang')
                ->get(),
        ]);
    }

    public function print(Request $request, string $type)
    {
        $search = $request->input('q');

        return view('exports.stock-print', [
            'title' => $this->stockTitle($type),
            'stocks' => $this->stockQuery($search, $type)->orderByDesc('stock.created_at')->get(),
            'meta' => $this->stockMeta($type, $search),
        ]);
    }

    public function pdf(Request $request, string $type)
    {
        $search = $request->input('q');

        $pdf = Pdf::loadView('exports.stock-pdf', [
            'title' => $this->stockTitle($type),
            'stocks' => $this->stockQuery($search, $type)->orderByDesc('stock.created_at')->get(),
            'meta' => $this->stockMeta($type, $search),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('stock-' . $type . '.pdf');
    }

    public function excel(Request $request, string $type)
    {
        $search = $request->input('q');

        return Excel::download(
            new StockTableExport(
                $this->stockTitle($type),
                $this->stockQuery($search, $type)->orderByDesc('stock.created_at')->get(),
                $this->stockMeta($type, $search),
            ),
            'stock-' . $type . '.xlsx'
        );
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

        $data['created_at'] = now();

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

    private function stockQuery(?string $search, ?string $type = null)
    {
        return Stock::query()
            ->leftJoin('barang', 'stock.id_barang', '=', 'barang.id_barang')
            ->select('stock.*', 'barang.nama_barang')
            ->when($type, function ($query, $type) {
                $query->where('stock.tipe', $type);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('stock.id_barang', 'like', "%{$search}%")
                        ->orWhere('barang.nama_barang', 'like', "%{$search}%")
                        ->orWhere('stock.jumlah', 'like', "%{$search}%")
                        ->orWhere('stock.harga_satuan', 'like', "%{$search}%")
                        ->orWhere('stock.total_harga', 'like', "%{$search}%")
                        ->orWhere('stock.tipe', 'like', "%{$search}%");
                });
            });
    }

    private function stockTitle(string $type): string
    {
        return $type === 'in' ? 'Stock In' : 'Stock Out';
    }

    private function stockMeta(string $type, ?string $search): array
    {
        return array_filter([
            'Type' => strtoupper($type),
            'Search' => $search ?: null,
        ]);
    }
}
