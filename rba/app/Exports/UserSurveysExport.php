<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserSurveysExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithStyles, WithColumnWidths
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
            $row->survey_title,
            $row->name,
            $row->email,
            $row->branch_title,
            $row->company_title,
            $row->department_title,
            $row->job_role_title,
            $row->date_created,
            $row->question_1,
            $row->answer_1,
            $row->question_2,
            $row->answer_2,
            $row->question_3,
            $row->answer_3,
            $row->question_4,
            $row->answer_4,
            $row->question_5,
            $row->answer_5,
            $row->question_6,
            $row->answer_6,
            $row->question_7,
            $row->answer_7,
            $row->question_8,
            $row->answer_8,
            $row->question_9,
            $row->answer_9,
            $row->question_10,
            $row->answer_10,
        ];
    }

    public function headings(): array
    {
        return [
            'Survey',
            'Name',
            'Email',
            'Branch',
            'Company',
            'Department',
            'Job Role',
            'Submitted',
            'Question 1',
            'Answer 1',
            'Question 2',
            'Answer 2',
            'Question 3',
            'Answer 3',
            'Question 4',
            'Answer 4',
            'Question 5',
            'Answer 5',
            'Question 6',
            'Answer 6',
            'Question 7',
            'Answer 7',
            'Question 8',
            'Answer 8',
            'Question 9',
            'Answer 9',
            'Question 10',
            'Answer 10',
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
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
        ];
    }
}