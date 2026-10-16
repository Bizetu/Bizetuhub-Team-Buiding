<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithStyles, WithColumnWidths
{
    public function __construct(protected $query) {}

    public function query()
    {
        return $this->query->with(['company','department','branch']);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->email,
            $row->gender_value,
            $row->company_name_value,
            $row->department_name_value,
            $row->branch_name_value,
            $row->job_role_name_value,
            $row->date_created,
            $row->last_login_at_value,
        ];
    }

    public function headings(): array
    {
        return [
            'USERNAME',
            'EMAIL',
            'GENDER',
            'BANK',
            'DEPARTMENT',
            'BRANCH',
            'JOB ROLE',
            'REGISTRATION DATE',
            'LAST LOGIN AT',
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
            'A' => 20,
            'B' => 40,
            'C' => 15,
            'D' => 40,
            'E' => 10,
            'F' => 40,
            'G' => 10,
            'H' => 40,
            'I' => 10,
            'J' => 40,
            'K' => 10,
            'L' => 40,
            'M' => 10,
            'N' => 40,
            'O' => 10,
            'P' => 40,
            'Q' => 10,
            'R' => 40,
            'S' => 10,
            'T' => 40,
            'U' => 10,
            'V' => 40,
            'W' => 10,
        ];
    }
}