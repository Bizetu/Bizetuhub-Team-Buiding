<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompletionReportSeeder extends Seeder
{
    /**
     * Spin up sample data for local development
     */
    public function run(): void
    {
        $reports = [];

        $courses = [
            1  => 'Analysis and Evaluation of Risk Exposure',
            2  => 'Introduction to Occupational Health and Safety',
            3  => 'Fire Safety and Emergency Procedures',
            4  => 'Environmental Management Systems',
            5  => 'Corporate Governance and Ethics',
            6  => 'Data Protection and Privacy',
            7  => 'Anti-Money Laundering Fundamentals',
            8  => 'Project Management Essentials',
        ];

        $completionStatuses = [
            'Completed',
            'Failed',
            'In Progress',
        ];

        $countries = ['1', '2', '3', '4', '5'];

        for ($i = 1; $i <= 200; $i++) {
            $createdAt      = Carbon::now()->subDays(rand(30, 365));
            $enrollmentDate = (clone $createdAt)->subDays(rand(1, 10));
            $status         = $completionStatuses[array_rand($completionStatuses)];
            $completionDate = in_array($status, ['Completed', 'Completed but Failed'])
                ? (clone $enrollmentDate)->addDays(rand(1, 60))
                : null;

            $score    = in_array($status, ['Completed', 'Completed but Failed'])
                ? round(rand(5000, 10000) / 10000, 4)
                : null;
            $maxScore = $score !== null ? 1 : null;

            $courseId = rand(1, 8);

            $effortMinutes = rand(5, 120);
            $effortSeconds = rand(0, 59);
            $effortHours   = sprintf('%d:%02d.0', $effortMinutes, $effortSeconds);

            $firstName = $this->randomFirstName();
            $lastName  = $this->randomLastName();
            $username  = $firstName . ' ' . $lastName;
            $emailSlug = Str::lower($firstName) . '.' . Str::lower($lastName) . rand(1, 999);
            $email     = $emailSlug . '@example.com';

            $reports[] = [
                'deleted_at'        => null,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
                'published'         => rand(0, 1),
                'user_id'           => $i + 100,
                'username'          => $username,
                'email'             => $email,
                'company_id'        => rand(1, 4),
                'department_id'     => rand(1, 4),
                'job_role_id'       => rand(1, 4),
                'branch_id'         => rand(1, 4),
                'course_id'         => $courseId,
                'course'            => $courses[$courseId],
                'employee_number'   => 'EMP' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'country_id'        => $countries[array_rand($countries)],
                'score'             => $score,
                'max_score'         => $maxScore,
                'completion_status' => $status,
                'enrollment_date'   => $enrollmentDate,
                'completion_date'   => $completionDate,
                'user_license_id'   => rand(1, 50),
                'effort_hours'      => $effortHours,
            ];
        }

        foreach (array_chunk($reports, 50) as $chunk) {
            DB::table('completion_reports')->insert($chunk);
        }
    }

    private function randomFirstName(): string
    {
        $names = [
            'James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael',
            'Linda', 'David', 'Barbara', 'William', 'Susan', 'Richard', 'Jessica',
            'Nancy', 'Daniel', 'Betty', 'Margaret', 'Joan',
            'Erick', 'Grace', 'Kevin', 'Alice', 'Peter', 'Esther', 'Samuel',
            'Paul', 'Angela', 'Joan', 'Farwell',
        ];

        return $names[array_rand($names)];
    }

    private function randomLastName(): string
    {
        $names = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller',
            'Davis', 'Wilson', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White',
            'Kwereba', 'Juma', 'Ochieng', 'Mwangi', 'Kamau', 'Otieno',
            'Njoroge', 'Mutua', 'Kariuki', 'Wanjiku', 'Akinyi', 'Adhiambo',
            'Kangethe', 'Nyabera',
        ];

        return $names[array_rand($names)];
    }
}