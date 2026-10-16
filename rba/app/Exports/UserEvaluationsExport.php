<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserEvaluationsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithStyles, WithColumnWidths
{
    public function __construct(protected $query) {}

    public function query()
    {
        return $this->query->with(['user','course']);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function map($row): array
    {
        return [
            $row->username,
            $row->course_title_value,
            $row->date_updated ?? 'N/A',
            $row->question1,
            $row->answer1,
            $row->question2,
            $row->answer2,
            $row->question3,
            $row->answer3,
            $row->question4,
            $row->answer4,
            $row->question5,
            $row->answer5,
            $row->question6,
            $row->answer6,
            $row->question7,
            $row->answer7,
            $row->question8,
            $row->answer8,
            $row->question9,
            $row->answer9,
            $row->question10,
            $row->answer10,
        ];
    }

    public function headings(): array
    {
        return [
            'USERNAME',
            'COURSE',
            'SUBMITTED AT',
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