<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $twillUsersTable = config('twill.users_table', 'twill_users');

        if (Schema::hasTable($twillUsersTable)) {
            Schema::table($twillUsersTable, function (Blueprint $table) use ($twillUsersTable) {
                if (Schema::hasColumn($twillUsersTable, 'is_admin')) {
                    $table->unsignedTinyInteger('is_admin')->nullable()->change();
                }

                if (Schema::hasColumn($twillUsersTable, 'is_company_admin')) {
                    $table->unsignedTinyInteger('is_company_admin')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $twillUsersTable = config('twill.users_table', 'twill_users');
        
        if (Schema::hasTable($twillUsersTable) && Schema::hasColumn($twillUsersTable, 'is_admin') && Schema::hasColumn($twillUsersTable, 'is_company_admin')) {
            Schema::table($twillUsersTable, function (Blueprint $table) {
                //
            });
        }
    }
};
