<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('web_settings')) {
            Schema::create('web_settings', function (Blueprint $table) {
                $table->id();
                $table->string('setting_key', 100)->unique();
                $table->text('setting_value')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->unsignedInteger('updated_by')->nullable();
            });
        }

        if (! Schema::hasTable('page_accesses')) {
            Schema::create('page_accesses', function (Blueprint $table) {
                $table->id();
                $table->string('page_key', 50);
                $table->unsignedInteger('role_id');
                $table->boolean('is_allowed')->default(false);
                $table->timestamp('updated_at')->nullable();
                $table->unsignedInteger('updated_by')->nullable();

                $table->unique(['page_key', 'role_id']);
                $table->index('role_id');
            });
        }

        $now = now();

        foreach ([
            ['setting_key' => 'company_name', 'setting_value' => 'Electro', 'updated_at' => $now, 'updated_by' => null],
            ['setting_key' => 'company_mark', 'setting_value' => 'E', 'updated_at' => $now, 'updated_by' => null],
        ] as $setting) {
            DB::table('web_settings')->updateOrInsert(['setting_key' => $setting['setting_key']], $setting);
        }

        $defaults = [
            'users' => [3, 4],
            'items' => [1, 3, 4],
            'types' => [1, 3, 4],
            'stock' => [1, 3, 4],
            'report' => [2, 3, 4],
        ];

        foreach ($defaults as $pageKey => $roles) {
            foreach ([1, 2, 3, 4] as $roleId) {
                DB::table('page_accesses')->updateOrInsert(
                    ['page_key' => $pageKey, 'role_id' => $roleId],
                    [
                        'is_allowed' => in_array($roleId, $roles, true),
                        'updated_at' => $now,
                        'updated_by' => null,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_accesses');
        Schema::dropIfExists('web_settings');
    }
};
