<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('excerpt', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('services')
            ->whereNull('excerpt')
            ->update(['excerpt' => '']);

        Schema::table('services', function (Blueprint $table) {
            $table->string('excerpt', 500)->nullable(false)->change();
        });
    }
};
