<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class UsersArchivingTemplate implements FromArray, WithHeadings, WithColumnWidths
{
    public function array(): array
    {
        return [
            ['template_user_1@example.com'],
            ['template_user_2@example.com'],
            ['template_user_3@example.com'],
        ];
    }

    public function headings(): array
    {
        return ['email'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
        ];
    }
}
