<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('test_attempts')) {
            return;
        }

        $missingColumns = array_filter([
            'total_questions' => ! Schema::hasColumn('test_attempts', 'total_questions'),
            'started_at' => ! Schema::hasColumn('test_attempts', 'started_at'),
            'expires_at' => ! Schema::hasColumn('test_attempts', 'expires_at'),
        ]);

        if ($missingColumns !== []) {
            Schema::table('test_attempts', function (Blueprint $table) use ($missingColumns) {
                if (isset($missingColumns['total_questions'])) {
                    $table->unsignedInteger('total_questions')->default(0)->after('score');
                }

                if (isset($missingColumns['started_at'])) {
                    $table->timestamp('started_at')->nullable()->after('test_id');
                }

                if (isset($missingColumns['expires_at'])) {
                    $table->timestamp('expires_at')->nullable()->after('started_at');
                }
            });
        }

        $updates = [];

        if (Schema::hasColumn('test_attempts', 'started_at')) {
            $updates['started_at'] = DB::raw('COALESCE(created_at, submitted_at)');
        }

        if (Schema::hasColumn('test_attempts', 'expires_at')) {
            $updates['expires_at'] = DB::raw('COALESCE(submitted_at, created_at)');
        }

        if (Schema::hasColumn('test_attempts', 'status')) {
            $updates['status'] = DB::raw("CASE WHEN submitted_at IS NULL THEN 'in_progress' ELSE 'submitted' END");
        }

        if ($updates !== []) {
            DB::table('test_attempts')->update($updates);
        }

        if (! $this->indexExists('test_attempts', 'test_attempts_user_id_test_id_unique')) {
            Schema::table('test_attempts', function (Blueprint $table) {
                $table->unique(['user_id', 'test_id'], 'test_attempts_user_id_test_id_unique');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('test_attempts')) {
            return;
        }

        if ($this->indexExists('test_attempts', 'test_attempts_user_id_test_id_unique')) {
            Schema::table('test_attempts', function (Blueprint $table) {
                $table->dropUnique('test_attempts_user_id_test_id_unique');
            });
        }

        $columnsToDrop = array_values(array_filter([
            Schema::hasColumn('test_attempts', 'total_questions') ? 'total_questions' : null,
            Schema::hasColumn('test_attempts', 'started_at') ? 'started_at' : null,
            Schema::hasColumn('test_attempts', 'expires_at') ? 'expires_at' : null,
        ]));

        if ($columnsToDrop !== []) {
            Schema::table('test_attempts', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }
};
