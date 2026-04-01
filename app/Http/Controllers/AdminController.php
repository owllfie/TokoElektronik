<?php

namespace App\Http\Controllers;

use App\Support\PageAccessManager;
use App\Support\WebSettingManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function settings()
    {
        return view('admin.settings', [
            'settings' => WebSettingManager::all(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:100'],
            'company_mark' => ['required', 'string', 'max:10'],
        ]);

        $actorId = $this->currentUserId($request);

        foreach ($data as $key => $value) {
            DB::table('web_settings')->updateOrInsert(
                ['setting_key' => $key],
                [
                    'setting_value' => trim($value),
                    'updated_at' => now(),
                    'updated_by' => $actorId,
                ]
            );
        }

        return redirect()
            ->route('admin.settings')
            ->with('status', 'Web settings updated.');
    }

    public function access()
    {
        return view('admin.access', [
            'roles' => DB::table('role')->select('id_role', 'role')->where('id_role', '!=', 4)->orderBy('id_role')->get(),
            'pages' => PageAccessManager::pages(),
            'accessMap' => DB::table('page_accesses')
                ->select('page_key', 'role_id', 'is_allowed')
                ->get()
                ->groupBy('page_key')
                ->map(fn ($rows) => $rows->pluck('is_allowed', 'role_id')->map(fn ($allowed) => (bool) $allowed)->all())
                ->all(),
        ]);
    }

    public function updateAccess(Request $request)
    {
        $roles = DB::table('role')->pluck('id_role')->map(fn ($id) => (int) $id)->all();
        $selected = $request->input('access', []);
        $actorId = $this->currentUserId($request);

        foreach (array_keys(PageAccessManager::pages()) as $pageKey) {
            foreach ($roles as $roleId) {
                DB::table('page_accesses')->updateOrInsert(
                    ['page_key' => $pageKey, 'role_id' => $roleId],
                    [
                        'is_allowed' => $roleId === 4 ? true : isset($selected[$pageKey][$roleId]),
                        'updated_at' => now(),
                        'updated_by' => $actorId,
                    ]
                );
            }
        }

        return redirect()
            ->route('admin.access')
            ->with('status', 'Page access updated.');
    }

    public function backup()
    {
        $backupDirectory = storage_path('app/backups');

        return view('admin.backup', [
            'backups' => collect(File::exists($backupDirectory) ? File::files($backupDirectory) : [])
                ->sortByDesc(fn ($file) => $file->getMTime())
                ->map(fn ($file) => [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'modified_at' => date('Y-m-d H:i:s', $file->getMTime()),
                ])
                ->values(),
        ]);
    }

    public function runBackup()
    {
        $directory = storage_path('app/backups');
        File::ensureDirectoryExists($directory);

        $filename = 'backup-' . now()->format('Ymd-His') . '.sql';
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        File::put($path, $this->buildSqlDump());

        return response()->download($path)->deleteFileAfterSend(false);
    }

    private function buildSqlDump(): string
    {
        $connection = DB::connection();
        $pdo = $connection->getPdo();
        $database = $connection->getDatabaseName();
        $tables = $connection->select('SHOW TABLES');

        $lines = [
            '-- Database backup for ' . $database,
            '-- Generated at ' . now()->toDateTimeString(),
            'SET FOREIGN_KEY_CHECKS=0;',
            '',
        ];

        foreach ($tables as $tableRow) {
            $tableName = array_values((array) $tableRow)[0];
            $createRow = (array) $connection->selectOne('SHOW CREATE TABLE `' . $tableName . '`');
            $createSql = $createRow['Create Table'] ?? end($createRow);

            $lines[] = 'DROP TABLE IF EXISTS `' . $tableName . '`;';
            $lines[] = $createSql . ';';

            $rows = $pdo->query('SELECT * FROM `' . $tableName . '`')->fetchAll(\PDO::FETCH_ASSOC);

            if (! empty($rows)) {
                $columns = array_map(fn ($column) => '`' . $column . '`', array_keys($rows[0]));

                foreach ($rows as $row) {
                    $values = array_map(function ($value) use ($pdo) {
                        if ($value === null) {
                            return 'NULL';
                        }

                        if (is_bool($value)) {
                            return $value ? '1' : '0';
                        }

                        if (is_numeric($value) && ! preg_match('/^0[0-9]+$/', (string) $value)) {
                            return (string) $value;
                        }

                        return $pdo->quote((string) $value);
                    }, array_values($row));

                    $lines[] = 'INSERT INTO `' . $tableName . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';
                }
            }

            $lines[] = '';
        }

        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }
}
