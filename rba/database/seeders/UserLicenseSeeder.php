<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserLicenseSeeder extends Seeder
{
    /**
     * Spin up sample data for local development
     */
    public function run(): void
    {
        $licenses = [];

        for ($i = 1; $i <= 200; $i++) {
            $createdAt  = Carbon::now()->subDays(rand(30, 365));
            $expiredAt   = Carbon::createFromFormat('Y', rand(2025, 2030))->endOfYear();

            $firstName  = $this->randomFirstName();
            $lastName   = $this->randomLastName();
            $username   = $firstName . ' ' . $lastName;
            $emailSlug  = Str::lower($firstName) . '.' . Str::lower($lastName) . rand(1, 999);
            $email      = $emailSlug . '@example.com';

            $licenses[] = [
                'deleted_at'         => null,
                'created_at'         => $createdAt,
                'updated_at'         => $createdAt,
                'published'          => rand(0, 1),
                'course_id'          => rand(1, 8),
                'user_id'            => $i + 100,
                'email'              => $email,
                'username'           => $username,
                'company_id'         => rand(1, 4),
                'job_role_id'        => rand(1, 4),
                'branch_id'          => rand(1, 4),
                'department_id'      => rand(1, 4),
                'expired_at'         => $expiredAt,
                'position'           => $i,
            ];
        }

        foreach (array_chunk($licenses, 50) as $chunk) {
            DB::table('user_licenses')->insert($chunk);
        }
    }

    private function randomFirstName(): string
    {
        $names = [
            'James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael',
            'Linda', 'David', 'Barbara', 'William', 'Susan', 'Richard', 'Jessica',
            'Nancy', 'Daniel', 'Betty', 'Userthri', 'Margaret', 'Usertu', 'Joan',
            'Erick', 'Grace', 'Kevin', 'Alice', 'Peter', 'Esther', 'Samuel',
        ];

        return $names[array_rand($names)];
    }

    private function randomLastName(): string
    {
        $names = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller',
            'Davis', 'Wilson', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White',
            'Userwan', 'Kwereba', 'Juma', 'Ochieng', 'Mwangi', 'Kamau', 'Otieno',
            'Njoroge', 'Mutua', 'Kariuki', 'Wanjiku', 'Akinyi', 'Adhiambo',
        ];

        return $names[array_rand($names)];
    }
}