<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $twillUsersTable = config('twill.users_table', 'twill_users');
        if (Schema::hasTable($twillUsersTable) && !Schema::hasColumn($twillUsersTable, 'policy_agree')) {
            Schema::table($twillUsersTable, function (Blueprint $table) {
                $table->integer('policy_agree')->nullable();
            });
        }
    }

    public function down(): void
    {
        $twillUsersTable = config('twill.users_table', 'twill_users');
        
        if (Schema::hasTable($twillUsersTable) && Schema::hasColumn($twillUsersTable, 'policy_agree')) {
            Schema::table($twillUsersTable, function (Blueprint $table) {
                $table->dropColumn('policy_agree');
            });
        }
    }
};
