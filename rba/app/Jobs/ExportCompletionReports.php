<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\CompletionReport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompletionReportsExport;
use App\Notifications\ExportReady;

class ExportCompletionReports implements ShouldQueue
{
    use Queueable;

    protected $filters;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $filters, int $userId)
    {
        $this->filters = $filters;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = CompletionReport::query()->orderBy('created_at', 'desc');
        
        // Apply filters
        $this->applyFilters($query);
        
        $fileName = 'Course_Completions_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';
        $filePath = 'exports/' . $fileName;
        
        // Store the file in the public disk
        Excel::store(new CompletionReportsExport($query), $filePath, 'public');
        
        // Send notification to user
        $user = User::find($this->userId);
        if ($user) {
            $user->notify(new ExportReady($filePath));
        }
    }

    protected function applyFilters($query): void
    {
        $multiSelectColumns = [
            'course_id',
            'branch_id',
            'department_id',
            'job_role_id',
            'company_id'
        ];

        if (!empty($this->filters['status']) && $this->filters['status'] !== 'all') {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('username', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        foreach ($multiSelectColumns as $col) {
            if (!empty($this->filters[$col]) && $this->filters[$col] !== 'all') {
                $query->whereIn($col, (array) $this->filters[$col]);
            }
        }

        if (!empty($this->filters['completion_status']) && $this->filters['completion_status'] !== 'all') {
            $query->whereIn('completion_status', (array) $this->filters['completion_status']);
        }
    }
}