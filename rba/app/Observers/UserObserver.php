<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserLicense;
use App\Models\UserSurvey;
use App\Models\UserEvaluation;
use App\Models\CompletionReport;

class UserObserver
{
    public function updated(User $user): void
    {
        $watched = [
            'first_name',
            'last_name',
            'email',
            'company_id',
            'branch_id',
            'department_id',
            'job_role_id',
        ];

        if (!$user->wasChanged($watched)) {
            return;
        }

        $name = $user->name;

        /*
        |--------------------------------------------------------------------------
        | user_licenses
        |--------------------------------------------------------------------------
        | Fields: email, username, company_id, job_role_id, branch_id, department_id
        */
        $licenses = [];

        if ($user->wasChanged(['first_name', 'last_name'])) {
            $licenses['username'] = $name;
        }

        if ($user->wasChanged('email')) {
            $licenses['email'] = $user->email;
        }

        if ($user->wasChanged('company_id')) {
            $licenses['company_id'] = $user->company_id;
        }

        if ($user->wasChanged('job_role_id')) {
            $licenses['job_role_id'] = $user->job_role_id;
        }

        if ($user->wasChanged('branch_id')) {
            $licenses['branch_id'] = $user->branch_id;
        }

        if ($user->wasChanged('department_id')) {
            $licenses['department_id'] = $user->department_id;
        }

        if (! empty($licenses)) {
            UserLicense::where('user_id', $user->id)->update($licenses);
        }

        /*
        |--------------------------------------------------------------------------
        | user_surveys
        |--------------------------------------------------------------------------
        | Fields: email, name, company_id, branch_id, department_id, job_role_id
        */
        $surveys = [];

        if ($user->wasChanged(['first_name', 'last_name'])) {
            $surveys['name'] = $name;
        }

        if ($user->wasChanged('email')) {
            $surveys['email'] = $user->email;
        }

        if ($user->wasChanged('company_id')) {
            $surveys['company_id'] = $user->company_id;
        }

        if ($user->wasChanged('branch_id')) {
            $surveys['branch_id'] = $user->branch_id;
        }

        if ($user->wasChanged('department_id')) {
            $surveys['department_id'] = $user->department_id;
        }

        if ($user->wasChanged('job_role_id')) {
            $surveys['job_role_id'] = $user->job_role_id;
        }

        if (! empty($surveys)) {
            UserSurvey::where('user_id', $user->id)->update($surveys);
        }

        /*
        |--------------------------------------------------------------------------
        | user_evaluations
        |--------------------------------------------------------------------------
        | Fields: username, company_id
        */
        $evaluations = [];

        if ($user->wasChanged(['first_name', 'last_name'])) {
            $evaluations['username'] = $name;
        }

        if ($user->wasChanged('company_id')) {
            $evaluations['company_id'] = $user->company_id;
        }

        if (! empty($evaluations)) {
            UserEvaluation::where('user_id', $user->id)->update($evaluations);
        }

        /*
        |--------------------------------------------------------------------------
        | completion_reports
        |--------------------------------------------------------------------------
        | Fields: username, email, company_id, department_id, job_role_id, branch_id
        */
        $reports = [];

        if ($user->wasChanged(['first_name', 'last_name'])) {
            $reports['username'] = $name;
        }

        if ($user->wasChanged('email')) {
            $reports['email'] = $user->email;
        }

        if ($user->wasChanged('company_id')) {
            $reports['company_id'] = $user->company_id;
        }

        if ($user->wasChanged('department_id')) {
            $reports['department_id'] = $user->department_id;
        }

        if ($user->wasChanged('job_role_id')) {
            $reports['job_role_id'] = $user->job_role_id;
        }

        if ($user->wasChanged('branch_id')) {
            $reports['branch_id'] = $user->branch_id;
        }

        if (! empty($reports)) {
            CompletionReport::where('user_id', $user->id)->update($reports);
        }
    }
}
