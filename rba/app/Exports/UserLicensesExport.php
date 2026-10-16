<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserLicensesExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithStyles, WithColumnWidths
{
    public function __construct(protected $query) {}

    public function query()
    {
        return $this->query->with(['jobRole','company','department','branch','course']);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function map($row): array
    {
        return [
            $row->username,
            $row->email,
            $row->company_title_value ?? 'N/A',
            $row->job_role_title_value ?? 'N/A',
            $row->department_title_value ?? 'N/A',
            $row->branch_title_value ?? 'N/A',
            $row->course_title_value ?? 'N/A',
            $row->date_created ?? 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'EMAIL',
            'COMPANY',
            'JOB ROLE',
            'DEPARTMENT',
            'BRANCH',
            'COURSE',
            'ENROLLMENT DATE',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 40,
            'C' => 30,
            'D' => 30,
            'E' => 30,
            'F' => 30,
            'G' => 30,
            'H' => 20,
        ];
    }
}