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
use App\Models\UserSurvey;
use App\Models\CompletionReport;
use App\Models\UserEvaluation;

class RestoreUsers implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        $restored = 0;
        $alreadyRestored = 0;
        $skippedRows = 0;
        $failedCount = 0;
        $unauthorizedCount = 0;
        $restoredUsers = [];
        $authUser = Auth::user();
        $restoredBy = $authUser->email ?? '';
        $isCompanyHR = $authUser->role_id === 3 || $authUser->is_company_admin || $authUser->role_value === 'Company HR';

        if ($collection->isEmpty()) {
            session()->put('restored', 'Empty');
            return;
        }

        $firstRow = $collection->first();
        if (!$firstRow->has('email')) {
            session()->put('restored', 'Email');
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

            if ($user->deleted_at === null) {
                $alreadyRestored++;
                Log::channel('archive')->info('User Already Active', [
                    'email' => $email,
                    'user_id' => $user->id,
                ]);
                continue;
            }

            // Check company authorization for Company HR users
            if ($isCompanyHR && $user->company_id !== $authUser->company_id) {
                $unauthorizedCount++;
                Log::channel('archive')->warning('Unauthorized Restore Attempt', [
                    'email' => $email,
                    'user_id' => $user->id,
                    'user_company_id' => $user->company_id,
                    'auth_company_id' => $authUser->company_id,
                    'performed_by' => $restoredBy,
                ]);
                continue;
            }

            try {

                DB::beginTransaction();

                $user->update([
                    'published' => 1,
                    'updated_at' => now(),
                ]);
                $user->restore();

                CompletionReport::withTrashed()->withoutGlobalScopes()->where('user_id', $user->id)->restore();
                UserEvaluation::withTrashed()->withoutGlobalScopes()->where('user_id', $user->id)->restore();
                UserLicense::withTrashed()->withoutGlobalScopes()->where('user_id', $user->id)->restore();
                UserSurvey::withTrashed()->withoutGlobalScopes()->where('user_id', $user->id)->restore();

                DB::commit();

                $restoredUsers[] = [$user->id, $user->email];
                $restored++;

            } catch (\Exception $e) {

                DB::rollBack();
                $failedCount++;
                Log::channel('archive')->error('User Restore Failed', [
                    'email' => $email,
                    'user_id' => $user->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($alreadyRestored > 0 && $restored === 0)
        {
            session()->put('restored', 'Active');
        }
        elseif ($alreadyRestored > 0 && $restored > 0)
        {
            session()->put('restored', 'Active_Success');
        }
        elseif ($restored > 0)
        {
            session()->put('restored', 'Yes');
        }
        elseif ($alreadyRestored === 0 && $restored === 0 && $skippedRows > 0)
        {
            session()->put('restored', 'Empty');
        }
        elseif ($failedCount > 0 && $restored === 0)
        {
            session()->put('restored', 'NoMatches');
        }
        elseif ($unauthorizedCount > 0 && $restored === 0)
        {
            session()->put('restored', 'Unauthorized');
        }
        else
        {
            session()->put('restored', 'Failed');
        }

        $end = now();
        Log::channel('archive')->info('Users Restore Complete', [
            'rows_in_file'                  => $collection->count(),
            'restored_users'                => $restoredUsers,
            'restored'                      => $restored,
            'alreadyRestored'               => $alreadyRestored,
            'unauthorized_attempts'         => $unauthorizedCount,
            'empty_rows'                    => $skippedRows,
            'failed'                        => $failedCount,
            'auth_user'                     => $restoredBy,
            'auth_user_id'                  => $authUser->id ?? null,
            'auth_user_role'                => $authUser->role_value ?? null,
            'auth_user_company_id'          => $authUser->company_id ?? null,
            'ended_at'                      => $end,
        ]);
    }
}
