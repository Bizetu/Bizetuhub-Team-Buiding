<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('events', 'fee')) {
            Schema::table('events', function (Blueprint $table): void {
                $table->decimal('fee', 10, 2)->nullable()->after('capacity');
            });
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table): void {
            $table->dropColumn('fee');
        });
    }
};
