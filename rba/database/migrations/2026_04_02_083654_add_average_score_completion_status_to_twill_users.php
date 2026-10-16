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
        Schema::table('twill_users', function (Blueprint $table) {
            //
            $table->string('average_score')->default(0);
            $table->string('progress_mandatory')->default(0);
            $table->string('completion_status')->nullable();
            $table->string('email_counter')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('twill_users', function (Blueprint $table) {
            //
            $table->dropColumn('average_score');
            $table->dropColumn('progress_mandatory');
            $table->dropColumn('completion_status');
            $table->dropColumn('email_counter');
        });
    }
};
