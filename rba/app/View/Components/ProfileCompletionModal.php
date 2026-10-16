<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Company;
use App\Models\Branch;
use App\Models\User;
use Closure;

class ProfileCompletionModal extends Component
{
    public bool $needsCompletion;
    public array $missingFields;

    // Dropdown options (only loaded when needed)
    public $companies;
    public $branches;
    public $departments;
    public $jobRoles;
    public $genders;

    public function __construct()
    {
        $this->missingFields    = [];
        $this->needsCompletion  = false;

        if (! Auth::check()) {
            return;
        }
        $legalPages = [
            'privacy-policy',
            'terms-conditions',
        ];

        if (in_array(Route::currentRouteName(), $legalPages)) {
            $this->needsCompletion = false;
            return;
        }

        $user = Auth::user();

        $checks = [
            'company_id'    => $user->company_id,
            'branch_id'     => $user->branch_id,
            'department_id' => $user->department_id,
            'job_role_id'   => $user->job_role_id,
            'gender_id'     => $user->gender_id,
            'policy_agree'  => $user->policy_agree,
        ];

        foreach ($checks as $field => $value) {
            if (empty($value)) {
                $this->missingFields[] = $field;
            }
        }

        $this->needsCompletion = ! empty($this->missingFields);

        if ($this->needsCompletion) {
            $this->companies   = in_array('company_id', $this->missingFields)
                ? Company::orderBy('title')->get(['id', 'title'])
                : collect();

            $this->branches    = in_array('branch_id', $this->missingFields)
                ? Branch::orderBy('title')->get(['id', 'title'])
                : collect();

            $this->departments = in_array('department_id', $this->missingFields)
                ? Department::orderBy('title')->get(['id', 'title'])
                : collect();

            $this->jobRoles    = in_array('job_role_id', $this->missingFields)
                ? JobRole::orderBy('title')->get(['id', 'title'])
                : collect();

            $this->genders     = in_array('gender_id', $this->missingFields)
                ? collect(User::getGender())
                : collect();
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.profile-completion-modal');
    }
}