<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Retur;
use App\Support\RecordHistoryLogger;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $tab = $request->input('tab', 'now');

        abort_unless(in_array($tab, ['now', 'trash', 'history'], true), 404);

        return view('retur', [
            'search' => $search,
            'tab' => $tab,
            'returs' => $tab === 'history'
                ? null
                : $this->returQuery($search, $tab === 'trash')
                    ->orderByDesc('tanggal_retur')
                    ->orderByDesc('id_retur')
                    ->paginate(5)
                    ->withQueryString(),
            'histories' => $tab === 'history'
                ? $this->historyQuery('retur')
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
            'items' => Item::query()
                ->select('id_barang', 'nama_barang')
                ->orderBy('nama_barang')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $actorId = $this->currentUserId($request);
        $data = $request->validate([
            'id_barang' => ['required', 'integer', 'exists:barang,id_barang'],
            'keterangan' => ['required', 'string', 'max:255'],
            'tanggal_retur' => ['required', 'date'],
        ]);

        $data['created_at'] = now();
        $data['created_by'] = $actorId;

        $retur = Retur::create($data);

        RecordHistoryLogger::log('retur', $retur->getKey(), 'create', null, $this->returState($retur->fresh('item')), $actorId);

        return redirect()
            ->route('retur')
            ->with('status', 'Retur created.');
    }

    public function update(Request $request, $id)
    {
        $actorId = $this->currentUserId($request);
        $retur = Retur::query()->with('item')->findOrFail($id);
        $beforeState = $this->returState($retur);

        $data = $request->validate([
            'id_barang' => ['required', 'integer', 'exists:barang,id_barang'],
            'keterangan' => ['required', 'string', 'max:255'],
            'tanggal_retur' => ['required', 'date'],
        ]);

        $data['updated_at'] = now();
        $data['updated_by'] = $actorId;

        $retur->update($data);

        RecordHistoryLogger::log('retur', $retur->getKey(), 'update', $beforeState, $this->returState($retur->fresh('item')), $actorId);

        return redirect()
            ->route('retur')
            ->with('status', 'Retur updated.');
    }

    public function destroy(Request $request, $id)
    {
        $retur = Retur::query()->with('item')->findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->returState($retur);

        $retur->deleted_by = $actorId;
        $retur->save();
        $retur->delete();

        RecordHistoryLogger::log('retur', $retur->getKey(), 'delete', $beforeState, $this->returState($retur), $actorId);

        return redirect()
            ->route('retur')
            ->with('status', 'Retur moved to trash.');
    }

    public function restore(Request $request, $id)
    {
        $retur = Retur::onlyTrashed()->with('item')->findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->returState($retur);

        $retur->restore();
        $retur->forceFill(['deleted_by' => null])->save();

        RecordHistoryLogger::log('retur', $retur->getKey(), 'restore', $beforeState, $this->returState($retur->fresh('item')), $actorId);

        return redirect()
            ->route('retur', ['tab' => 'trash'])
            ->with('status', 'Retur restored.');
    }

    public function forceDelete(Request $request, $id)
    {
        $retur = Retur::onlyTrashed()->with('item')->findOrFail($id);
        $actorId = $this->currentUserId($request);

        RecordHistoryLogger::log('retur', $retur->getKey(), 'permanent_delete', $this->returState($retur), null, $actorId);
        $retur->forceDelete();

        return redirect()
            ->route('retur', ['tab' => 'trash'])
            ->with('status', 'Retur deleted permanently.');
    }

    public function revertHistory(Request $request, $historyId)
    {
        $history = $this->historyQuery('retur')
            ->where('action', 'update')
            ->findOrFail($historyId);

        $retur = Retur::findOrFail($history->record_id);
        $targetState = $history->before_state ?? [];

        $retur->update([
            'id_barang' => $targetState['id_barang'] ?? $retur->id_barang,
            'keterangan' => $targetState['keterangan'] ?? $retur->keterangan,
            'tanggal_retur' => $targetState['tanggal_retur'] ?? $retur->tanggal_retur,
            'updated_at' => now(),
            'updated_by' => $this->currentUserId($request),
        ]);

        $history->delete();

        return redirect()
            ->route('retur', ['tab' => 'history'])
            ->with('status', 'Retur reverted.');
    }

    private function returQuery(?string $search, bool $trash = false)
    {
        return Retur::query()
            ->with('item')
            ->when($trash, fn ($query) => $query->onlyTrashed())
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id_barang', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhere('tanggal_retur', 'like', "%{$search}%")
                        ->orWhereHas('item', function ($itemQuery) use ($search) {
                            $itemQuery->where('nama_barang', 'like', "%{$search}%");
                        });
                });
            });
    }

    private function returState(Retur $retur): array
    {
        return $this->modelState($retur, [
            'nama_barang' => $retur->item?->nama_barang,
        ]);
    }
}
