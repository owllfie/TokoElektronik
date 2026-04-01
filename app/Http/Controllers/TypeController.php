<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Support\RecordHistoryLogger;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $tab = $request->input('tab', 'now');

        abort_unless(in_array($tab, ['now', 'trash', 'history'], true), 404);

        return view('types', [
            'search' => $search,
            'tab' => $tab,
            'types' => $tab === 'history'
                ? null
                : $this->typeQuery($search, $tab === 'trash')
                    ->orderBy('tipe')
                    ->paginate(5)
                    ->withQueryString(),
            'histories' => $tab === 'history'
                ? $this->historyQuery('types')
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
        ]);
    }

    public function store(Request $request)
    {
        $actorId = $this->currentUserId($request);
        $data = $request->validate([
            'tipe' => ['required', 'string', 'max:50'],
        ]);

        $data['created_at'] = now();
        $data['created_by'] = $actorId;

        $type = Type::create($data);

        RecordHistoryLogger::log('types', $type->getKey(), 'create', null, $this->typeState($type), $actorId);

        return redirect()
            ->route('types')
            ->with('status', 'Type created.');
    }

    public function update(Request $request, $id)
    {
        $actorId = $this->currentUserId($request);
        $type = Type::findOrFail($id);
        $beforeState = $this->typeState($type);

        $data = $request->validate([
            'tipe' => ['required', 'string', 'max:50'],
        ]);

        $data['updated_at'] = now();
        $data['updated_by'] = $actorId;

        $type->update($data);

        RecordHistoryLogger::log('types', $type->getKey(), 'update', $beforeState, $this->typeState($type->fresh()), $actorId);

        return redirect()
            ->route('types')
            ->with('status', 'Type updated.');
    }

    public function destroy(Request $request, $id)
    {
        $type = Type::findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->typeState($type);

        $type->deleted_by = $actorId;
        $type->save();
        $type->delete();

        RecordHistoryLogger::log('types', $type->getKey(), 'delete', $beforeState, $this->typeState($type), $actorId);

        return redirect()
            ->route('types')
            ->with('status', 'Type moved to trash.');
    }

    public function restore(Request $request, $id)
    {
        $type = Type::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);
        $beforeState = $this->typeState($type);

        $type->restore();
        $type->forceFill(['deleted_by' => null])->save();

        RecordHistoryLogger::log('types', $type->getKey(), 'restore', $beforeState, $this->typeState($type->fresh()), $actorId);

        return redirect()
            ->route('types', ['tab' => 'trash'])
            ->with('status', 'Type restored.');
    }

    public function forceDelete(Request $request, $id)
    {
        $type = Type::onlyTrashed()->findOrFail($id);
        $actorId = $this->currentUserId($request);

        RecordHistoryLogger::log('types', $type->getKey(), 'permanent_delete', $this->typeState($type), null, $actorId);
        $type->forceDelete();

        return redirect()
            ->route('types', ['tab' => 'trash'])
            ->with('status', 'Type deleted permanently.');
    }

    public function revertHistory(Request $request, $historyId)
    {
        $history = $this->historyQuery('types')
            ->where('action', 'update')
            ->findOrFail($historyId);

        $type = Type::findOrFail($history->record_id);
        $targetState = $history->before_state ?? [];

        $type->update([
            'tipe' => $targetState['tipe'] ?? $type->tipe,
            'updated_at' => now(),
            'updated_by' => $this->currentUserId($request),
        ]);

        $history->delete();

        return redirect()
            ->route('types', ['tab' => 'history'])
            ->with('status', 'Type reverted.');
    }

    private function typeQuery(?string $search, bool $trash = false)
    {
        return Type::query()
            ->when($trash, fn ($query) => $query->onlyTrashed())
            ->when($search, function ($query, $search) {
                $query->where('tipe', 'like', "%{$search}%");
            });
    }

    private function typeState(Type $type): array
    {
        return $this->modelState($type);
    }
}
