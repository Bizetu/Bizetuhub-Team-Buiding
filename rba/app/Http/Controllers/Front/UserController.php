<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\View\View;
use App\Models\JobRole;
use App\Models\Company;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $key = 'profile';
        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        $branches = Branch::published()->pluck('title', 'id');
        $companies = Company::published()->pluck('title', 'id');
        $departments = Department::published()->pluck('title', 'id');
        $jobRoles = JobRole::published()->pluck('title', 'id');
        $genders = collect(User::getGender())->pluck('title', 'id');

        return view('site.pages.profile', [
            'user'          => $request->user(),
            'pageItem'      => $itemPage,
            'branches'      => $branches,
            'companies'     => $companies,
            'departments'   => $departments,
            'jobRoles'      => $jobRoles,
            'genders'       => $genders,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'company_id'    => 'required|exists:companies,id',
            'branch_id'     => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'job_role_id'   => 'required|exists:job_roles,id',
            'gender_id'     => 'required',
            'phone_number'  => 'nullable|string|max:20',
            'profile_pic'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ],[
            'company_id.required' => 'The bank field is required.',
            'branch_id.required' => 'The branch field is required.',
            'department_id.required' => 'The department field is required.',
            'job_role_id.required' => 'The job role field is required.',
            'gender_id.required' => 'The gender field is required.',
            'profile_pic.max' => 'The profile photo cannot be greater than 2MB.',
        ]);

        if ($validator->fails()) {
            return Redirect::route('profile.edit')->withErrors($validator)->withInput();
        }

        $user = $request->user();

        $user->fill(
            collect($validator->validated())->except('profile_pic')->toArray()
        );

        $user->company_name    = $user->company?->title;
        $user->department_name = $user->department?->title;
        $user->branch_name     = $user->branch?->title;
        $user->job_role        = $user->jobRole?->title;

        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            $user->profile_pic = $request->file('profile_pic')->store('uploads/ppics', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function updateMF(Request $request)
    {
        $user = Auth::user();

        $rules = [];

        if (empty($user->company_id) && $request->has('company_id')) {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        if (empty($user->branch_id) && $request->has('branch_id')) {
            $rules['branch_id'] = 'required|exists:branches,id';
        }

        if (empty($user->department_id) && $request->has('department_id')) {
            $rules['department_id'] = 'required|exists:departments,id';
        }

        if (empty($user->job_role_id) && $request->has('job_role_id')) {
            $rules['job_role_id'] = 'required|exists:job_roles,id';
        }

        if (empty($user->gender_id) && $request->has('gender_id')) {
            $rules['gender_id'] = 'required';
        }

        if (empty($user->phone_number) && $request->has('phone_number')) {
            $rules['phone_number'] = 'required|string|max:20';
        }

        if (empty($user->policy_agree) && $request->has('policy_agree')) {
            $rules['policy_agree'] = 'required|accepted';
        }

        if (empty($rules)) {
            return redirect()->back();
        }

        $messages = [
            'company_id.required' => 'Select your Bank to proceed',
            'branch_id.required' => 'Select your Branch to proceed',
            'department_id.required' => 'Select your Department to proceed',
            'job_role_id.required' => 'Select your Job Role to proceed',
            'gender_id.required' => 'Select your Gender to proceed',
            'phone_number.required' => 'Enter your phone number to proceed',
            'policy_agree.required' => 'This is required to access the platform',
        ];

        $validated = $request->validate($rules, $messages);

        $user->fill($validated);

        if (array_key_exists('policy_agree', $rules)) {
            $user->policy_agree = $request->has('policy_agree') ? 1 : 0;
        }

        $user->company_name     = $user->company?->title;
        $user->department_name  = $user->department?->title;
        $user->branch_name      = $user->branch?->title;
        $user->job_role         = $user->jobRole?->title;
        $user->last_login_at    = Carbon::now();

        $user->save();

        return Redirect::route('home')->with('success', 'Profile updated successfully!');
    }

    public function companyBranches(Company $company): JsonResponse
    {
        try {
            $branches = $company
                ->branches()
                ->published()
                ->orderBy('title')
                ->get(['id', 'title']);

            // Explicit empty-array response so the client can distinguish
            // "company exists but has no branches" from an error.
            return response()->json($branches);

        } catch (QueryException $e) {
            // Database-level failure — log full detail, return a safe message.
            Log::error('companyBranches DB error', [
                'company_id' => $company->id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json(
                ['message' => 'A database error occurred. Please try again later.'],
                500
            );

        } catch (Throwable $e) {
            Log::error('companyBranches unexpected error', [
                'company_id' => $company->id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json(
                ['message' => 'Could not load branches. Please try again.'],
                500
            );
        }
    }

    public function departmentJobRoles(Department $department): JsonResponse
    {
        try {
            $jobRoles = $department
                ->jobRoles()
                ->published()
                ->orderBy('title')
                ->get(['id', 'title']);

            return response()->json($jobRoles);

        } catch (QueryException $e) {
            Log::error('departmentJobRoles DB error', [
                'department_id' => $department->id,
                'error'         => $e->getMessage(),
            ]);

            return response()->json(
                ['message' => 'A database error occurred. Please try again later.'],
                500
            );

        } catch (Throwable $e) {
            Log::error('departmentJobRoles unexpected error', [
                'department_id' => $department->id,
                'error'         => $e->getMessage(),
            ]);

            return response()->json(
                ['message' => 'Could not load job roles. Please try again.'],
                500
            );
        }
    }
}
