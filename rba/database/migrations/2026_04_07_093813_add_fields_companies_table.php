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
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'staff_number')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->integer('staff_number')->nullable();
                $table->string('authentication_method')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'staff_number')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('staff_number');
                $table->dropColumn('authentication_method');
            });
        }
    }
};
