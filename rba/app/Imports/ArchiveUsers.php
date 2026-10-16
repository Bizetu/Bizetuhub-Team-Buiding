<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\UserLicense;
use App\Models\CompletionReport;
use App\Models\UserEvaluation;
use App\Models\UserSurvey;

class ArchiveUsers implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        $archived = 0;
        $alreadyArchived = 0;
        $skippedRows = 0;
        $failedCount = 0;
        $unauthorizedCount = 0;
        $archivedUsers = [];
        $authUser = Auth::user();
        $archivedBy = $authUser->email ?? '';
        $isCompanyHR = $authUser->role_id === 3 || $authUser->is_company_admin === 1 || $authUser->role_value === 'Company HR';


        if ($collection->isEmpty()) {
            session()->put('archived', 'Empty_File');
            return;
        }

        $firstRow = $collection->first();
        if (!$firstRow->has('email')) {
            session()->put('archived', 'Email_Column_Missing');
            return;
        }

        foreach ($collection as $index => $row) {

            $email = trim(strtolower($row['email']));

            if (empty($email) || is_null($email)) {
                $skippedRows++;
                continue;
            }

            $user = User::withTrashed()->where('email', $email)->first();

            if (!$user) {
                $failedCount++;
                Log::channel('archive')->warning('User Not Found', [
                    'email' => $email,
                ]);
                continue;
            }

            if ($user->trashed()) {
                $alreadyArchived++;
                Log::channel('archive')->info('User Already Archived', [
                    'email' => $email,
                    'user_id' => $user->id,
                ]);
                continue;
            }

            if ($user->email === $archivedBy) {
                session()->put('archived', 'Cannot_Self_Archive');
                return;
            }

            // Check company authorization for Company HR users
            if ($isCompanyHR && $user->company_id !== $authUser->company_id) {
                $unauthorizedCount++;
                Log::channel('archive')->warning('Unauthorized Archive Attempt', [
                    'email' => $email,
                    'user_id' => $user->id,
                    'user_company_id' => $user->company_id,
                    'auth_company_id' => $authUser->company_id,
                    'performed_by' => $archivedBy,
                ]);
                continue;
            }

            try {
                DB::beginTransaction();

                CompletionReport::withoutGlobalScopes()->where('user_id', $user->id)->delete();
                UserEvaluation::withoutGlobalScopes()->where('user_id', $user->id)->delete();
                UserLicense::withoutGlobalScopes()->where('user_id', $user->id)->delete();
                UserSurvey::withoutGlobalScopes()->where('user_id', $user->id)->delete();

                $user->update([
                    'published' => 0,
                    'updated_at' => now(),
                ]);

                $user->delete();

                DB::commit();

                $archivedUsers[] = [$user->id, $user->email];
                $archived++;

            } catch (\Exception $e) {
                DB::rollBack();
                $failedCount++;
                Log::channel('archive')->error('User Archive Failed', [
                    'email' => $email,
                    'user_id' => $user->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($alreadyArchived > 0 && $archived === 0)
        {
            session()->put('archived', 'Trashed');
        }
        elseif ($alreadyArchived > 0 && $archived > 0)
        {
            session()->put('archived', 'Trashed_Success');
        }
        elseif ($archived > 0)
        {
            session()->put('archived', 'Success');
        }
        elseif ($alreadyArchived === 0 && $archived === 0 && $skippedRows > 0)
        {
            session()->put('archived', 'Empty_File');
        }
        elseif ($unauthorizedCount > 0 && $archived === 0)
        {
            session()->put('archived', 'Unauthorized');
        }
        else
        {
            session()->put('archived', 'Failed');
        }

        $end = now();

        Log::channel('archive')->info('Users Archive Complete', [
            'rows_in_file'                                  => $collection->count(),
            'archived_users'                                => $archivedUsers,
            'number_of_users_archived'                      => $archived,
            'number_of_users_that_were_already_archived'    => $alreadyArchived,
            'unauthorized_attempts'                         => $unauthorizedCount,
            'empty_rows_in_file'                            => $skippedRows,
            'failed'                                        => $failedCount,
            'archive_performed_by'                          => $archivedBy,
            'auth_user_role'                                => $authUser->role_value ?? null,
            'user_id'                                       => $authUser->id ?? null,
            'auth_user_company_id'                          => $authUser->company_id ?? null,
            'timestamp'                                     => $end,
        ]);
    }
}
