<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompletionReportsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithStyles, WithColumnWidths
{
    public function __construct(protected $query) {}

    public function query()
    {
        return $this->query->with(['jobRole','company','department','branch','courseRelationship']);
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
            $row->job_role_title_value ?? 'N/A',
            $row->company_title_value ?? 'N/A',
            $row->department_title_value ?? 'N/A',
            $row->branch_title_value ?? 'N/A',
            $row->course ?? 'N/A',
            $row->completion_status ?? 'N/A',
            $row->date_enrolled ?? 'N/A',
            $row->date_completed ?? 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'USERNAME',
            'EMAIL',
            'JOB ROLE',
            'COMPANY',
            'DEPARTMENT',
            'BRANCH',
            'COURSE',
            'COMPLETION STATUS',
            'ENROLLMENT DATE',
            'COMPLETION DATE',
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
            'A' => 25,
            'B' => 35,
            'C' => 30,
            'D' => 30,
            'E' => 30,
            'F' => 30,
            'G' => 40,
            'H' => 25,
            'I' => 20,
            'J' => 20,
        ];
    }
}