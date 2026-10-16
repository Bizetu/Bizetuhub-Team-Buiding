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

class MessagesExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithStyles, WithColumnWidths
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
            $row->title,
            $row->company_value ?? 'N/A',
            $row->sent_value ?? 'N/A',
            $row->message_type_value ?? 'N/A',
            $row->audience_value ?? 'N/A',
            $row->is_recurent_value ?? 'N/A',
            $row->send_date ?? 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'TITLE',
            'COMPANY',
            'SENT STATUS',
            'MESSAGE CATEGORY',
            'TARGET LEARNERS',
            'MESSAGE TYPE',
            'SEND DATE',
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
        ];
    }
}