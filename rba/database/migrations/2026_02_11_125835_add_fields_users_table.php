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
        if (Schema::hasTable($twillUsersTable) && !Schema::hasColumn($twillUsersTable, 'username')) {
            Schema::table($twillUsersTable, function (Blueprint $table) {
                $table->string('username')->after('email')->nullable();
                $table->string('first_name')->after('name')->nullable();
                $table->string('last_name')->after('first_name')->nullable();
                $table->string('phone_number')->nullable();
                $table->timestamp('email_verified_at')->after('language')->nullable();
                $table->string('is_admin')->after('is_superadmin')->nullable();
                $table->string('is_company_admin')->after('is_admin')->nullable();

                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('company_name')->nullable();
                
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('department_name')->nullable();

                $table->unsignedBigInteger('branch_id')->nullable();
                $table->string('branch_name')->nullable();

                $table->unsignedBigInteger('job_role_id')->nullable();
                $table->string('job_role')->nullable();

                $table->integer('gender_id')->nullable();
                $table->string('profile_pic')->default(0);
                $table->string('initial_profile')->default(0);

                $table->string('google_id')->nullable();
                $table->string('microsoft_id')->nullable();
                $table->string('authentication_type')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $twillUsersTable = config('twill.users_table', 'twill_users');
        
        if (Schema::hasTable($twillUsersTable) && Schema::hasColumn($twillUsersTable, 'username')) {
            Schema::table($twillUsersTable, function (Blueprint $table) use ($twillUsersTable) {                
                $table->dropColumn([
                    'username',
                    'first_name',
                    'last_name',
                    'phone_number',
                    'email_verified_at',
                    'is_admin',
                    'is_company_admin',
                    'company_id',
                    'company_name',
                    'department_id',
                    'department_name',
                    'branch_id',
                    'branch_name',
                    'job_role_id',
                    'job_role',
                    'gender_id',
                    'profile_pic',
                    'initial_profile',
                    'google_id',
                    'microsoft_id',
                    'authentication_type',
                ]);
            });
        }
        
        if (Schema::hasTable($twillUsersTable) && Schema::hasColumn($twillUsersTable, 'title') && Schema::hasColumn($twillUsersTable, 'description')) {
            Schema::table($twillUsersTable, function (Blueprint $table) {
                $table->dropColumn('title');
                $table->dropColumn('description');
            });
        }
    }
};