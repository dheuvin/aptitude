<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_answers') || ! Schema::hasColumn('user_answers', 'selected_answer')) {
            return;
        }

        Schema::table('user_answers', function (Blueprint $table) {
            $table->string('selected_answer')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('user_answers') || ! Schema::hasColumn('user_answers', 'selected_answer')) {
            return;
        }

        Schema::table('user_answers', function (Blueprint $table) {
            $table->string('selected_answer')->nullable(false)->change();
        });
    }
};
