<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retur', function (Blueprint $table) {
            if (! Schema::hasColumn('retur', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('updated_by');
            }

            if (! Schema::hasColumn('retur', 'deleted_by')) {
                $table->unsignedInteger('deleted_by')->nullable()->after('deleted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('retur', function (Blueprint $table) {
            if (Schema::hasColumn('retur', 'deleted_by')) {
                $table->dropColumn('deleted_by');
            }

            if (Schema::hasColumn('retur', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }
};
