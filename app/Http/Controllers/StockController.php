<?php

namespace App\Http\Controllers;

use App\Exports\StockTableExport;
use App\Models\Item;
use App\Models\Stock;
use App\Support\RecordHistoryLogger;
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
        $tab = $request->input('tab', 'now');

        abort_unless(in_array($tab, ['now', 'trash', 'history'], true), 404);

        return view('stock', [
            'stockIn' => $tab === 'history'
                ? null
                : $this->stockQuery($search, 'in', $tab === 'trash')
                    ->orderByDesc('stock.created_at')
                    ->paginate(5, ['*'], 'in_page')
                    ->withQueryString(),
            'stockOut' => $tab === 'history'
                ? null
                : $this->stockQuery($search, 'out', $tab === 'trash')
                    ->orderByDesc('stock.created_at')
                    ->paginate(5, ['*'], 'out_page')
                    ->withQueryString(),
            'search' => $search,
            'tab' => $tab,
            'histories' => $tab === 'history'
                ? $this->historyQuery('stock')
                    ->where('action', 'update')
                    ->when($search, function ($query, $search) {
                        $query->where(function ($query) use ($search) {
                            $query->where('record_id', 'like', "%{$search}%")
                                ->orWhere('before_state', 'like', "%{$search}%")
                                ->orWhere('after_state', 'like', "%{$search}%");
                        });
                    })
                    ->paginate(8)
                    ->withQueryString()
                : null,
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
        $actorId = $this->currentUserId($request);
        $data = $request->validate([
            'id_barang' => ['required', 'integer', Rule::exists('barang', 'id_barang')],
            'jumlah' => ['required', 'integer', 'min:0'],
            'harga_satuan' => ['required', 'integer', 'min:0'],
            'total_harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['required', 'in:in,out'],
        ]);

        $data['created_at'] = now();
        $data['created_by'] = $actorId;

        DB::transaction(function () use ($data, $actorId) {
            $item = Item::where('id_barang', $data['id_barang'])->lockForUpdate()->firstOrFail();
            $this->applyStockEffect($item, $data['tipe'], (int) $data['jumlah']);
            $item->save();

            $stock = Stock::create($data);

            RecordHistoryLogger::log('stock', $stock->getKey(), 'create', null, $this->stockState($stock), $actorId);
        });

        return redirect()
            ->route('stock')
            ->with('status', 'Stock created.');
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->stockState($stock);

        $data = $request->validate([
            'id_barang' => ['required', 'integer', Rule::exists('barang', 'id_barang')],
            'jumlah' => ['required', 'integer', 'min:0'],
            'harga_satuan' => ['required', 'integer', 'min:0'],
            'total_harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['required', 'in:in,out'],
        ]);

        $data['updated_at'] = now();
        $data['updated_by'] = $actorId;

        DB::transaction(function () use ($stock, $data, $beforeState, $actorId) {
            $this->syncStockRecord($stock, $beforeState, $data);

            RecordHistoryLogger::log('stock', $stock->getKey(), 'update', $beforeState, $this->stockState($stock->fresh()), $actorId);
        });

        return redirect()
            ->route('stock')
            ->with('status', 'Stock updated.');
    }

    public function destroy(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->stockState($stock);

        DB::transaction(function () use ($stock, $actorId, $beforeState) {
            $item = Item::where('id_barang', $stock->id_barang)->lockForUpdate()->firstOrFail();
            $this->applyStockEffect($item, $stock->tipe, (int) $stock->jumlah, true);
            $item->save();

            $stock->deleted_by = $actorId;
            $stock->save();
            $stock->delete();

            RecordHistoryLogger::log('stock', $stock->getKey(), 'delete', $beforeState, $this->stockState($stock), $actorId);
        });

        return redirect()
            ->route('stock')
            ->with('status', 'Stock moved to trash.');
    }

    public function restore(Request $request, $id)
    {
        $stock = Stock::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->stockState($stock);

        DB::transaction(function () use ($stock, $actorId, $beforeState) {
            $item = Item::where('id_barang', $stock->id_barang)->lockForUpdate()->firstOrFail();
            $this->applyStockEffect($item, $stock->tipe, (int) $stock->jumlah);
            $item->save();

            $stock->restore();
            $stock->forceFill(['deleted_by' => null])->save();

            RecordHistoryLogger::log('stock', $stock->getKey(), 'restore', $beforeState, $this->stockState($stock->fresh()), $actorId);
        });

        return redirect()
            ->route('stock', ['tab' => 'trash'])
            ->with('status', 'Stock restored.');
    }

    public function forceDelete(Request $request, $id)
    {
        $stock = Stock::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);

        RecordHistoryLogger::log('stock', $stock->getKey(), 'permanent_delete', $this->stockState($stock), null, $actorId);
        $stock->forceDelete();

        return redirect()
            ->route('stock', ['tab' => 'trash'])
            ->with('status', 'Stock deleted permanently.');
    }

    public function revertHistory(Request $request, $historyId)
    {
        $history = $this->historyQuery('stock')
            ->where('action', 'update')
            ->findOrFail($historyId);

        $stock = Stock::findOrFail($history->record_id);
        $beforeState = $this->stockState($stock);
        $targetState = $history->before_state ?? [];

        DB::transaction(function () use ($stock, $beforeState, $targetState, $history, $request) {
            $payload = [
                'id_barang' => $targetState['id_barang'] ?? $stock->id_barang,
                'jumlah' => $targetState['jumlah'] ?? $stock->jumlah,
                'harga_satuan' => $targetState['harga_satuan'] ?? $stock->harga_satuan,
                'total_harga' => $targetState['total_harga'] ?? $stock->total_harga,
                'tipe' => $targetState['tipe'] ?? $stock->tipe,
                'updated_at' => now(),
                'updated_by' => $this->currentUserId($request),
            ];

            $this->syncStockRecord($stock, $beforeState, $payload);
            $history->delete();
        });

        return redirect()
            ->route('stock', ['tab' => 'history'])
            ->with('status', 'Stock reverted.');
    }

    private function stockQuery(?string $search, ?string $type = null, bool $trash = false)
    {
        return Stock::query()
            ->when($trash, fn ($query) => $query->onlyTrashed())
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

    private function applyStockEffect(Item $item, string $type, int $jumlah, bool $reverse = false): void
    {
        $delta = $type === 'in' ? $jumlah : -$jumlah;

        if ($reverse) {
            $delta *= -1;
        }

        $newStok = $item->stok + $delta;

        if ($newStok < 0) {
            throw new \RuntimeException('Stock cannot be negative.');
        }

        $item->stok = $newStok;
    }

    private function syncStockRecord(Stock $stock, array $beforeState, array $afterPayload): void
    {
        $oldItem = Item::where('id_barang', $beforeState['id_barang'])->lockForUpdate()->firstOrFail();
        $newItem = (int) $beforeState['id_barang'] === (int) $afterPayload['id_barang']
            ? $oldItem
            : Item::where('id_barang', $afterPayload['id_barang'])->lockForUpdate()->firstOrFail();

        $this->applyStockEffect($oldItem, $beforeState['tipe'], (int) $beforeState['jumlah'], true);
        $this->applyStockEffect($newItem, $afterPayload['tipe'], (int) $afterPayload['jumlah']);

        $stock->update($afterPayload);
        $oldItem->save();

        if ($newItem->getKey() !== $oldItem->getKey()) {
            $newItem->save();
        }
    }

    private function stockState(Stock $stock): array
    {
        return $this->modelState($stock);
    }
}
