<?php

namespace App\Support;

use App\Models\PageAccess;
use Illuminate\Support\Facades\Schema;

class PageAccessManager
{
    public static function pages(): array
    {
        return [
            'users' => 'Users',
            'items' => 'Items',
            'types' => 'Item Types',
            'stock' => 'Stock',
            'retur' => 'Retur',
            'report' => 'Report',
        ];
    }

    public static function defaultMap(): array
    {
        return [
            'users' => [3, 4],
            'items' => [1, 3, 4],
            'types' => [1, 3, 4],
            'stock' => [1, 3, 4],
            'retur' => [1, 3, 4],
            'report' => [2, 3, 4],
        ];
    }

    public static function canAccess(?int $roleId, string $pageKey): bool
    {
        if (! $roleId) {
            return false;
        }

        if ($roleId === 4) {
            return true;
        }

        if (! array_key_exists($pageKey, static::pages())) {
            return false;
        }

        if (! Schema::hasTable('page_accesses')) {
            return in_array($roleId, static::defaultMap()[$pageKey] ?? [], true);
        }

        $record = PageAccess::query()
            ->where('page_key', $pageKey)
            ->where('role_id', $roleId)
            ->first();

        if (! $record) {
            return in_array($roleId, static::defaultMap()[$pageKey] ?? [], true);
        }

        return (bool) $record->is_allowed;
    }

    public static function allowedPageKeysForRole(?int $roleId): array
    {
        return collect(array_keys(static::pages()))
            ->filter(fn (string $pageKey) => static::canAccess($roleId, $pageKey))
            ->values()
            ->all();
    }
}
