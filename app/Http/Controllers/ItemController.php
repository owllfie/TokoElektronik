<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Support\RecordHistoryLogger;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $tab = $request->input('tab', 'now');

        abort_unless(in_array($tab, ['now', 'trash', 'history'], true), 404);

        return view('items', [
            'search' => $search,
            'tab' => $tab,
            'items' => $tab === 'history'
                ? null
                : $this->itemQuery($search, $tab === 'trash')
                    ->orderBy('nama_barang')
                    ->paginate(5)
                    ->withQueryString(),
            'histories' => $tab === 'history'
                ? $this->historyQuery('items')
                    ->where('action', 'update')
                    ->when($search, function ($query, $search) {
                        $query->where(function ($query) use ($search) {
                            $query->where('record_id', 'like', "%{$search}%")
                                ->orWhere('before_state', 'like', "%{$search}%")
                                ->orWhere('after_state', 'like', "%{$search}%");
                        });
                    })
                    ->paginate(5)
                    ->withQueryString()
                : null,
            'types' => \App\Models\Type::query()
                ->select('id_tipe', 'tipe')
                ->orderBy('tipe')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $actorId = $this->currentUserId($request);
        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['nullable', 'string', 'max:50'],
        ]);

        $data['created_at'] = now();
        $data['created_by'] = $actorId;

        $item = Item::create($data);

        RecordHistoryLogger::log('items', $item->getKey(), 'create', null, $this->itemState($item), $actorId);

        return redirect()
            ->route('items')
            ->with('status', 'Item created.');
    }

    public function update(Request $request, $id)
    {
        $actorId = $this->currentUserId($request);
        $item = Item::findOrFail($id);
        $beforeState = $this->itemState($item);

        $data = $request->validate([
            'nama_barang' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
            'tipe' => ['nullable', 'string', 'max:50'],
        ]);

        $data['updated_at'] = now();
        $data['updated_by'] = $actorId;

        $item->update($data);

        RecordHistoryLogger::log('items', $item->getKey(), 'update', $beforeState, $this->itemState($item->fresh()), $actorId);

        return redirect()
            ->route('items')
            ->with('status', 'Item updated.');
    }

    public function destroy(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->itemState($item);

        $item->deleted_by = $actorId;
        $item->save();
        $item->delete();

        RecordHistoryLogger::log('items', $item->getKey(), 'delete', $beforeState, $this->itemState($item), $actorId);

        return redirect()
            ->route('items')
            ->with('status', 'Item moved to trash.');
    }

    public function restore(Request $request, $id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->itemState($item);

        $item->restore();
        $item->forceFill(['deleted_by' => null])->save();

        RecordHistoryLogger::log('items', $item->getKey(), 'restore', $beforeState, $this->itemState($item->fresh()), $actorId);

        return redirect()
            ->route('items', ['tab' => 'trash'])
            ->with('status', 'Item restored.');
    }

    public function forceDelete(Request $request, $id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);

        RecordHistoryLogger::log('items', $item->getKey(), 'permanent_delete', $this->itemState($item), null, $actorId);
        $item->forceDelete();

        return redirect()
            ->route('items', ['tab' => 'trash'])
            ->with('status', 'Item deleted permanently.');
    }

    public function revertHistory(Request $request, $historyId)
    {
        $history = $this->historyQuery('items')
            ->where('action', 'update')
            ->findOrFail($historyId);

        $item = Item::findOrFail($history->record_id);
        $targetState = $history->before_state ?? [];

        $item->update([
            'nama_barang' => $targetState['nama_barang'] ?? $item->nama_barang,
            'stok' => $targetState['stok'] ?? $item->stok,
            'harga' => $targetState['harga'] ?? $item->harga,
            'tipe' => $targetState['tipe'] ?? $item->tipe,
            'updated_at' => now(),
            'updated_by' => $this->currentUserId($request),
        ]);

        $history->delete();

        return redirect()
            ->route('items', ['tab' => 'history'])
            ->with('status', 'Item reverted.');
    }

    private function itemQuery(?string $search, bool $trash = false)
    {
        return Item::query()
            ->when($trash, fn ($query) => $query->onlyTrashed())
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('stok', 'like', "%{$search}%")
                        ->orWhere('harga', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                });
            });
    }

    private function itemState(Item $item): array
    {
        return $this->modelState($item);
    }
}
