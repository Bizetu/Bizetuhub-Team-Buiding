<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Auth;
use App\Models\CompletionReport;
use App\Models\Course;

class CourseAverageExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithColumnWidths
{
    protected $courseIds;
    protected $search;
    protected $isSuperAdmin;
    protected $companyValue;
    protected $currentCompany;
    protected $rowNumber = 0;

    public function __construct($courseIds = null, $search = null, $currentCompany)
    {
        $this->courseIds = $courseIds;
        $this->search = $search;
        $this->isSuperAdmin = Auth::user()->role_value === 'SUPERADMIN';
        $this->companyValue = $currentCompany;
    }

    public function collection()
    {
        $query = CompletionReport::selectRaw("
                course_id,
                COUNT(DISTINCT user_id) as total_completed,
                AVG(
                    CASE
                        WHEN max_score > 0
                        THEN (score / max_score) * 100
                        ELSE NULL
                    END
                ) as average_score
            ");

        if (!$this->isSuperAdmin) {
            $query->where('company_id', Auth::user()->company_id);
        }

        if (!empty($this->courseIds)) {
            $query->whereIn('course_id', $this->courseIds);
        }

        if (!empty($this->search)) {
            $query->whereHas('courseRelationship', function ($q) {
                $q->where('display_name', 'LIKE', "%{$this->search}%");
            });
        }

        $completions = $query->groupBy('course_id')->get();

        if ($completions->isEmpty()) {
            return collect([]);
        }

        $courseIds = $completions->pluck('course_id');
        $courses = Course::whereIn('id', $courseIds)->get()->keyBy('id');

        return $completions->map(function ($completion) use ($courses) {
            return (object) [
                'course_id' => $completion->course_id,
                'course_name' => optional($courses->get($completion->course_id))->display_name ?? '-',
                'total_completed' => $completion->total_completed,
                'average_score' => $completion->average_score ?? 0,
            ];
        });
    }

    public function map($completion): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $completion->course_name,
            $completion->total_completed,
            round($completion->average_score, 2) . '%',
        ];
    }

    public function headings(): array
    {
        return [
            [$this->companyValue],
            [],
            [
                '#',
                'Module Name',
                'Total Completed',
                'Average Score (%)'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            3 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8E8E8'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 50,
            'C' => 18,
            'D' => 20,
        ];
    }
}