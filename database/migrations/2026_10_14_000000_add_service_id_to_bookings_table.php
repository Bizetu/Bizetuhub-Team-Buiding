<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('service_id')->nullable()->after('service')->constrained('services')->nullOnDelete();
        });

        DB::table('bookings')
            ->whereNotNull('service')
            ->orderBy('id')
            ->get(['id', 'service'])
            ->each(function (object $booking): void {
                $serviceId = DB::table('services')
                    ->where('title', $booking->service)
                    ->value('id');

                if ($serviceId !== null) {
                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update(['service_id' => $serviceId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
};
