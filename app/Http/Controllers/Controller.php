<?php

namespace App\Http\Controllers;

use App\Models\RecordHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function currentUserId(Request $request): ?int
    {
        return data_get($request->session()->get('user'), 'id_user');
    }

    protected function historyQuery(string $entityType)
    {
        return RecordHistory::query()
            ->where('entity_type', $entityType)
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    protected function modelState(Model $model, array $extra = []): array
    {
        return array_merge($model->getAttributes(), $extra);
    }
}
