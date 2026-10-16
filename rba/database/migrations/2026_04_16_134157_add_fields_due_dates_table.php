<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('due_dates') && !Schema::hasColumn('due_dates', 'branch_id')) {
            Schema::table('due_dates', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('company_id');
                $table->unsignedBigInteger('department_id')->nullable()->after('branch_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('due_dates') && Schema::hasColumn('due_dates', 'branch_id')) {
            Schema::table('due_dates', function (Blueprint $table) {
                $table->dropColumn(['branch_id', 'department_id']);
            });
        }
    }
};
