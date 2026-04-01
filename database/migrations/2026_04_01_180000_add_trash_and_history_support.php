<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['barang', 'tipe', 'stock', 'users'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->timestamp('deleted_at')->nullable()->after('updated_by');
                }

                if (! Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->unsignedInteger('deleted_by')->nullable()->after('deleted_at');
                }
            });
        }

        if (! Schema::hasTable('record_histories')) {
            Schema::create('record_histories', function (Blueprint $table) {
                $table->id();
                $table->string('entity_type', 50);
                $table->unsignedBigInteger('record_id');
                $table->string('action', 30);
                $table->json('before_state')->nullable();
                $table->json('after_state')->nullable();
                $table->unsignedInteger('changed_by')->nullable();
                $table->timestamp('created_at')->nullable();

                $table->index(['entity_type', 'record_id']);
                $table->index(['entity_type', 'action']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('record_histories')) {
            Schema::drop('record_histories');
        }

        foreach (['barang', 'tipe', 'stock', 'users'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->dropColumn('deleted_by');
                }

                if (Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropColumn('deleted_at');
                }
            });
        }
    }
};
