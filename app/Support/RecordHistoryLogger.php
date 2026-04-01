<?php

namespace App\Support;

use App\Models\RecordHistory;

class RecordHistoryLogger
{
    public static function log(
        string $entityType,
        int $recordId,
        string $action,
        ?array $beforeState,
        ?array $afterState,
        ?int $changedBy
    ): void {
        RecordHistory::create([
            'entity_type' => $entityType,
            'record_id' => $recordId,
            'action' => $action,
            'before_state' => $beforeState,
            'after_state' => $afterState,
            'changed_by' => $changedBy,
            'created_at' => now(),
        ]);
    }
}
