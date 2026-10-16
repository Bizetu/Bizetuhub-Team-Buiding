<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubsidiaryCompletionsExport implements FromCollection, WithHeadings, WithChunkReading, WithMapping, WithStyles, WithColumnWidths
{
    protected $companies;

    public function __construct($companies)
    {
        $this->companies = $companies;
    }

    public function collection()
    {
        return $this->companies;
    }

    public function headings(): array
    {
        return [
            'BANK NAME',
            'STAFF POPULATION',
            'NUMBER OF REGISTERED USERS',
            'NUMBER OF COMPLETIONS',
            'PERCENTAGE COMPLETED',
        ];
    }
    
    public function map($company): array
    {
        return [
            $company->title ?? 'Untitled',
            (string) $company->staff_number ?? '-',
            (string) $company->registered ?? '-',
            (string) $company->completed ?? '-',
            (string) round((float) $company->percentage) . '%' ?? '0%',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true]  // Row 1 = headings bold
            ],
        ];
    }

    /**
     * Set custom column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 25,
            'C' => 40,
            'D' => 45,
            'E' => 20,
        ];
    }
}
