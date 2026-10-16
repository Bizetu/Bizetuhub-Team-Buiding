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

class CertifiedUsersExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithColumnWidths, WithStyles
{
    public function __construct(protected $query) {}

    public function query()
    {
        return $this->query;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function map($row): array
    {
        return [
            $this->clean($row->name),
            $this->clean($row->email),
            $this->clean($row->company),
            $this->clean($row->department),
            $this->clean($row->jobrole),
            $this->clean($row->branch),

            $row->download_status == 1 || $row->download_status === 'Downloaded'
                ? 'Downloaded'
                : 'Yet to Download',

            $this->clean($row->downloaded_at),

            $this->clean($row->average_score),
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'EMAIL',
            'COMPANY',
            'DEPARTMENT',
            'JOB ROLE',
            'BRANCH',
            'DOWNLOAD STATUS',
            'DOWNLOADED ON',
            'AVERAGE SCORE',
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
            'A' => 35,
            'B' => 35,
            'C' => 30,
            'D' => 25,
            'E' => 25,
            'F' => 20,
            'G' => 20,
            'H' => 25,
            'I' => 20,
        ];
    }

    private function clean($value)
    {
        if ($value === null) {
            return '';
        }

        return trim(
            preg_replace('/[\x00-\x1F\x7F]/u', '', (string) $value)
        );
    }

    public function collection()
{
    return $this->users->map(function ($user) {
        // We iterate through our column map and just grab the values
        $row = [];
        foreach ($this->columns as $label => $field) {
            // Using data_get helps if $user is an array or object, 
            // and handles nested properties (like company.title)
            $row[] = data_get($user, $field) ?? '-';
        }
        return $row;
    });
}
}